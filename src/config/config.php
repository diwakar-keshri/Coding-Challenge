<?php
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
define('FILE_PATH', realpath(dirname(__FILE__)));
require_once(FILE_PATH.'../../class/Transaction.php');
require_once(FILE_PATH.'../../class/Bank.php');
require_once(FILE_PATH.'../../class/Account.php');
require_once(FILE_PATH.'../../class/Helper.php');
require_once(FILE_PATH.'../../class/Banking.php');
require_once(FILE_PATH.'../../class/Message.php');

/*
	Use for to call api in json and return the result in json
*/

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE, PUT");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
