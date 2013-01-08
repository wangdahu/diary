<?php
class Init {
    public function init() {
        error_reporting(E_ALL | E_STRICT);
        mb_internal_encoding('utf-8');
        spl_autoload_register(array($this, 'loadClass'));
    }

    private function loadClass($name) {
        $classes = $this->classes();
        $classPath = dirname(__FILE__)."/class/";
        if(!in_array($name.".php", $classes)) {
            die('Class "'.$name.'" not Found.');
        }

        require_once $classPath.$name.".php";
    }

    public function classes() {
        $classPath = dirname(__FILE__)."/class/";
        $fileArr = scandir($classPath);
        array_shift($fileArr);
        array_shift($fileArr);
        return $fileArr;
    }
}

$init = new Init();
