<?php

namespace Mlezitom;

/**
 * Translator
 *
 * @author Tomas Mleziva
 */
class Translator implements \Nette\Localization\Translator
{

    public $data = array();

    function __construct()
    {
        if (!class_exists("\Spyc")) {
            require_once(dirname(__FILE__) . '/spyc.php');
        }
    }

    public function translate($message, ...$parameters): string
    {
        if (array_key_exists($message, $this->data)) {
            $translation = $this->data[$message];
        } else {
            $translation = $message;
        }

        if (!count($parameters)) {
            return (string)$translation;
        }

        $sprintfParams = [$translation];
        foreach ($parameters as $parameter) {
            $sprintfParams[] = $parameter;
        }
        return call_user_func_array("sprintf", $sprintfParams);
    }

    public function loadFile($filename)
    {
        if (!is_file($filename)) {
            throw new \InvalidArgumentException("Translations file not found: " . $filename);
        }

        $data = \Spyc::YAMLLoad($filename);
        $this->parseArrayRecursive($data);
    }

    private function parseArrayRecursive($node, $prefix = "")
    {
        foreach ($node as $key => $item) {
            if (is_array($item)) {
                $this->parseArrayRecursive($item, $prefix . "." . $key);
            } else {
                $this->data[ltrim($prefix . "." . $key, ".")] = $item;
            }
        }
    }
}