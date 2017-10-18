<?php

class CRM_Generic_Teamstanden {
	
	/**
	 * Returns the total amount donated for a campaign
	 * 
	 * @param int $team_id
	 * 	The contact id of the team
	 * @param int $campaign_id
	 * 	The ID of the campaign.
	 * @return float
	 */
	public static function getTotalAmountDonated($campaign_id) {
		try {
			$config = CRM_Generic_Config::singleton();
		
			$financialTypeIds[] = $config->getDonatieFinancialTypeId();
			$financialTypeIds[] = $config->getCollecteFinancialTypeId();
			$financialTypeIds[] = $config->getLoterijFinancialTypeId();
		
			$sql = "
				SELECT SUM(total_amount) 
				FROM civicrm_contribution
				WHERE civicrm_contribution.campaign_id = %1
				AND civicrm_contribution.is_test = 0
				AND civicrm_contribution.financial_type_id IN (" . implode(",", $financialTypeIds) . ")
				AND civicrm_contribution.contribution_status_id = %2";
			$params[1] = array($campaign_id, 'Integer');
			$params[2] = array($config->getCompletedContributionStatusId(), 'Integer');
			
			return (float) CRM_Core_DAO::singleValueQuery($sql, $params);
		} catch (Exception $e) {
			CRM_Core_Error::createError('Could not calculate the total amount', 800, 'Warning');
			return 0.00;
		}
	}
	
	/**
	 * Returns the total amount donated for a campaign
	 * 
	 * @param int $team_id
	 * 	The contact id of the team
	 * @param int $campaign_id
	 * 	The ID of the campaign.
	 * @return float
	 */
	public static function getTotalAmountDonatedForRoparun($campaign_id) {
		try {
			$config = CRM_Generic_Config::singleton();
		
			$financialTypeIds[] = $config->getDonatieFinancialTypeId();
			$financialTypeIds[] = $config->getCollecteFinancialTypeId();
			$financialTypeIds[] = $config->getLoterijFinancialTypeId();
		
			$sql = "
				SELECT SUM(total_amount) 
				FROM civicrm_contribution
				LEFT JOIN `{$config->getDonatedTowardsCustomGroupTableName()}` donated_towards ON donated_towards.entity_id = civicrm_contribution.id
				WHERE (donated_towards.`{$config->getTowardsTeamCustomFieldColumnName()}` IS NULL OR donated_towards.`{$config->getTowardsTeamCustomFieldColumnName()}` = 0)
				AND civicrm_contribution.campaign_id = %1
				AND civicrm_contribution.is_test = 0
				AND civicrm_contribution.financial_type_id IN (" . implode(",", $financialTypeIds) . ")
				AND civicrm_contribution.contribution_status_id = %2";
			$params[1] = array($campaign_id, 'Integer');
			$params[2] = array($config->getCompletedContributionStatusId(), 'Integer');
			return (float) CRM_Core_DAO::singleValueQuery($sql, $params);
		} catch (Exception $e) {
			CRM_Core_Error::createError('Could not calculate the total amount', 800, 'Warning');
			return 0.00;
		}
	}
	
	/**
	 * Returns the total amount donated for a campaign
	 * 
	 * @param int $team_id
	 * 	The contact id of the team
	 * @param int $campaign_id
	 * 	The ID of the campaign.
	 * @return float
	 */
	public static function getTotalAmountDonatedForTeams($campaign_id) {
		try {
			$config = CRM_Generic_Config::singleton();
		
			$financialTypeIds[] = $config->getDonatieFinancialTypeId();
			$financialTypeIds[] = $config->getCollecteFinancialTypeId();
			$financialTypeIds[] = $config->getLoterijFinancialTypeId();
		
			$sql = "
				SELECT SUM(total_amount) 
				FROM civicrm_contribution
				LEFT JOIN `{$config->getDonatedTowardsCustomGroupTableName()}` donated_towards ON donated_towards.entity_id = civicrm_contribution.id
				WHERE (donated_towards.`{$config->getTowardsTeamCustomFieldColumnName()}` IS NOT NULL AND donated_towards.`{$config->getTowardsTeamCustomFieldColumnName()}` != 0)
				AND civicrm_contribution.campaign_id = %1
				AND civicrm_contribution.is_test = 0
				AND civicrm_contribution.financial_type_id IN (" . implode(",", $financialTypeIds) . ")
				AND civicrm_contribution.contribution_status_id = %2";
			$params[1] = array($campaign_id, 'Integer');
			$params[2] = array($config->getCompletedContributionStatusId(), 'Integer');
			
			return (float) CRM_Core_DAO::singleValueQuery($sql, $params);
		} catch (Exception $e) {
			CRM_Core_Error::createError('Could not calculate the total amount', 800, 'Warning');
			return 0.00;
		}
	}
	
	/**
	 * Returns the total amount donated for a team and a campaign.
	 * 
	 * @param int $team_id
	 * 	The contact id of the team
	 * @param int $campaign_id
	 * 	The ID of the campaign.
	 * @return float
	 */
	public static function getTotalAmountDonatedForTeam($team_id, $campaign_id) {
		try {
			$config = CRM_Generic_Config::singleton();
		
			$financialTypeIds[] = $config->getDonatieFinancialTypeId();
			$financialTypeIds[] = $config->getCollecteFinancialTypeId();
			$financialTypeIds[] = $config->getLoterijFinancialTypeId();
		
			$sql = "
				SELECT SUM(total_amount) 
				FROM civicrm_contribution
				INNER JOIN `{$config->getDonatedTowardsCustomGroupTableName()}` donated_towards ON donated_towards.entity_id = civicrm_contribution.id
				WHERE donated_towards.`{$config->getTowardsTeamCustomFieldColumnName()}` = %1
				AND civicrm_contribution.campaign_id = %2
				AND civicrm_contribution.is_test = 0
				AND civicrm_contribution.financial_type_id IN (" . implode(",", $financialTypeIds) . ")
				AND civicrm_contribution.contribution_status_id = %3";
			$params[1] = array($team_id, 'Integer');
			$params[2] = array($campaign_id, 'Integer');
			$params[3] = array($config->getCompletedContributionStatusId(), 'Integer');
			
			return (float) CRM_Core_DAO::singleValueQuery($sql, $params);
		} catch (Exception $e) {
			CRM_Core_Error::createError('Could not calculate the total amount donated towards team ('.$team_id.')', 800, 'Warning');
			return 0.00;
		}
	}

	/**
	 * Returns the total amount donated for a team and a campaign.
	 * 
	 * @param int $team_id
	 * 	The contact id of the team
	 * @param int $campaign_id
	 * 	The ID of the campaign.
	 * @return float
	 */
	public static function getTotalAmountDonatedForTeam_OnlyTeam($team_id, $campaign_id) {
		try {
			$config = CRM_Generic_Config::singleton();
		
			$financialTypeIds[] = $config->getDonatieFinancialTypeId();
		
			$sql = "
				SELECT SUM(total_amount) 
				FROM civicrm_contribution
				INNER JOIN `{$config->getDonatedTowardsCustomGroupTableName()}` donated_towards ON donated_towards.entity_id = civicrm_contribution.id
				WHERE donated_towards.`{$config->getTowardsTeamCustomFieldColumnName()}` = %1
				AND donated_towards.{$config->getTowardsTeamMemberCustomFieldColumnName()} IS NULL
				AND civicrm_contribution.is_test = 0
				AND civicrm_contribution.campaign_id = %2
				AND civicrm_contribution.financial_type_id IN (" . implode(",", $financialTypeIds) . ")
				AND civicrm_contribution.contribution_status_id = %3
				";
			$params[1] = array($team_id, 'Integer');
			$params[2] = array($campaign_id, 'Integer');
			$params[3] = array($config->getCompletedContributionStatusId(), 'Integer');
			
			return (float) CRM_Core_DAO::singleValueQuery($sql, $params);
		} catch (Exception $e) {
			CRM_Core_Error::createError('Could not calculate the total amount donated towards team ('.$team_id.')', 800, 'Warning');
			return 0.00;
		}
	}

	/**
	 * Returns the total amount donated for a team and a campaign.
	 * 
	 * @param int $team_id
	 * 	The contact id of the team
	 * @param int $campaign_id
	 * 	The ID of the campaign.
	 * @return float
	 */
	public static function getTotalAmountDonatedForTeam_TeamMembers($team_id, $campaign_id) {
		try {
			$config = CRM_Generic_Config::singleton();
		
			$financialTypeIds[] = $config->getDonatieFinancialTypeId();
		
			$sql = "
				SELECT SUM(total_amount) 
				FROM civicrm_contribution
				INNER JOIN `{$config->getDonatedTowardsCustomGroupTableName()}` donated_towards ON donated_towards.entity_id = civicrm_contribution.id
				WHERE donated_towards.`{$config->getTowardsTeamCustomFieldColumnName()}` = %1
				AND donated_towards.{$config->getTowardsTeamMemberCustomFieldColumnName()} IS NOT NULL
				AND civicrm_contribution.campaign_id = %2
				AND civicrm_contribution.is_test = 0
				AND civicrm_contribution.financial_type_id IN (" . implode(",", $financialTypeIds) . ")
				AND civicrm_contribution.contribution_status_id = %3
				";
			$params[1] = array($team_id, 'Integer');
			$params[2] = array($campaign_id, 'Integer');
			$params[3] = array($config->getCompletedContributionStatusId(), 'Integer');
			
			return (float) CRM_Core_DAO::singleValueQuery($sql, $params);
		} catch (Exception $e) {
			CRM_Core_Error::createError('Could not calculate the total amount donated towards team ('.$team_id.')', 800, 'Warning');
			return 0.00;
		}
	}

	/**
	 * Returns the total amount donated for a team and a campaign.
	 * 
	 * @param int $team_id
	 * 	The contact id of the team
	 * @param int $campaign_id
	 * 	The ID of the campaign.
	 * @return float
	 */
	public static function getTotalAmountDonatedForTeam_Collecte($team_id, $campaign_id) {
		try {
			$config = CRM_Generic_Config::singleton();
		
			$financialTypeIds[] = $config->getCollecteFinancialTypeId();
		
			$sql = "
				SELECT SUM(total_amount) 
				FROM civicrm_contribution
				INNER JOIN `{$config->getDonatedTowardsCustomGroupTableName()}` donated_towards ON donated_towards.entity_id = civicrm_contribution.id
				WHERE donated_towards.`{$config->getTowardsTeamCustomFieldColumnName()}` = %1
				AND civicrm_contribution.campaign_id = %2
				AND civicrm_contribution.is_test = 0
				AND civicrm_contribution.financial_type_id IN (" . implode(",", $financialTypeIds) . ")
				AND civicrm_contribution.contribution_status_id = %3";
			$params[1] = array($team_id, 'Integer');
			$params[2] = array($campaign_id, 'Integer');
			$params[3] = array($config->getCompletedContributionStatusId(), 'Integer');
			
			return (float) CRM_Core_DAO::singleValueQuery($sql, $params);
		} catch (Exception $e) {
			CRM_Core_Error::createError('Could not calculate the total amount donated towards team ('.$team_id.')', 800, 'Warning');
			return 0.00;
		}
	}

	/**
	 * Returns the total amount donated for a team and a campaign.
	 * 
	 * @param int $team_id
	 * 	The contact id of the team
	 * @param int $campaign_id
	 * 	The ID of the campaign.
	 * @return float
	 */
	public static function getTotalAmountDonatedForTeam_Loterij($team_id, $campaign_id) {
		try {
			$config = CRM_Generic_Config::singleton();
		
			$financialTypeIds[] = $config->getLoterijFinancialTypeId();
		
			$sql = "
				SELECT SUM(total_amount) 
				FROM civicrm_contribution
				INNER JOIN `{$config->getDonatedTowardsCustomGroupTableName()}` donated_towards ON donated_towards.entity_id = civicrm_contribution.id
				WHERE donated_towards.`{$config->getTowardsTeamCustomFieldColumnName()}` = %1
				AND civicrm_contribution.campaign_id = %2
				AND civicrm_contribution.is_test = 0
				AND civicrm_contribution.financial_type_id IN (" . implode(",", $financialTypeIds) . ")
				AND civicrm_contribution.contribution_status_id = %3";
			$params[1] = array($team_id, 'Integer');
			$params[2] = array($campaign_id, 'Integer');
			$params[3] = array($config->getCompletedContributionStatusId(), 'Integer');
			
			return (float) CRM_Core_DAO::singleValueQuery($sql, $params);
		} catch (Exception $e) {
			CRM_Core_Error::createError('Could not calculate the total amount donated towards team ('.$team_id.')', 800, 'Warning');
			return 0.00;
		}
	}

	/**
	 * Returns the total amount donated for a team member and a campaign.
	 * 
	 * @param int $contact_id
	 * 	The contact id of the team member
	 * @param int $campaign_id
	 * 	The ID of the campaign.
	 * @return float
	 */
	public static function getTotalAmountDonatedForTeamMember($contact_id, $campaign_id) {
		try {
			$config = CRM_Generic_Config::singleton();
		
			$financialTypeIds[] = $config->getDonatieFinancialTypeId();
		
			$sql = "
				SELECT SUM(total_amount) 
				FROM civicrm_contribution
				INNER JOIN `{$config->getDonatedTowardsCustomGroupTableName()}` donated_towards ON donated_towards.entity_id = civicrm_contribution.id
				WHERE donated_towards.`{$config->getTowardsTeamMemberCustomFieldColumnName()}` = %1
				AND civicrm_contribution.campaign_id = %2
				AND civicrm_contribution.is_test = 0
				AND civicrm_contribution.financial_type_id IN (" . implode(",", $financialTypeIds) . ")
				AND civicrm_contribution.contribution_status_id = %3";
			$params[1] = array($contact_id, 'Integer');
			$params[2] = array($campaign_id, 'Integer');
			$params[3] = array($config->getCompletedContributionStatusId(), 'Integer');
			
			return (float) CRM_Core_DAO::singleValueQuery($sql, $params);
		} catch (Exception $e) {
			CRM_Core_Error::createError('Could not calculate the total amount donated towards team member ('.$contact_id.')', 800, 'Warning');
			return 0.00;
		}
	}
	
}
