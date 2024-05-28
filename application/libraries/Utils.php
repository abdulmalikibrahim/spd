<?php

class Utils {
    function pagination($config){
		if($config['offset'] == 0 && count($config['array_data']) == $config['limit']){
			$data['div_next_url'] = $config['url_redirect'].($config['offset']+$config['limit']);
			$data['div_prev_url'] = '';
		}else if($config['offset'] >= $config['limit'] && count($config['array_data']) == $config['limit']){
			$data['div_next_url'] = $config['url_redirect'].($config['offset']+$config['limit']);
			$data['div_prev_url'] = $config['url_redirect'].($config['offset']-$config['limit']);
		}else if($config['offset'] >= $config['limit']){
			$data['div_next_url'] = '';
			$data['div_prev_url'] = $config['url_redirect'].($config['offset']-$config['limit']);
		}else{
			$data['div_next_url'] = '';
			$data['div_prev_url'] = '';
		}
		return $data;
	}
}

?>