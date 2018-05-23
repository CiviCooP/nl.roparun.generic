<?php

class CRM_Generic_Team {
	
	/**
	 * Returns the contact if of the team captain.
	 */
	public static function getTeamCaptainContacts($team_contact_id, $event_id) {
		$config = CRM_Generic_Config::singleton();
		$active_status_ids = implode(",", $config->getTeamMemberParticipantActiveStatusIds());
		
		$sql = "SELECT civicrm_participant.contact_id 
						FROM civicrm_participant
						INNER JOIN {$config->getTeamMemberDataCustomGroupTableName()} team_member_data ON team_member_data.entity_id = civicrm_participant.id
						WHERE event_id = %1
						AND status_id IN ({$active_status_ids}) 
						AND role_id = %3
						AND `team_member_data`.`{$config->getMemberOfTeamCustomFieldColumnName()}` = %2
						AND `team_member_data`.`{$config->getTeamRoleCustomFieldColumnName()}` = %4";
		 $params[1] = array($event_id, 'Integer');
		 $params[2] = array($team_contact_id, 'Integer');
		 $params[3] = array($config->getTeamMemberParticipantRoleId(), 'Integer');
		 $params[4] = array($config->getTeamCaptainRoleValue(), 'String');
		 $captain_contact_ids = array();
		 
		 $dao = CRM_Core_DAO::executeQuery($sql, $params);
		 while($dao->fetch()) {
		 	$captain_contact_ids[] = $dao->contact_id;
		 }
		 return $captain_contact_ids;
	}
	
}
