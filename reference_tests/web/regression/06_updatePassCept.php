<?php
require 'tests/acceptance/_bootstrap.php';

//@group general

$I = new AcceptanceTester($scenario);
$I->wantTo('test Update password in My Account functionality');

$I->amOnPage('/');
$I->maximizeWindow();

$I->amGoingTo('Verify elements on Landing page');
$I->waitForElementVisible('[title="Sports"]',30); //Wait for Sports tab

$I->amGoingTo('Open Sports page'); 
$I->click('[title="Sports"]'); //click on Sports tab
$I->waitForElement('iframe',30); //wait for Twitter iframe

$serverid = $I->grabTextFrom('.hiddensrv'); // Get Server ID
$I->greenText('Server ID is: '.$serverid);

$I->weblogin($username_03,$password_03);

$I->amGoingTo('Open MyAccount page');
$I->click('.dropdown.accountnumber .spanLink');
$I->waitForElementVisible('.account_header_link',30);
$I->click('.account_header_link');

$I->amGoingTo('Open Password Tab');
$I->wait(1);
$I->click('Password','.subtabs');
$I->waitForElementVisible('.fpfld h4',30);

$I->amGoingTo('Change user password');
$I->see($username_03,'.fpfld h4'); //Verify username
$I->seeElement('#lbloldPassword input');
$I->fillField('#lbloldPassword input',$password_03);
$I->seeElement('#lblpassword input');
$I->fillField('#lblpassword input','123456');
$I->seeElement('#lblpassword2 input');
$I->fillField('#lblpassword2 input','123456');

$I->waitForElementVisible('.accountcontent:nth-child(4) .accountsave',30);
$I->click('.accountcontent:nth-child(4) .accountsave');
$I->waitForText('Your Password was successfully changed',30);

$I->weblogoutFull();

$I->amGoingTo('Log in using new password');
$I->amGoingTo('Log in');
$I->fillField('[name="username"]',$username_03);//Fill Username Field
$I->fillField('[name="password"]','123456');//Fill NEW Password Field
$I->click('#xLogin.loginbtn');
$I->waitForElementVisible('.accountname a',30);
$I->amGoingTo('Verify username');
$I->click('.accountname a');
$I->see($username_03,'.accountnameselection li:nth-child(2)'); //username
$I->click('.accountname a');
		
$I->amGoingTo('Open MyAccount page');
$I->click('.dropdown.accountnumber .spanLink');
$I->waitForElementVisible('.account_header_link',30);
$I->click('.account_header_link');

$I->amGoingTo('Open Password Tab');
$I->click('Password','.subtabs');
$I->waitForElementVisible('.fpfld h4',30);

$I->amGoingTo('Change password back to original');
$I->see($username_03,'.fpfld h4'); //Verify username
$I->seeElement('#lbloldPassword input');
$I->fillField('#lbloldPassword input','123456');
$I->seeElement('#lblpassword input');
$I->fillField('#lblpassword input',$password_03);
$I->seeElement('#lblpassword2 input');
$I->fillField('#lblpassword2 input',$password_03);

$I->waitForElementVisible('.accountcontent:nth-child(4) .accountsave',30);
$I->click('.accountcontent:nth-child(4) .accountsave');
$I->waitForText('Your Password was successfully changed',30);

$I->weblogoutFull();

$I->weblogin($username_03,$password_03);

$I->weblogout();

?> 