# Coding-Challenge
Core PHP based on oops concept Coding challenge

### Technology used

1) CORE PHP
2) Postman and RestApi

### Files which create relation between them.

1) Bank.php used for create the bank which is by default constant
2) Account.php used for creating account types and user accounts
3) Transaction.php used for create transaction like deposit, withdrawal and transfer
4) Core.php used for writting logic for all functionality
5) Helper.php used for validation purpose
6) Banking.php used for to call rest apis
7) Message.php used for provide all the response messages 
8) Config.php use for configuration
9) index.php use for trigger the api with the help of postman

### Unit testing file
1) Accounttest.php
2) Transactiontest.php

Due to manage the data in session we are only able to manage the test cases for single account like
create user account
check balance of user account
account transaction like deposit, withdrawal and transfer

To run the test case first of all you have to install PHP Unit Testing with PHPUnit inside the unittesting folder
After installation of PHP Unit Testing open the terminal and go to the path of unittesting folder
To run the testcase you have to use the below mentioned path in terminal

./vendor/bin/phpunit Accounttest.php
./vendor/bin/phpunit Transactiontest.php
