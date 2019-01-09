<?php
/**
 * @author Jaap Jansma <jaap.jansma@civicoop.org>
 * @license AGPL-3.0
 */

class CRM_Generic_Tokens_MissingTeamInformation extends CRM_Generic_Tokens_Tokens {

  private $token_name = 'team';

  /**
   * @var \CRM_Generic_Tokens_MissingTeamInformation
   */
  private static $instance;

  private function __construct() {

  }

  /**
   * @return String
   */
  protected function getTokenName() {
    return $this->token_name;
  }

  /**
   * @return \CRM_Generic_Tokens_MissingTeamInformation
   */
  public static function singleton() {
    if (!self::$instance) {
      self::$instance = new CRM_Generic_Tokens_MissingTeamInformation();
    }
    return self::$instance;
  }

  public function tokens(&$tokens) {
    $tokens[$this->token_name][$this->token_name.'.missing_info'] = ts('Ontbrekende gegevens');
  }

  public function tokenValues(&$values, $cids, $tokens = array()) {
    if ($this->isTokenInTokens($tokens, 'missing_info')) {
      $this->missing_info_token($values, $cids, 'missing_info');
    }
  }

  protected function missing_info_token(&$values, $cids, $token) {
    $event_id = CRM_Generic_CurrentEvent::getCurrentRoparunEventId();
    $contact_ids = $cids;
    if (!is_array($contact_ids)) {
      $contact_ids = array($contact_ids);
    }
    $tokenValues = array();
    foreach($contact_ids as $contact_id) {
      $missing_information = array();
      $missing_information['average_speed'] = ts("Gemiddelde snelheid is niet opgegeven");
      $missing_information['vehicle1'] = ts("Voertuig 1 is niet opgegeven");
      $missing_information['vehicle2'] = ts("Voertuig 2 is niet opgegeven");
      $missing_information['vehicle3'] = ts("Voertuig 3 is niet opgegeven");
      $missing_information['vehicle4'] = ts("Voertuig 4 is niet opgegeven");

      $participant_id = $this->getParticipantRecordId($contact_id, $event_id);
      if ($participant_id) {
        $this->missingAverageSpeed($missing_information, $participant_id);
        $this->missingVehicle($missing_information, 1, $participant_id);
        $this->missingVehicle($missing_information, 2, $participant_id);
        $this->missingVehicle($missing_information, 3, $participant_id);
        $this->missingVehicle($missing_information, 4, $participant_id);
        $this->missingTeamMembers($missing_information, $participant_id, $event_id);
      }
      if (!count($missing_information)) {
        $missing_information = array(ts("Alle gegevens zijn juist ingevuld"));
      }
      $tokenValues[$contact_id] = implode("<br>\r\n", $missing_information);
    }
    $this->setTokenValue($values, $cids, $token, $tokenValues);
  }

  private function getParticipantRecordId($contact_id, $event_id) {
    $config = CRM_Generic_Config::singleton();
    $sql = "
      SELECT civicrm_participant.id as id
      FROM civicrm_contact 
      INNER JOIN civicrm_participant ON civicrm_participant.contact_id = civicrm_contact.id
      WHERE civicrm_participant.status_id IN (".implode(',', $config->getActiveParticipantStatusIds()).") 
             AND civicrm_participant.event_id = %1 AND civicrm_participant.role_id = %2 
             AND civicrm_contact.id = %3
      UNION SELECT civicrm_participant.id as id
      FROM civicrm_contact team
      INNER JOIN civicrm_participant ON civicrm_participant.contact_id = team.id
      INNER JOIN civicrm_relationship r ON r.contact_id_b = team.id AND r.is_active = 1 
      WHERE civicrm_participant.status_id IN (".implode(',', $config->getActiveParticipantStatusIds()).") 
             AND civicrm_participant.event_id = %1 AND civicrm_participant.role_id = %2 
             AND r.contact_id_a = %3
             AND r.relationship_type_id = %4 
    ";
    $params[1] = array($event_id, 'Integer');
    $params[2] = array($config->getTeamParticipantRoleId(), 'Integer');
    $params[3] = array($contact_id, 'Integer');
    $params[4] = array($config->getTeamCaptainRelationshipTypeId(), 'Integer');
    return CRM_Core_DAO::singleValueQuery($sql, $params);
  }

  private function missingAverageSpeed(&$missing_information, $participant_id) {
    $config = CRM_Generic_Config::singleton();

    $sql = "
      SELECT {$config->getAverageSpeedCustomFieldColumnName()} as average_speed 
      FROM {$config->getTeamDataCustomGroupTableName()} 
      WHERE entity_id = %1
    ";
    $params[1] = array($participant_id, 'Integer');
    $averageSpeed = (float) CRM_Core_DAO::singleValueQuery($sql, $params);
    if ($averageSpeed && $averageSpeed > 0.0) {
      unset($missing_information['average_speed']);
    }
  }

  private function missingVehicle(&$missing_information, $vehicle_nr, $participant_id) {
    unset($missing_information['vehicle'.$vehicle_nr]);
    $vehicle_config = CRM_Generic_VehicleConfig::instance($vehicle_nr);
    $sql = "
     SELECT
     `{$vehicle_config->getTypeColumnName()}` as `type`,
     `{$vehicle_config->getBrandColumnName()}` as `brand`,
     `{$vehicle_config->getMakeColumnName()}` as `make`,
     `{$vehicle_config->getRegistrationNrColumnName()}` as `registration_nr`,
     `{$vehicle_config->getTrailerTypeColumnName()}` as `trailer_type`
      FROM {$vehicle_config->getTableName()}
      WHERE entity_id = %1
    ";
    $params[1] = array($participant_id, 'Integer');
    $dao = CRM_Core_DAO::executeQuery($sql, $params);
    if ($dao->fetch()) {
      if (!$dao->type) {
        $missing_information[] = ts('Voertuig %1:  "Soort" is niet opgegeven', array(1 => $vehicle_nr));
      }
      if ($dao->type && $dao->type == 'unknown') {
        $missing_information[] = ts('Voertuig %1: is niet opgegeven', array(1 => $vehicle_nr));
      } elseif ($dao->type && $dao->type != 'none') {
        if (!$dao->brand) {
          $missing_information[] = ts('Voertuig %1:  "Merk" is niet opgegeven', array(1 => $vehicle_nr));
        }
        if (!$dao->make) {
          $missing_information[] = ts('Voertuig %1:  "Type" is niet opgegeven', array(1 => $vehicle_nr));
        }
        if (!$dao->registration_nr) {
          $missing_information[] = ts('Voertuig %1:  "Kenteken" is niet opgegeven', array(1 => $vehicle_nr));
        }
        if (!$dao->trailer_type) {
          $missing_information[] = ts('Voertuig %1:  "Aanhanger" is niet opgegeven', array(1 => $vehicle_nr));
        }
      }
    } else {
      $missing_information[] = ts('Voertuig %1 is niet opgegeven', array(1 => $vehicle_nr));
    }
  }

  private function missingTeamMembers(&$missing_information, $participant_id, $event_id) {
    $config = CRM_Generic_Config::singleton();
    $sql = "
      SELECT 
        civicrm_contact.display_name, 
        civicrm_contact.first_name,
        civicrm_contact.last_name,
        civicrm_address.street_address,
        civicrm_address.postal_code, 
        civicrm_address.city,
        civicrm_address.country_id,
        civicrm_phone.phone,
        civicrm_email.email,
        team_member_data.{$config->getTeamRoleCustomFieldColumnName()} as role,
        ice.{$config->getICEWaarschuwInGevalVanNoodCustomFieldColumnName()} as waarschuw_in_nood,
        ice.{$config->getICETelefoonInGevalVanNoodCustomFieldColumnName()} as telefoon_in_nood,
        ice.{$config->getICEVerzekeringsnummerCustomFieldColumnName()} as verzekeringsnummer,
        ice.{$config->getICEBijzonderhedenCustomFieldColumnName()} as bijzonderheden
    FROM civicrm_contact
    INNER JOIN civicrm_participant ON civicrm_contact.id = civicrm_participant.contact_id
    INNER JOIN {$config->getTeamMemberDataCustomGroupTableName()} team_member_data ON team_member_data.entity_id = civicrm_participant.id
    LEFT JOIN {$config->getICECustomGroupTableName()} `ice` ON `ice`.entity_id = civicrm_contact.id
    INNER JOIN civicrm_participant team_participant ON team_participant.contact_id = team_member_data.{$config->getMemberOfTeamCustomFieldColumnName()}
    LEFT JOIN civicrm_address ON civicrm_address.contact_id = civicrm_contact.id AND civicrm_address.is_primary = 1
    LEFT JOIN civicrm_phone ON civicrm_phone.contact_id = civicrm_contact.id AND civicrm_phone.is_primary = 1
    LEFT JOIN civicrm_email ON civicrm_email.contact_id = civicrm_contact.id AND civicrm_email.is_primary = 1
    WHERE civicrm_contact.is_deleted = '0'
    AND civicrm_participant.status_id IN (".implode(", ", $config->getActiveParticipantStatusIds()).")
    AND team_participant.id = %1
    AND civicrm_participant.event_id = %2
     ";
    $params[1] = array($participant_id, 'Integer');
    $params[2] = array($event_id, 'Integer');
    $dao = CRM_Core_DAO::executeQuery($sql, $params);
    while ($dao->fetch()) {
      $missing_contact_info = array();
      if (!$dao->first_name) {
        $missing_contact_info[] = ts('voornaam');
      }
      if (!$dao->last_name) {
        $missing_contact_info[] = ts('achternaam');
      }
      if (!$dao->street_address) {
        $missing_contact_info[] = ts('adres');
      }
      if (!$dao->postal_code) {
        $missing_contact_info[] = ts('postcode');
      }
      if (!$dao->city) {
        $missing_contact_info[] = ts('plaats');
      }
      if (!$dao->country_id) {
        $missing_contact_info[] = ts('land');
      }
      if (!$dao->phone) {
        $missing_contact_info[] = ts('telefoonnummer');
      }
      if (!$dao->email) {
        $missing_contact_info[] = ts('e-mailadres');
      }
      if (!$dao->role) {
        $missing_contact_info[] = ts('functie');
      }
      if (!$dao->waarschuw_in_nood) {
        $missing_contact_info[] = ts('waarschuwen in geval van nood');
      }
      if (!$dao->telefoon_in_nood) {
        $missing_contact_info[] = ts('telefoonnummer in geval van nood');
      }
      if (!$dao->verzekeringsnummer) {
        $missing_contact_info[] = ts('verzekeringsnummer');
      }
      if (!$dao->bijzonderheden) {
        $missing_contact_info[] = ts('bijzonderheden');
      }
      if (count($missing_contact_info)) {
        $missing_information[] = 'Teamlid "'.$dao->display_name.'" mist de volgende gegevens: '.implode(", ", $missing_contact_info);
      }
    }

  }

}