<?php
namespace Allbanks;

class Bank
{
	private const BANKNAME = 'State Bank of India';

	public function __construct()
	{

	}

	/*
		Show the name of bank
		It will directly return the name of bank
	*/

	public static function showBankName()
	{
		return self::BANKNAME;
	}
}
