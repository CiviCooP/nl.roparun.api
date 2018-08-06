<?php

class CRM_Api_RoparunTeam_Overview extends CRM_Api_RoparunTeam {
	
	public function overview($event_id=null) {
		$roparun_event_id = $event_id;
		if (empty($roparun_event_id)) {
			$roparun_event_id = $this->getCurrentRoparunEventId();
		}
		$config = CRM_Api_RoparunConfig::singleton();
		$start_location_options = $config->getStartLocationOptions();
		$campaign_id = $this->getRoparunCampaignId($roparun_event_id);
		
		$teamSql = "SELECT civicrm_contact.id, 
  						 civicrm_contact.display_name,
  						 `{$config->getTeamDataCustomGroupTableName()}`.`{$config->getTeamNrCustomFieldColumnName()}` AS `team_nr`,
  						 `{$config->getTeamDataCustomGroupTableName()}`.`{$config->getTeamNameCustomFieldColumnName()}` AS `team_name`,
  						 `{$config->getTeamDataCustomGroupTableName()}`.`{$config->getStartLocationCustomFieldColumnName()}` AS `start_location`,
  						 civicrm_address.city as city,
  						 civicrm_country.name as country,
  						 website.url as website,
  						 facebook.url as facebook,
  						 instagram.url as instagram,
  						 twitter.url as twitter
  						 FROM civicrm_contact 
  						 INNER JOIN civicrm_participant ON civicrm_participant.contact_id = civicrm_contact.id 
  						 INNER JOIN civicrm_participant_status_type ON civicrm_participant.status_id = civicrm_participant_status_type.id
  						 LEFT JOIN civicrm_address ON civicrm_address.contact_id = civicrm_contact.id AND civicrm_address.location_type_id = %1
  						 LEFT JOIN civicrm_country ON civicrm_country.id = civicrm_address.country_id
  						 LEFT JOIN `{$config->getTeamDataCustomGroupTableName()}` ON `{$config->getTeamDataCustomGroupTableName()}`.entity_id = civicrm_participant.id
  						 LEFT JOIN civicrm_website website ON website.contact_id = civicrm_contact.id and website.website_type_id = {$config->getWebsiteWebsiteTypeId()}
							 LEFT JOIN civicrm_website facebook ON facebook.contact_id = civicrm_contact.id and facebook.website_type_id = {$config->getFacebookWebsiteTypeId()}
							 LEFT JOIN civicrm_website instagram ON instagram.contact_id = civicrm_contact.id and instagram.website_type_id = {$config->getInstagramWebsiteTypeId()}
							 LEFT JOIN civicrm_website twitter ON twitter.contact_id = civicrm_contact.id and twitter.website_type_id = {$config->getTwitterWebsiteTypeId()}
  						 WHERE civicrm_participant_status_type.class = 'Positive' AND civicrm_participant_status_type.is_active = 1
  						 AND civicrm_participant.event_id = %2 AND civicrm_participant.role_id = %3
  						 ORDER BY team_nr, team_name";
	  $teamParams[1] = array($config->getVestingsplaatsLocationTypeId(), 'Integer');
	  $teamParams[2] = array($roparun_event_id, 'Integer');
	  $teamParams[3] = array($config->getTeamParticipantRoleId(), 'Integer');
		$teamDao = CRM_Core_DAO::executeQuery($teamSql, $teamParams);
		$returnValues = array();
		while($teamDao->fetch()) {
			$team = array();
			$team['id'] = $teamDao->id;
			$team['event_id'] = $roparun_event_id;
			$team['name'] = $teamDao->team_name;
			$team['teamnr'] = $teamDao->team_nr;
			$team['start_location'] = $teamDao->start_location;
			if (isset($start_location_options[$teamDao->start_location])) {
				$team['start_location'] = $start_location_options[$teamDao->start_location];
			}
			$team['city'] = $teamDao->city;
			$team['country'] = $teamDao->country;
			$team['website'] = $teamDao->website;
			$team['facebook'] = $teamDao->facebook;
			$team['instagram'] = $teamDao->instagram;
			$team['twitter'] = $teamDao->twitter;
			$team['total_amount'] = CRM_Generic_Teamstanden::getTotalAmountDonatedForTeam($teamDao->id, $campaign_id);
			
			$teams[$teamDao->id] = $team;
		}
		return $teams;
	}

	public function gettotal($event_id=null) {
		$roparun_event_id = $event_id;
		if (empty($roparun_event_id)) {
			$roparun_event_id = $this->getCurrentRoparunEventId();
		}
		$config = CRM_Api_RoparunConfig::singleton();
		$campaign_id = $this->getRoparunCampaignId($roparun_event_id);
		
		$total['total_amount'] = CRM_Generic_Teamstanden::getTotalAmountDonated($campaign_id);
		$total['total_amount_teams'] = CRM_Generic_Teamstanden::getTotalAmountDonatedForTeams($campaign_id, false);
		$total['total_amount_roparun'] = CRM_Generic_Teamstanden::getTotalAmountForRoparun($campaign_id);
		
		return $total;
	}
	
}
