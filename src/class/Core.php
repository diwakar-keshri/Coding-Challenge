<?php
namespace Allcores;

use \Exception;
use Allbanks\Bank as Bank;
use Allaccounts\Account as Account;
use Allhelpers\Helper as Helper;
use Alltransaction\Transaction as Transaction;
use Allmessages\Message as Message;

class Core extends Transaction
{
	public $message = '';
	public $return  = [];
	public $status = true;

	public function __construct()
	{
		$this->status = true;
	}

	/*
		do the transaction of amount deposit to the owner account
		It will return the transaction information of amount deposit
	*/

	final public function deposit($data)
	{
		try {
			$inputData = self::getVerifyDepositData($data);
			if ($this->status == $inputData['status']) {
				$return = self::createTransaction($inputData);
			} else {
				throw new Exception($inputData['message']);
			}
		} catch (Exception $e) {
			$return = $e->getMessage();
		}
		$this->message = $return;
	}

	/*
		do the validation and verification of account and transaction deposit
		It will return the owner account details and validate the amount etc
	*/

	private function getVerifyDepositData($data)
	{
		try {
			$validation = Helper::transactionValidation($data);
			if ($this->status == $validation['status']) {
				if ($data['transactionType'] == Transaction::getTransactionType('DEPOSIT')) {
					$getAccountData = Account::getAccountData($data['accountId']);
					if (Helper::isResultEmpty($getAccountData)) {
						$inputData['status']           = true;
						$inputData['accountId']        = $getAccountData['accountId'];
						$inputData['transactionType']  = $data['transactionType'];
						$inputData['amount']           = $data['amount'];
						$inputData['availableBalance'] = $getAccountData['availableBalance'];

						$return = $inputData;
					} else {
						throw new Exception(Message::getMessageValue('OWNER_BANK_RECORDS'));
					}
				} else {
					throw new Exception(Message::getMessageValue('CORRECT_DEPOSIT_TYPE'));
				}
			} else {
				throw new Exception($validation['message']);
			}
		} catch (Exception $e) {
			$return['status']  = false;
			$return['message'] = $e->getMessage();
		}
		return $return;
	}

	/*
		do the transaction of amount withdrawal from owner account
		It will return the withdrawal transaction amount information
	*/

	final public function withdrawal($data)
	{
		try {
			$inputData = self::getVerifyWithdrawalData($data);
			if ($this->status == $inputData['status']) {
				$return = self::createTransaction($inputData);
			} else {
				throw new Exception($inputData['message']);
			}
		} catch (Exception $e) {
			$return = $e->getMessage();
		}
		$this->message = $return;
	}

	/*
		do the validation and verification of account and transaction withdrawal
		It will return the owner account details and validate the amount etc
	*/

	private function getVerifyWithdrawalData($data)
	{
		try {
			$validation = Helper::transactionValidation($data);
			if ($this->status == $validation['status']) {
				if ($data['transactionType'] == Transaction::getTransactionType('WITHDRAWAL')) {
					$getAccountData = Account::getAccountData($data['accountId']);
					if (Helper::isResultEmpty($getAccountData)) {
						$subAccountType   = $getAccountData['subAccountType'];
						$availableBalance = $getAccountData['availableBalance'];

						$getAvailable = self::getWithdrawalAvailableAmount($data['amount'], $availableBalance, $subAccountType);
						if ($this->status == $getAvailable['status']) {
							$inputData['status']           = true;
							$inputData['accountId']        = $getAccountData['accountId'];
							$inputData['transactionType']  = $data['transactionType'];
							$inputData['amount']           = $data['amount'];
							$inputData['availableBalance'] = $getAccountData['availableBalance'];

							$return = $inputData;
						} else {
							throw new Exception($getAvailable['message']);
						}
					} else {
						throw new Exception(Message::getMessageValue('OWNER_BANK_RECORDS'));
					}
				} else {
					throw new Exception(Message::getMessageValue('CORRECT_WITHDRAWAL_TYPE'));
				}
			} else {
				throw new Exception($validation['message']);
			}
		} catch (Exception $e) {
			$return['status']  = false;
			$return['message'] = $e->getMessage();
		}
		return $return;
	}

	/*
		It will check the owner account have the sufficient amount or not
		Also it will the Indiviudal account limit of dollar 500
	*/

	private function getWithdrawalAvailableAmount($amount, $availableBalance, $subAccountType)
	{
		try {
			if (($availableBalance > 0) && ($availableBalance >= $amount)) {
				if ($subAccountType == Account::getAccountType('INDIVIDUAL')) {
					if ($amount <= 500) {
						$return['status'] = true;
					} else {
						throw new Exception(Message::getMessageValue('WITHDRAWAL_LIMIT'));
					}
				} else {
					$return['status'] = true;
				}
			} else {
				throw new Exception(Message::getMessageValue('SUFFICIENT_AMOUNT'));
			}
		} catch (Exception $e) {
			$return['status']  = false;
			$return['message'] = $e->getMessage();
		}
		return $return;
	}

	/*
		do the transaction of amount transfer from owner account to other owner account
		It will return the transfer amount transaction from one account to other account
	*/

	final public function transfer($data)
	{
		try {
			$fromInput = self::getVerifyFromTransferData($data);
			$toInput   = self::getVerifyToTransferData($data);
			if ($this->status == $fromInput['status']) {
				if ($this->status == $toInput['status']) {
					$fromTransfer           = self::createTransaction($fromInput);
					$return['fromTransfer'] = $fromTransfer;
					if ($this->status == $fromTransfer['status']) {
						$toTransfer           = self::createTransaction($toInput);
						$return['toTransfer'] = $toTransfer;
					} else {
						// Transaction roll back
						$inputData          = self::rollBackTransaction($data);
						$rollBack           = self::createTransaction($inputData);
						$return['rollBack'] = $rollBack;
						if ($rollBack) {
							throw new Exception(Message::getMessageValue('ROLL_BACK'));
						} else {
							throw new Exception(Message::getMessageValue('BANK_ROLL_BACK'));
						}
					}
				} else {
					throw new Exception($toInput['message']);
				}
			} else {
				throw new Exception($fromInput['message']);
			}
		} catch (Exception $e) {
			$return = $e->getMessage();
		}
		$this->message = $return;
	}

	/*
		do the validation and verification of from account while transfer
		It will return from owner account details and validate the amount etc
	*/

	private function getVerifyFromTransferData($data)
	{
		try {
			$validation = Helper::transactionValidation($data);
			if ($this->status == $validation['status']) {
				if ($data['transactionType'] == Transaction::getTransactionType('TRANSFER')) {
					$getAccountData = Account::getAccountData($data['accountId']);
					if (Helper::isResultEmpty($getAccountData)) {
						$availableBalance = $getAccountData['availableBalance'];

						if (($availableBalance > 0) && ($availableBalance >= $data['amount'])) {
							$inputData['status']           = true;
							$inputData['accountId']        = $getAccountData['accountId'];
							$inputData['transactionType']  = $data['transactionType'];
							$inputData['amount']           = $data['amount'];
							$inputData['availableBalance'] = $getAccountData['availableBalance'];
							$inputData['balanceType']      = Transaction::getTransactionType('WITHDRAWAL');
							$return = $inputData;
						} else {
							throw new Exception(Message::getMessageValue('SUFFICIENT_AMOUNT'));
						}
					} else {
						throw new Exception(Message::getMessageValue('OWNER_BANK_RECORDS'));
					}
				} else {
					throw new Exception(Message::getMessageValue('CORRECT_TRANSFER_TYPE'));
				}
			} else {
				throw new Exception($validation['message']);
			}
		} catch (Exception $e) {
			$return['status']  = false;
			$return['message'] = $e->getMessage();
		}
		return $return;
	}

	/*
		do the validation and verification of other account while transfer
		It will return other owner account details
	*/

	private function getVerifyToTransferData($data)
	{
		try {
			$getAccountData = Account::getAccountData($data['toAccountId']);
			if (Helper::isResultEmpty($getAccountData)) {
				$inputData['status']           = true;
				$inputData['accountId']        = $getAccountData['accountId'];
				$inputData['transactionType']  = $data['transactionType'];
				$inputData['amount']           = $data['amount'];
				$inputData['availableBalance'] = $getAccountData['availableBalance'];
				$inputData['balanceType']      = Transaction::getTransactionType('DEPOSIT');
				$return = $inputData;
			} else {
				throw new Exception(Message::getMessageValue('TO_OWNER_ACCOUNT_RECORDS'));
			}
		} catch (Exception $e) {
			$return['status']  = false;
			$return['message'] = $e->getMessage();
		}
		return $return;
	}

	/*
		rollback the transaction
	*/

	private function rollBackTransaction($data)
	{
		try {
			$getAccountData = Account::getAccountData($data['accountId']);
			if (Helper::isResultEmpty($getAccountData)) {
				$inputData['status']           = true;
				$inputData['accountId']        = $getAccountData['accountId'];
				$inputData['transactionType']  = $data['transactionType'];
				$inputData['amount']           = $data['amount'];
				$inputData['availableBalance'] = $getAccountData['availableBalance'];
				$inputData['balanceType']      = Transaction::getTransactionType('DEPOSIT');
				$return = $inputData;
			} else {
				throw new Exception(Message::getMessageValue('OWNER_BANK_RECORDS'));
			}
		} catch (Exception $e) {
			$return['status']  = false;
			$return['message'] = $e->getMessage();
		}
		return $return;
	}

	/*
		do transaction of amount like deposit, withdrawal and transfer
	*/

	private function createTransaction($inputData)
	{
		$accountId        = $inputData['accountId'];
		$transactionType  = $inputData['transactionType'];
		$amount           = (int)$inputData['amount'];
		$availableBalance = (int)$inputData['availableBalance'];
		$balanceType      = isset($inputData['balanceType'])?$inputData['balanceType']:'';
		$return           = array();
		$count            = 0;
		$lastInsertId     = 1;

		if (isset($_SESSION['transaction']) && !empty($_SESSION['transaction']) && is_array($_SESSION['transaction'])) {
			$count        = count($_SESSION['transaction']);
			$rowCount     = count($_SESSION['transaction']) - 1;
			$lastInsertId = $_SESSION['transaction'][$rowCount]['transactionId'] + 1;
		}

		if ($transactionType == Transaction::getTransactionType('DEPOSIT')) {
			$credit        = $amount;
			$debit         = '';
			$totalBalance  = $availableBalance + $amount;
			$message       = Message::getMessageValue('AMOUNT_CREDITED');
			$msgError      = Message::getMessageValue('AMOUNT_CREDITED_FAILED');
		} elseif ($transactionType == Transaction::getTransactionType('WITHDRAWAL')) {
			$credit        = '';
			$debit         = $amount;
			$totalBalance  = $availableBalance - $amount;
			$message       = Message::getMessageValue('AMOUNT_DEBITED');
			$msgError      = Message::getMessageValue('AMOUNT_DEBITED_FAILED');
		} elseif ($transactionType == Transaction::getTransactionType('TRANSFER')) {
			if ($balanceType == Transaction::getTransactionType('WITHDRAWAL')) {
				$credit        = '';
				$debit         = $amount;
				$totalBalance  = $availableBalance - $amount;
				$message       = Message::getMessageValue('AMOUNT_DEBITED');
				$msgError      = Message::getMessageValue('AMOUNT_DEBITED_FAILED_TRANSFER');
			} elseif ($balanceType == Transaction::getTransactionType('DEPOSIT')) {
				$credit        = $amount;
				$debit         = '';
				$totalBalance  = $availableBalance + $amount;
				$message       = Message::getMessageValue('AMOUNT_CREDITED');
				$msgError      = Message::getMessageValue('AMOUNT_CREDITED_FAILED_TRANSFER');
			}
		}

		$insertData = array(
			'transactionId'   => $lastInsertId,
			'accountId'       => $accountId,
			'transactionType' => $transactionType,
			'creditBalance'   => $credit,
			'debitBalance'    => $debit,
			'totalBalance'    => $totalBalance,
			'createdDate'     => date('Y-m-d H:i:s')
		);

		$_SESSION['transaction'][] = $insertData;

		if ($_SESSION['transaction'][$count]['transactionId'] == $lastInsertId) {
			$getAccountData = Account::getAccountData($accountId);

			$updateData = array(
				'bankName'         => $getAccountData['bankName'],
				'accountType'      => $getAccountData['accountType'],
				'subAccountType'   => $getAccountData['subAccountType'],
				'accountId'        => $getAccountData['accountId'],
				'accountName'      => $getAccountData['accountName'],
				'availableBalance' => $totalBalance,
			);

			$update = Account::updateAccountData($accountId, $updateData);

			if ($update) {
				$return['status']  = true;
				$return['data']    = $insertData;
				$return['message'] = $message;
			} else {
				$return['status']  = false;
				$return['message'] = $msgError;
			}
		} else {
			$return['status']  = false;
			$return['message'] = Message::getMessageValue('TRANSACTION_FAILED');
		}
		return $return;
	}

	public function getBankName()
	{
		$this->message = Bank::showBankName();
	}

	public function createAccount($data)
	{
		try {
			$validation = Helper::createAccountValidation($data);
			if ($this->status == $validation['status']) {
				$getVerifyAccountTypeData = self::getVerifyAccountTypeData($data);
				if ($this->status == $getVerifyAccountTypeData['status']) {
					$inputData['bankName']         = Bank::showBankName();
					$inputData['accountType']      = $data['accountType'];
					$inputData['subAccountType']   = $data['subAccountType'];
					$inputData['accountId']        = self::getAccountLastInsertId();
					$inputData['accountName']      = $data['accountName'];
					$inputData['availableBalance'] = '';

					$createData = Account::createAccountData($inputData);
					if (Helper::isResultEmpty($createData)) {
						$return['status']  = true;
						$return['data']    = $createData;
						$return['message'] = Message::getMessageValue('ACCOUNT_CREATED');
					} else {
						$return['status']  = false;
						$return['message'] = Message::getMessageValue('FAILED_CREATE_ACCOUNT');
					}
				} else {
					throw new Exception($getVerifyAccountTypeData['message']);
				}
			} else {
				throw new Exception($validation['message']);
			}
		} catch (Exception $e) {
			$return = $e->getMessage();
		}
		$this->message = $return;
	}

	private function getVerifyAccountTypeData($data)
	{
		$accountType       = $data['accountType'];
		$subAccountType    = $data['subAccountType'];
		$return            = array();
		$return['status']  = false;

		$checkingType      = Account::getAccountType('CHECKING');
		$investmentType    = Account::getAccountType('INVESTMENT');
		$individualSubType = Account::getAccountType('INDIVIDUAL');
		$corporateSubType  = Account::getAccountType('CORPORATE');

		if (($accountType != $checkingType) && ($accountType != $investmentType)) {
       		$return['message'] = Message::getMessageValue('CORRECT_ACCOUNT_TYPE');
		} elseif (($subAccountType == '') && ($accountType != $checkingType)) {
       		$return['message'] = Message::getMessageValue('SUB_ACCOUNT_TYPE');
		} elseif (($subAccountType != '') && ($accountType != $investmentType)) {
       		$return['message'] = Message::getMessageValue('CORRECT_ACCOUNT_TYPE');
		} elseif (($accountType == $investmentType) && (($subAccountType != $individualSubType) && (($subAccountType != $corporateSubType)))) {
   			$return['message'] = Message::getMessageValue('CORRECT_SUB_ACCOUNT_TYPE');
		} else {
			$return['status']  = true;
		}
		return $return;
	}

	private function getAccountLastInsertId()
	{
		$count        = 0;
		$lastInsertId = 1;
		if (isset($_SESSION['accounts']) && !empty($_SESSION['accounts']) && is_array($_SESSION['accounts'])) {
			$count        = count($_SESSION['accounts']);
			$rowCount     = $count - 1;
			$lastInsertId = $_SESSION['accounts'][$rowCount]['accountId'] + 1;
		}
		return $lastInsertId;
	}

	public function accountBalance($data)
	{
		try {
			if (Helper::isMandatoryRequired($data['accountId'])) {
				$getAccountData = Account::getAccountData($data['accountId']);
				if (Helper::isResultEmpty($getAccountData)) {
					$return['status']  = true;
					$return['data']    = $getAccountData;
					$return['message'] = Message::getMessageValue('BALANCE_DETAILS');
				} else {
					throw new Exception(Message::getMessageValue('BALANCE_RECORDS'));
				}
			} else {
				throw new Exception(Message::getMessageValue('ACCOUNT_ID'));
			}
		} catch (Exception $e) {
			$return = $e->getMessage();
		}
		$this->message = $return;
	}
}