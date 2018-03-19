<?php

class CRM_Api_RoparunTeam_Captains extends CRM_Api_RoparunTeam {
		
	public function captains($event_id = null, $role="Teamcaptain") {
		$roparun_event_id = $event_id;
		if (empty($roparun_event_id)) {
			$roparun_event_id = $this->getCurrentRoparunEventId();
		}
		return $this->getTeamCaptains($roparun_event_id, $role);
	}
	
	protected function getTeamCaptains($event_id, $role="Teamcaptain") {
		$config = CRM_Api_RoparunConfig::singleton();
		$campaign_id = $this->getRoparunCampaignId($event_id);
		
		$captainSql = "
			SELECT 
			`civicrm_contact`.`id`,
			`civicrm_participant`.`id` as `participant_id`, 
			`civicrm_participant`.`event_id` as `event_id`,
			`civicrm_contact`.`first_name`,
			`civicrm_contact`.`middle_name`,
			`civicrm_contact`.`last_name`, 
			`civicrm_address`.`city` as `city`,
			`civicrm_email`.`email` as `email`,
			`team_member_data`.`{$config->getTeamRoleCustomFieldColumnName()}` as `role`,
			`team_member_data`.`{$config->getMemberOfTeamCustomFieldColumnName()}` as `team_id`,
			`{$config->getTeamDataCustomGroupTableName()}`.`{$config->getTeamNrCustomFieldColumnName()}` AS `team_nr`,
		  `{$config->getTeamDataCustomGroupTableName()}`.`{$config->getTeamNameCustomFieldColumnName()}` AS `team_name`
			FROM civicrm_contact
			INNER JOIN civicrm_participant ON civicrm_contact.id = civicrm_participant.contact_id
			INNER JOIN {$config->getTeamMemberDataCustomGroupTableName()} team_member_data ON team_member_data.entity_id = civicrm_participant.id
			INNER JOIN civicrm_participant team_participant ON team_participant.contact_id = `team_member_data`.`{$config->getMemberOfTeamCustomFieldColumnName()}` 
			LEFT JOIN `{$config->getTeamDataCustomGroupTableName()}` ON `{$config->getTeamDataCustomGroupTableName()}`.entity_id = team_participant.id
			LEFT JOIN civicrm_address ON civicrm_address.contact_id = civicrm_contact.id AND civicrm_address.is_primary = 1
			LEFT JOIN civicrm_email ON civicrm_email.contact_id = civicrm_contact.id AND civicrm_email.is_primary = 1
			WHERE team_member_data.{$config->getTeamRoleCustomFieldColumnName()} = %1
			AND civicrm_participant.event_id = %2 AND team_participant.event_id = %2
			ORDER BY civicrm_contact.display_name	
		";
		$params[1] = array($role, 'String');
		$params[2] = array($event_id, 'Integer');
		
		$captains = array();
		$captainsDAO = CRM_Core_DAO::executeQuery($captainSql, $params);
		
		while ($captainsDAO->fetch()) {
			$captain = array();
			$captain['contact_id'] = $captainsDAO->id;
			$captain['participant_id'] = $captainsDAO->participant_id;
			$captain['event_id'] = $captainsDAO->event_id;
			$contact['first_name'] = $captainsDAO->first_name;
			$contact['middle_name'] = $captainsDAO->middle_name;
			$contact['last_name'] = $captainsDAO->last_name;
			$captain['name'] = $this->display_name($contact);
			$captain['city'] = $captainsDAO->city;
			$captain['role'] = $captainsDAO->role;
			$captain['email'] = $captainsDAO->email;
			$captain['team_id'] = $captainsDAO->team_id;
			$captain['team'] = $captainsDAO->team_name;
			$captain['teamnr'] = $captainsDAO->team_nr;
			$captains[] = $captain;
		}
		return $captains;
	}
	
	/**
	 * Get the basic team information.
	 */
	protected function getTeamInfo($team_id, $event_id) {
		$config = CRM_Api_RoparunConfig::singleton();
		$start_location_options = $config->getStartLocationOptions();
		$campaign_id = $this->getRoparunCampaignId($event_id);
		
		$teamSql = "SELECT civicrm_contact.id, 
  						 civicrm_contact.display_name,
  						 `{$config->getTeamDataCustomGroupTableName()}`.`{$config->getTeamNrCustomFieldColumnName()}` AS `team_nr`,
  						 `{$config->getTeamDataCustomGroupTableName()}`.`{$config->getTeamNameCustomFieldColumnName()}` AS `team_name`,
  						 `{$config->getTeamDataCustomGroupTableName()}`.`{$config->getStartLocationCustomFieldColumnName()}` AS `start_location`,
  						 civicrm_address.city as city,
  						 civicrm_country.name as country,
  						 website.url as website,
  						 facebook.url as facebook,
  						 googleplus.url as googleplus,
  						 instagram.url as instagram,
  						 linkedin.url as linkedin,
  						 myspace.url as myspace,
  						 pinterest.url as pinterest,
  						 snapchat.url as snapchat,
  						 tumblr.url as tumblr,
  						 twitter.url as twitter,
  						 vine.url as vine
  						 FROM civicrm_contact 
  						 INNER JOIN civicrm_participant ON civicrm_participant.contact_id = civicrm_contact.id 
  						 INNER JOIN civicrm_participant_status_type ON civicrm_participant.status_id = civicrm_participant_status_type.id
  						 LEFT JOIN civicrm_address ON civicrm_address.contact_id = civicrm_contact.id AND civicrm_address.location_type_id = %1
  						 LEFT JOIN civicrm_country ON civicrm_country.id = civicrm_address.country_id
  						 LEFT JOIN `{$config->getTeamDataCustomGroupTableName()}` ON `{$config->getTeamDataCustomGroupTableName()}`.entity_id = civicrm_participant.id
  						 LEFT JOIN civicrm_website website ON website.contact_id = civicrm_contact.id and website.website_type_id = {$config->getWebsiteWebsiteTypeId()}
							 LEFT JOIN civicrm_website facebook ON facebook.contact_id = civicrm_contact.id and facebook.website_type_id = {$config->getFacebookWebsiteTypeId()}
							 LEFT JOIN civicrm_website googleplus ON googleplus.contact_id = civicrm_contact.id and googleplus.website_type_id = {$config->getGooglePlusWebsiteTypeId()}
							 LEFT JOIN civicrm_website instagram ON instagram.contact_id = civicrm_contact.id and instagram.website_type_id = {$config->getInstagramWebsiteTypeId()}
							 LEFT JOIN civicrm_website linkedin ON linkedin.contact_id = civicrm_contact.id and linkedin.website_type_id = {$config->getLinkedInWebsiteTypeId()}
							 LEFT JOIN civicrm_website myspace ON myspace.contact_id = civicrm_contact.id and myspace.website_type_id = {$config->getMySpaceWebsiteTypeId()}
							 LEFT JOIN civicrm_website pinterest ON pinterest.contact_id = civicrm_contact.id and pinterest.website_type_id = {$config->getPinterestWebsiteTypeId()}
							 LEFT JOIN civicrm_website snapchat ON snapchat.contact_id = civicrm_contact.id and snapchat.website_type_id = {$config->getSnapChatWebsiteTypeId()}
							 LEFT JOIN civicrm_website tumblr ON tumblr.contact_id = civicrm_contact.id and tumblr.website_type_id = {$config->getTumblrWebsiteTypeId()}
							 LEFT JOIN civicrm_website twitter ON twitter.contact_id = civicrm_contact.id and twitter.website_type_id = {$config->getTwitterWebsiteTypeId()}
							 LEFT JOIN civicrm_website vine ON vine.contact_id = civicrm_contact.id and vine.website_type_id = {$config->getVineWebsiteTypeId()}
  						 WHERE civicrm_contact.id = %2 
  						 AND civicrm_participant_status_type.class = 'Positive' AND civicrm_participant_status_type.is_active = 1
  						 AND civicrm_participant.event_id = %3";
		$teamParams[1] = array($config->getVestingsplaatsLocationTypeId(), 'Integer');
	  $teamParams[2] = array($team_id, 'Integer');
	  $teamParams[3] = array($event_id, 'Integer');
		$teamDao = CRM_Core_DAO::executeQuery($teamSql, $teamParams);		
		if($teamDao->fetch()) {
			$team = array();
			$team['id'] = $teamDao->id;
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
			$team['googleplus'] = $teamDao->googleplus;
			$team['instagram'] = $teamDao->instagram;
			$team['linkedin'] = $teamDao->linkedin;
			$team['myspace'] = $teamDao->myspace;
			$team['pinterest'] = $teamDao->pinterest;
			$team['snapchat'] = $teamDao->snapchat;
			$team['tumblr'] = $teamDao->tumblr;
			$team['twitter'] = $teamDao->twitter;
			$team['vine'] = $teamDao->vine;
			$team['total_amount'] = CRM_Generic_Teamstanden::getTotalAmountDonatedForTeam($teamDao->id, $campaign_id);
			$team['total_amount_team'] = CRM_Generic_Teamstanden::getTotalAmountDonatedForTeam_OnlyTeam($teamDao->id, $campaign_id);
			$team['total_amount_team_members'] = CRM_Generic_Teamstanden::getTotalAmountDonatedForTeam_TeamMembers($teamDao->id, $campaign_id);
			$team['total_amount_collecte'] = CRM_Generic_Teamstanden::getTotalAmountDonatedForTeam_Collecte($teamDao->id, $campaign_id);
			$team['total_amount_loterij'] = CRM_Generic_Teamstanden::getTotalAmountDonatedForTeam_Loterij($teamDao->id, $campaign_id);
			return $team;
		} else {
			throw new Exception('Could not find team');
		}
	}
	
}
