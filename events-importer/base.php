<?php // Turn Errors On
include 'config.php';

// Add Zend to load path
set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__FILE__));

// Include the loader
require 'Zend/Loader.php';

Zend_Loader::loadClass('Zend_Gdata');
Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
Zend_Loader::loadClass('Zend_Gdata_Spreadsheets');
Zend_Loader::loadClass('Zend_Gdata_App_AuthException');
Zend_Loader::loadClass('Zend_Http_Client');

try {
   $client = Zend_Gdata_ClientLogin::getHttpClient($gdata_user, $gdata_pass, 'wise');
} catch (Zend_Gdata_App_CaptchaRequiredException $cre) {
   // This was from the sample code, haven't even begun to look at what
   // needs to happen if this error occurs
   echo 'URL of CAPTCHA image: ' . $cre->getCaptchaUrl() . "\n";
   echo 'Token ID: ' . $cre->getCaptchaToken() . "\n";
   die();
} catch (Zend_Gdata_App_AuthException $ae) {
   echo 'Problem authenticating: ' . $ae->exception() . "\n";
   die();
} ?>