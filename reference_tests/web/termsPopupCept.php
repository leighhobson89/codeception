<?php
require 'tests/acceptance/_bootstrap.php';

global $accbutton;
$accbutton=array();

// @group general
// @group t_c_web

$I = new AcceptanceTester($scenario);

$I->wantTo('Check that Terms & Conditions PopUp appears in different page areas');

//Clear T&Cs flag before starting
$I->amOnUrl($termsClearPath.$environment);
$I->waitForText('T&Cs Value Successfully Changed',30);

//Open homepage
$I->amGoingTo('Open website');
$I->amOnUrl($url);
$I->maximizeWindow();

$I->amGoingTo('Verify elements on Landing page');
$I->waitForElementVisible('a.loginbtn',30); // Wait for Login button

$I->amGoingTo('Open HomePage');
$I->click('a.loginbtn'); //Click on Login
$I->waitForElement('iframe',30);

$serverid = $I->grabTextFrom('.hiddensrv'); // Get Server ID
$I->greenText('Server ID is: '.$serverid);

$I->amGoingTo('Log in');
$I->fillField('[name="username"]',$termsuser);
$I->fillField('[name="password"]',$termsuserpass);
$I->click('#xLogin.loginbtn');
$I->waitForElementVisible('div.overlay_message.box_shadow.termsandconditions',30); //Wait for popup to appear

$currenturl = $I->grabFromCurrentUrl('');
$I->amGoingTo('Popup appears in: '.$currenturl);

$I->amGoingTo('Verify popup');
$I->waitForElementVisible('.accountsave.termscancelbutton',30);
$I->seeElement('.overlay_deposit_setup_btn.btn_orange.termsorangebut');
$I->see('Our Terms and Conditions have changed, by clicking','.termtext');
$I->see('Accept','.termtext');
$I->see('you are agreeing to our new','.termtext');
$I->see('Terms and Conditions','.termtext .greenhighlighttext');

$I->amGoingTo('Open T&C Page from popup');
$I->wait(2);
$I->click('.termtext a');
$I->waitForElementVisible('.termshelpcontainer .orrangebuthelp',30);
$I->see('Please read and accept our new Terms and Conditions','.termtexthelp');
$I->seeElement('.termshelpcontainer .orrangebuthelp');

$I->weblogoutFull();

$I->amGoingTo('Accept T&C buttons are not available');
$I->dontSee('Please read and accept our new Terms and Conditions','.termtexthelp');
$I->dontSeeElement('.termshelpcontainer .orrangebuthelp');

//Test Help page area
$I->amGoingTo('Log in');
$I->fillField('[name="username"]',$termsuser);
$I->fillField('[name="password"]',$termsuserpass);
$I->click('#xLogin.loginbtn');
$I->waitForElementVisible('.termshelpcontainer .orrangebuthelp',30);//Wait for Accept button to be available
$I->see('Please read and accept our new Terms and Conditions','.termtexthelp');
$I->seeElement('.termshelpcontainer .orrangebuthelp');

$currenturl = $I->grabFromCurrentUrl('');
$I->amGoingTo('Popup appears in: '.$currenturl);

$I->weblogoutFull();

$I->amGoingTo('Open Homepage');
$I->click('.main-logo a'); // Click on Main Logo
$I->waitForElement('iframe',30);

//Test Betting page area
$I->amGoingTo('Navigate to Betting area');
$I->waitForElementVisible('.wghorse',30);
$I->click('.wghorse');
$I->waitForElementVisible('.maintabs .current_tab',30);

$I->amGoingTo('Log in');
$I->fillField('[name="username"]',$termsuser);
$I->fillField('[name="password"]',$termsuserpass);
$I->click('#xLogin.loginbtn');
$I->waitForElementVisible('div.overlay_message.box_shadow.termsandconditions',30); //Wait for popup to appear

$currenturl = $I->grabFromCurrentUrl('');
$I->amGoingTo('Popup appears in: '.$currenturl);

$I->amGoingTo('Verify popup');
$I->waitForElementVisible('.accountsave.termscancelbutton',30);
$I->seeElement('.overlay_deposit_setup_btn.btn_orange.termsorangebut');
$I->see('Our Terms and Conditions have changed, by clicking','.termtext');
$I->see('Accept','.termtext');
$I->see('you are agreeing to our new','.termtext');
$I->see('Terms and Conditions','.termtext .greenhighlighttext');

$I->amGoingTo('Cancel Acceptance and Log Out');
$I->click('.accountsave.termscancelbutton');
$I->waitForElementVisible('[name="username"]',60);
$I->dontSeeElement('a span.balance_visible');

//Test BetAndWatch page area
$I->amGoingTo('Navigate to BetAndWatch page area');
$I->click('li[action=live]');
$I->waitForElementVisible('.streaminglogo',30); //wait for streaming logo

$I->amGoingTo('Log in');
$I->fillField('[name="username"]',$termsuser);
$I->fillField('[name="password"]',$termsuserpass);
$I->click('#xLogin.loginbtn');
$I->waitForElementVisible('div.overlay_message.box_shadow.termsandconditions',30); //Wait for popup to appear

$currenturl = $I->grabFromCurrentUrl('');
$I->amGoingTo('Popup appears in: '.$currenturl);

$I->amGoingTo('Verify popup');
$I->waitForElementVisible('.accountsave.termscancelbutton',30);
$I->seeElement('.overlay_deposit_setup_btn.btn_orange.termsorangebut');
$I->see('Our Terms and Conditions have changed, by clicking','.termtext');
$I->see('Accept','.termtext');
$I->see('you are agreeing to our new','.termtext');
$I->see('Terms and Conditions','.termtext .greenhighlighttext');

$I->amGoingTo('Cancel Acceptance and Log Out');
$I->click('.accountsave.termscancelbutton');
$I->waitForElementVisible('[name="username"]',60);
$I->dontSeeElement('a span.balance_visible');

//Test Accounting page area
$I->amGoingTo('Navigate to Accounting page area - deposit page');
$I->amOnPage('/UK/802/Accounting#action=deposit');
$I->waitForElementVisible('.customersupport',30); //wait for captcha

$I->amGoingTo('Log in');
$I->fillField('[name="username"]',$termsuser);
$I->fillField('[name="password"]',$termsuserpass);
$I->click('#xLogin.loginbtn');
$I->waitForElementVisible('div.overlay_message.box_shadow.termsandconditions',30); //Wait for popup to appear

$currenturl = $I->grabFromCurrentUrl('');
$I->amGoingTo('Popup appears in: '.$currenturl);

$I->amGoingTo('Verify popup');
$I->waitForElementVisible('.accountsave.termscancelbutton',30);
$I->seeElement('.overlay_deposit_setup_btn.btn_orange.termsorangebut');
$I->see('Our Terms and Conditions have changed, by clicking','.termtext');
$I->see('Accept','.termtext');
$I->see('you are agreeing to our new','.termtext');
$I->see('Terms and Conditions','.termtext .greenhighlighttext');

$I->amGoingTo('Cancel Acceptance and Log Out');
$I->click('.accountsave.termscancelbutton');
$I->waitForElementVisible('[name="username"]',60);
$I->dontSeeElement('a span.balance_visible');

//Test Casino page area
$I->amGoingTo('Navigate to Casino page area');
$I->click('.casino');
$I->waitForElementVisible('.slides_container',60); //wait for Casino sliders

$I->amGoingTo('Log in');
$I->fillField('[name="username"]',$termsuser);
$I->fillField('[name="password"]',$termsuserpass);
$I->click('#xLogin.loginbtn');
$I->waitForElementVisible('div.overlay_message.box_shadow.termsandconditions',30); //Wait for popup to appear

$currenturl = $I->grabFromCurrentUrl('');
$I->amGoingTo('Popup appears in: '.$currenturl);

$I->amGoingTo('Verify popup');
$I->waitForElementVisible('.accountsave.termscancelbutton',30);
$I->seeElement('.overlay_deposit_setup_btn.btn_orange.termsorangebut');
$I->see('Our Terms and Conditions have changed, by clicking','.termtext');
$I->see('Accept','.termtext');
$I->see('you are agreeing to our new','.termtext');
$I->see('Terms and Conditions','.termtext .greenhighlighttext');

$I->amGoingTo('Cancel Acceptance and Log Out');
$I->click('.accountsave.termscancelbutton');
$I->waitForElementVisible('[name="username"]',60);
$I->dontSeeElement('a span.balance_visible');

//Test Inplay page area
$I->amGoingTo('Navigate to InPlay page area');
$I->click('.inplay');
$I->waitForElementVisible('.livescore',60); //wait for livescore

$I->amGoingTo('Log in');
$I->fillField('[name="username"]',$termsuser);
$I->fillField('[name="password"]',$termsuserpass);
$I->click('#xLogin.loginbtn');
$I->waitForElementVisible('div.overlay_message.box_shadow.termsandconditions',30); //Wait for popup to appear

$currenturl = $I->grabFromCurrentUrl('');
$I->amGoingTo('Popup appears in: '.$currenturl);

$I->amGoingTo('Verify popup');
$I->waitForElementVisible('.accountsave.termscancelbutton',30);
$I->seeElement('.overlay_deposit_setup_btn.btn_orange.termsorangebut');
$I->see('Our Terms and Conditions have changed, by clicking','.termtext');
$I->see('Accept','.termtext');
$I->see('you are agreeing to our new','.termtext');
$I->see('Terms and Conditions','.termtext .greenhighlighttext');

$I->amGoingTo('Cancel Acceptance and Log Out');
$I->click('.accountsave.termscancelbutton');
$I->waitForElementVisible('[name="username"]',60);
$I->dontSeeElement('a span.balance_visible');

//Test Games page area
$I->amGoingTo('Navigate to Games page area');
$I->click('.gamesli');
$I->waitForElementVisible('.slides_container',60); //wait for Games sliders

$I->amGoingTo('Log in');
$I->fillField('[name="username"]',$termsuser);
$I->fillField('[name="password"]',$termsuserpass);
$I->click('#xLogin.loginbtn');
$I->waitForElementVisible('div.overlay_message.box_shadow.termsandconditions',30); //Wait for popup to appear

$currenturl = $I->grabFromCurrentUrl('');
$I->amGoingTo('Popup appears in: '.$currenturl);

$I->amGoingTo('Verify popup');
$I->waitForElementVisible('.accountsave.termscancelbutton',30);
$I->seeElement('.overlay_deposit_setup_btn.btn_orange.termsorangebut');
$I->see('Our Terms and Conditions have changed, by clicking','.termtext');
$I->see('Accept','.termtext');
$I->see('you are agreeing to our new','.termtext');
$I->see('Terms and Conditions','.termtext .greenhighlighttext');

$I->amGoingTo('Cancel Acceptance and Log Out');
$I->click('.accountsave.termscancelbutton');
$I->waitForElementVisible('[name="username"]',60);
$I->dontSeeElement('a span.balance_visible');

//Test Poker page area
$I->amGoingTo('Navigate to Poker page area');
$I->click('.poker');
$I->waitForElementVisible('.slideshow',30); //wait for Poker sliders

$I->amGoingTo('Log in');
$I->fillField('[name="username"]',$termsuser);
$I->fillField('[name="password"]',$termsuserpass);
$I->click('#xLogin.loginbtn');
$I->waitForElementVisible('div.overlay_message.box_shadow.termsandconditions',30); //Wait for popup to appear

$currenturl = $I->grabFromCurrentUrl('');
$I->amGoingTo('Popup appears in: '.$currenturl);

$I->amGoingTo('Verify popup');
$I->waitForElementVisible('.accountsave.termscancelbutton',30);
$I->seeElement('.overlay_deposit_setup_btn.btn_orange.termsorangebut');
$I->see('Our Terms and Conditions have changed, by clicking','.termtext');
$I->see('Accept','.termtext');
$I->see('you are agreeing to our new','.termtext');
$I->see('Terms and Conditions','.termtext .greenhighlighttext');

$I->amGoingTo('Cancel Acceptance and Log Out');
$I->click('.accountsave.termscancelbutton');
$I->waitForElementVisible('[name="username"]',60);
$I->dontSeeElement('a span.balance_visible');

//Test Live Casino page area
$I->amGoingTo('Navigate to Live Casino page area');
$I->click('.livecasino');
$I->waitForElementVisible('.slides_container',30); //wait for Live Casino sliders

$I->amGoingTo('Log in');
$I->fillField('[name="username"]',$termsuser);
$I->fillField('[name="password"]',$termsuserpass);
$I->click('#xLogin.loginbtn');
$I->waitForElementVisible('div.overlay_message.box_shadow.termsandconditions',30); //Wait for popup to appear

$currenturl = $I->grabFromCurrentUrl('');
$I->amGoingTo('Popup appears in: '.$currenturl);

$I->amGoingTo('Verify popup');
$I->waitForElementVisible('.accountsave.termscancelbutton',30);
$I->seeElement('.overlay_deposit_setup_btn.btn_orange.termsorangebut');
$I->see('Our Terms and Conditions have changed, by clicking','.termtext');
$I->see('Accept','.termtext');
$I->see('you are agreeing to our new','.termtext');
$I->see('Terms and Conditions','.termtext .greenhighlighttext');

$I->amGoingTo('Cancel Acceptance and Log Out');
$I->click('.accountsave.termscancelbutton');
$I->waitForElementVisible('[name="username"]',60);
$I->dontSeeElement('a span.balance_visible');

//Test Mobile page area
$I->amGoingTo('Navigate to Mobile page area');
$I->click('.mobile');
$I->waitForElementVisible('iframe',60);
$mobframe=$I->grabAttributeFrom('iframe', 'id'); //Pick IFrame ID
$I->switchToIFrame($mobframe);
$I->waitForElementVisible('.free-bet-banner',90);
$I->switchToIFrame();

$I->amGoingTo('Log in');
$I->fillField('[name="username"]',$termsuser);
$I->fillField('[name="password"]',$termsuserpass);
$I->click('#xLogin.loginbtn');
$I->waitForElementVisible('div.overlay_message.box_shadow.termsandconditions',30); //Wait for popup to appear

$currenturl = $I->grabFromCurrentUrl('');
$I->amGoingTo('Popup appears in: '.$currenturl);

$I->amGoingTo('Verify popup');
$I->waitForElementVisible('.accountsave.termscancelbutton',30);
$I->seeElement('.overlay_deposit_setup_btn.btn_orange.termsorangebut');
$I->see('Our Terms and Conditions have changed, by clicking','.termtext');
$I->see('Accept','.termtext');
$I->see('you are agreeing to our new','.termtext');
$I->see('Terms and Conditions','.termtext .greenhighlighttext');

$I->amGoingTo('Cancel Acceptance and Log Out');
$I->click('.accountsave.termscancelbutton');
$I->waitForElementVisible('[name="username"]',60);
$I->dontSeeElement('a span.balance_visible');

?> 