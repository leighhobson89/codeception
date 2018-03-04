<?php
//Preparations for testing
//shell_exec('RunDll32.exe InetCpl.cpl,ClearMyTracksByProcess 2'); // Clear cookies and cache
require 'tests/acceptance/_bootstrap.php';

$enviro = $scenario->current('env'); //checking on which environment the test is running

//WEB Country variables
global $countrycodes;
global $validUKCountries,$novalidUKCountries;
global $mvalidUKCountries,$mnovalidUKCountries; //Mobile Country Variable
global $UKfailures,$UKNfailures,$MUKfailures,$MUKNfailures,$Pokerfailures,$PokerNfailures; //Failures Storage Variables
global $failed;$ToTest;


$failed=false;




//Countries Arrays


//Valid Country codes
$countrycodes=array('AS','AD','AI','AQ','AG','AR','AM','AW','AZ','BS','BH','BD','BB','BY','BZ','BJ','BM','BT','BO','BA','BW','BV','BR','IO','BN','BF','BI','KH','CM','CA','CV','KY','CF','TD','CL','CX','CC','CO','KM','CG','CD','CK','CI','CU','CY','DJ','DM','DO','TP','SV','GQ','FK','FJ','FI','GF','PF','TF','GA','GM','GE','DE','GH','GI','GL','GD','GT','GN','GY','HM','VA','HN','HU','IS','IN','ID','IE','JM','JP','KZ','KE','KI','KP','KR','KG','LA','LV','LB','LS','LR','LY','LI','LT','LU','MO','MK','MG','MW','MY','MV','ML','MT','MH','MQ','MR','MU','YT','FM','MD','MC','MN','ME','MS','MA','MZ','NA','NR','NP','NC','NZ','NI','NE','NG','NU','NF','MP','NO','OM','PW','PA','PG','PY','PE','PN','PL','PR','QA','RW','SH','KN','LC','PM','VC','WS','SM','ST','SN','RS','XS','SC','SL','SK','SI','SB','ZA','GS','LK','SR','SJ','SZ','SE','TW','TJ','TZ','TH','TG','TK','TO','TT','TN','TC','TV','UG','UA','AE','GB','UY','VU','VN','VG','VI','WF','EH','ZM');

//NOT Valid Country codes
$Ncountrycodes=array('DZ','AL','AF','AO','AU','AT','BE','BG','CN','CR','CZ','EC','HR','DK','EG','EE','ER','ET','FO','FR','GP','GR','GU','GW','HK','HT','IL','IR','IQ','IT','JO','KW','MM','MX','NL','AN','PK','PH','PT','RE','RO','RU','SA','SD','SG','SO','SY','ES','CH','TM','TR','US','UM','UZ','VE','YE','YU','ZW'); 

//UK Valid Countries (Updated, same on all platforms)
$validUKCountries=array('American Samoa','Andorra','Anguilla','Antarctica','Antigua And Barbuda','Argentina','Armenia','Aruba','Azerbaijan','Bahamas','Bahrain','Bangladesh','Barbados','Belarus','Belize','Benin','Bermuda','Bhutan','Bolivia','Bosnia And Herzegovina','Botswana','Bouvet Island','Brazil','British Indian Ocean Territory','Brunei Darussalam','Burkina Faso','Burundi','Cambodia','Cameroon','Canada','Cape Verde','Cayman Islands','Central African Republic','Chad','Chile','Christmas Island','Cocos (Keeling) Islands','Colombia','Comoros','Congo / Zaire, The Democratic Republic Of The','Congo, The Democratic Republic Of The','Cook Islands','Cote D\'Ivoire','Cuba','Cyprus','Djibouti','Dominica','Dominican Republic','East Timor','El Salvador','Equatorial Guinea','Falkland Islands (Malvinas)','Fiji','Finland','French Guiana','French Polynesia','French Southern Territories','Gabon','Gambia','Georgia','Germany','Ghana','Gibraltar','Greenland','Grenada','Guatemala','Guinea','Guyana','Heard Island And Mcdonald Islands','Holy See / Vatican City State','Honduras','Hungary','Iceland','India','Indonesia','Ireland','Jamaica','Japan','Kazakstan','Kenya','Kiribati','Korea, Democratic People\'S Republic Of','Korea, Republic Of','Kyrgyzstan','Lao People\'S Democratic Republic','Latvia','Lebanon','Lesotho','Liberia','Libyan Arab Jamahiriya','Liechtenstein','Lithuania','Luxembourg','Macau','Macedonia, The Former Yugoslav Republic Of','Madagascar','Malawi','Malaysia','Maldives','Mali','Malta','Marshall Islands','Martinique','Mauritania','Mauritius','Mayotte','Micronesia, Federated States Of','Moldova, Republic Of','Monaco','Mongolia','Montenegro','Montserrat','Morocco','Mozambique','Namibia','Nauru','Nepal','New Caledonia','New Zealand','Nicaragua','Niger','Nigeria','Niue','Norfolk Island','Northern Mariana Islands','Norway','Oman','Palau','Panama','Papua New Guinea','Paraguay','Peru','Pitcairn','Poland','Puerto Rico','Qatar','Rwanda','Saint Helena','Saint Kitts And Nevis','Saint Lucia','Saint Pierre And Miquelon','Saint Vincent And The Grenadines','Samoa','San Marino','Sao Tome And Principe','Senegal','Serbia','Serbia And Montenegro','Seychelles','Sierra Leone','Slovakia','Slovenia','Solomon Islands','South Africa','South Georgia And The South Sandwich Islands','Sri Lanka','Suriname','Svalbard And Jan Mayen','Swaziland','Sweden','Taiwan','Tajikistan','Tanzania, United Republic Of','Thailand','Togo','Tokelau','Tonga','Trinidad And Tobago','Tunisia','Turks And Caicos Islands','Tuvalu','Uganda','Ukraine','United Arab Emirates','United Kingdom','Uruguay','Vanuatu','Vietnam','Virgin Islands, British','Virgin Islands, U.S.','Wallis And Futuna','Western Sahara','Zambia');

//UK NOT Valid Countries (Updated, same on all platforms)
$novalidUKCountries=array('Algeria','Albania','Afghanistan','Angola','Australia','Austria','Belgium','Bulgaria','China','Costa Rica','Croatia','Czech Republic','Denmark','Ecuador','Egypt','Eritrea','Estonia','Ethiopia','Faroe Islands','France','Greece','Guadeloupe','Guam','Guinea-Bissau','Haiti','Hong Kong','Israel','Iran','Iraq','Italy','Jordan','Kuwait','Mexico','Myanmar','Netherlands','Netherlands Antilles','Pakistan','Philippines','Portugal','Reunion','Romania','Russian Federation','Saudi Arabia','Singapore','Somalia','Spain','Sudan','Switzerland','Syrian Arab Republic','Turkey','Turkmenistan','United States','United States Minor Outlying Islands','Uzbekistan','Venezuela','Yemen','Yugoslavia','Zimbabwe');

//Mobile
$mvalidUKCountries=array('American Samoa','Andorra','Anguilla','Antarctica','Antigua And Barbuda','Argentina','Armenia','Aruba','Azerbaijan','Bahamas','Bahrain','Bangladesh','Barbados','Belarus','Belize','Benin','Bermuda','Bhutan','Bolivia','Bosnia And Herzegovina','Botswana','Bouvet Island','Brazil','British Indian Ocean Territory','Brunei Darussalam','Burkina Faso','Burundi','Cambodia','Cameroon','Canada','Cape Verde','Cayman Islands','Central African Republic','Chad','Chile','Christmas Island','Cocos (Keeling) Islands','Colombia','Comoros','Congo / Zaire, The Democratic Republic Of The','Congo, The Democratic Republic Of The','Cook Islands','Cote D\'Ivoire','Cuba','Cyprus','Djibouti','Dominica','Dominican Republic','East Timor','El Salvador','Equatorial Guinea','Falkland Islands (Malvinas)','Fiji','Finland','French Guiana','French Polynesia','French Southern Territories','Gabon','Gambia','Georgia','Germany','Ghana','Gibraltar','Greenland','Grenada','Guatemala','Guinea','Guyana','Heard Island And Mcdonald Islands','Holy See / Vatican City State','Honduras','Hungary','Iceland','India','Indonesia','Ireland','Jamaica','Japan','Kazakstan','Kenya','Kiribati','Korea, Democratic People\'s Republic Of','Korea, Republic Of','Kyrgyzstan','Lao People\'S Democratic Republic','Latvia','Lebanon','Lesotho','Liberia','Libyan Arab Jamahiriya','Liechtenstein','Lithuania','Luxembourg','Macau','Macedonia, The Former Yugoslav Republic Of','Madagascar','Malawi','Malaysia','Maldives','Mali','Malta','Marshall Islands','Martinique','Mauritania','Mauritius','Mayotte','Micronesia, Federated States Of','Moldova, Republic Of','Monaco','Mongolia','Montenegro','Montserrat','Morocco','Mozambique','Namibia','Nauru','Nepal','New Caledonia','New Zealand','Nicaragua','Niger','Nigeria','Niue','Norfolk Island','Northern Mariana Islands','Norway','Oman','Palau','Panama','Papua New Guinea','Paraguay','Peru','Pitcairn','Poland','Puerto Rico','Qatar','Rwanda','Saint Helena','Saint Kitts And Nevis','Saint Lucia','Saint Pierre And Miquelon','Saint Vincent And The Grenadines','Samoa','San Marino','Sao Tome And Principe','Senegal','Serbia','Serbia and Montenegro','Seychelles','Sierra Leone','Slovakia','Slovenia','Solomon Islands','South Africa','South Georgia And The South Sandwich Islands','Sri Lanka','Suriname','Svalbard And Jan Mayen','Swaziland','Sweden','Taiwan','Tajikistan','Tanzania, United Republic Of','Thailand','Togo','Tokelau','Tonga','Trinidad And Tobago','Tunisia','Turks And Caicos Islands','Tuvalu','Uganda','Ukraine','United Arab Emirates','United Kingdom','Uruguay','Vanuatu','Vietnam','Virgin Islands, British','Virgin Islands, U.S.','Wallis And Futuna','Western Sahara','Zambia');

$mnovalidUKCountries=array('Algeria','Albania','Afghanistan','Angola','Australia','Austria','Belgium','Bulgaria','China','Costa Rica','Croatia','Czech Republic','Denmark','Ecuador','Egypt','Estonia','Eritrea','Ethiopia','Faroe Islands','France','Greece','Guadeloupe','Guam','Guinea-Bissau','Haiti','Hong Kong','Israel','Iran','Iraq','Italy','Jordan','Kuwait','Mexico','Myanmar','Netherlands','Netherlands Antilles','Pakistan','Philippines','Portugal','Reunion','Romania','Russian Federation','Saudi Arabia','Singapore','Somalia','Spain','Sudan','Switzerland','Syrian Arab Republic','Turkey','Turkmenistan','United States','United States Minor Outlying Islands','Uzbekistan','Venezuela','Yemen','Yugoslavia','Zimbabwe');




// @group general

$I = new AcceptanceTester($scenario);
$I->wantTo('Check Countries List on Registration');
$I->amOnPage('/');
$I->maximizeWindow();



$UKfailures="";
$UKNfailures="";

if(strpos($enviro,'wp')===false AND strpos($enviro,'ios')===false AND strpos($enviro,'an')===false AND strpos($enviro,'poker')===false){ //If not Mobile and not Poker

//WEB UK
	$ToTest="webuk";
//	$I->amGoingTo('Verify elements on Landing page');
//	$I->waitForElementVisible('.main-joinnow-cta a',30);
//	$I->click('.main-joinnow-cta a');
	$I->amGoingTo('Open Registration page');
	$I->amOnPage('/UK/802/accounting#action=register');	
	$I->waitForElementVisible('.register_field');
	
	$serverid = $I->grabTextFrom('.hiddensrv'); // Get Server ID
	$I->greenText('Server ID is: '.$serverid);
	
	/*
	$cmpid=$I->grabAttributeFrom('.register_field[type="checkbox"]', 'id'); //Grabbing the component ID
	$cmpid=str_replace(".IsTermsAndConditionsAgreed','",$cmpid);
	*/
	
	$I->waitForElementVisible('select[id$="IDMMCountry"]',30);

	//Check Valid Countries List are present in registration
	$I->amGoingTo('Check Valid Countries List are present in registration');
	foreach($validUKCountries as $country){
		
		try{
		
			$I->selectOption('select[id$="IDMMCountry"]', $country);
		//	$I->seeOptionIsSelected('#'.$cmpid.'\2e IDMMCountry',$country);
		
		}catch(Exception $e){
				
			global $UKfailures;
			$I->redText($country.' not found in the list');
			$UKfailures=$UKfailures.$country.'<>';
							
		}
		
	}
	
	//Check Not Valid Countries List are not in registration
	$I->amGoingTo('Check NOT Valid Countries List are not in registration');
	foreach($novalidUKCountries as $country){
		
		try{
		
			$I->selectOption('select[id$="IDMMCountry"]', $country);
		//	$I->seeOptionIsSelected('#'.$cmpid.'\2e IDMMCountry',$country);
			global $UKNfailures;
			$I->redText('Not valid country found in the list==>'.$country);
			$UKNfailures=$UKNfailures.$country.'<>';
			
		}catch(Exception $e){
				
										
		}
		
	}

}else{ //if it is mobile
	
	if(strpos($enviro,'poker')===false){ //check that is not Greece or Poker
		
		$ToTest='mobuk';
		
		try{
		
			$I->amGoingTo('Verify elements on Landing page');
			$I->waitForElementVisible('.main_offer img',30);

			$I->amGoingTo('Open Registration page');
			$I->click('.main_offer img');
		
		}catch(Exception $E){
			
			$I->amGoingTo('Open Registration page');
			$I->waitForElementVisible('.menu-nav-links',30);
			$I->click('Open Account','.menu-nav-links');
			
		}
		
		$I->waitForElementVisible('#edit-country',30);

		//Check Valid Countries List are present in registration
		$I->amGoingTo('Check Valid Countries List are present in registration');
		foreach($mvalidUKCountries as $country){
			
			try{
			
				$I->selectOption('#edit-country', $country);
			//	$I->seeOptionIsSelected('#edit-country',$country);
			
			}catch(Exception $e){
					
				global $MUKfailures;
				$I->redText($country.' not found in the list');
				$MUKfailures=$MUKfailures.$country.'<>';
								
			}
			
		}
		
		//Check Not Valid Countries List are not in registration
		$I->amGoingTo('Check NOT Valid Countries List are not in registration');
		foreach($mnovalidUKCountries as $country){
			
			try{
			
				$I->selectOption('#edit-country', $country);
			//	$I->seeOptionIsSelected('#edit-country',$country);
				global $MUKNfailures;
				$I->redText('Not valid country found in the list==>'.$country);
				$MUKNfailures=$MUKNfailures.$country.'<>';
				
			}catch(Exception $e){
					
											
			}
			
		}
		
	}
	
}

//If Poker

if(strpos($enviro,'poker')!==false){ 

	$ToTest="webpoker";
	$codename=0;
	$I->amOnUrl('http://www.stanjames.com/gamesroom/poker/index.ashx');
	global $countrycodes;
	
	
	$I->amGoingTo('Verify Registration page');
	$I->waitForElementVisible('[name="idmmcountry"]');
		
	
	//Get inner Html from Country Select Component
	
	$innerhtml=$I->grabAttributeFrom('[name="idmmcountry"]', 'outerHTML');
	
	foreach($countrycodes as $code){
		
		//$I->amGoingTo(sizeof($countrycodes));
		
		if(!strpos($innerhtml,$code) OR !strpos($innerhtml,$validUKCountries[$codename])){
			
			global $Pokerfailures;
			$I->redText($code."==>".$validUKCountries[$codename]." not found in Poker Registration");
			$Pokerfailures=$Pokerfailures.$code."===>".$validUKCountries[$codename]."<>";
			
		}else{
			
			$I->amGoingTo($code."===>".$validUKCountries[$codename]." Successfully Found");
		
		}
		$codename++;
	}
	
	$codename=0;
	foreach($Ncountrycodes as $code){
		
		//$I->amGoingTo(sizeof($countrycodes));
		
		if(strpos($innerhtml,$code) OR strpos($innerhtml,$novalidUKCountries[$codename])){
			
			global $PokerNfailures;
			$I->redText("Not valid code found in Poker Registration===>".$code."==>".$novalidUKCountries[$codename]);
			$PokerNfailures=$PokerNfailures.$code."==>".$novalidUKCountries[$codename]."<>";
			
		}else{
			
			$I->amGoingTo($code."==>".$novalidUKCountries[$codename]." Successfully Not Found");
		
		}
		$codename++;
	}

}

//Reports

//WEB UK
if($ToTest=="webuk"){
	
	$I->amGoingTo("It is WEB UK");
	$report='<font color="red"><h2>WEB UK</h2></font>';

	//Report Valid Countries not Found in WEB UK

	if($UKfailures!=""){
		
		global $htmlcode,$failed;
		
		$failures=explode("<>",$UKfailures);
		
		$htmlcode='<p>';
		
		foreach($failures as $fail){
			
			$htmlcode=$htmlcode.$fail.'<br>';
		
		}
			
		$htmlcode=$htmlcode.'</p>';
		
		$report=$report.'<h3> The Following valid countries were not found in registration</h3></font>'.$htmlcode;
		
			
			
		$failed=true;

	}


	//Report Not Valid Countries Found in WEB UK


	if($UKNfailures!=""){
		
		global $htmlcode,$failed;
		
		$failures=explode("<>",$UKNfailures);
		
		$htmlcode='<p>';
		
		foreach($failures as $fail){
			
			$htmlcode=$htmlcode.$fail.'<br>';
		
		}
			
		$htmlcode=$htmlcode.'</p>';
		
		$report=$report.'<font color="red"><h3> The Following not valid countries were found in registration</h3></font>'.$htmlcode;
				
		$failed=true;

	}
}


//Mobile UK

if($ToTest=="mobuk"){
	
	$I->amGoingTo("It is mobile");
	$report='<font color="red"><h2>MOBILE UK</h2></font>';

	//Report Valid Countries not Found in WEB UK

	if($MUKfailures!=""){
		
		global $htmlcode,$failed;
		
		$failures=explode("<>",$MUKfailures);
		
		$htmlcode='<p>';
		
		foreach($failures as $fail){
			
			$htmlcode=$htmlcode.$fail.'<br>';
		
		}
			
		$htmlcode=$htmlcode.'</p>';
		
		$report=$report.'<h3> The Following valid countries were not found in mobile registration</h3></font>'.$htmlcode;
		
			
			
		$failed=true;

	}

	//Report Not Valid Countries Found in Mobile UK


	if($MUKNfailures!=""){
		
		global $htmlcode,$failed;
		
		$failures=explode("<>",$MUKNfailures);
		
		$htmlcode='<p>';
		
		foreach($failures as $fail){
			
			$htmlcode=$htmlcode.$fail.'<br>';
		
		}
			
		$htmlcode=$htmlcode.'</p>';
		
		$report=$report.'<font color="red"><h3> The Following not valid countries were found in mobile registration</h3></font>'.$htmlcode;
				
		$failed=true;

	}

}

//WEB Poker

if($ToTest=="webpoker"){
	
	$I->amGoingTo("It is Web Poker");
	$report='<font color="red"><h2>WEB Poker</h2></font>';

	//Report Valid Countries not Found in WEB Poker

	if($Pokerfailures!=""){
		
		global $htmlcode,$failed;
		
		$failures=explode("<>",$Pokerfailures);
		
		$htmlcode='<p>';
		
		foreach($failures as $fail){
			
			$htmlcode=$htmlcode.$fail.'<br>';
		
		}
			
		$htmlcode=$htmlcode.'</p>';
		
		$report=$report.'<h3> The Following valid countries were not found in Poker registration</h3></font>'.$htmlcode;
		
			
			
		$failed=true;

	}

	if($PokerNfailures!=""){
		
		global $htmlcode,$failed;
		
		$failures=explode("<>",$PokerNfailures);
		
		$htmlcode='<p>';
		
		foreach($failures as $fail){
			
			$htmlcode=$htmlcode.$fail.'<br>';
		
		}
			
		$htmlcode=$htmlcode.'</p>';
		
		$report=$report.'<h3> The Following not valid countries were found in Poker registration</h3></font>'.$htmlcode;
		
			
			
		$failed=true;

	}

}

//Fail TestCase if needed

if($failed){
	
	$I->amGoingTo($report);
	$I->see('Failed');
			
}

?> 