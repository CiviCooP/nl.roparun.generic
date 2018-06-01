<?php

class CRM_Generic_WebsiteTypeConfig {
  
  /**
   * @var CRM_Generic_WebsiteTypeConfig
   */
  private static $singleton;
  
  private $_facebookWebsiteTypeId;
  private $_twitterWebsiteTypeId;
  private $_googlePlusWebsiteTypeId;
  private $_instagramWebsiteTypeId;
  private $_linkedinWebsiteTypeId;
  private $_myspaceWebsiteTypeId;
  private $_pinterestWebsiteTypeId;
  private $_snapchatWebsiteTypeId;
  private $_tumblrWebsiteTypeId;
  private $_vineWebsiteTypeId;
  private $_websiteWebsiteTypeId;
  
  private function __construct() {
    $this->loadWebsiteTypes();
  }
  
  /**
   * Getter for the main website type id.
   */
  public function getWebsiteWebsiteTypeId() {
    return $this->_websiteWebsiteTypeId;
  }
  /**
   * Getter for the facebook website type id.
   */
  public function getFacebookWebsiteTypeId() {
    return $this->_facebookWebsiteTypeId;
  }
  /**
   * Getter for the Google+ website type id.
   */
  public function getGooglePlusWebsiteTypeId() {
    return $this->_googlePlusWebsiteTypeId;
  }
  /**
   * Getter for the instagram website type id.
   */
  public function getInstagramWebsiteTypeId() {
    return $this->_instagramWebsiteTypeId;
  }
  /**
   * Getter for the LinkedIn website type id.
   */
  public function getLinkedInWebsiteTypeId() {
    return $this->_linkedinWebsiteTypeId;
  }
  /**
   * Getter for the myspace website type id.
   */
  public function getMySpaceWebsiteTypeId() {
    return $this->_myspaceWebsiteTypeId;
  }
  /**
   * Getter for the pinterest website type id.
   */
  public function getPinterestWebsiteTypeId() {
    return $this->_pinterestWebsiteTypeId;
  }
  /**
   * Getter for the snapchat website type id.
   */
  public function getSnapChatWebsiteTypeId() {
    return $this->_snapchatWebsiteTypeId;
  }
  /**
   * Getter for the tumblr website type id.
   */
  public function getTumblrWebsiteTypeId() {
    return $this->_tumblrWebsiteTypeId;
  }
  /**
   * Getter for the twitter website type id.
   */
  public function getTwitterWebsiteTypeId() {
    return $this->_twitterWebsiteTypeId;
  }
  /**
   * Getter for the vine website type id.
   */
  public function getVineWebsiteTypeId() {
    return $this->_vineWebsiteTypeId;
  }
  
  /**
   * @return CRM_Generic_WebsiteTypeConfig
   */
  public static function singleton() {
    if (!self::$singleton) {
      self::$singleton = new CRM_Generic_WebsiteTypeConfig();
    }
    return self::$singleton;
  }
  
  private function loadWebsiteTypes() {
    try {
      $this->_websiteWebsiteTypeId = civicrm_api3('OptionValue', 'getvalue', array(
        'return' => 'value',
        'name' => 'Main',
        'option_group_id' => 'website_type',
      ));
    } catch (exception $ex) {
      throw new Exception ('Could not retrieve Main website type id');
    } 
    try {
      $this->_facebookWebsiteTypeId = civicrm_api3('OptionValue', 'getvalue', array(
        'return' => 'value',
        'name' => 'Facebook',
        'option_group_id' => 'website_type',
      ));
    } catch (exception $ex) {
      throw new Exception ('Could not retrieve Facebook website type id');
    }
    
    try {
      $this->_googlePlusWebsiteTypeId = civicrm_api3('OptionValue', 'getvalue', array(
        'return' => 'value',
        'name' => 'Google_',
        'option_group_id' => 'website_type',
      ));
    } catch (exception $ex) {
      throw new Exception ('Could not retrieve Google+ website type id');
    }
    try {
      $this->_instagramWebsiteTypeId = civicrm_api3('OptionValue', 'getvalue', array(
        'return' => 'value',
        'name' => 'Instagram',
        'option_group_id' => 'website_type',
      ));
    } catch (exception $ex) {
      throw new Exception ('Could not retrieve Instagram website type id');
    }
    try {
      $this->_linkedinWebsiteTypeId = civicrm_api3('OptionValue', 'getvalue', array(
        'return' => 'value',
        'name' => 'LinkedIn',
        'option_group_id' => 'website_type',
      ));
    } catch (exception $ex) {
      throw new Exception ('Could not retrieve LinkedIn website type id');
    }
    try {
      $this->_myspaceWebsiteTypeId = civicrm_api3('OptionValue', 'getvalue', array(
        'return' => 'value',
        'name' => 'MySpace',
        'option_group_id' => 'website_type',
      ));
    } catch (exception $ex) {
      throw new Exception ('Could not retrieve MySpace website type id');
    }
    try {
      $this->_pinterestWebsiteTypeId = civicrm_api3('OptionValue', 'getvalue', array(
        'return' => 'value',
        'name' => 'Pinterest',
        'option_group_id' => 'website_type',
      ));
    } catch (exception $ex) {
      throw new Exception ('Could not retrieve Pinterest website type id');
    }
    try {
      $this->_snapchatWebsiteTypeId = civicrm_api3('OptionValue', 'getvalue', array(
        'return' => 'value',
        'name' => 'SnapChat',
        'option_group_id' => 'website_type',
      ));
    } catch (exception $ex) {
      throw new Exception ('Could not retrieve SnapChat website type id');
    }
    try {
      $this->_tumblrWebsiteTypeId = civicrm_api3('OptionValue', 'getvalue', array(
        'return' => 'value',
        'name' => 'Tumblr',
        'option_group_id' => 'website_type',
      ));
    } catch (exception $ex) {
      throw new Exception ('Could not retrieve Tumblr website type id');
    }
    try {
      $this->_vineWebsiteTypeId = civicrm_api3('OptionValue', 'getvalue', array(
        'return' => 'value',
        'name' => 'Vine',
        'option_group_id' => 'website_type',
      ));
    } catch (exception $ex) {
      throw new Exception ('Could not retrieve Vinc website type id');
    }
    try {
      $this->_twitterWebsiteTypeId = civicrm_api3('OptionValue', 'getvalue', array(
        'return' => 'value',
        'name' => 'Twitter',
        'option_group_id' => 'website_type',
      ));
    } catch (exception $ex) {
      throw new Exception ('Could not retrieve Twitter website type id');
    }
  }
  
}
