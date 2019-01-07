<?php
/**
 * @author Jaap Jansma <jaap.jansma@civicoop.org>
 * @license AGPL-3.0
 */

class CRM_Generic_VehicleConfig {

  /**
   * @var \CRM_Generic_VehicleConfig[]
   */
  private static $_instances;

  /**
   * @var int
   */
  private $vehicleNumber;

  private $vehicleCustomTable;
  private $vehicleTypeColumnName;
  private $vehicleBrandColumnName;
  private $vehicleMakeColumnName;
  private $vehicleRegistrationNrColumnName;
  private $vehicleTrailerColumnName;

  private function __construct($vehicleNumber) {
    $this->vehicleNumber = $vehicleNumber;

    $customGroup = civicrm_api3('CustomGroup', 'getsingle', array('name' => 'vehicle_'.$vehicleNumber));
    $this->vehicleCustomTable = $customGroup['table_name'];
    $apiParams['custom_group_id'] = $customGroup['id'];
    $apiParams['return'] = 'column_name';
    $apiParams['name'] = 'vehicle_'.$vehicleNumber.'_type';
    $this->vehicleTypeColumnName = civicrm_api3('CustomField', 'getvalue', $apiParams);
    $apiParams['name'] = 'vehicle_'.$vehicleNumber.'_brand';
    $this->vehicleBrandColumnName = civicrm_api3('CustomField', 'getvalue', $apiParams);
    $apiParams['name'] = 'vehicle_'.$vehicleNumber.'_make';
    $this->vehicleMakeColumnName = civicrm_api3('CustomField', 'getvalue', $apiParams);
    $apiParams['name'] = 'vehicle_'.$vehicleNumber.'_registration_nr';
    $this->vehicleRegistrationNrColumnName = civicrm_api3('CustomField', 'getvalue', $apiParams);
    $apiParams['name'] = 'vehicle_'.$vehicleNumber.'_trailer_type';
    $this->vehicleTrailerColumnName = civicrm_api3('CustomField', 'getvalue', $apiParams);
  }

  /**
   * @param int $vehicleNr
   *
   * @return \CRM_Generic_VehicleConfig
   */
  public static function instance($vehicleNr) {
    if (!isset(self::$_instances[$vehicleNr])) {
      self::$_instances[$vehicleNr] = new CRM_Generic_VehicleConfig($vehicleNr);
    }
    return self::$_instances[$vehicleNr];
  }

  /**
   * @return String
   */
  public function getTableName() {
    return $this->vehicleCustomTable;
  }

  /**
   * @return String
   */
  public function getTypeColumnName() {
    return $this->vehicleTypeColumnName;
  }

  /**
   * @return String
   */
  public function getBrandColumnName() {
    return $this->vehicleBrandColumnName;
  }

  /**
   * @return String
   */
  public function getMakeColumnName() {
    return $this->vehicleMakeColumnName;
  }

  /**
   * @return String
   */
  public function getRegistrationNrColumnName() {
    return $this->vehicleRegistrationNrColumnName;
  }

  /**
   * @return String
   */
  public function getTrailerTypeColumnName() {
    return $this->vehicleTrailerColumnName;
  }


}