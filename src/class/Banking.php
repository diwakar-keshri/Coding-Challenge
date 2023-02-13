<?php
namespace Allbanking;

require_once(FILE_PATH.'../../class/Core.php');
use Allcores\Core as Core;
use Allmessages\Message as Message;

class Banking extends Core
{
	public $message = '';
	
	public function __construct()
	{

	}

	public function index()
	{
		$data = file_get_contents('php://input');
		$data = (array) json_decode($data);
		$type = $data['api_type'];

		switch ($type)
		{
			case 'getBankName':
				Core::getBankName();
				break;
			case 'createAccount':
				Core::createAccount($data);
				break;
			case 'accountBalance':
				Core::accountBalance($data);
				break;
			case 'deposit':
				Core::deposit($data);
				break;
			case 'withdrawal':
				Core::withdrawal($data);
				break;
			case 'transfer':
				Core::transfer($data);
				break;
			case 'logout':
				self::logout($data);
				break;
			default:
				self::invalidRequest();
				break;
		}
	}

	/* session start */

	public function login()
	{
		self::startSession();
	}

	private function startSession()
	{
		session_start();
	}

	/* session destroy */

	private function logout()
	{
		unset($_SESSION);
		session_destroy();
		$this->message = Message::getMessageValue('LOGOUT');
	}

	/*
		Invalid request api
	*/

	private function invalidRequest()
	{
		$this->message = Message::getMessageValue('CORRECT_API_TYPE');
	}
}