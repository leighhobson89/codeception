<?php
require 'tests/acceptance/_bootstrap.php';

//@group general

$I = new AcceptanceTester($scenario);
$I->wantTo('test Recover password functionality');

$I->amOnPage('/');
$I->maximizeWindow();

$I->amGoingTo('Verify elements on Landing page');
$I->waitForElementVisible('[title="Sports"]',30); //Wait for Sports tab

$I->amGoingTo('Open Sports page'); 
$I->click('[title="Sports"]'); //click on Sports tab
$I->waitForElement('iframe',30); //wait for Twitter iframe

$serverid = $I->grabTextFrom('.hiddensrv'); // Get Server ID
$I->greenText('Server ID is: '.$serverid);

$I->amGoingTo('Recover Password');
$I->click('.component_container form p a'); //click on forgot password link
$I->waitForElementVisible('.customersupport',30); //wait for customer support frame

$cmpid=$I->grabAttributeFrom('div.register_field_line_wrapper:nth-child(2) input.register_field.register_field_full_width', 'id'); //Grabbing the component ID
$cmpid=str_replace("-username","",$cmpid);
$I->amGoingTo('The component ID is:'.$cmpid);

$I->waitForElementVisible('input#'.$cmpid.'-username',30); // ait for Username field
$I->waitForElementVisible('form#'.$cmpid.'-form-1 a.register_account_btn:nth-child(1)',30); //wait for Next Button

$I->fillField('input#'.$cmpid.'-username',$username_02); //fill username field 
$I->selectOption('select#'.$cmpid.'-date-of-birth-day','1'); //fill day
$I->selectOption('select#'.$cmpid.'-date-of-birth-month','January'); //fill month
$I->selectOption('select#'.$cmpid.'-date-of-birth-year','1991'); //fill year

$I->seeCheckboxIsChecked('input#'.$cmpid.'-retrieval-method-secret-question'); // Answer secret question is checked
$I->dontSeeCheckboxIsChecked('input#'.$cmpid.'-retrieval-method-email-new-password'); // New password by Email is not checked

//Bypass captcha
$I->seeElement('.g-recaptcha');
$I->executeJS('document.getElementById("g-recaptcha-response").style.display = "block"');
$I->fillField('.g-recaptcha-response','5765617265746865626573747161');

$I->click('form#'.$cmpid.'-form-1 a.register_account_btn:nth-child(1)'); //click on next button

$I->waitForElementVisible('input#'.$cmpid.'-answer',30); //wait for Answer field is visible

$I->see('Free text','span#'.$cmpid.'-step-2-0.retrieve_password_question');
$I->fillField('input#'.$cmpid.'-answer','QA'); //fill answer field 
$I->fillField('input#'.$cmpid.'-new-password','123456'); //fill New Password field 
$I->fillField('input#'.$cmpid.'-confirm-new-password','123456'); //fill Confirm New Password field 

$I->click('form#'.$cmpid.'-form-2 a.register_account_btn:nth-child(1)'); //click on next button

$I->waitForElementVisible('div#'.$cmpid.'-success a.register_account_btn',30); // wait for Continue betting button
$I->see('Your password has been changed','li#'.$cmpid.'-success-0.msg');
$I->see('You have been logged in and are being redirected back to our homepage','li#'.$cmpid.'-success-1.msg');

$I->amGoingTo('Verify username');
$I->waitForElementVisible('a span.balance_visible',30); //Verify Balance is available
$I->see($username_02,'.balance p'); // Verify username
$I->click('[title="Display account summary"]');
$I->see($username_02,'.accountnameselection li:nth-child(2)'); //Verify username

$I->click('div#'.$cmpid.'-success a.register_account_btn'); // Click on Continue Betting button
$I->waitForElementVisible('div.twitter.rightcol_block iframe',30); // Wait for Twitter iframe

$I->weblogoutFull();

$I->amGoingTo('Log in using new password');

$I->amGoingTo('Log in');
$I->fillField('[name="username"]',$username_02);//Fill Username Field
$I->fillField('[name="password"]','123456');//Fill NEW Password Field
$I->click('#xLogin.loginbtn');
$I->waitForElementVisible('a span.balance_visible',30); //Verify Balance is available
$I->waitForElementVisible('[title="Display account summary"]',30);

$I->amGoingTo('Verify username Again');
$I->click('[title="Display account summary"]');
$I->see($username_02,'.accountnameselection li:nth-child(2)'); //Verify username
$I->click('[title="Display account summary"]');

$I->weblogout();

?> 