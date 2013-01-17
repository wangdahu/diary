<?php
include dirname(dirname(dirname(__FILE__))).'/vars.php';
include_once dirname(__FILE__).'/Method.php';
include_once dirname(__FILE__).'/DB.php';

ini_set('soap.wsdl_cache_dir', "cache");

$diary = new DB();
$server = new SoapServer('./soap.wsdl');

function doAct($func,$args)
{
    return Method::$func($diary, $args);
}

$server->addFunction('doAct');

$server->handle();
