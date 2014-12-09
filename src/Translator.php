<?php

namespace Mlezitom;

/**
 * Translator
 *
 * @version 1.1
 * @author Tomas Mleziva
 */
class Translator implements \Nette\Localization\ITranslator {
    
    public $data = array();
    
    function __construct() {
		if(!class_exists("\Spyc")) {
			require_once(dirname(__FILE__) . '/spyc.php');
		}
    }
    
    public function translate($message, $count = NULL) {
        if(array_key_exists($message, $this->data)) {
            $translation = $this->data[$message];
        }
        else {
            $translation = $message;
        } 
        
        $params = func_get_args();
		$params[0] = $translation;
        return ($count) ? call_user_func_array("sprintf", $params ) : $translation;
    }
    
    public function loadFile($filename) {
        if(!is_file($filename)) {
            throw new \InvalidArgumentException("Translations file not found: " . $filename);
        }
        
        $data = \Spyc::YAMLLoad($filename);
        $this->parseArrayRecursive($data);
    }
    
    private function parseArrayRecursive($node, $prefix = "") {
        foreach($node as $key => $item) {
            if(is_array($item)) {
                $this->parseArrayRecursive($item, $prefix . "." . $key);
            }
            else {
                $this->data[ltrim($prefix . "." . $key, ".")] = $item;
            }
        }
    }
}