<?php
require_once('../src/config/config.php');
require_once('./vendor/autoload.php');
use PHPUnit\Framework\TestCase;

class TransactionTest extends TestCase
{
	public function testTransactionValidation()
    {
        $helper = new \Allhelpers\Helper();

        $field['accountId']       = '1';
        $field['transactionType'] = 'Deposit';
        $field['amount']          = '500';

        $data = $helper->transactionValidation($field);

        $this->assertTrue($data['status']);
        $this->assertIsArray($data);
    }

    public function testCreateAccount()
    {
        $core = new \Allcores\Core();

        $field['accountType']      = 'Checking';
        $field['subAccountType']   = '';
        $field['accountName']      = 'Diwakar Kumar';
        $core->createAccount($field);
        $expected = 'Diwakar Kumar';
        $this->assertIsArray($core->message);
        $this->assertTrue($core->message['status']);
        $this->assertEquals($expected, $core->message['data']['accountName']);
    }

    public function testCreateAccountData()
    {
        $core = new \Allcores\Core();

        $field['accountType']      = 'Investment';
        $field['subAccountType']   = 'Individual';
        $field['accountName']      = 'Ujjawal Kumar';
        $core->createAccount($field);
        $expected = 'Ujjawal Kumar';
        $this->assertIsArray($core->message);
        $this->assertTrue($core->message['status']);
        $this->assertEquals($expected, $core->message['data']['accountName']);
    }

    public function testDeposit()
    {
        $core = new \Allcores\Core();

        $field['accountId']       = '1';
        $field['transactionType'] = 'Deposit';
        $field['amount']          = '1500';

        $core->deposit($field);
        $expected = true;

        $this->assertIsArray($core->message);
        $this->assertTrue($core->message['status']);
        $this->assertEquals($expected, $core->message['status']);
    }

    public function testDepositData()
    {
        $core = new \Allcores\Core();

        $field['accountId']       = '2';
        $field['transactionType'] = 'Deposit';
        $field['amount']          = '1500';

        $core->deposit($field);
        $expected = true;

        $this->assertIsArray($core->message);
        $this->assertTrue($core->message['status']);
        $this->assertEquals($expected, $core->message['status']);
    }

    public function testWithdrawal()
    {
        $core = new \Allcores\Core();

        $field['accountId']       = '1';
        $field['transactionType'] = 'Withdrawal';
        $field['amount']          = '500';

        $core->withdrawal($field);
        $expected = true;

        $this->assertIsArray($core->message);
        $this->assertTrue($core->message['status']);
        $this->assertEquals($expected, $core->message['status']);
    }

    public function testWithdrawalData()
    {
        $core = new \Allcores\Core();

        $field['accountId']       = '2';
        $field['transactionType'] = 'Withdrawal';
        $field['amount']          = '500';

        $core->withdrawal($field);
        $expected = true;

        $this->assertIsArray($core->message);
        $this->assertTrue($core->message['status']);
        $this->assertEquals($expected, $core->message['status']);
    }

    public function testTransfer()
    {
        $core = new \Allcores\Core();

        $field['accountId']       = '2';
        $field['transactionType'] = 'Transfer';
        $field['amount']          = '500';
        $field['toAccountId']     = '1';

        $core->transfer($field);
        $expected = true;

        echo "<pre>"; print_r($_SESSION['accounts']);
        echo "<pre>"; print_r($_SESSION['transaction']);
        $this->assertIsArray($core->message);
        $this->assertTrue($core->message['fromTransfer']['status']);
        $this->assertEquals($expected, $core->message['fromTransfer']['status']);
    }
}