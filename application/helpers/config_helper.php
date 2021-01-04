<?php
    function getConfig($key) {
	$CI =& get_Instance();
        return ($CI->config->item($key));
    }
    
    function appendConfig($key, $value) {
        $CI     =& get_Instance();
        $config =& getConfig($key);
        
        if (is_array($config) || is_array($value)) {
            $CI->config->set_item($key, array_merge(((is_array($config))?$config:array($config)),
                                                    ((is_array($value ))?$value :array($value ))));
        } else {
            $CI->config->set_item($key, $menuItems);
        }
    }
?>
