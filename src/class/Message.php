<?php
namespace Allmessages;

class Message
{
	private const CORRECT_API_TYPE                = 'Please provide correct api type.';
	private const LOGOUT                          = 'Successfully logout.';
	private const ACCOUNT_CREATED                 = 'Account created successfully.';
	private const FAILED_CREATE_ACCOUNT           = 'Failed to create account. Might be something wrong.';
	private const BANK_ACCOUNT_TYPE               = 'Please provide bank account type.';
	private const ACCOUNT_NAME                    = 'Please provide owner account name.';
	private const ACCOUNT_NAME_ALPHABETICAL       = 'Account name should be only alphabets and whitespace allowed.';
	private const ACCOUNT_ID                      = 'Please provide owner account id.';
	private const TRANSACTION_TYPE                = 'Please provide transaction type.';
	private const TRANSACTION_AMOUNT              = 'Please provide transaction amount.';
	private const AMOUNT_NUMERIC                  = 'Please provide only numreic value for transaction amount.';
	private const ACCOUNT_TO_ID                   = 'Please provide to account id.';
	private const ROLL_BACK                       = 'Transaction roll back. Failed to transfer the amount.';
	private const BANK_ROLL_BACK                  = 'Please contact bank to roll back your last transaction.';
	private const WITHDRAWAL_LIMIT                = 'Individual user account have a withdrawal limit of dollars 500 only.';
	private const SUFFICIENT_AMOUNT               = 'Sorry you do not have sufficient amount.';
	private const TO_OWNER_ACCOUNT_RECORDS        = 'To owner bank account records not found.';
	private const CORRECT_TRANSACTION_TYPE        = 'Please provide correct input of transaction type.';
	private const OWNER_BANK_RECORDS              = 'Owner bank account records not found.';
	private const AMOUNT_CREDITED                 = 'Amount credited successfully.';
	private const AMOUNT_CREDITED_FAILED          = 'Amount credited successfully but failed to update available balance.';
	private const AMOUNT_DEBITED                  = 'Amount debited successfully.';
	private const AMOUNT_DEBITED_FAILED           = 'Amount debited successfully but failed to update available balance.';
	private const AMOUNT_DEBITED_FAILED_TRANSFER  = 'Amount debited failed during transfer the amount.';
	private const AMOUNT_CREDITED_FAILED_TRANSFER = 'Amount credited failed during transfer the amount.';
	private const TRANSACTION_FAILED              = 'Transaction failed.';
	private const CORRECT_ACCOUNT_TYPE            = 'Please provide correct bank account type.';
	private const SUB_ACCOUNT_TYPE                = 'Please provide bank sub account type.';
	private const CORRECT_SUB_ACCOUNT_TYPE        = 'Please provide correct bank sub account type.';
	private const BALANCE_DETAILS                 = 'Get account balance details.';
	private const BALANCE_RECORDS                 = 'Account balance records not found.';
	private const CORRECT_DEPOSIT_TYPE            = 'Please provide correct input of transaction deposit type.';
	private const CORRECT_WITHDRAWAL_TYPE         = 'Please provide correct input of transaction withdrawal type.';
	private const CORRECT_TRANSFER_TYPE           = 'Please provide correct input of transaction transfer type.';

	public function __construct()
	{
		
	}

	public static function getMessageValue($kwd)
	{
        return constant('self::'. $kwd);
	}
}
