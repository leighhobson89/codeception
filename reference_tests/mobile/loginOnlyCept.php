<?php
require 'tests/acceptance/_bootstrap.php';

// @group general

$I = new AcceptanceTester($scenario);

$I->wantTo('Test LogIn');

$I->amOnPage('/');
$I->resizeWindow(411, 731); // Nexus5 size

$I->amGoingTo('Verify elements on Landing page');
$I->waitForElementVisible('.cta',30);

$I->amGoingTo('Open Login');
$I->click('.cta');
$I->waitForElement('.login-button-link',30);

//Login
$I->amGoingTo('Log in');
$I->fillField('.form-item-username input',$logintestuser);
$I->fillField('.form-item-password input',$logintestuserPass);
$I->click('.login-button-link');

$I->waitForElementVisible('.login-modal-continue-container',30); //Wait for popup to appear
$I->click('.login-modal-continue-container');

//Get Balance
$I->waitForElementVisible('.tradingbalance',30);
$startbalance=$I->grabTextFrom('.tradingbalance');
$startbalance= filter_var($startbalance, FILTER_SANITIZE_NUMBER_FLOAT); //get actual balance in Cents
$I->amGoingTo("User Balance is £".($startbalance/100));

//LogOut

$I->amGoingTo('Log Out');
$I->wait(1);
$I->click('Account','.menu-nav-links.menu_table');

$I->waitForElementVisible('#logoutId',30);
$I->click('#logoutId');

$I->waitForElementVisible('.urlAftLogin',30);

?> 
