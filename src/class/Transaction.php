<?php
namespace Alltransaction;

abstract class Transaction
{
	private const DEPOSIT    = 'Deposit';
	private const WITHDRAWAL = 'Withdrawal';
	private const TRANSFER   = 'Transfer';

	abstract public function deposit($data);

	abstract public function withdrawal($data);

	abstract public function transfer($data);

	public function getTransactionType($kwd)
	{
		return constant('self::'. $kwd);
	}
}