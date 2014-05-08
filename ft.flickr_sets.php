<?php
class Fieldtype_flickr_sets extends Fieldtype
{
	var $meta = array(
		'name'			 => 'flickr_sets',
		'version'		 => '1',
		'author'		 => 'Clay Harmon',
		'author_url' => 'http:/clayharmon.com'
		);
	const no_keys = null; // error code for missing API key 
	const config_missing = "Could not get Flickr photosets, `flickr_api` key or flickr user ID wasn't set in _config/add-ons/flickr_sets/flickr_sets.yaml";
	const url	= 'https://api.flickr.com/services/rest/?method=flickr.photosets.getList';

	public function render()
	{
		
		$html = "<div class='input-wrap'><select name='$this->fieldname' tabindex='{$this->tabindex}'>";
		
		$photoset_list = $this->get_photosets();
		
		if ($photoset_list == self::no_keys) // if get photosets returns config error, write helpful message to panel
		{
			return self::config_missing;
		}
			
		foreach ($photoset_list->photosets->photoset as $unit) 
		{
			$selected = "";
			if ($unit->id == $this->field_data)
			{
				$selected= "selected='selected'";
			}	

			$html .= "<option value='{$unit->id}' "."{$selected}".">{$unit->title->_content}</option>";
		}

		$html .= "</select></div>";
		
		return $html; 
		
		
	}
	
	function get_photosets()
	{
			$api_key = $this->fetchConfig('flickr_api_key', null, null, false, false); // fetch this information from config files
			$flickr_user_id = $this->fetchConfig('flickr_user_id', null, null, false, false);
			
			if( (!$api_key) || (!$flickr_user_id) ) //check that it fetched values from flickr_sets.yaml, if not return error
			{
				$this->log->error(self::config_missing);
				return self::no_keys;
			}	
			
			$request = curl_init(self::url.'&api_key='."{$api_key}".'&user_id='."{$flickr_user_id}".'&format=json&nojsoncallback=1');
			curl_setopt($request, CURLOPT_RETURNTRANSFER, true);

			$contents = curl_exec($request);

			if ($contents)
				return json_decode($contents);
			echo "Flickr requires the CURL library to be installed."; 

	}




}
