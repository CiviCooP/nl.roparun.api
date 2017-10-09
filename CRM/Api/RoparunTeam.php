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
	
	/**
	 * Returns the total amount donated for a campaign
	 * 
	 * @param int $team_id
	 * 	The contact id of the team
	 * @param int $campaign_id
	 * 	The ID of the campaign.
	 * @return float
	 */
	protected function getTotalAmountDonated($campaign_id) {
		try {
			$config = CRM_Api_RoparunConfig::singleton();
		
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
	protected function getTotalAmountDonatedForRoparun($campaign_id) {
		try {
			$config = CRM_Api_RoparunConfig::singleton();
		
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
	protected function getTotalAmountDonatedForTeams($campaign_id) {
		try {
			$config = CRM_Api_RoparunConfig::singleton();
		
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
	protected function getTotalAmountDonatedForTeam($team_id, $campaign_id) {
		try {
			$config = CRM_Api_RoparunConfig::singleton();
		
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
	protected function getTotalAmountDonatedForTeam_OnlyTeam($team_id, $campaign_id) {
		try {
			$config = CRM_Api_RoparunConfig::singleton();
		
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
	protected function getTotalAmountDonatedForTeam_TeamMembers($team_id, $campaign_id) {
		try {
			$config = CRM_Api_RoparunConfig::singleton();
		
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
	protected function getTotalAmountDonatedForTeam_Collecte($team_id, $campaign_id) {
		try {
			$config = CRM_Api_RoparunConfig::singleton();
		
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
	protected function getTotalAmountDonatedForTeam_Loterij($team_id, $campaign_id) {
		try {
			$config = CRM_Api_RoparunConfig::singleton();
		
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
	protected function getTotalAmountDonatedForTeamMember($contact_id, $campaign_id) {
		try {
			$config = CRM_Api_RoparunConfig::singleton();
		
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