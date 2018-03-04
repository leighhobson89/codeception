<?php
require 'tests/acceptance/_bootstrap.php';
$failed =0; // test is not failed if this parameter = 0

// @group payments
// @group general

$I = new AcceptanceTester($scenario);
$I->wantTo('do Deposit, Quick Deposit and Withdraw money by using Credit Card');

$I->amOnPage('/');
$I->maximizeWindow();

$I->amGoingTo('Verify elements on Landing page');
$I->waitForElementVisible('a.loginbtn',30); // Wait for Login button

$I->amGoingTo('Open Sports page'); 
$I->click('[title="Sports"]'); //click on Sports tab
$I->waitForElement('iframe',30); //wait for Twitter iframe

$serverid = $I->grabTextFrom('.hiddensrv'); // Get Server ID
$I->greenText('Server ID is: '.$serverid);

$I->weblogin($cardUser,$cardUserPass); //Login

//CHECK BALANCE BEFORE DOING ANY DEPOSIT
$funds=$I->grabTextFrom('span.balance_visible');
$funds= filter_var($funds, FILTER_SANITIZE_NUMBER_FLOAT); //get actual balance in Cents
$I->greentext('Actual Funds => £'.($funds/100)); //Shows Funds


//QUICK DEPOSIT
$I->amGoingTo('do a Quick Deposit');
$I->waitForElementVisible('.deposit_link',30);
$I->click('.deposit_link');
$I->waitForElementVisible('.overlay_deposit_deposit_btn',30);
$I->seeElement('input[id$="-Amount"]');
$I->fillField('input[id$="-Amount"]','5');
$I->seeElement('input[id$="-Password"]');
$I->fillField('input[id$="-Password"]',$cardUserPass);
$I->seeElement('input[id$="-CVV2Number"]');
$I->fillField('input[id$="-CVV2Number"]',$cardCVV);
$I->click('.overlay_deposit_deposit_btn'); //click deposit button after 

//CHECK CONFIRMATION POP UP
$I->waitForElementVisible('div[id$="-status-success"] a[value="continue"]',30);
$I->waitForText('Your deposit has been successful!',30,'div[id$="-status-success"] .quickdeposit_processing');
$I->seeElement('div[id$="-status-success"] .quickdeposit_processing .validated_icon_quickdeposit_accepted'); //Icon
$I->see('Your deposit has been successful!','div[id$="-status-success"] .quickdeposit_processing'); //
$I->see('Your transaction reference is:','div[id$="-status-success"] .quickdeposit_processing');
$I->click('div[id$="-status-success"] a[value="continue"]');
$I->wait(1); 

//CHECK BALANCE AFTER QUICK DEPOSIT
$I->amGoingTo('Check the balance after Quick Deposit');
$I->click('.accountbalancerefresh');
$I->wait(2); 
$fundsafterdep1=$I->grabTextFrom('span.balance_visible');
$fundsafterdep1= filter_var($fundsafterdep1, FILTER_SANITIZE_NUMBER_FLOAT); //get actual balance in Cents
$I->greentext('Funds after Quick Deposit => £'.($fundsafterdep1/100));

	if ($funds + 500 == $fundsafterdep1){
		$I->amGoingTo('Balance is correct after Quick Deposit');
	} else{
		$I->redText('Balance is NOT correct after Quick Deposit');
		$failed = 1; //marking test as failed becuase balance was not updated  correctly
	}

//REGULAR DEPOSIT
$I->amGoingTo('do a Regular Deposit');
$I->click('[title="Display account details"]');
$I->click('[title="Deposit funds"]');
$I->waitForElementVisible('input[id$="-BeginPaymentTransaction"]',30);
$I->waitForElementVisible('.register_field[id$="-Amount"]',30);
$I->fillField('.register_field[id$="-Amount"]','5');
$I->fillField('.register_field[id$="-Password"]',$cardUserPass);
$I->fillField('.register_field[id$="-cvv2number"]',$cardCVV);
$I->click('input[id$="-BeginPaymentTransaction"]');

$I->waitForElementVisible('[id$="-status-transient"] .msg',30);
$I->see('Deposit in progress.','[id$="-status-transient"] .msg');

//CHECK CONFIRMATION MESSAGE
$I->waitForElementVisible('[id$="-status-success"] .msg',30);
$I->waitForElementVisible('.msg[id$="-status-success-txref"]',30);

$I->see('Deposit is successful.','[id$="-status-success"] .msg');
$I->see('Your transaction reference is:','.msg[id$="-status-success-txref"]');

//CHECK BALANCE AFTER REGULAR DEPOSIT
$I->amGoingTo('Check the balance after Regular Deposit');
$I->click('.accountbalancerefresh');
$I->wait(2); 
$fundsafterdep2=$I->grabTextFrom('span.balance_visible');
$fundsafterdep2= filter_var($fundsafterdep2, FILTER_SANITIZE_NUMBER_FLOAT); //get actual balance in Cents
$I->greentext('Funds after Regular Deposit => £'.($fundsafterdep2/100));

	if ($fundsafterdep1 + 500 == $fundsafterdep2){
		$I->amGoingTo('Balance is correct after Regular Deposit');
	} else{
		$I->redText('Balance is NOT correct after Regular Deposit');
		$failed = 2; //marking test as failed becuase balance was not updated  correctly
	}

//DO WITHDRAWAL
$I->amGoingTo('withdraw funds');
$I->click('[title="Display account details"]');
$I->click('[title="Withdraw funds"]');

$I->waitForElementVisible('.subtabs #tab2 .current_tab',30);
$I->waitForElementVisible('input[id$="-BeginPaymentTransaction"]',30);
$I->waitForElementVisible('.register_field[id$="-Amount"]',30);
$I->fillField('.register_field[id$="-Amount"]','10');
$I->fillField('.register_field[id$="-Password"]',$cardUserPass);
$I->click('input[id$="-BeginPaymentTransaction"]');

//CHECK CONFIRMATION MESSAGE
$I->waitForElementVisible('[id$="-status-approval"] .msg',30);
$I->waitForElementVisible('.msg[id$="-status-approval-txref"]',30);
$I->see('Withdraw sent for approval.','[id$="-status-approval"] .msg');
$I->see('Your transaction reference is:','.msg[id$="-status-approval-txref"]');

//CHECK BALANCE AFTER WITHDRAWAL
$I->amGoingTo('Check the balance after Withdrawal');
$I->click('.accountbalancerefresh');
$I->wait(2); 
$fundsafterwi=$I->grabTextFrom('span.balance_visible');
$fundsafterwi= filter_var($fundsafterwi, FILTER_SANITIZE_NUMBER_FLOAT); //get actual balance in Cents
$I->greentext('Funds after Withdrawal => £'.($fundsafterwi/100));

	if ($fundsafterdep2 - 1000 == $fundsafterwi){
		$I->amGoingTo('Balance is correct after Withdrawal');
	} else{
		$I->redText('Balance is NOT correct after Withdrawal');
		$failed = 3; //marking test as failed becuase balance was not updated  correctly
	}

//TRANSACTIONS REPORT
$I->greenText('<br/>Original Funds => £'.($funds/100).'<br/>Funds after Quick Deposit => £'.($fundsafterdep1/100).'<br/>Funds after Regular Deposit => £'.($fundsafterdep2/100).'<br/>Funds after Withdrawal => '.($fundsafterwi/100));

//Make test fail if balance was not updated corectly
if ($failed != 0){

	$I->redText('Balance was not correctly updated. Check teh red lines in teh report to find out which transactions failed. Failing the test.');
	$I->see('failed');
}

$I->weblogout();


?> 