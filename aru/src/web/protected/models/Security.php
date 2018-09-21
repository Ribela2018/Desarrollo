<?php

class Security
{
    const PBKDF2_HASH_ALGORITHM = 'SHA256';
    const PBKDF2_ITERATIONS = 64000;
    const PBKDF2_SALT_BYTE_SIZE = 24;
    const PBKDF2_HASH_BYTE_SIZE = 24;

    private $password;

    function __construct()
    {
        $this->password = $this->publickey();
    }

    private function pbkdf2($algorithm, $password, $salt, $count, $key_length, $raw_output = false)
    {
        $algorithm = strtolower($algorithm);
        if (!in_array($algorithm, hash_algos(), true)) {
            trigger_error('PBKDF2 ERROR: Invalid hash algorithm.', E_USER_ERROR);
        }
        if ($count <= 0 || $key_length <= 0) {
            trigger_error('PBKDF2 ERROR: Invalid parameters.', E_USER_ERROR);
        }
        if (function_exists('hash_pbkdf2')) {
            if (!$raw_output) {
                $key_length = $key_length * 2;
            }
            return hash_pbkdf2($algorithm, $password, $salt, $count, $key_length, $raw_output);
        }
        $hash_length = strlen(hash($algorithm, '', true));
        $block_count = ceil($key_length / $hash_length);
        $output = '';
        for ($i = 1; $i <= $block_count; $i++) {
            $last = $salt . pack('N', $i);
            $last = $xorsum = hash_hmac($algorithm, $last, $password, true);
            for ($j = 1; $j < $count; $j++) {
                $xorsum ^= ($last = hash_hmac($algorithm, $last, $password, true));
            }
            $output .= $xorsum;
        }
        if ($raw_output) {
            return substr($output, 0, $key_length);
        } else {
            return bin2hex(substr($output, 0, $key_length));
        }
    }

    private function pbkfd2Hash($password, $salt) {
        return base64_encode(
            $this->pbkdf2(self::PBKDF2_HASH_ALGORITHM, $password, $salt, self::PBKDF2_ITERATIONS, self::PBKDF2_HASH_BYTE_SIZE, true)
        );
    }

    function encrypt($input)
    {
        $pbkdf2Salt = base64_encode(
            mcrypt_create_iv(self::PBKDF2_SALT_BYTE_SIZE, MCRYPT_DEV_URANDOM)
        );
        $pbkdf2SecureKey = $this->pbkfd2Hash($this->password, $pbkdf2Salt);
        $mcryptIvSize = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        $iv = mcrypt_create_iv($mcryptIvSize, MCRYPT_DEV_URANDOM);
        return implode(':', array(
            $pbkdf2Salt,
            base64_encode($iv),
            base64_encode(
                mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $pbkdf2SecureKey, $input, MCRYPT_MODE_CBC, $iv)
            )
        ));
    }

    function decrypt($input)
    {
        list($pbkdf2Salt, $iv, $encryptedText) = explode(':', $input);
        $pbkdf2SecureKey = $this->pbkfd2Hash($this->password, $pbkdf2Salt);
        return rtrim(
            mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $pbkdf2SecureKey, base64_decode($encryptedText), MCRYPT_MODE_CBC, base64_decode($iv)),
            "\0"
        );
    }
    
    function filepublickey($idUser=NULL) 
    {
        $condition = '';
        if($idUser == NULL){
            $condition = '';
        }else{
            $condition = "AND id IN($idUser)";
        }
        $modelUsers = Users::model()->findAll("id_status != 2 $condition");
        foreach ($modelUsers as $key => $value) {
            $username = $value->username;
            $fileName = "/root/.ssh/id_rsa_$username.pub";
            $startKey = '';
            if(file_exists("/root/.ssh/")){
                if (!file_exists($fileName)) {
                    chmod($fileName, 755);
                    if(is_writable($fileName)){
                        $privateKey = openssl_pkey_new(array('private_key_bits' => 2048));
                        $details = openssl_pkey_get_details($privateKey);
                        $publicKey = $details['key'];
                        $publicKeyFile = fopen($fileName,"a") or die("Problemas en la creacion del archivo");
                        fputs($publicKeyFile,$publicKey);
                        fclose($publicKeyFile);
                    }
                }
            }
        }
    }
    
    function publickey() 
    {
        $username = Users::getUsers(Yii::app()->user->id)->username;
        $fileName = "/root/.ssh/id_rsa_$username.pub";
        $startKey = '';
        if (file_exists($fileName)) {
            $publicKey = file_get_contents($fileName);
            $startKey = str_replace('-----BEGIN PUBLIC KEY-----', '', $publicKey);
            $startKey = trim(str_replace('-----END PUBLIC KEY-----', '', $startKey));
            return $startKey;
        }
    }
    
}
