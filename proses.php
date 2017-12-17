<?php
session_start();
require './db.php';
$cmd = $_GET['cmd'];

/**
* 
*/
class Crypto3des // ASCII 3DES Buatan sendiri
{
	public function char_at($str, $pos)
	{
	  return $str{$pos};
	}

	public function encryptToken($plain, $token)
	{

		$hasil1 = "";
		$hasil2 = "";
		$hasil3 = "";

		$key = 20;

		$key1 = substr($token, 2, -28);
		$key2 = substr($token, 28, -2);

		for($i = 0; $i < strlen($plain); $i++){
			$c = char_at($plain, $i);
			$ch = ord($c);
			$ch+=$key;
			$ascii = chr($ch);
			$hasil1 = $hasil1.''.$ascii.'';
		}

		for($i = 0; $i < strlen($plain); $i++){
			$c = char_at($plain, $i);
			$ch = ord($c);
			$ch-=$key1;
			$ascii = chr($ch);
			$hasil2 = $hasil2.''.$ascii.'';
		}

		for($i = 0; $i < strlen($plain); $i++){
			$c = char_at($plain, $i);
			$ch = ord($c);
			$ch+=$key2;
			$ascii = chr($ch);
			$hasil3 = $hasil3.''.$ascii.'';
		}

		return $hasil3;
	}
	
	public function encrypt($plain, $token)
	{

		$hasil1 = "";
		$hasil2 = "";
		$hasil3 = "";

		$key = 20;

		$key1 = substr($token, 2, -28);
		$key2 = substr($token, 28, -2);

		for($i = 0; $i < strlen($plain); $i++){
			$c = char_at($plain, $i);
			$ch = ord($c);
			$ch+=$key;
			$ascii = chr($ch);
			$hasil1 = $hasil1.''.$ascii.'';
		}

		for($i = 0; $i < strlen($hasil1); $i++){
			$c = char_at($hasil1, $i);
			$ch = ord($c);
			$ch-=$key1;
			$ascii = chr($ch);
			$hasil2 = $hasil2.''.$ascii.'';
		}

		for($i = 0; $i < strlen($hasil2); $i++){
			$c = char_at($hasil2, $i);
			$ch = ord($c);
			$ch+=$key2;
			$ascii = chr($ch);
			$hasil3 = $hasil3.''.$ascii.'';
		}

		return $hasil3;
	}

	public function decrypt($crypto, $key, $token)
	{

		$hasil1 = "";
		$hasil2 = "";
		$hasil3 = "";

		$key1 = substr($token, 2, -28);
		$key2 = substr($token, 28, -2);

		for($i = 0; $i < strlen($crypto); $i++){
			$c = char_at($crypto, $i);
			$ch = ord($c);
			$ch-=$key2;
			$ascii = chr($ch);
			$hasil1 = $hasil1.''.$ascii.'';
		}

		for($i = 0; $i < strlen($hasil1); $i++){
			$c = char_at($hasil1, $i);
			$ch = ord($c);
			$ch+=$key1;
			$ascii = chr($ch);
			$hasil2 = $hasil2.''.$ascii.'';
		}

		for($i = 0; $i < strlen($hasil2); $i++){
			$c = char_at($hasil2, $i);
			$ch = ord($c);
			$ch-=$key;
			$ascii = chr($ch);
			$hasil3 = $hasil3.''.$ascii.'';
		}

		return $hasil3;
	}
}

/**
* 
*/
class DEScrypto // 3DES yang dipakai
{
	
	public function encrypt($data, $secret)
	{
	    //Generate a key from a hash
	    $key = md5(utf8_encode($secret), true);

	    //Take first 8 bytes of $key and append them to the end of $key.
	    $key .= substr($key, 0, 8);

	    //Pad for PKCS7
	    $blockSize = mcrypt_get_block_size('tripledes', 'ecb');
	    $len = strlen($data);
	    $pad = $blockSize - ($len % $blockSize);
	    $data .= str_repeat(chr($pad), $pad);

	    //Encrypt data
	    $encData = mcrypt_encrypt('tripledes', $key, $data, 'ecb');

	    return base64_encode($encData);
	}

	public function decrypt($data, $secret)
	{
	    //Generate a key from a hash
	    $key = md5(utf8_encode($secret), true);

	    //Take first 8 bytes of $key and append them to the end of $key.
	    $key .= substr($key, 0, 8);

	    $data = base64_decode($data);

	    $data = mcrypt_decrypt('tripledes', $key, $data, 'ecb');

	    $block = mcrypt_get_block_size('tripledes', 'ecb');
	    $len = strlen($data);
	    $pad = ord($data[$len-1]);

	    return substr($data, 0, strlen($data) - $pad);
	}
}

class UnsafeCrypto // Enkripsi yang dapat diganti dengan methodenya
{
    const METHOD = 'aes-256-ctr';
    
    /**
     * Encrypts (but does not authenticate) a message
     * 
     * @param string $message - plaintext message
     * @param string $key - encryption key (raw binary expected)
     * @param boolean $encode - set to TRUE to return a base64-encoded 
     * @return string (raw binary)
     */
    public static function encrypt($message, $key, $encode = false)
    {
        $nonceSize = openssl_cipher_iv_length(self::METHOD);
        $nonce = openssl_random_pseudo_bytes($nonceSize);
        
        $ciphertext = openssl_encrypt(
            $message,
            self::METHOD,
            $key,
            OPENSSL_RAW_DATA,
            $nonce
        );
        
        // Now let's pack the IV and the ciphertext together
        // Naively, we can just concatenate
        if ($encode) {
            return base64_encode($nonce.$ciphertext);
        }
        return $nonce.$ciphertext;
    }
    
    /**
     * Decrypts (but does not verify) a message
     * 
     * @param string $message - ciphertext message
     * @param string $key - encryption key (raw binary expected)
     * @param boolean $encoded - are we expecting an encoded string?
     * @return string
     */
    public static function decrypt($message, $key, $encoded = false)
    {
        if ($encoded) {
            $message = base64_decode($message, true);
            if ($message === false) {
                throw new Exception('Encryption failure');
            }
        }

        $nonceSize = openssl_cipher_iv_length(self::METHOD);
        $nonce = mb_substr($message, 0, $nonceSize, '8bit');
        $ciphertext = mb_substr($message, $nonceSize, null, '8bit');
        
        $plaintext = openssl_decrypt(
            $ciphertext,
            self::METHOD,
            $key,
            OPENSSL_RAW_DATA,
            $nonce
        );
        
        return $plaintext;
    }
}

switch ($cmd) {
	case 'ip':
		if (!empty($_SERVER['HTTP_CLIENT_IP']))   
		  {
		    $ip_address = $_SERVER['HTTP_CLIENT_IP'];
		  }
		//whether ip is from proxy
		elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))  
		  {
		    $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
		  }
		//whether ip is from remote address
		else
		  {
		    $ip_address = $_SERVER['REMOTE_ADDR'];
		  }
		if ($ip_address == '::1'){
			$ip_address = '127.0.0.1';
		}
		echo $ip_address;
		break;
	case 'login':
		// proses.php?cmd=login&user=admin&pswd=admin
		$id = $_POST['user'];
        $psw = $_POST['pswd'];

        // $id = $_GET['user'];
        // $psw = $_GET['pswd'];
        $pswd = md5($psw);

        $pesan = 1;

        $sql = "SELECT * FROM users WHERE username ='" . $id . "' ";
        $result = mysqli_query($link, $sql);
        $row = mysqli_fetch_array($result);

        if(mysqli_num_rows($result) > 0) {
            if($pswd == $row['password']){
                setcookie("id", $row['username'], time() + 6000); 
                setcookie("login", TRUE, time() + 6000);

                $sqlu = "UPDATE users SET status = 'ON' WHERE username = '".$id."'";
            	$resultu = mysqli_query($link, $sqlu);
            	if (!$result) {
	                die("SQL ERROR.". $sqlu);
	                header("Location: dashboard/index.php");
	            }
            }
            else {
                $pesan = 'Password salah!';
            }
        }
        else {
            $pesan = 'Username tidak ada!';
        }
        echo json_encode(array('pesan'=>$pesan));
		break;
	case "logout":
		if(!isset($_COOKIE['login'])) {
		    header('location: index.php');
		}else{
			$sqlu = "UPDATE users SET status = 'OFF' WHERE username = '".$_COOKIE['id']."'";
	    	$resultu = mysqli_query($link, $sqlu);
	    	if (!$resultu) {
	            die("SQL ERROR.". $sqlu);
	            header("Location: index.php");
	        }
	        setcookie("id", '', time() -1);
	        setcookie("login", FALSE, time() -1);
	        header('location: index.php');
		}
        break;
	case 'register':
		// proses.php?cmd=register&user=ishal12$name=Faishal&pswd=asd&repswd=asd

		$id = $_POST['user'];
		$name = $_POST['name'];
		$psw = $_POST['pswd'];
		$repsw = $_POST['repswd'];

		$pesan = 'a';

		$sqlu = "SELECT * FROM users WHERE username ='" . $id . "' ";
        $resultu = mysqli_query($link, $sqlu);
        $rowu = mysqli_fetch_array($resultu);

        if(mysqli_num_rows($resultu) > 0){
        	$pesan = 'username sudah ada!';
        }
		else if( strlen($id) > 12 || preg_match('/\s/',$id) == 1){
			$pesan = 'format username salah, tidak boleh melebihi 12 char';
		}
		else if($psw !== $repsw){
			$pesan = 'password harus sama!';
		}else{
			$pswd = md5($psw);

			$sql = "INSERT INTO users(username, password, name, status) VALUE( '".$id."', '".$pswd."', '".$name."', 'OFF')";
			$result = mysqli_query($link, $sql);
	        if(!$result){
	            die('SQL ERROR: '.$sql);
	        }
	        $pesan = 'sukses';
		}
		echo json_encode(array('pesan'=>$pesan));
		break;
	case 'add_chat':
		// proses.php?cmd=add_chat&user1=ishal12&user2=admin
		$id1 = $_POST['user1'];
		$id2 = $_POST['user2'];

		function generateRandomString($length) {
		    $characters = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		    $charactersLength = strlen($characters);
		    $randomString = '';
		    for ($i = 0; $i < $length; $i++) {
		        $randomString .= $characters[rand(0, $charactersLength - 1)];
		    }
		    return $randomString;
		}
		function trim_value(&$value) 
		{ 
		    $value = trim($value); 
		}
		$x = rand(10,50); // keynya message
		$k1 = rand(10,50);; // keynya token
		$k2 = rand(10,50);; // keynya token
		$token = generateRandomString(2).''.$k1.''.generateRandomString(24).''.$k2.''.generateRandomString(2);

		$encryption_key = "CE51E06875F7D964";
		$edes = DEScrypto::encrypt($token, $encryption_key);

		$sqlCek = "SELECT c.*, a1.name as name1, a2.name as name2
		  FROM chat_rooms as c INNER JOIN users as a1 ON a1.username = c.user1
		  INNER JOIN users as a2 ON a2.username = c.user2
		  WHERE c.user1 = '".$id1."' AND c.user2 = '".$id2."' OR c.user1 = '".$id2."' AND c.user2 = '".$id1."'";
		$resultCek = mysqli_query($link, $sqlCek);
		if(!$resultCek) {
		    echo "SQL ERROR: ".$sqlCek;
		}
		if(mysqli_num_rows($resultCek) >= 1){
			$pesan = 'Room Chat sudah ada!';
		}else{
			$sql = "INSERT INTO chat_rooms(user1, user2, token) VALUES( '".$id1."', '".$id2."', '".$edes."')";
			$result = mysqli_query($link, $sql);
	        if(!$result){
	            die('SQL ERROR: '.$sql);
	        }
	        $pesan = 1;
		}

		echo json_encode(array('pesan'=>$pesan));
		break;
	case 'add_msg':
		$msg = $_POST['msg'];
		$room = $_POST['room'];
		$id = $_POST['id'];

		$sqlR = "SELECT * FROM chat_rooms WHERE id = '".$room."'";
		$resultR = mysqli_query($link, $sqlR);
		$rowR = mysqli_fetch_array($resultR);
		if(!$resultR){
			die('SQL ERROR: '.$sqlR);
		}

		$token = $rowR['token'];
		$encryption_key = "CE51E06875F7D964";
		$ddes = DEScrypto::decrypt($token, $encryption_key);
		$key1 = substr($token, 2, -28);
		$key2 = substr($token, 28, -2);
		$key = '';

		if($key1 > $key2){
			$key = $key1;
		}else{
			$key = $key2;
		}

		$edes = DEScrypto::encrypt($msg, $key);

		// proses.php?cmd=add_msg&msg=a&room=1&id=admin

		$timestamp = date("Y-m-d H:i:s");

		$sql = "INSERT INTO messages(id, message,chat_rooms_id, users_username) VALUES(NULL, '".$edes."', ".$room.", '".$id."')";
		$result = mysqli_query($link, $sql);
        if(!$result){
            die('SQL ERROR: '.$sql);
        }

        $pesan = array();

		$sqlMsg = "SELECT * FROM messages WHERE chat_rooms_id = '".$room."' ORDER BY created_at ASC";
		$resultMsg = mysqli_query($link, $sqlMsg);
		while ($rowMsg = mysqli_fetch_object($resultMsg)) {
			$pesan[] = array(
				'msg' => $ddes = DEScrypto::decrypt($rowMsg->message, $key),
				'tgl' 	=> $rowMsg->created_at,
				'user'	=> $rowMsg->users_username
			);
		}

		echo json_encode($pesan);
		break;
	case 'chat':
		$room = $_POST['room'];
		$sqlChat = "SELECT * FROM chat_rooms WHERE id = '".$room."'";
		$resultChat = mysqli_query($link, $sqlChat);
		$rowChat = mysqli_fetch_array($resultChat);
		if(!$resultChat) {
		    echo "SQL ERROR: ".$sqlChat;
		}
		$token = $rowChat['token'];
		$encryption_key = "CE51E06875F7D964";
		$ddes = DEScrypto::decrypt($token, $encryption_key);
		$key1 = substr($token, 2, -28);
		$key2 = substr($token, 28, -2);
		$key = '';

		if($key1 > $key2){
			$key = $key1;
		}else{
			$key = $key2;
		}

		$ddes = DEScrypto::decrypt($token, $encryption_key);

		$pesan = array();

		$sqlMsg = "SELECT * FROM messages WHERE chat_rooms_id = '".$room."' ORDER BY created_at ASC";
		$resultMsg = mysqli_query($link, $sqlMsg);
		while ($rowMsg = mysqli_fetch_object($resultMsg)) {
			$pesan[] = array(
				'msg' => $ddes = DEScrypto::decrypt($rowMsg->message, $key),
				'tgl' 	=> $rowMsg->created_at,
				'user'	=> $rowMsg->users_username
			);
		}
		echo json_encode($pesan);
		break;
	default:
        die("UNKNOWN");
        break;

}
?>