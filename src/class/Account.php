<?php
namespace Allaccounts;

class Account
{
	public $message = '';
	private const CHECKING   = 'Checking';		// Account tye checking
	private const INVESTMENT = 'Investment';	// Account type Investment
	private const INDIVIDUAL = 'Individual';	// Sub account type Individual for Investment account type
	private const CORPORATE  = 'Corporate';		// Sub account type Corporate for Investment account type

	public function __construct()
	{

	}

	/*
		It will provide the name of account type
		It will return the name of account type
	*/

	public static function getAccountType($kwd)
	{
		return constant('self::'. $kwd);
	}

	/*
		create bank account for owner by getting owner details
		It will return the owner account details
	*/

	public static function createAccountData($insertData)
	{
		$lastInsertId = $insertData['accountId'];
		$count = 0;
		if (isset($_SESSION['accounts']) && !empty($_SESSION['accounts']) && is_array($_SESSION['accounts'])) {
			$count = count($_SESSION['accounts']);
		}

		$_SESSION['accounts'][] = $insertData;

		if ($_SESSION['accounts'][$count]['accountId'] == $lastInsertId) {
			return $insertData;
		}
		return false;
	}

	/*
		get the account owner information
		It will return the owner account details
	*/

	public static function getAccountData($accountId)
	{
		$accounts = $_SESSION['accounts'];
		return self::getSearchAccounts($accounts, 'accountId', $accountId);
	}

	/*
		search account owner information based on accountId
	*/

	private static function getSearchAccounts($searchInput, $key, $value)
	{
		$results = array();
	    if (is_array($searchInput)) {
	        if (isset($searchInput[$key]) && $searchInput[$key] == $value) {
	            $results = $searchInput;
	        }

	        foreach ($searchInput as $searchVal) {
	            $results = array_merge($results, self::getSearchAccounts($searchVal, $key, $value));
	        }
	    }
	    return $results;
	}

	/*
		update owner bank account information
	*/

	public static function updateAccountData($accountId, $updateData)
	{
		if (isset($_SESSION['accounts']) && !empty($_SESSION['accounts']) && is_array($_SESSION['accounts'])) {
			foreach ($_SESSION['accounts'] as $key => $value) {
				if ($_SESSION['accounts'][$key]['accountId'] == $accountId) {
					$_SESSION['accounts'][$key]['bankName']         = $updateData['bankName'];
					$_SESSION['accounts'][$key]['accountType']      = $updateData['accountType'];
					$_SESSION['accounts'][$key]['subAccountType']   = $updateData['subAccountType'];
					$_SESSION['accounts'][$key]['accountId']        = $updateData['accountId'];
					$_SESSION['accounts'][$key]['accountName']      = $updateData['accountName'];
					$_SESSION['accounts'][$key]['availableBalance'] = $updateData['availableBalance'];
				}
			}
			return true;
		} else {
			return false;
		}
	}
}