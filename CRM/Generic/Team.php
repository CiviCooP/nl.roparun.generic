<?php

class CRM_Generic_Team {
	
	/**
	 * Returns the contact if of the team captain.
	 */
	public static function getTeamCaptainContacts($team_contact_id) {
		$config = CRM_Generic_Config::singleton();

		$sql = "SELECT civicrm_relationship.contact_id_a as contact_id 
						FROM civicrm_relationship
						WHERE is_active = 1
						AND (start_date IS NULL OR start_date <= CURRENT_DATE()) 
			      AND (end_date IS NULL OR end_date >= CURRENT_DATE())
			      AND civicrm_relationship.relationship_type_id = %1
			      AND contact_id_b = %2";
		 $params[1] = array($config->getTeamCaptainRelationshipTypeId(), 'Integer');
		 $params[2] = array($team_contact_id, 'Integer');
		 $captain_contact_ids = array();
		 
		 $dao = CRM_Core_DAO::executeQuery($sql, $params);
		 while($dao->fetch()) {
		 	$captain_contact_ids[] = $dao->contact_id;
		 }
		 return $captain_contact_ids;
	}
	
}
