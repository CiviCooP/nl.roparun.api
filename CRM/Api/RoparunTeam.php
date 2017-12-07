<?php

class CRM_Api_RoparunTeam {

	/**
	 * Returns the ID of the next roparun event.
	 * 
	 * @return int 
	 */
	protected function getCurrentRoparunEventId() {
		return CRM_Generic_CurrentEvent::getCurrentRoparunEventId();
	}
	
	/**
	 * Returns the campaign ID of the roparun event.
	 * 
	 * @param int $event_id 
	 * 	ID of the event.
	 * @return int
	 */
	protected function getRoparunCampaignId($event_id) {
		return CRM_Generic_CurrentEvent::getRoparunCampaignId($event_id);
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