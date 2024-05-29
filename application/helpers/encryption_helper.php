<?php
if(!function_exists('e_nzm')){
    function e_nzm($value)
    {
        $ciphering = "AES-128-CBC";
        $iv_length = openssl_cipher_iv_length($ciphering);
        $options = 0;
        $encryption_iv = '1234567891011121';
        $encryption_key = 'NZM_Encryption';
        $encryption = bin2hex(openssl_encrypt($value, $ciphering, $encryption_key, $options, $encryption_iv));
        return $encryption;
    }

}

if(!function_exists('d_nzm')){
    function d_nzm($value)
    {
        $ciphering = "AES-128-CBC";
        $iv_length = openssl_cipher_iv_length($ciphering);
        $options = 0;
        $decryption_iv = '1234567891011121';
        $decryption_key = 'NZM_Encryption';
		if (ctype_xdigit($value) && strlen($value) % 2 == 0) {
			$decryption = openssl_decrypt(hex2bin($value), $ciphering, $decryption_key, $options, $decryption_iv);
		} else {
			$decryption = "Decryption error";
		}
        return $decryption;
    }
}

if(!function_exists('formatDMT')){
    function formatDMT($dtm)
    {
        $day = "";
        if(substr($dtm, 1,1) == "d" || substr($dtm, 2,1) == "d"){
            $day = explode("d",$dtm)[0]."d";
            $day = str_replace("d", "d ", str_replace("w", "w ", $day));
            $dtm = explode("d",$dtm)[0]."d";
        }elseif(substr($dtm, 1,1) == "w" && substr($dtm, 3,1) == "d" || substr($dtm, 2,1) == "w" && substr($dtm, 4,1) == "d"){
            $day = explode("d",$dtm)[0]."d";
            $day = str_replace("d", "d ", str_replace("w", "w ", $day));
            $dtm = explode("d",$dtm)[0]."d";
        }elseif (substr($dtm, 1,1) == "w" || substr($dtm, 2,1) == "w" ) {
            $day = explode("w",$dtm)[0]."w";
            $day = str_replace("d", "d ", str_replace("w", "w ", $day));
            $dtm = explode("w",$dtm)[0]."w";
        }
        // secs
        if(strlen($dtm) == "2" && substr($dtm, -1) == "s"){
            $format = $day." 00:00:0".substr($dtm, 0,-1);
        }elseif(strlen($dtm) == "3" && substr($dtm, -1) == "s"){
            $format = $day." 00:00:".substr($dtm, 0,-1);
        //minutes
        }elseif(strlen($dtm) == "2" && substr($dtm, -1) == "m"){
            $format = $day." 00:0".substr($dtm, 0,-1).":00";
        }elseif(strlen($dtm) == "3" && substr($dtm, -1) == "m"){
            $format = $day." 00:".substr($dtm, 0,-1).":00";
        //hours
        }elseif(strlen($dtm) == "2" && substr($dtm, -1) == "h"){
            $format = $day." 0".substr($dtm, 0,-1).":00:00";
        }elseif(strlen($dtm) == "3" && substr($dtm, -1) == "h"){
            $format = $day." ".substr($dtm, 0,-1).":00:00";
         
        //minutes -secs
        }elseif(strlen($dtm) == "4" && substr($dtm, -1) == "s" && substr($dtm,1,-2) == "m"){
            $format = $day." "."00:0".substr($dtm, 0,1).":0".substr($dtm, 2,-1);
        }elseif(strlen($dtm) == "5" && substr($dtm, -1) == "s" && substr($dtm,1,-3) == "m"){
            $format = $day." "."00:0".substr($dtm, 0,1).":".substr($dtm, 2,-1);
        }elseif(strlen($dtm) == "5" && substr($dtm, -1) == "s" && substr($dtm,2,-2) == "m"){
            $format = $day." "."00:".substr($dtm, 0,2).":0".substr($dtm, 3,-1);
        }elseif(strlen($dtm) == "6" && substr($dtm, -1) == "s" && substr($dtm,2,-3) == "m"){
            $format = $day." "."00:".substr($dtm, 0,2).":".substr($dtm, 3,-1);
        
        //hours -secs
        }elseif(strlen($dtm) == "4" && substr($dtm, -1) == "s" && substr($dtm,1,-2) == "h"){
            $format = $day." 0".substr($dtm, 0,1).":00:0".substr($dtm, 2,-1);
        }elseif(strlen($dtm) == "5" && substr($dtm, -1) == "s" && substr($dtm,1,-3) == "h"){
            $format = $day." 0".substr($dtm, 0,1).":00:".substr($dtm, 2,-1);
        }elseif(strlen($dtm) == "5" && substr($dtm, -1) == "s" && substr($dtm,2,-2) == "h"){
            $format = $day." ".substr($dtm, 0,2).":00:0".substr($dtm, 3,-1);
        }elseif(strlen($dtm) == "6" && substr($dtm, -1) == "s" && substr($dtm,2,-3) == "h"){
            $format = $day." ".substr($dtm, 0,2).":00:".substr($dtm, 3,-1);
        
        //hours -secs
        }elseif(strlen($dtm) == "4" && substr($dtm, -1) == "m" && substr($dtm,1,-2) == "h"){
            $format = $day." 0".substr($dtm, 0,1).":0".substr($dtm, 2,-1).":00";
        }elseif(strlen($dtm) == "5" && substr($dtm, -1) == "m" && substr($dtm,1,-3) == "h"){
            $format = $day." 0".substr($dtm, 0,1).":".substr($dtm, 2,-1).":00";
        }elseif(strlen($dtm) == "5" && substr($dtm, -1) == "m" && substr($dtm,2,-2) == "h"){
            $format = $day." ".substr($dtm, 0,2).":0".substr($dtm, 3,-1).":00";
        }elseif(strlen($dtm) == "6" && substr($dtm, -1) == "m" && substr($dtm,2,-3) == "h"){
            $format = $day." ".substr($dtm, 0,2).":".substr($dtm, 3,-1).":00";
        
        //hours minutes secs
        }elseif(strlen($dtm) == "6" && substr($dtm, -1) == "s" && substr($dtm,3,-2) == "m" && substr($dtm,1,-4) == "h"){
            $format = $day." 0".substr($dtm, 0,1).":0".substr($dtm, 2,-3).":0".substr($dtm, 4,-1);
        }elseif(strlen($dtm) == "7" && substr($dtm, -1) == "s" && substr($dtm,3,-3) == "m" && substr($dtm,1,-5) == "h"){
            $format = $day." 0".substr($dtm, 0,1).":0".substr($dtm, 2,-4).":".substr($dtm, 4,-1);
        }elseif(strlen($dtm) == "7" && substr($dtm, -1) == "s" && substr($dtm,4,-2) == "m" && substr($dtm,1,-5) == "h"){
            $format = $day." 0".substr($dtm, 0,1).":".substr($dtm, 2,-3).":0".substr($dtm, 5,-1);
        }elseif(strlen($dtm) == "8" && substr($dtm, -1) == "s" && substr($dtm,4,-3) == "m" && substr($dtm,1,-6) == "h"){
            $format = $day." 0".substr($dtm, 0,1).":".substr($dtm, 2,-4).":".substr($dtm, 5,-1);
        }elseif(strlen($dtm) == "7" && substr($dtm, -1) == "s" && substr($dtm,4,-2) == "m" && substr($dtm,2,-4) == "h"){
            $format = $day." ".substr($dtm, 0,2).":0".substr($dtm, 3,-3).":0".substr($dtm, 5,-1);
        }elseif(strlen($dtm) == "8" && substr($dtm, -1) == "s" && substr($dtm,4,-3) == "m" && substr($dtm,2,-5) == "h"){
            $format = $day." ".substr($dtm, 0,2).":0".substr($dtm, 3,-4).":".substr($dtm, 5,-1);
        }elseif(strlen($dtm) == "8" && substr($dtm, -1) == "s" && substr($dtm,5,-2) == "m" && substr($dtm,2,-5) == "h"){
            $format = $day." ".substr($dtm, 0,2).":".substr($dtm, 3,-3).":0".substr($dtm, 6,-1);
        }elseif(strlen($dtm) == "9" && substr($dtm, -1) == "s" && substr($dtm,5,-3) == "m" && substr($dtm,2,-6) == "h"){
            $format = $day." ".substr($dtm, 0,2).":".substr($dtm, 3,-4).":".substr($dtm, 6,-1);
        
        }else{
            $format = $dtm;
        }
        return $format;
    }
    
    if(!function_exists('randN')){
        function randN($length) {
            $chars = "23456789";
            $charArray = str_split($chars);
            $charCount = strlen($chars);
            $result = "";
            for($i=1;$i<=$length;$i++)
            {
                $randChar = rand(0,$charCount-1);
                $result .= $charArray[$randChar];
            }
            return $result;
        }
    }

    if(!function_exists('randUC')){
        function randUC($length) {
            $chars = "ABCDEFGHJKLMNPRSTUVWXYZ";
            $charArray = str_split($chars);
            $charCount = strlen($chars);
            $result = "";
            for($i=1;$i<=$length;$i++)
            {
                $randChar = rand(0,$charCount-1);
                $result .= $charArray[$randChar];
            }
            return $result;
        }
    }
    
    if(!function_exists('randLC')){
        function randLC($length) {
            $chars = "abcdefghijkmnprstuvwxyz";
            $charArray = str_split($chars);
            $charCount = strlen($chars);
            $result = "";
            for($i=1;$i<=$length;$i++)
            {
                $randChar = rand(0,$charCount-1);
                $result .= $charArray[$randChar];
            }
            return $result;
        }
    }

    if(!function_exists('randULC')){
        function randULC($length) {
            $chars = "ABCDEFGHJKLMNPRSTUVWXYZabcdefghijkmnprstuvwxyz";
            $charArray = str_split($chars);
            $charCount = strlen($chars);
            $result = "";
            for($i=1;$i<=$length;$i++)
            {
                $randChar = rand(0,$charCount-1);
                $result .= $charArray[$randChar];
            }
            return $result;
        }
    }

    if(!function_exists('randNLC')){
        function randNLC($length) {
            $chars = "23456789abcdefghijkmnprstuvwxyz";
            $charArray = str_split($chars);
            $charCount = strlen($chars);
            $result = "";
            for($i=1;$i<=$length;$i++)
            {
                $randChar = rand(0,$charCount-1);
                $result .= $charArray[$randChar];
            }
            return $result;
        }
    }

    if(!function_exists('randNUC')){
        function randNUC($length) {
            $chars = "23456789ABCDEFGHJKLMNPRSTUVWXYZ";
            $charArray = str_split($chars);
            $charCount = strlen($chars);
            $result = "";
            for($i=1;$i<=$length;$i++)
            {
                $randChar = rand(0,$charCount-1);
                $result .= $charArray[$randChar];
            }
            return $result;
        }
    }

    if(!function_exists('randNULC')){
        function randNULC($length) {
            $chars = "23456789ABCDEFGHJKLMNPRSTUVWXYZabcdefghijkmnprstuvwxyz";
            $charArray = str_split($chars);
            $charCount = strlen($chars);
            $result = "";
            for($i=1;$i<=$length;$i++)
            {
                $randChar = rand(0,$charCount-1);
                $result .= $charArray[$randChar];
            }
            return $result;
        }
    }

	if(!function_exists('searcharray')){
		function searcharray($array,$column,$search)
		{
			$return = '';
			if(is_array($array))
			{
				$key = array_search($search, array_column($array, $column));
				if($key != ""){
					$return = $array[$key][".id"];
				}else{
					$return = "";
				}
			}
			return $return;
		}
	}

	if(!function_exists('formatBytes')){
		// function  format bytes
		function formatBytes($size, $decimals = 0){
			$unit = array(
				'0' => 'Byte',
				'1' => 'KiB',
				'2' => 'MiB',
				'3' => 'GiB',
				'4' => 'TiB',
				'5' => 'PiB',
				'6' => 'EiB',
				'7' => 'ZiB',
				'8' => 'YiB'
			);
			for($i = 0; $size >= 1024 && $i <= count($unit); $i++){
				$size = $size/1024;
			}
			return round($size, $decimals).' '.$unit[$i];
		}
	}
	
	if(!function_exists('formatBytes2')){
		// function  format bytes2
		function formatBytes2($size, $decimals = 0){
			$unit = array(
				'0' => 'Byte',
				'1' => 'KB',
				'2' => 'MB',
				'3' => 'GB',
				'4' => 'TB',
				'5' => 'PB',
				'6' => 'EB',
				'7' => 'ZB',
				'8' => 'YB'
			);
			for($i = 0; $size >= 1000 && $i <= count($unit); $i++){
				$size = $size/1000;
			}
			return round($size, $decimals).''.$unit[$i];
		}
	}
	
	if(!function_exists('formatBites')){
		// function  format bites
		function formatBites($size, $decimals = 0){
			$unit = array(
				'0' => 'bps',
				'1' => 'kbps',
				'2' => 'Mbps',
				'3' => 'Gbps',
				'4' => 'Tbps',
				'5' => 'Pbps',
				'6' => 'Ebps',
				'7' => 'Zbps',
				'8' => 'Ybps'
			);
			for($i = 0; $size >= 1000 && $i <= count($unit); $i++){
				$size = $size/1000;
			}
			return round($size, $decimals).' '.$unit[$i];
		}
	}

	if(!function_exists('removechar')){
		function removechar($max_char, $array)
		{
			$key_remove = "";
			foreach ($array as $key => $value) {
				if(strlen($value) >= $max_char){
					$key_remove .= $key+1;
					$next_value = $array[$key+1];
					$new_value = $value.$next_value;
					$newdata[] = $new_value;
				}else{
					if(empty($key_remove)){
						$newdata[] = $value;
					}else{
						$key_remove = "";
					}
				}
			}
			return $newdata;
		}
	}

	if(!function_exists('formatting_script')){
		function formatting_script($script)
		{
			$data = explode("\n",$script);
			if(!empty($data)){
				foreach ($data as $key => $value) {
					$formatting_space = preg_replace('!\s+!',' ',$value);
					$parsing_space = explode(" ",$formatting_space);
					$newdata[$key] = array_filter($parsing_space);
				}
				return array_filter($newdata);
			}else{
				$newdata = [];
				return $newdata;
			}
		}
	}

	if(!function_exists('format_script_space')){
		function format_script_space($script)
		{
			$data = explode("\n",$script);
			if(!empty($data)){
				foreach ($data as $key => $value) {
					$formatting_space = preg_replace('!\s+!',' ',$value);
					$newdata[$key] = $formatting_space;
				}
				return array_filter($newdata);
			}else{
				$newdata = [];
				return $newdata;
			}
		}
	}
}
?>
