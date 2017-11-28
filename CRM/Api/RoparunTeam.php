<?php

class CRM_Api_RoparunTeam {

	/**
	 * Returns the ID of the next roparun event.
	 * 
	 * @return int 
	 */
	protected function getCurrentRoparunEventId() {
		$config = CRM_Api_RoparunConfig::singleton();
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
	protected function getRoparunCampaignId($event_id) {
		$params[1] = array($event_id, 'Integer');
		$campaign_id = CRM_Core_DAO::singleValueQuery("SELECT campaign_id FROM civicrm_event WHERE id = %1", $params);
		return $campaign_id;  
	}
	
	protected function display_name($contact) {
		$display_name = '';
		if (isset($contact['first_name'])) {
			if (strlen($display_name)) {
				$display_name .= ' ';
			}
			$display_name .= $contact['first_name'];
		}
		if (isset($contact['middle_name'])) {
			if (strlen($display_name)) {
				$display_name .= ' ';
			}
			$display_name .= $contact['middle_name'];
		}
		if (isset($contact['last_name'])) {
			if (strlen($display_name)) {
				$display_name .= ' ';
			}
			$display_name .= $contact['last_name'];
		}
		return trim($display_name);
	}

}