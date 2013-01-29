<?php
include dirname(dirname(dirname(__FILE__))).'/vars.php';

include_once dirname(__FILE__).'/DB.php';

include_once dirname(__FILE__).'/Method.php';
class classdb {
	public static function getdb() {
		return new DB();
	}
}

ini_set('soap.wsdl_cache_dir', dirname(__FILE__)."/cache");

$server = new SoapServer('./soap.wsdl', array('soap_version' => SOAP_1_2));

function doAct($func,$args) {
   return Method::$func($args);
}

$server->addFunction('doAct');

$server->handle();
