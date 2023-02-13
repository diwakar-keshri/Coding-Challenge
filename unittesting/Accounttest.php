<?php
require_once('../src/config/config.php');
require_once('./vendor/autoload.php');
use PHPUnit\Framework\TestCase;

class AccountTest extends TestCase
{
	public function testAccountValidation()
    {
        $helper = new \Allhelpers\Helper();

        $field['accountType']    = 'Checking';
        $field['subAccountType'] = '';
        $field['accountName']    = 'Diwakar Kumar';

        $data = $helper->createAccountValidation($field);

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

    public function testAccountBalance()
    {
        $core = new \Allcores\Core();
        $field['accountId'] = '1';
        $core->accountBalance($field);
        $expected = '';

        $this->assertIsArray($core->message);
        $this->assertTrue($core->message['status']);
        $this->assertEquals($expected, $core->message['data']['availableBalance']);
    }
}
