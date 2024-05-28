<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class MToken extends CI_Model {

	function check(){
		//check token
		$lic = $this->get_setting('LICENSE_AKTIVASI');
		$exp_date = $this->get_setting('LICENSE_EXPIRED_DATE');
		$client = $this->get_setting('CLIENT_ID');

		if($exp_date == '' || $exp_date == null || $lic == '' || $lic == null){
			return false;
		}else{
			// Store the cipher method
			$ciphering = "AES-128-CTR";
		
			// Use OpenSSl Encryption method
			// $iv_length = openssl_cipher_iv_length($ciphering);
			$options = 0;
			
			// Non-NULL Initialization Vector for decryption
			$decryption_iv = MY_SECRET;

			// Store the decryption key
			$decryption_key = $client;
			
			// Use openssl_decrypt() function to decrypt the data
			$decryption=openssl_decrypt($lic, $ciphering, 
					$decryption_key, $options, $decryption_iv);

			$source = json_decode($decryption);

			if(is_object($source)){
				if($source->client_id == $decryption_key && $source->expired_at == $exp_date){
					$date1=date_create(date('Y-m-d'));
					$date2=date_create($exp_date);
					$diff=date_diff($date1,$date2);
					$diff = (int) str_replace('+','',$diff->format("%R%a"));
					if($diff >= 0){
						return true;
					}else{
						return false;
					}
				}else{
					return false;
				}
			}else{
				return false;
			}

		}
	}

	function get_setting($key){
		$query = "SELECT * FROM setting WHERE `key` = ?";
		$get = $this->db->query($query,[$key]);
		return $get->row()->val;
	}
}
?>