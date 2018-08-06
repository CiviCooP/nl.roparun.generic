<?php

class CRM_Generic_WebsiteTypeConfig {
  
  /**
   * @var CRM_Generic_WebsiteTypeConfig
   */
  private static $singleton;
  
  private $_facebookWebsiteTypeId;
  private $_twitterWebsiteTypeId;
  private $_instagramWebsiteTypeId;
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
   * Getter for the instagram website type id.
   */
  public function getInstagramWebsiteTypeId() {
    return $this->_instagramWebsiteTypeId;
  }
  /**
   * Getter for the twitter website type id.
   */
  public function getTwitterWebsiteTypeId() {
    return $this->_twitterWebsiteTypeId;
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
      $this->_instagramWebsiteTypeId = civicrm_api3('OptionValue', 'getvalue', array(
        'return' => 'value',
        'name' => 'Instagram',
        'option_group_id' => 'website_type',
      ));
    } catch (exception $ex) {
      throw new Exception ('Could not retrieve Instagram website type id');
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
