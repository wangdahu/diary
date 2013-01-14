<?php

include dirname(dirname(dirname(__FILE__))).'/vars.php';
$soap_dir=Cfg::get('core.tmp_path',APP_PATH.'tmp/').'soap'.DS;

ini_set('soap.wsdl_cache_dir',$soap_dir);

include_once dirname(__FILE__).'/Method.php';
include dirname(dirname(__FILE__)).'/class/Diary.php';
$diary = new Diary();
$server = new SoapServer('./soap.wsdl', array('soap_version' => SOAP_1_2));

function doAct($func,$args)
{
	return Method::$func(diary, $args);
}

$server->addFunction('doAct');

$server->handle();
