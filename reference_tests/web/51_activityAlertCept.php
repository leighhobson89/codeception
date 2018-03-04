<?php
require 'tests/acceptance/_bootstrap.php';

// @group general
// @group activity

$I = new AcceptanceTester($scenario);
$I->wantTo('Test Activity Alert popup on Website');

$I->amOnPage('/');
$I->maximizeWindow();

$I->amGoingTo('Verify elements on Landing page');
$I->waitForElementVisible('div.account h4:nth-child(3) a',30); // Wait for Registration button

$I->amGoingTo('Navigate to homepage');
$I->click('.main-logo a');
$I->waitForElement('iframe',30);

$serverid = $I->grabTextFrom('.hiddensrv'); // Get Server ID
$I->greenText('Server ID is: '.$serverid);

$I->amGoingTo('Log in');
$I->fillField('[name="username"]',$activitytestuser);
$I->fillField('[name="password"]',$activitytestPass);
$I->click('#xLogin.loginbtn');
$I->waitForElementVisible('.accountname a',30);

$I->amGoingTo('Verify Activity Alert');
$I->waitForElementVisible('.overlay_message_activityalert',30);
$I->seeElement('.overlay_message_activityalert');
$I->seeElement('.overlay_message_activityalert .overlay_message_btn');
$I->see('Yes, I\'m fine','.overlay_message_activityalert .overlay_message_btn');
$I->seeElement('.overlay_message_activityalert .btn_gray');
$I->see('No, please log me out','.overlay_message_activityalert .btn_gray');
$I->seeElement('.overlay_message_activityalert .odds_text_link');
$I->see('Click here to view your account history','.overlay_message_activityalert .odds_text_link');

$I->amGoingTo('Click on Yes button');
$I->click('.overlay_message_activityalert .overlay_message_btn');
$I->waitForElementNotVisible('.overlay_message_activityalert',30);

$I->amGoingTo('wait for Alert to appear again and Verify it');
$I->waitForElementVisible('.overlay_message_activityalert',30);
$I->seeElement('.overlay_message_activityalert');
$I->seeElement('.overlay_message_activityalert .overlay_message_btn');
$I->see('Yes, I\'m fine','.overlay_message_activityalert .overlay_message_btn');
$I->seeElement('.overlay_message_activityalert .btn_gray');
$I->see('No, please log me out','.overlay_message_activityalert .btn_gray');
$I->seeElement('.overlay_message_activityalert .odds_text_link');
$I->see('Click here to view your account history','.overlay_message_activityalert .odds_text_link');

$I->amGoingTo('Click on Statement link');
$I->click('.overlay_message_activityalert .odds_text_link');
$I->waitForElementVisible('.statement-history',30);
$I->seeInCurrentUrl('statement');

$I->amGoingTo('wait for Alert to appear again and Verify it');
$I->waitForElementVisible('.overlay_message_activityalert',30);
$I->seeElement('.overlay_message_activityalert');
$I->seeElement('.overlay_message_activityalert .overlay_message_btn');
$I->see('Yes, I\'m fine','.overlay_message_activityalert .overlay_message_btn');
$I->seeElement('.overlay_message_activityalert .btn_gray');
$I->see('No, please log me out','.overlay_message_activityalert .btn_gray');
$I->seeElement('.overlay_message_activityalert .odds_text_link');
$I->see('Click here to view your account history','.overlay_message_activityalert .odds_text_link');

$I->amGoingTo('Click on Log out button');
$I->click('.overlay_message_activityalert .btn_gray');

$I->waitForElementVisible('[name="username"]',60);
$I->dontSeeElement('a span.balance_visible');

$I->waitForElementNotVisible('.overlay_message_activityalert',30);
$I->dontSeeElement('.overlay_message_activityalert');


?>

