<?php

class CRM_Api_RoparunTeam_Overview extends CRM_Api_RoparunTeam {
	
	public function overview($event_id=null) {
		$roparun_event_id = $event_id;
		if (empty($roparun_event_id)) {
			$roparun_event_id = $this->getCurrentRoparunEventId();
		}
		$config = CRM_Api_RoparunConfig::singleton();
		$campaign_id = $this->getRoparunCampaignId($roparun_event_id);
		
		$teamSql = "SELECT civicrm_contact.id, 
  						 civicrm_contact.display_name,
  						 `{$config->getTeamDataCustomGroupTableName()}`.`{$config->getTeamNrCustomFieldColumnName()}` AS `team_nr`,
  						 `{$config->getTeamDataCustomGroupTableName()}`.`{$config->getTeamNameCustomFieldColumnName()}` AS `team_name`,
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
		$total['total_amount_teams'] = CRM_Generic_Teamstanden::getTotalAmountDonatedForTeams($campaign_id);
		$total['total_amount_roparun'] = CRM_Generic_Teamstanden::getTotalAmountDonatedForRoparun($campaign_id);
		
		return $total;
	}
	
}
