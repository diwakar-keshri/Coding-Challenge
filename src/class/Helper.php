<?php
namespace Allhelpers;

use Allmessages\Message as Message;

class Helper
{
	public function __construct()
	{

	}

	public static function isMandatoryRequired($inputField)
	{
		if (isset($inputField) && !empty($inputField)) {
	    	return true;
	    } else {
	    	return false;
	    }
	}

	/*
		return true if the array is not empty
 		return false if it is empty
 		break - stop the process we have seen that at least 1 of the array has value so its not empty
	*/

	public static function isResultEmpty($searchResult)
	{
	  	if (isset($searchResult) && !empty($searchResult) && is_array($searchResult)) {
	      	foreach ($searchResult as $key => $value) {
	          	if (!empty($value) || $value != null || $value != "") {
	              	return true;
	          	}
	     	}
	     	return false;
	  	}
	}

	/*
		search the value based on key
	*/

	public static function getSearchData($searchInput, $key, $value)
	{
	    if (is_array($searchInput)) {
	        if (isset($searchInput[$key]) && $searchInput[$key] == $value) {
	            $results = $searchInput;
	        }
	        foreach ($searchInput as $searchVal) {
	            $results = array_merge($results, self::getSearchData($searchVal, $key, $value));
	        }
	    }
	    return $results;
	}

	/*
		Empty field validation during create account
	*/

	public static function createAccountValidation($data)
	{
		$return['status'] = false;
		if (self::isMandatoryRequired($data['accountType']) == false) {
	        $return['message'] = Message::getMessageValue('BANK_ACCOUNT_TYPE');
	    } elseif (self::isMandatoryRequired($data['accountName']) == false) {
	        $return['message'] = Message::getMessageValue('ACCOUNT_NAME');
	    } elseif (!preg_match("/^([a-zA-Z ]*)$/", $data['accountName'])) {
	        $return['message'] = Message::getMessageValue('ACCOUNT_NAME_ALPHABETICAL');
        } else {
	        $return['status']  = true;
	    }
	    return $return;
	}

	/*
		Empty field validation during deposit transaction
	*/

	public static function transactionValidation($data)
	{
		$return['status'] = false;
		if (self::isMandatoryRequired($data['accountId']) == false) {
    		$return['message'] = Message::getMessageValue('ACCOUNT_ID');
		} elseif (self::isMandatoryRequired($data['transactionType']) == false) {
    		$return['message'] = Message::getMessageValue('TRANSACTION_TYPE');
		} elseif (self::isMandatoryRequired($data['amount']) == false) {
    		$return['message'] = Message::getMessageValue('TRANSACTION_AMOUNT');
		} elseif (!is_numeric($data['amount'])) {
    		$return['message'] = Message::getMessageValue('AMOUNT_NUMERIC');
		} else {
			$return['status']  = true;
		}
		return $return;
	}

	/*
		Empty field validation during transaction transfer like NEFT, UPI etc
	*/

	public static function transferValidation($data)
	{
		$return['status'] = false;
		if (self::isMandatoryRequired($data['accountId']) == false) {
    		$return['message'] = Message::getMessageValue('ACCOUNT_ID');
		} elseif (self::isMandatoryRequired($data['transactionType']) == false) {
    		$return['message'] = Message::getMessageValue('TRANSACTION_TYPE');
		} elseif (self::isMandatoryRequired($data['amount']) == false) {
    		$return['message'] = Message::getMessageValue('TRANSACTION_AMOUNT');
		} elseif (!is_numeric($data['amount'])) {
    		$return['message'] = Message::getMessageValue('AMOUNT_NUMERIC');
		} elseif (self::isMandatoryRequired($data['toAccountId']) == false) {
    		$return['message'] = Message::getMessageValue('ACCOUNT_TO_ID');
		} else {
			$return['status']  = true;
		}
		return $return;
	}
}