<?php

class CRM_Generic_Config {
	
	private static $singleton;
	
  private $_teamMemberDataCustomGroupId;
  private $_teamMemberDataCustomGroupTableName;
  private $_memberOfTeamCustomFieldId;
  private $_memberOfTeamCustomFieldColumnName;
	private $_teamDataCustomGroupId;
	private $_teamDataCustomGroupTableName;
	private $_teamNrCustomFieldId;
	private $_teamNrCustomFieldColumnName;
	private $_teamNameCustomFieldId;
	private $_teamNameCustomFieldColumnName;
	private $_donatedTowardsCustomGroupId;
	private $_donatedTowardsCustomGroupTableName;
	private $_towardsTeamCustomFieldId;
	private $_towardsTeamCustomFieldColumnName;
	private $_towardsTeamMemberCustomFieldId;
	private $_towardsTeamMemberCustomFieldColumnName;
	private $_donatieFinancialTypeId;
	private $_collecteFinancialTypeId;
	private $_loterijFinancialTypeId;
	private $_completedContributionStatusId;
	private $_teamParticipantRoleId;
	private $_roparunEventTypeId;
	private $_roparunEventCustomGroupId;
	private $_roparunEventCustomGroupTableName;
	private $_endDateDonationsCustomFieldId;
	private $_endDateDonationsCustomFieldColumnName;
	
	private function __construct() {
		$this->loadCustomGroups();
		$this->loadFinancialTypes();
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
			$this->_completedContributionStatusId = civicrm_api3('OptionValue', 'getvalue', array(
				'return' => 'value',
				'name' => 'Completed',
				'option_group_id' => 'contribution_status',
			));
		} catch (Exception $ex) {
			throw new Exception ('Could not retrieve the Contribution status completed');
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
	 * Getter for the Roparun event type id.
	 */
	public function getRoparunEventTypeId() {
		return $this->_roparunEventTypeId;
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
	}

	private function loadCustomGroups() {
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
	}
	
}
