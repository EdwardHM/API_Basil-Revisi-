<?php
class DB_Functions {
 
    private $conn;

    // constructor
    function __construct() {
        require_once 'DB_Connect.php';
        // koneksi ke database
        $db = new Db_Connect();
        $this->conn = $db->connect();
    }

    // destructor
    function __destruct() {
        
    }

    public function simpanUser($nama, $phone, $password) {
        $uuid = uniqid('', true);
        $hash = $this->hashSSHA($password);
        $encrypted_password = $hash["encrypted"]; // encrypted password
        $salt = $hash["salt"]; // salt

        $stmt = $this->conn->prepare("INSERT INTO tbl_user(uuid_user, nama, phone, encrypted_password, salt) VALUES(?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $uuid, $nama, $phone, $encrypted_password, $salt);
        $result = $stmt->execute();
        $stmt->close();

        // cek jika sudah sukses
        if ($result) {
            $stmt = $this->conn->prepare("SELECT * FROM tbl_user WHERE phone = ?");
            $stmt->bind_param("s", $phone);
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            return $user;
        } else {
            return false;
        }
    }

    /**
     * Get user berdasarkan phone dan password
    */
    public function getUserByphoneAndPassword($phone, $password) {

        $stmt = $this->conn->prepare("SELECT * FROM tbl_user WHERE phone = ?");

        $stmt->bind_param("s", $phone);

        if ($stmt->execute()) {
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            // verifikasi password user
            $salt = $user['salt'];
            $encrypted_password = $user['encrypted_password'];
            $hash = $this->checkhashSSHA($salt, $password);
            // cek password jika sesuai
            if ($encrypted_password == $hash) {
                // autentikasi user berhasil
                return $user;
            }
        } else {
            return NULL;
        }
    }

    /**
     * Cek User ada atau tidak
    */
    public function isUserExisted($phone) {
        $stmt = $this->conn->prepare("SELECT phone from tbl_user WHERE phone = ?");

        $stmt->bind_param("s", $phone);

        $stmt->execute();

        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // user telah ada 
            $stmt->close();
            return true;
        } else {
            // user belum ada 
            $stmt->close();
            return false;
        }
    }

    /**
     * Encrypting password
    * @param password
    * returns salt and encrypted password
    */
    public function hashSSHA($password) {

        $salt = sha1(rand());
        $salt = substr($salt, 0, 10);
        $encrypted = base64_encode(sha1($password . $salt, true) . $salt);
        $hash = array("salt" => $salt, "encrypted" => $encrypted);
        return $hash;
    }

    /**
     * Decrypting password
    * @param salt, password
    * returns hash string
    */
    public function checkhashSSHA($salt, $password) {

        $hash = base64_encode(sha1($password . $salt, true) . $salt);

        return $hash;
    }




    /**
     * Simpan Kehadiran
    */

    public function simpanKehadiran($user_id,$keterangan,$is_in_office,$lokasi,$img){
        // simpan gambar
        $folderPath = "upload_foto/";
        $img = str_replace('data:image/png;base64,', '', $img);
		$img = str_replace(' ', '+', $img);
		$data = base64_decode($img);
		$file = $folderPath . uniqid() . '.png';
		$success = file_put_contents($file, $data);
        // selesai conversi simpan gambar

        $uuid = uniqid('', true);
        $vali = "Belum Tervalidasi";
        $stmt = $this->conn->prepare("INSERT INTO tbl_kehadiran(uuid, uuid_user, keterangan, is_in_office, lokasi, valid, foto) VALUES(?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $uuid, $user_id, $keterangan, $is_in_office, $lokasi, $vali, $file);
        $result = $stmt->execute();
        $stmt->close();

        // cek jika sudah sukses
        if ($result) {
            $stmt = $this->conn->prepare("SELECT * FROM tbl_kehadiran WHERE uuid_user = ?");
            $stmt->bind_param("s", $user_id);
            $stmt->execute();
            $kehadiran = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            return $kehadiran;
        } else {
            return false;
        }    
    }

    public function updateValidasi($nama, $waktu, $status){

        if($status == "Belum Tervalidasi"){
            $stmt = $this->conn->prepare("UPDATE tbl_kehadiran a INNER JOIN tbl_user b ON a.uuid_user = b.uuid_user 
                                        SET a.valid = 'Tervalidasi' WHERE b.nama = '".$nama."' 
                                        AND a.created_at='".$waktu."'");
            $result = $stmt->execute();
            $stmt->close();

            return true;

        } elseif ($status == "Tervalidasi"){
            $stmt = $this->conn->prepare("UPDATE tbl_kehadiran a INNER JOIN tbl_user b ON a.uuid_user = b.uuid_user 
                                        SET a.valid = 'Belum Tervalidasi' WHERE b.nama = '".$nama."' 
                                        AND a.created_at='".$waktu."' ");
                    
            $result = $stmt->execute();
            $stmt->close();

            return true;
        } else{
            return false;
        } 
    }


    public function updateUser($user_id,$password){
        $hash = $this->hashSSHA($password);
        $encrypted_password = $hash["encrypted"]; // encrypted password
        $salt = $hash["salt"]; // salt

        $stmt = $this->conn->prepare("UPDATE tbl_user  SET encrypted_password = '".$encrypted_password."', salt='".$salt."' WHERE uuid_user = '".$user_id."' ");
        $result = $stmt->execute();
        $stmt->close();

        return true;
    }


    public function updateProf($user_id,$phone,$nama){

        // $stmt = $this->conn->prepare("UPDATE tbl_user  SET nama = '".$nama."' WHERE uuid_user = '".$user_id."' ");
        // $result = $stmt->execute();
        // $stmt->close();
       
        if(!is_null($nama)){
            $stmt = $this->conn->prepare("UPDATE tbl_user  SET nama = '".$nama."' WHERE uuid_user = '".$user_id."' ");
            $result = $stmt->execute();
            $stmt->close();
            return true;
        } elseif(!is_null($phone)){
            $stmt = $this->conn->prepare("UPDATE tbl_user  SET phone = '".$phone."' WHERE uuid_user = '".$user_id."' ");
            $result = $stmt->execute();
            $stmt->close();
            return true;
        }
    }
}

?>