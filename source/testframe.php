<?php

#加载框架核心
include('vars.php')

#获得数据库对象
$this->db = Database::instance('wisetong');

#获得表结果(数组)
$_zh_rs = $this->db->query($_zh_sql)->result_array(false);

#获取session
$_session_arr = Session::instance()->get();

#设置session
Session::instance()->set('version', $version);

#debug 函数
debug($_session_arr);

#获取配置文件信息
$_db_config = Kohana::config('database.wisetong');

**结构如下：
$config['wisetong'] = array	(
'benchmark'     => TRUE,
'persistent'    => FALSE,
'connection'    => array(
'type'     => 'mysqli',
'host'     => '192.168.1.246', #数据库地址
'user'     => 'root', #数据库用户
'pass'     => 'wiseuc501200', #数据库密码
'port'     => '3306', #数据库端口
'socket'   => FALSE,
'database' => 'ids5'  #数据库名称
),
'character_set' => 'utf8',
'table_prefix'  => '',
'object'        => TRUE,
'cache'         => FALSE,
'escape'        => TRUE
);

