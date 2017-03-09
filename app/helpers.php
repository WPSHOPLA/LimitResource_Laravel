<?php

function getConfig($key = false, $default = null) {
	if($key !== false){
		if(Session::has('options')){
			$options = Session::get('options');
		} else {
			$options = SettingsController::getOptions();
			Session::flash('options', $options);
		}

		if(array_key_exists($key, $options)){
			return $options[$key];
		}
	}

	return $default;
}