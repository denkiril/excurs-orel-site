<?php

/*  This code was written by Ihor Slyva
    http://ihorsl.com 
    License GPLv2 */


namespace wpmjgu;

class class_wpmjgu_func
{
   
    public $url_file; 
    public $validate;
    
    public function __construct()
    {
        $this->string = null;
        $this->url_file = null;
        $this->validate = null;
        $this->image = null;
    } 
    
    public function loadChildren()
    {
        $this->string = new \wpmjgu\class_wpmjgu_string();
        $this->url_file = new \wpmjgu\class_wpmjgu_url_file();
        $this->validate = new \wpmjgu\class_wpmjgu_validate();
        $this->image = new \wpmjgu\class_wpmjgu_image();        
    }        

    public function unset_all_settings()
    {
        $all_options =  wp_load_alloptions();
        foreach ($all_options as $name => $value)
        {
            if (strlen($name) > 7   && substr($name, 0, 7) === "wpmjgu_" )
            {
                delete_option($name);
            }        
        }    
        
   }           
   
    public function get_all_wpmjgu_settings()
    {
        $ret = array();
        
        $all_options =  wp_load_alloptions();
        foreach ($all_options as $name => $value)
        {
            if (strlen($name) > 7   && substr($name, 0, 7) === "wpmjgu_" )
            {
                if (strlen($name) > 11   && substr($name, -11) === "_errorvalue" )
                {
                    continue;
                }
                else
                {
                    $ret[$name] = $value;
                }    
            }        
        }
        
        return $ret;
    }
    
    
    public function get_all_wpmjgu_settings_from_post_query()       
    {
        $ret = array();
        
        foreach ($_POST as $name => $value)
        {        
            if (strlen($name) > 7   && substr($name, 0, 7) === "wpmjgu_" )
            {
                $ret[$name] = urldecode($value);
            }
        }
    
        return $ret;
    }

    
}    