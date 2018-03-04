<?php
require 'tests/acceptance/_bootstrap.php';

// @group general
// @group selfExclusion

$I = new AcceptanceTester($scenario);
$I->wantTo('Ensure Self Excluded player can\'t Deposit');

$I->amOnPage('/');
$I->maximizeWindow();

$I->amGoingTo('Verify elements on Landing page');
$I->waitForElementVisible('a.loginbtn',30); // Wait for Login button

$I->amGoingTo('Open Sports page'); 
$I->click('[title="Sports"]'); //click on Sports tab
$I->waitForElement('iframe',30); //wait for Twitter iframe

$serverid = $I->grabTextFrom('.hiddensrv'); // Get Server ID
$I->greenText('Server ID is: '.$serverid);

$I->weblogin($excludedtestuser,$excludedtestPass);

$funds=$I->grabTextFrom('span.balance_visible');
$funds=filter_var($funds, FILTER_SANITIZE_NUMBER_FLOAT); //get actual balance in Cents
$I->amGoingTo('Actual Funds => '.$funds); //Shows Funds

//QUICK DEPOSIT
$I->amGoingTo('Try to do Quick Deposit');
$I->amGoingTo('do a Quick Deposit');
$I->waitForElementVisible('.deposit_link',30);
$I->click('.deposit_link');
$I->waitForElementVisible('.selfexcluded-qd',30);
$I->see('SELF-EXCLUDED','.selfexcluded-qd h1');
$I->dontSeeElement('input[id$="-Amount"]');
$I->dontSeeElement('input[id$="-Password"]');
$I->dontSeeElement('input[id$="-CVV2Number"]');
$I->dontSeeElement('.overlay_deposit_deposit_btn');

$I->click('div[id$="quick-deposit-form"] .overlay_close'); // Close Quick Deposit window

//REGULAR DEPOSIT
$I->amGoingTo('Try to do Regular Deposit');
$I->click('[title="Display account details"]');
$I->click('[title="Deposit funds"]');

$I->waitForElementVisible('.selfexcluded',30);
$I->see('SELF-EXCLUDED','.selfexcluded h1');
$I->dontSeeElement('.register_field[id$="-Amount"]');
$I->dontSeeElement('.register_field[id$="-Password"]');
$I->dontSeeElement('.register_field[id$="-cvv2number"]');
$I->dontSeeElement('input[id$="-BeginPaymentTransaction"]');

//WITHDRAWAL
$I->amGoingTo('Try to do Withdrawal');
$I->click('[title="Display account details"]');
$I->click('[title="Withdraw funds"]');

$I->waitForElementVisible('.subtabs #tab2 .current_tab',30);
$I->waitForElementVisible('.selfexcluded',30);
$I->see('SELF-EXCLUDED','.selfexcluded h1');

$I->dontSeeElement('.register_field[id$="-Amount"]');
$I->dontSeeElement('.register_field[id$="-Password"]');
$I->dontSeeElement('input[id$="-BeginPaymentTransaction"]');

$I->weblogout();

?>