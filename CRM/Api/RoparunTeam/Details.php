<?php

class CRM_Api_RoparunTeam_Details extends CRM_Api_RoparunTeam {
	
	public function details($team_id, $event_id = null, $includeTeamMembersWithDonations = true) {
		$roparun_event_id = $event_id;
		if (empty($roparun_event_id)) {
			$roparun_event_id = $this->getCurrentRoparunEventId();
		}
		$team['info'] = $this->getTeamInfo($team_id, $roparun_event_id);
		$team['members'] = $this->getTeamMembers($team_id, $roparun_event_id, true, $includeTeamMembersWithDonations);
		$team['donations'] = $this->getDonations($team_id, $roparun_event_id);
		return array($team_id => $team);
	}
	
	public function members($team_id, $event_id = null, $includeTeamMembersWithDonations = true) {
		$roparun_event_id = $event_id;
		if (empty($roparun_event_id)) {
			$roparun_event_id = $this->getCurrentRoparunEventId();
		}
		return $this->getTeamMembers($team_id, $roparun_event_id, false, $includeTeamMembersWithDonations);
	}
	
	protected function getDonations($team_id, $event_id) {
		$generic_config = CRM_Generic_Config::singleton();
		$config = CRM_Api_RoparunConfig::singleton();
		$financialTypeIds[] = $generic_config->getDonatieFinancialTypeId();
		$campaign_id = $this->getRoparunCampaignId($event_id);
		$donationsSql = "SELECT total_amount,
										team_member.id as team_member_id,
										team_member.first_name as team_member_first_name,
										team_member.middle_name as team_member_middle_name,
										team_member.last_name as team_member_last_name, 
										donor.first_name as donor_first_name,
										donor.middle_name as donor_middle_name,
										donor.last_name as donor_last_name,
										address.city as city,
										donor_info.{$config->getDonateAnonymousCustomFieldColumnName()} as anonymous_donation 
										FROM civicrm_contribution
										INNER JOIN civicrm_contact donor ON civicrm_contribution.contact_id = donor.id
										INNER JOIN `{$generic_config->getDonatedTowardsCustomGroupTableName()}` donated_towards ON donated_towards.entity_id = civicrm_contribution.id
										LEFT JOIN civicrm_contact as team_member ON team_member.id = donated_towards.{$generic_config->getTowardsTeamMemberCustomFieldColumnName()}
										LEFT JOIN {$config->getDonorInformationCustomGroupTableName()} donor_info ON donor_info.entity_id = civicrm_contribution.id
										LEFT JOIN civicrm_address address ON donor.id = address.contact_id and address.is_primary = 1
										WHERE donated_towards.{$generic_config->getTowardsTeamCustomFieldColumnName()} = %1
										AND civicrm_contribution.campaign_id = %2
										AND civicrm_contribution.is_test = 0
										AND civicrm_contribution.financial_type_id IN (" . implode(",", $financialTypeIds) . ")
										AND civicrm_contribution.contribution_status_id = %3
										";
		$donationsParams[1] = array($team_id, 'Integer');
		$donationsParams[2] = array($campaign_id, 'Integer');
		$donationsParams[3] = array($generic_config->getCompletedContributionStatusId(), 'Integer');
		$donations = array();
		$donationsDao = CRM_Core_DAO::executeQuery($donationsSql, $donationsParams);
		while ($donationsDao->fetch()) {
			$donation = array();
			$donor['first_name'] = $donationsDao->donor_first_name;
			$donor['middle_name'] = $donationsDao->donor_middle_name;
			$donor['last_name'] = $donationsDao->donor_last_name;
			$donation['donor'] = $this->display_name($donor);
			$donation['city'] = $donationsDao->city;
			$donation['team_member'] = '';
			if ($donationsDao->team_member_id) {
				$team_member['first_name'] = $donationsDao->team_member_first_name;
				$team_member['middle_name'] = $donationsDao->team_member_middle_name;
				$team_member['last_name'] = $donationsDao->team_member_last_name;
				$donation['team_member'] = $this->display_name($team_member);	
			}
			$donation['amount'] = $donationsDao->total_amount;
			if ($donationsDao->anonymous_donation == $config->getDonateAnonymousOptionValue()) {
				$donation['donor'] = ts('Anonymous');
				$donation['city'] = '';
			}
			$donations[] = $donation;
		}
		return $donations;
	}
	
	protected function getTeamMembers($team_id, $event_id, $includeDonationTotals, $includeTeamMembersWithDonations) {
		$config = CRM_Api_RoparunConfig::singleton();
    $generic_config = CRM_Generic_Config::singleton();
		$campaign_id = $this->getRoparunCampaignId($event_id);
    $financialTypeIds[] = $generic_config->getDonatieFinancialTypeId();
		
		$teamMemberSql = "
			SELECT civicrm_contact.id, 
			civicrm_contact.first_name,
			civicrm_contact.middle_name,
			civicrm_contact.last_name, 
			civicrm_address.city as city,
			team_member_data.{$config->getTeamRoleCustomFieldColumnName()} as role,
			(CASE
			  WHEN civicrm_relationship.id IS NOT NULL THEN 1
			  ELSE 0 
			END) AS is_team_captain
			FROM civicrm_contact
			INNER JOIN civicrm_participant ON civicrm_contact.id = civicrm_participant.contact_id
			INNER JOIN {$config->getTeamMemberDataCustomGroupTableName()} team_member_data ON team_member_data.entity_id = civicrm_participant.id
			LEFT JOIN civicrm_address ON civicrm_address.contact_id = civicrm_contact.id AND civicrm_address.is_primary = 1
			LEFT JOIN civicrm_relationship ON civicrm_relationship.contact_id_a = civicrm_contact.id 
			  AND civicrm_relationship.relationship_type_id = %3
			  AND is_active = 1 
			  AND (start_date IS NULL OR start_date <= CURRENT_DATE()) 
			  AND (end_date IS NULL OR end_date >= CURRENT_DATE())
			WHERE team_member_data.{$config->getMemberOfTeamCustomFieldColumnName()} = %1
			AND civicrm_participant.event_id = %2
			";
		$params[1] = array($team_id, 'Integer');
		$params[2] = array($event_id, 'Integer');
		$params[3] = array($config->getTeamCaptainRelationshipTypeId(), 'Integer');
		if ($includeTeamMembersWithDonations) {
      $teamMemberSql .= " 
      AND (
        team_member_data.{$config->getShowOnWebsiteCustomFieldColumnName()} = 1
        OR civicrm_contact.id IN (
          SELECT  donated_towards.{$generic_config->getTowardsTeamMemberCustomFieldColumnName()} 
          FROM civicrm_contribution
          INNER JOIN {$generic_config->getDonatedTowardsCustomGroupTableName()} donated_towards ON donated_towards.entity_id = civicrm_contribution.id
          WHERE
          civicrm_contribution.campaign_id = %4
          AND civicrm_contribution.is_test = 0
          AND civicrm_contribution.financial_type_id IN (" . implode(",", $financialTypeIds) . ")
          AND civicrm_contribution.contribution_status_id = %5
          )
      )";
      $params[4] = array($campaign_id, 'Integer');
      $params[5] = array($generic_config->getCompletedContributionStatusId(), 'Integer');
    } else {
      $teamMemberSql .= " AND team_member_data.{$config->getShowOnWebsiteCustomFieldColumnName()} = 1";
    }
		
		$teamMemberSql .= "
			ORDER BY civicrm_contact.display_name	
		";
		$teamMembers = array();
		$teamMembersDao = CRM_Core_DAO::executeQuery($teamMemberSql, $params);
		while ($teamMembersDao->fetch()) {
			$teamMember = array();
			$teamMember['id'] = $teamMembersDao->id;
			$contact['first_name'] = $teamMembersDao->first_name;
			$contact['middle_name'] = $teamMembersDao->middle_name;
			$contact['last_name'] = $teamMembersDao->last_name;
			$teamMember['name'] = $this->display_name($contact);
			$teamMember['city'] = $teamMembersDao->city;
			$teamMember['role'] = $teamMembersDao->role;
			$teamMember['is_team_captain'] = $teamMembersDao->is_team_captain;
			if ($includeDonationTotals) {
				$teamMember['total_amount'] = CRM_Generic_Teamstanden::getTotalAmountDonatedForTeamMember($teamMembersDao->id, $campaign_id);
			}
			$teamMembers[] = $teamMember;
		}
		return $teamMembers;
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
			$team['instagram'] = $teamDao->instagram;
			$team['twitter'] = $teamDao->twitter;
			$team['total_amount'] = CRM_Generic_Teamstanden::getTotalAmountDonatedForTeam($teamDao->id, $campaign_id);
			$team['total_amount_team'] = CRM_Generic_Teamstanden::getTotalAmountDonatedForTeam_OnlyTeam($teamDao->id, $campaign_id);
			$team['total_amount_team_members'] = CRM_Generic_Teamstanden::getTotalAmountDonatedForTeam_TeamMembers($teamDao->id, $campaign_id);
			$team['total_amount_collecte'] = CRM_Generic_Teamstanden::getTotalAmountDonatedForTeam_Collecte($teamDao->id, $campaign_id);
      $team['total_amount_veiling'] = CRM_Generic_Teamstanden::getTotalAmountDonatedForTeam_Veiling($teamDao->id, $campaign_id);
			$team['total_amount_loterij'] = CRM_Generic_Teamstanden::getTotalAmountDonatedForTeam_Loterij($teamDao->id, $campaign_id);
      $team['total_amount_sms'] = CRM_Generic_Teamstanden::getTotalAmountDonatedForTeam_Sms($teamDao->id, $campaign_id);
			return $team;
		} else {
			throw new Exception('Could not find team');
		}
	}
	
}
