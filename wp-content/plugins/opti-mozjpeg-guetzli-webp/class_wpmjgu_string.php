<?php

/*  This code was written by Ihor Slyva
    http://ihorsl.com 
    License GPLv2 */


namespace wpmjgu;

class class_wpmjgu_string
{
   
    public function mbPregQuote($str, $delimiter = null )
    {
        $ret = '';
        
        $specialChars = '\.+*?[^]$(){}=!<>|:-';
        if ($delimiter) {
            $specialChars .= $delimiter;
        }
        
        for ($ci = 0; $ci < mb_strlen($str); $ci++) {
            $c = mb_substr($str, $ci, 1);
            if (mb_strpos($specialChars, $c) !== false) {
                $c = "\\" . $c;
            }
            $ret .= $c;
        }
            
        return $ret;
    }      
    
    
    public function mbStrReplace($search, $replace, $subject, &$count = 0) {
        if (!is_array($subject)) {
            $searches = is_array($search) ? array_values($search) : array($search);
            $replacements = is_array($replace) ? array_values($replace) : array($replace);
            //$replacements = array_pad($replacements, count($searches), '');
            $replacements = array_pad($replacements, count($searches), count($replacements) === 1 ? $replacements[0] : '');
            foreach ($searches as $key => $search) {
                    $parts = mb_split($this->mbPregQuote($search), $subject);
                    $count += count($parts) - 1;
                    $subject = implode($replacements[$key], $parts);
            }
        } else {
            foreach ($subject as $key => $value) {
                    $subject[$key] = mb_str_replace($search, $replace, $value, $count);
            }
        }
        return $subject;
    }    
    
    public function mbRTrim($str, $charactersMask = null )
    {
        if ($charactersMask) {
            $pattern = '/[';
            $pattern .= $this->mbPregQuote($charactersMask, '/');
            $pattern .= ']+$/u';
        } else {
            $pattern = '/\s+$/u';
        }
            
        return preg_replace($pattern, '', $str);
    }        

    public function mbLTrim($str, $charactersMask = null )
    {
        if ($charactersMask) {
            $pattern = '/^[';
            $pattern .= $this->mbPregQuote($charactersMask, '/');
            $pattern .= ']+/u';
        } else {
            $pattern = '/^\s+/u';
        }
            
        return preg_replace($pattern, '', $str);
    }            
    
    public function mbTrim($str, $charactersMask = null )
    {
        $str = $this->mbLTrim($str, $charactersMask);
        $str = $this->mbRTrim($str, $charactersMask);
        
        return $str;
    }        
    
    
}    