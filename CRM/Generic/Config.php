<?php

class CRM_Generic_Config {
	
	private static $singleton;

	private $_iceCustomGroupId;
	private $_iceCustomGroupTableName;
	private $_iceWaarschuwInGevalVanNoodCustomFieldId;
  private $_iceWaarschuwInGevalVanNoodCustomFieldColumnName;
  private $_iceTelefoonInGevalVanNoodCustomFieldId;
  private $_iceTelefoonInGevalVanNoodCustomFieldColumnName;
  private $_iceVerzekeringsnummerCustomFieldId;
  private $_iceVerzekeringsnummerCustomFieldColumnName;
  private $_iceBijzonderhedenCustomFieldId;
  private $_iceBijzonderhedenCustomFieldColumnName;
	private $_teamcaptainTeamportalCustomGroupId;
	private $_teamcaptainTeamportalCustomGroupTableName;
	private $_teamcaptainTeamportalAccessFieldId;
  private $_teamcaptainTeamportalAccessColumnName;
	private $_teamDataCustomGroupId;
	private $_teamDataCustomGroupTableName;
	private $_teamNrCustomFieldId;
	private $_teamNrCustomFieldColumnName;
	private $_teamNameCustomFieldId;
	private $_teamNameCustomFieldColumnName;
	private $_averageSpeedCustomFieldId;
	private $_averageSpeedCustomFieldColumnName;
  private $_startLocationCustomFieldId;
  private $_startLocationCustomFieldColumnName;
	private $_donatedTowardsCustomGroupId;
	private $_donatedTowardsCustomGroupTableName;
	private $_towardsTeamCustomFieldId;
	private $_towardsTeamCustomFieldColumnName;
	private $_towardsTeamMemberCustomFieldId;
	private $_towardsTeamMemberCustomFieldColumnName;
	private $_donatieFinancialTypeId;
	private $_collecteFinancialTypeId;
	private $_loterijFinancialTypeId;
  private $_smsDonatieFinancialTypeId;
	private $_completedContributionStatusId;
	private $_teamParticipantRoleId;
	private $_teamMemberParticipantRoleId;
	private $_roparunEventTypeId;
	private $_roparunEventCustomGroupId;
	private $_roparunEventCustomGroupTableName;
	private $_endDateDonationsCustomFieldId;
	private $_endDateDonationsCustomFieldColumnName;
	private $_teamMemberDataCustomGroupId;
	private $_teamMemberDataCustomGroupTableName;
	private $_memberOfTeamCustomFieldId;
	private $_memberOfTeamCustomFieldColumnName;
	private $_teamRoleCustomFieldId;
	private $_teamRoleCustomFieldColumnName;
  private $_showOnWebsiteCustomFieldId;
  private $_showOnWebsiteCustomFieldColumnName;
	private $_donorInformationCustomGroupId;
	private $_donorInformationCustomGroupTableName;
	private $_donateAnoymousCustomFieldId;
	private $_donateAnonymousCustomFieldColumnName;
	private $_donateAnonymousOptionValue;
  private $_vestigingsLocationTypeId;
  private $_teamCaptainRelationshipTypeId;
  private $_billingLocationTypeId;
  private $_phoneDuringEventTypeId;
  private $_activeParticipantStatusIds = array();
	
	private function __construct() {
		$this->loadCustomGroups();
		$this->loadFinancialTypes();
    $participantStatuses = civicrm_api3('ParticipantStatusType', 'get', array('is_active' => 1, 'class' => array('IN' => array("Positive")), 'options' => array('limit' => 0)));
    foreach($participantStatuses['values'] as $participantStatus) {
      $this->_activeParticipantStatusIds[] = $participantStatus['id'];
    }
		try {
			$this->_roparunEventTypeId = civicrm_api3('OptionValue', 'getvalue', array(
				'return' => 'value',
				'name' => 'Roparun',
				'option_group_id' => 'event_type',
			));
		} catch (Exception $ex) {
			throw new Exception ('Could not retrieve the Roparun Event Type');
		}
		try {
			$this->_teamParticipantRoleId = civicrm_api3('OptionValue', 'getvalue', array(
				'return' => 'value',
				'name' => 'Team',
				'option_group_id' => 'participant_role',
			));
		} catch (Exception $ex) {
			throw new Exception ('Could not retrieve the Team participant role');
		}
		try {
			$this->_teamMemberParticipantRoleId = civicrm_api3('OptionValue', 'getvalue', array(
				'return' => 'value',
				'name' => 'team_member',
				'option_group_id' => 'participant_role',
			));
		} catch (Exception $ex) {
			throw new Exception ('Could not retrieve the Team participant role');
		}
		try {
			$this->_completedContributionStatusId = civicrm_api3('OptionValue', 'getvalue', array(
				'return' => 'value',
				'name' => 'Completed',
				'option_group_id' => 'contribution_status',
			));
		} catch (Exception $ex) {
			throw new Exception ('Could not retrieve the Contribution status completed');
		}
		try {
			$this->_donateAnonymousOptionValue = civicrm_api3('OptionValue', 'getvalue', array(
				'return' => 'value',
				'name' => 'anonymous',
				'option_group_id' => 'anonymous_donation',
			));
		} catch (Exception $ex) {
			throw new Exception ('Could not retrieve the option value Anonymous for option group anonymous donation');
		}
    try {
      $this->_vestigingsLocationTypeId = civicrm_api3('LocationType', 'getvalue', array(
        'return' => 'id',
        'name' => 'Vestigingsplaats',
      ));
    } catch (Exception $ex) {
      throw new Exception('Could not find Vestigingsadres location type id');
    }
    try {
      $this->_teamCaptainRelationshipTypeId = civicrm_api3('RelationshipType', 'getvalue', array('name_b_a' => 'Teamcaptain is', 'return' => 'id'));
    } catch (Exception $e) {
      throw new Exception('Could not find relationship type team captain');
    }
    try {
		  $this->_billingLocationTypeId = civicrm_api3('LocationType', 'getvalue', array('return' => 'id', 'name' => 'Billing'));
    } catch (Exception $e) {
		  throw new Exception('Could not retrieve Billing location type');
    }
    try {
      $this->_phoneDuringEventTypeId = civicrm_api3('OptionValue', 'getvalue', array('return' => 'value', 'name' => 'during_event', 'option_group_id' => 'phone_type'));
    } catch (Exception $e) {
      throw new Exception('Could not retrieve phone type: during_event');
    }
	}
	
	/**
	 * @return CRM_Generic_Config
	 */
	public static function singleton() {
		if (!self::$singleton) {
			self:: $singleton = new CRM_Generic_Config();
		}
		return self::$singleton;
	}

  /**
   * Returns an array with status ids for active participant statuses.
   */
  public function getActiveParticipantStatusIds() {
    return $this->_activeParticipantStatusIds;
  }

  /**
   * Getter for the phone type during event.
   *
   * @return int
   */
	public function getDuringEventPhoneTypeId() {
	  return $this->_phoneDuringEventTypeId;
  }

  /**
   * Getter for the id of the custom group teamcaptain_teamportal
   */
	public function getTeamcaptainCustomGroupId() {
	  return $this->_teamcaptainTeamportalCustomGroupId;
  }

  /**
   * Getter for the id of the custom group teamcaptain_teamportal
   */
  public function getTeamcaptainCustomGroupTableName() {
    return $this->_teamcaptainTeamportalCustomGroupTableName;
  }

  /**
   * Getter for the id of the custom field teamcaptain_teamportal_access.
   */
  public function getTeamcaptainTeamportalAccessCustomFieldId() {
    return $this->_teamcaptainTeamportalAccessFieldId;
  }

  /**
   * Getter for the column name of the custom field teamcaptain_teamportal_access.
   */
  public function getTeamcaptainTeamportalAccessCustomFieldColumnName() {
    return $this->_teamcaptainTeamportalAccessColumnName;
  }
	
	/**
	 * Getter for the id of the custom group team_data.
	 */
	public function getTeamDataCustomGroupId() {
		return $this->_teamDataCustomGroupId;
	}
	
	/**
	 * Getter for the table name of the custom group team_data.
	 */
	public function getTeamDataCustomGroupTableName() {
		return $this->_teamDataCustomGroupTableName;
	}
	
	/**
	 * Getter for the id of the custom field team_nr.
	 */
	public function getTeamNrCustomFieldId() {
		return $this->_teamNrCustomFieldId;
	}
	
	/**
	 * Getter for the column name of the custom field team_nr.
	 */
	public function getTeamNrCustomFieldColumnName() {
		return $this->_teamNrCustomFieldColumnName;
	}
	
	/**
	 * Getter for the id of the custom field team_name.
	 */
	public function getTeamNameCustomFieldId() {
		return $this->_teamNameCustomFieldId;
	}
	
	/**
	 * Getter for the column name of the custom field team_name.
	 */
	public function getTeamNameCustomFieldColumnName() {
		return $this->_teamNameCustomFieldColumnName;
	}

  /**
   * Getter for the id of the custom field average_speed.
   */
  public function getAverageSpeedCustomFieldId() {
    return $this->_averageSpeedCustomFieldId;
  }

  /**
   * Getter for the column name of the custom field average_speed.
   */
  public function getAverageSpeedCustomFieldColumnName() {
    return $this->_averageSpeedCustomFieldColumnName;
  }
  
  /**
   * Getter for the id of the custom field start_location.
   */
  public function getStartLocationCustomFieldId() {
    return $this->_startLocationCustomFieldId;
  }
  
  /**
   * Getter for the column name of the custom field start_location.
   */
  public function getStartLocationCustomFieldColumnName() {
    return $this->_startLocationCustomFieldColumnName;
  }
	
	/**
	 * Getter for custom group id of donated towards.
	 */
	public function getDonatedTowardsCustomGroupId() {
		return $this->_donatedTowardsCustomGroupId;
	}
	
	/**
	 * Getter for custom group table name of donated towards.
	 */
	public function getDonatedTowardsCustomGroupTableName() {
		return $this->_donatedTowardsCustomGroupTableName;
	}
	
	/**
	 * Getter for custom field id of towards team.
	 */
	public function getTowardsTeamCustomFieldId() {
		return $this->_towardsTeamCustomFieldId;
	}
	
	/**
	 * Getter for custom field column name of towards teams.
	 */
	public function getTowardsTeamCustomFieldColumnName() {
		return $this->_towardsTeamCustomFieldColumnName;
	}
	
	/**
	 * Getter for custom field id of towards team member.
	 */
	public function getTowardsTeamMemberCustomFieldId() {
		return $this->_towardsTeamMemberCustomFieldId;
	}
	
	/**
	 * Getter for custom field column name of towards team member.
	 */
	public function getTowardsTeamMemberCustomFieldColumnName() {
		return $this->_towardsTeamMemberCustomFieldColumnName;
	}
  
  /**
	 * Getter for the custom group id of custom group team_member_data.
	 */
	public function getTeamMemberDataCustomGroupId() {
		return $this->_teamMemberDataCustomGroupId;
	}
	
	/**
	 * Getter for the table name of the custom group team_member_data.
	 */
	public function getTeamMemberDataCustomGroupTableName() {
		return $this->_teamMemberDataCustomGroupTableName;
	}
	
	/**
	 * Getter for the custom field id of the custom field team_member_of_team.
	 */
	public function getMemberOfTeamCustomFieldId() {
		return $this->_memberOfTeamCustomFieldId;
	}
	
	/**
	 * Getter for the column name of the custom field team_member_of_team.
	 */
	public function getMemberOfTeamCustomFieldColumnName() {
		return $this->_memberOfTeamCustomFieldColumnName;
	}
	
	/**
	 * Getter for the custom field id of the custom field team_role.
	 */
	public function getTeamRoleCustomFieldId() {
		return $this->_teamRoleCustomFieldId;
	}
	
	/**
	 * Getter for the column name of the custom field team_role.
	 */
	public function getTeamRoleCustomFieldColumnName() {
		return $this->_teamRoleCustomFieldColumnName;
	}
  
  /**
   * Getter for the id fo the custom field website.
   */
  public function getShowOnWebsiteCustomFieldId() {
    return $this->_showOnWebsiteCustomFieldId;
  }
  
  /**
   * Getter for the column name of the custom field website.
   */
  public function getShowOnWebsiteCustomFieldColumnName() {
    return $this->_showOnWebsiteCustomFieldColumnName;
  }
	
	/**
	 * Getter for the column name of the custom field donations.
	 */
	public function getDonationsCustomFieldColumnName() {
		return $this->_donationsCustomFieldColumnName;
	}
	
	/**
	 * Getter for custom group id donor information.
	 */
	public function getDonorInformationCustomGroupdId() {
		return $this->_donorInformationCustomGroupId;
	}
	
	/**
	 * Getter for custom group table name of donor information.
	 */
	public function getDonorInformationCustomGroupTableName() {
		return $this->_donorInformationCustomGroupTableName; 
	}
	
	/**
	 * Getter for custom field column name for donate anonymous.
	 */
	public function getDonateAnonymousCustomFieldColumnName() {
		return $this->_donateAnonymousCustomFieldColumnName;
	}
	
	/**
	 * Getter for custom field id for donate anonymous.
	 */
	public function getDonateAnonymousCustomFieldId() {
		return $this->_donateAnoymousCustomFieldId;
	}
	
	/**
	 * Getter for the option value donate anonymous.
	 */
	public function getDonateAnonymousOptionValue() {
		return $this->_donateAnonymousOptionValue;
	}
	
	/**
	 * Getter for completed contribution status id.
	 */
	public function getCompletedContributionStatusId() {
		return $this->_completedContributionStatusId;
	}
	
	/**
	 * Getter for donation financial type id.
	 */
	public function getDonatieFinancialTypeId() {
		return $this->_donatieFinancialTypeId;
	}
  
  /**
   * Getter for sms donation financial type id.
   */
  public function getSmsDonatieFinancialTypeId() {
    return $this->_smsDonatieFinancialTypeId;
  }
	
	/**
	 * Getter for collecte financial type id.
	 */
	public function getCollecteFinancialTypeId() {
		return $this->_collecteFinancialTypeId;
	}
	
	/**
	 * Getter for loterij financial type id.
	 */
	public function getLoterijFinancialTypeId() {
		return $this->_loterijFinancialTypeId;
	}
	
	/**
	 * Getter for role id of team.
	 */
	public function getTeamParticipantRoleId() {
		return $this->_teamParticipantRoleId;
	}
	
	/**
	 * Getter for role id of team member.
	 */
	public function getTeamMemberParticipantRoleId() {
		return $this->_teamMemberParticipantRoleId;
	}
	
	/** 
	 * Getter for the Roparun event type id.
	 */
	public function getRoparunEventTypeId() {
		return $this->_roparunEventTypeId;
	}

  /**
   * Getter for the custom group id of the custom group 'Teamlidgegevens_ICE'.
   */
  public function getICECustomGroupId() {
    return $this->_iceCustomGroupId;
  }

  /**
   * Getter for the custom group table name of the custom group 'Teamlidgegevens_ICE'.
   */
  public function getICECustomGroupTableName() {
    return $this->_iceCustomGroupTableName;
  }

  /**
   * Getter for the custom field id of the custom field Waarschuwen_in_geval_van_nood.
   */
  public function getICEWaarschuwInGevalVanNoodCustomFieldId() {
    return $this->_iceWaarschuwInGevalVanNoodCustomFieldId;
  }

  /**
   * Getter for the custom field column name of the custom field Waarschuwen_in_geval_van_nood.
   */
  public function getICEWaarschuwInGevalVanNoodCustomFieldColumnName() {
    return $this->_iceWaarschuwInGevalVanNoodCustomFieldColumnName;
  }

  /**
   * Getter for the custom field id of the custom field Telefoon_in_geval_van_nood.
   */
  public function getICETelefoonInGevalVanNoodCustomFieldId() {
    return $this->_iceTelefoonInGevalVanNoodCustomFieldId;
  }

  /**
   * Getter for the custom field column name of the custom field Telefoon_in_geval_van_nood.
   */
  public function getICETelefoonInGevalVanNoodCustomFieldColumnName() {
    return $this->_iceTelefoonInGevalVanNoodCustomFieldColumnName;
  }

  /**
   * Getter for the custom field id of the custom field Verzekeringsnummer.
   */
  public function getICEVerzekeringsnummerCustomFieldId() {
    return $this->_iceVerzekeringsnummerCustomFieldId;
  }

  /**
   * Getter for the custom field column name of the custom field Verzekeringsnummer.
   */
  public function getICEVerzekeringsnummerCustomFieldColumnName() {
    return $this->_iceVerzekeringsnummerCustomFieldColumnName;
  }

  /**
   * Getter for the custom field id of the custom field Bijzonderheden.
   */
  public function getICEBijzonderhedenCustomFieldId() {
    return $this->_iceBijzonderhedenCustomFieldId;
  }

  /**
   * Getter for the custom field column name of the custom field Bijzonderheden.
   */
  public function getICEBijzonderhedenCustomFieldColumnName() {
    return $this->_iceBijzonderhedenCustomFieldColumnName;
  }
	
	/**
	 * Getter for the custom group id of the custom group 'roparun event'.
	 */
	public function getRoparunEventCustomGroupId() {
		return $this->_roparunEventCustomGroupId;
	}
	
	/**
	 * Getter for the custom group table name of the custom group 'roparun event'.
	 */
	public function getRoparunEventCustomGroupTableName() {
		return $this->_roparunEventCustomGroupTableName;
	}
	
	/**
	 * Getter for the custom field id of the custom field end date donations.
	 */
	public function getEndDateDonationsCustomFieldId() {
		return $this->_endDateDonationsCustomFieldId;
	}
	
	/**
	 * Getter for the custom field column name of the custom field end date donations.
	 */
	public function getEndDateDonationsCustomFieldColumnName() {
		return $this->_endDateDonationsCustomFieldColumnName;
	}
  
  /**
   * Getter for vestigingsplaats location type id.
   */
  public function getVestingsplaatsLocationTypeId() {
    return $this->_vestigingsLocationTypeId;
  }

  /**
   * Getter for the relationship type id.
   */
  public function getTeamCaptainRelationshipTypeId() {
    return $this->_teamCaptainRelationshipTypeId;
  }

  /**
   * Getter for the billing location type id.
   *
   * @return int
   */
  public function getBillingLocationTypeId() {
    return $this->_billingLocationTypeId;
  }

	private function loadFinancialTypes() {
		try {
			$this->_donatieFinancialTypeId = civicrm_api3('FinancialType', 'getvalue', array(
				'name' => 'Donatie',
				'return' => 'id',
			));
		} catch (Exception $e) {
			throw new Exception('Could not retrieve financial type Donatie');
		}
		try {
			$this->_collecteFinancialTypeId = civicrm_api3('FinancialType', 'getvalue', array(
				'name' => 'Opbrengst collecte',
				'return' => 'id',
			));
		} catch (Exception $e) {
			throw new Exception('Could not retrieve financial type Opbrengst collecte');
		}
		try {
			$this->_loterijFinancialTypeId = civicrm_api3('FinancialType', 'getvalue', array(
				'name' => 'Opbrengst loterij',
				'return' => 'id',
			));
		} catch (Exception $e) {
			throw new Exception('Could not retrieve financial type Opbrengst loterij');
		}
    try {
      $this->_smsDonatieFinancialTypeId = civicrm_api3('FinancialType', 'getvalue', array(
        'name' => 'Opbrengst SMS donaties',
        'return' => 'id',
      ));
    } catch (Exception $e) {
      throw new Exception('Could not retrieve financial type Opbrengst SMS donaties');
    }
	}

	private function loadCustomGroups() {
    try {
      $_iceCustomGroup = civicrm_api3('CustomGroup', 'getsingle', array('name' => 'Teamlidgegevens_ICE'));
      $this->_iceCustomGroupId = $_iceCustomGroup['id'];
      $this->_iceCustomGroupTableName = $_iceCustomGroup['table_name'];
    } catch (Exception $ex) {
      throw new Exception('Could not find custom group for Teamlidgegevens_ICE');
    }
    try {
      $_waarschuwInGevalvanNoodCustomField = civicrm_api3('CustomField', 'getsingle', array('name' => 'Waarschuwen_in_geval_van_nood', 'custom_group_id' => $this->_iceCustomGroupId));
      $this->_iceWaarschuwInGevalVanNoodCustomFieldColumnName = $_waarschuwInGevalvanNoodCustomField['column_name'];
      $this->_iceWaarschuwInGevalVanNoodCustomFieldId = $_waarschuwInGevalvanNoodCustomField['id'];
    } catch (Exception $ex) {
      throw new Exception('Could not find custom field teamcaptain_teamportal_access');
    }
    try {
      $_TelefoonInGevalvanNoodCustomField = civicrm_api3('CustomField', 'getsingle', array('name' => 'Telefoon_in_geval_van_nood', 'custom_group_id' => $this->_iceCustomGroupId));
      $this->_iceTelefoonInGevalVanNoodCustomFieldColumnName = $_TelefoonInGevalvanNoodCustomField['column_name'];
      $this->_iceTelefoonInGevalVanNoodCustomFieldId = $_TelefoonInGevalvanNoodCustomField['id'];
    } catch (Exception $ex) {
      throw new Exception('Could not find custom field teamcaptain_teamportal_access');
    }

    try {
      $_verzekersnummerCustomField = civicrm_api3('CustomField', 'getsingle', array('name' => 'Verzekeringsnummer', 'custom_group_id' => $this->_iceCustomGroupId));
      $this->_iceVerzekeringsnummerCustomFieldColumnName = $_verzekersnummerCustomField['column_name'];
      $this->_iceVerzekeringsnummerCustomFieldId = $_verzekersnummerCustomField['id'];
    } catch (Exception $ex) {
      throw new Exception('Could not find custom field teamcaptain_teamportal_access');
    }
    try {
      $_BijzonderhedenCustomField = civicrm_api3('CustomField', 'getsingle', array('name' => 'Bijzonderheden', 'custom_group_id' => $this->_iceCustomGroupId));
      $this->_iceBijzonderhedenCustomFieldColumnName = $_BijzonderhedenCustomField['column_name'];
      $this->_iceBijzonderhedenCustomFieldId = $_BijzonderhedenCustomField['id'];
    } catch (Exception $ex) {
      throw new Exception('Could not find custom field teamcaptain_teamportal_access');
    }



    try {
      $_teamcaptainTeamportalCustomGroup = civicrm_api3('CustomGroup', 'getsingle', array('name' => 'teamcaptain_teamportal'));
      $this->_teamcaptainTeamportalCustomGroupId = $_teamcaptainTeamportalCustomGroup['id'];
      $this->_teamcaptainTeamportalCustomGroupTableName = $_teamcaptainTeamportalCustomGroup['table_name'];
    } catch (Exception $ex) {
      throw new Exception('Could not find custom group for teamcaptain_teamportal');
    }
    try {
      $_teamcaptainTeamportalAccessCustomField = civicrm_api3('CustomField', 'getsingle', array('name' => 'teamcaptain_teamportal_access', 'custom_group_id' => $this->_teamcaptainTeamportalCustomGroupId));
      $this->_teamcaptainTeamportalAccessColumnName = $_teamcaptainTeamportalAccessCustomField['column_name'];
      $this->_teamcaptainTeamportalAccessFieldId = $_teamcaptainTeamportalAccessCustomField['id'];
    } catch (Exception $ex) {
      throw new Exception('Could not find custom field teamcaptain_teamportal_access');
    }
		try {
			$_roparunEventCustomGroup = civicrm_api3('CustomGroup', 'getsingle', array('name' => 'roparun_event'));
			$this->_roparunEventCustomGroupId = $_roparunEventCustomGroup['id'];
			$this->_roparunEventCustomGroupTableName = $_roparunEventCustomGroup['table_name'];
		} catch (Exception $ex) {
			throw new Exception('Could not find custom group for roparun events');
		}
		try {
			$_roparunEndDateDonationsCustomField = civicrm_api3('CustomField', 'getsingle', array('name' => 'end_date_donations', 'custom_group_id' => $this->_roparunEventCustomGroupId));
			$this->_endDateDonationsCustomFieldColumnName = $_roparunEndDateDonationsCustomField['column_name'];
			$this->_endDateDonationsCustomFieldId = $_roparunEndDateDonationsCustomField['id'];
		} catch (Exception $ex) {
			throw new Exception('Could not find custom field End Date Donations');
		}
		try {
			$_teamDataCustomGroup = civicrm_api3('CustomGroup', 'getsingle', array('name' => 'team_data'));
			$this->_teamDataCustomGroupId = $_teamDataCustomGroup['id'];
			$this->_teamDataCustomGroupTableName = $_teamDataCustomGroup['table_name'];
		} catch (Exception $ex) {
			throw new Exception('Could not find custom group for Team data');
		}
		try {
			$_teamNrCustomField = civicrm_api3('CustomField', 'getsingle', array('name' => 'team_nr', 'custom_group_id' => $this->_teamDataCustomGroupId));
			$this->_teamNrCustomFieldColumnName = $_teamNrCustomField['column_name'];
			$this->_teamNrCustomFieldId = $_teamNrCustomField['id'];
		} catch (Exception $ex) {
			throw new Exception('Could not find custom field Team NR');
		}
		try {
			$_teamNameCustomField = civicrm_api3('CustomField', 'getsingle', array('name' => 'team_name', 'custom_group_id' => $this->_teamDataCustomGroupId));
			$this->_teamNameCustomFieldColumnName = $_teamNameCustomField['column_name'];
			$this->_teamNameCustomFieldId = $_teamNameCustomField['id'];
		} catch (Exception $ex) {
			throw new Exception('Could not find custom field Team Name');
		}
    try {
      $_averageCustomField = civicrm_api3('CustomField', 'getsingle', array('name' => 'average_speed', 'custom_group_id' => $this->_teamDataCustomGroupId));
      $this->_averageSpeedCustomFieldColumnName = $_averageCustomField['column_name'];
      $this->_averageSpeedCustomFieldId = $_averageCustomField['id'];
    } catch (Exception $ex) {
      throw new Exception('Could not find custom field Team Name');
    }
    try {
      $_startLocationCustomField = civicrm_api3('CustomField', 'getsingle', array('name' => 'start_location', 'custom_group_id' => $this->_teamDataCustomGroupId));
      $this->_startLocationCustomFieldColumnName = $_startLocationCustomField['column_name'];
      $this->_startLocationCustomFieldId = $_startLocationCustomField['id'];
    } catch (Exception $ex) {
      throw new Exception('Could not find custom field Start Location');
    }
		
		try {
			$_donatedTowardsCustomGroup = civicrm_api3('CustomGroup', 'getsingle', array('name' => 'donated_towards'));
			$this->_donatedTowardsCustomGroupId = $_donatedTowardsCustomGroup['id'];
			$this->_donatedTowardsCustomGroupTableName = $_donatedTowardsCustomGroup['table_name'];
		} catch (Exception $ex) {
			throw new Exception('Could not find custom group for Donated Towards');
		}
		try {
			$_towardsTeamCustomField = civicrm_api3('CustomField', 'getsingle', array('name' => 'towards_team', 'custom_group_id' => $this->_donatedTowardsCustomGroupId));
			$this->_towardsTeamCustomFieldColumnName = $_towardsTeamCustomField['column_name'];
			$this->_towardsTeamCustomFieldId = $_towardsTeamCustomField['id'];
		} catch (Exception $ex) {
			throw new Exception('Could not find custom field Towards Team');
		}
		try {
			$_towardsTeamMemberCustomField = civicrm_api3('CustomField', 'getsingle', array('name' => 'towards_team_member', 'custom_group_id' => $this->_donatedTowardsCustomGroupId));
			$this->_towardsTeamMemberCustomFieldColumnName = $_towardsTeamMemberCustomField['column_name'];
			$this->_towardsTeamMemberCustomFieldId = $_towardsTeamMemberCustomField['id'];
		} catch (Exception $ex) {
			throw new Exception('Could not find custom field Towards Team Member');
		}
		try {
			$_teamMemberCustomGroup = civicrm_api3('CustomGroup', 'getsingle', array('name' => 'team_member_data'));
			$this->_teamMemberDataCustomGroupId = $_teamMemberCustomGroup['id'];
			$this->_teamMemberDataCustomGroupTableName = $_teamMemberCustomGroup['table_name'];
		} catch (Exception $ex) {
			throw new Exception('Could not find custom group for Team Member Data');
		}
		try {
			$_memberOfTeamCustomField = civicrm_api3('CustomField', 'getsingle', array('name' => 'team_member_of_team', 'custom_group_id' => $this->_teamMemberDataCustomGroupId));
			$this->_memberOfTeamCustomFieldColumnName = $_memberOfTeamCustomField['column_name'];
			$this->_memberOfTeamCustomFieldId = $_memberOfTeamCustomField['id'];
		} catch (Exception $ex) {
			throw new Exception('Could not find custom field Member of Team');
		}
		try {
			$_teamRoleCustomField = civicrm_api3('CustomField', 'getsingle', array('name' => 'team_role', 'custom_group_id' => $this->_teamMemberDataCustomGroupId));
			$this->_teamRoleCustomFieldColumnName = $_teamRoleCustomField['column_name'];
			$this->_teamRoleCustomFieldId = $_teamRoleCustomField['id'];
		} catch (Exception $ex) {
			throw new Exception('Could not find custom field Team role');
		}
    try {
      $websiteCustomField = civicrm_api3('CustomField', 'getsingle', array('name' => 'website', 'custom_group_id' => $this->_teamMemberDataCustomGroupId));
      $this->_showOnWebsiteCustomFieldColumnName = $websiteCustomField['column_name'];
      $this->_showOnWebsiteCustomFieldId = $websiteCustomField['id'];
    } catch (Exception $ex) {
      throw new Exception('Could not find custom field website');
    }
		try {
			$_donorInformationCustomGroup = civicrm_api3('CustomGroup', 'getsingle', array('name' => 'donor_information'));
			$this->_donorInformationCustomGroupId = $_donorInformationCustomGroup['id'];
			$this->_donorInformationCustomGroupTableName = $_donorInformationCustomGroup['table_name'];
		} catch (Exception $ex) {
			throw new Exception('Could not find custom group for Donor Information');
		}
		try {
			$_anonymousCustomField = civicrm_api3('CustomField', 'getsingle', array('name' => 'anonymous', 'custom_group_id' => $this->_donorInformationCustomGroupId));
			$this->_donateAnonymousCustomFieldColumnName = $_anonymousCustomField['column_name'];
			$this->_donateAnoymousCustomFieldId = $_anonymousCustomField['id'];
		} catch (Exception $ex) {
			throw new Exception('Could not find custom field Anonymous');
		}
	}
	
}
