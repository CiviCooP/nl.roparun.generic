<?php

class CRM_Generic_CurrentEvent {
	
	/**
	 * Returns the ID of the next roparun event.
	 * 
	 * @return int 
	 */
	public static function getCurrentRoparunEventId() {
		$config = CRM_Generic_Config::singleton();
		$id = CRM_Core_DAO::singleValueQuery("
			SELECT civicrm_event.id
			FROM civicrm_event
			INNER JOIN `{$config->getRoparunEventCustomGroupTableName()}` ON `{$config->getRoparunEventCustomGroupTableName()}`.entity_id = civicrm_event.id 
			WHERE 
				civicrm_event.event_type_id = '".$config->getRoparunEventTypeId()."'
				AND DATE(`{$config->getRoparunEventCustomGroupTableName()}`.`{$config->getEndDateDonationsCustomFieldColumnName()}`) > NOW()
		");
		if (!$id) {
			throw new Exception('Could not find an active Roparun Event');
		}
		return $id;
	}
	
	/**
	 * Returns the campaign ID of the roparun event.
	 * 
	 * @param int $event_id 
	 * 	ID of the event.
	 * @return int
	 */
	public static function getRoparunCampaignId($event_id) {
		$params[1] = array($event_id, 'Integer');
		$campaign_id = CRM_Core_DAO::singleValueQuery("SELECT campaign_id FROM civicrm_event WHERE id = %1", $params);
		return $campaign_id;  
	}
	
	/**
	 * Returns the campaign title of the campaign.
	 * 
	 * @param int $campaign_id 
	 * 	ID of the campaign.
	 * @return string
	 */
	public static function getRoparunCampaignTitle($campaign_id) {
		$params[1] = array($event_id, 'Integer');
		try {
			return civicrm_api3('campaign', 'getvalue', array(
				'return' => 'title',
				'id' => $campaign_id,
			));
		} catch (Exception $e) {
			return '';
		}
		return '';
	}
	
}
