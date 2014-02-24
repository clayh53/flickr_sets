<?php
 
class Plugin_flickr_sets extends Plugin
{
	var $meta = array(
		'name'			 => 'flickr_sets',
		'version'		 => '1',
		'author'		 => 'Clay Harmon, forked from flickr plugin by Nikolai Strandskogen',
		'author_url' => 'http://clayharmon.com'
		);

		const format = 'json&nojsoncallback=1';
		const url	= 'http://api.flickr.com/services/rest/?method=';
		const no_key = null; // error code for missing API key 
		const config_missing = "Could not get Flickr photosets, `flickr_api` key  wasn't set in _config/add-ons/flickr_sets/flickr_sets.yaml";
		
		public function sets()
		{
			$limit	= $this->fetchParam('limit', 5, 'is_numeric');
			$id = $this->fetchParam('id', null);
			$params = "flickr.photosets.getPhotos&photoset_id=$id&extras=url_m,url_l,url_n,url_q,geo,url_sq,url_t,url_s,url_o&per_page=$limit";

			if ($response = $this->flickr_curl($params)) {
				return object_to_array($response->photoset);
			}

			return false;
		}

		public function api()
		{
			$params = $this->fetchParam('request', false);

			if ($params) {
				if ($response = $this->flickr_curl($params)) {
					print_r($response);
					return object_to_array($response);
				}						 
			}

			return false;
		}	


		function flickr_curl($params) 
		{
			$api_key = $this->fetchConfig('flickr_api_key', null, null, false, false); // fetch this information from config files

			if(!$api_key)  //check that it fetched values from flickr_sets.yaml, if not return error
			{
				$this->log->error(self::config_missing);
				return self::no_key;
			}

			$request = curl_init(self::url.$params.'&api_key='."{$api_key}".'&format='.self::format);
			curl_setopt($request, CURLOPT_RETURNTRANSFER, true);

			$contents = curl_exec($request);

			if ($contents)
				return json_decode($contents);

			echo "Flickr requires the CURL library to be installed."; // else
		}
}	

function object_to_array($d) 
{
	if (is_object($d)) {
		$d = get_object_vars($d);
	}

	if (is_array($d)) {
		return array_map(__FUNCTION__, $d);
	}
	else {
		return $d;								
	}
}
