<?php

class CRM_Api_RoparunConfig {
	
	private static $singleton;
	
	private $_facebookWebsiteTypeId;
	private $_twitterWebsiteTypeId;
	private $_googlePlusWebsiteTypeId;
	private $_instagramWebsiteTypeId;
	private $_linkedinWebsiteTypeId;
	private $_myspaceWebsiteTypeId;
	private $_pinterestWebsiteTypeId;
	private $_snapchatWebsiteTypeId;
	private $_tumblrWebsiteTypeId;
	private $_vineWebsiteTypeId;
	private $_websiteWebsiteTypeId;
	private $_roparunEventTypeId;
	private $_roparunEventCustomGroupId;
	private $_roparunEventCustomGroupTableName;
	private $_endDateDonationsCustomFieldId;
	private $_endDateDonationsCustomFieldColumnName;
	private $_teamDataCustomGroupId;
	private $_teamDataCustomGroupTableName;
	private $_teamNrCustomFieldId;
	private $_teamNrCustomFieldColumnName;
	private $_teamNameCustomFieldId;
	private $_teamNameCustomFieldColumnName;
	private $_donatedTowardsCustomGroupId;
	private $_donatedTowardsCustomGroupTableName;
	private $_towardsTeamCustomFieldId;
	private $_towardsTeamCustomFieldColumnName;
	private $_towardsTeamMemberCustomFieldId;
	private $_towardsTeamMemberCustomFieldColumnName;
	private $_donatieFinancialTypeId;
	private $_collecteFinancialTypeId;
	private $_loterijFinancialTypeId;
	private $_completedContributionStatusId;
	private $_donorInformationCustomGroupId;
	private $_donorInformationCustomGroupTableName;
	private $_donateAnoymousCustomFieldId;
	private $_donateAnonymousCustomFieldColumnName;
	private $_teamMemberDataCustomGroupId;
	private $_teamMemberDataCustomGroupTableName;
	private $_memberOfTeamCustomFieldId;
	private $_memberOfTeamCustomFieldColumnName;
	private $_teamRoleCustomFieldId;
	private $_teamRoleCustomFieldColumnName;
	private $_donateAnonymousOptionValue;
	private $_teamParticipantRoleId;
	private $_teammemberParticipantRoleId;
	
	private function __construct() {
		$this->loadWebsiteTypes();
		$this->loadCustomGroups();
		$this->loadFinancialTypes();
		try {
			$this->_roparunEventTypeId = civicrm_api3('OptionValue', 'getvalue', array(
				'return' => 'value',
				'name' => 'Roparun',
				'option_group_id' => 'event_type',
			));
		} catch (Exception $ex) {
			throw new Exception ('Could not retrieve the Roparun Event Type');
		}
		
		try {
			$this->_teamParticipantRoleId = civicrm_api3('OptionValue', 'getvalue', array(
				'return' => 'value',
				'name' => 'Team',
				'option_group_id' => 'participant_role',
			));
		} catch (Exception $ex) {
			throw new Exception ('Could not retrieve the Team participant role');
		}
		try {
			$this->_teammemberParticipantRoleId = civicrm_api3('OptionValue', 'getvalue', array(
				'return' => 'value',
				'name' => 'team_member',
				'option_group_id' => 'participant_role',
			));
		} catch (Exception $ex) {
			throw new Exception ('Could not retrieve the Team Member participant role');
		}
		
		try {
			$this->_completedContributionStatusId = civicrm_api3('OptionValue', 'getvalue', array(
				'return' => 'value',
				'name' => 'Completed',
				'option_group_id' => 'contribution_status',
			));
		} catch (Exception $ex) {
			throw new Exception ('Could not retrieve the Contribution status completed');
		}
		try {
			$this->_donateAnonymousOptionValue = civicrm_api3('OptionValue', 'getvalue', array(
				'return' => 'value',
				'name' => 'anonymous',
				'option_group_id' => 'anonymous_donation',
			));
		} catch (Exception $ex) {
			throw new Exception ('Could not retrieve the option value Anonymous for option group anonymous donation');
		}
	}
	
	/**
	 * @return CRM_Api_RoparunConfig
	 */
	public static function singleton() {
		if (!self::$singleton) {
			self:: $singleton = new CRM_Api_RoparunConfig();
		}
		return self::$singleton;
	}
	
	/** 
	 * Getter for the Roparun event type id.
	 */
	public function getRoparunEventTypeId() {
		return $this->_roparunEventTypeId;
	}
	
	/**
	 * Getter for the custom group id of the custom group 'roparun event'.
	 */
	public function getRoparunEventCustomGroupId() {
		return $this->_roparunEventCustomGroupId;
	}
	
	/**
	 * Getter for the custom group table name of the custom group 'roparun event'.
	 */
	public function getRoparunEventCustomGroupTableName() {
		return $this->_roparunEventCustomGroupTableName;
	}
	
	/**
	 * Getter for the custom field id of the custom field end date donations.
	 */
	public function getEndDateDonationsCustomFieldId() {
		return $this->_endDateDonationsCustomFieldId;
	}
	
	/**
	 * Getter for the custom field column name of the custom field end date donations.
	 */
	public function getEndDateDonationsCustomFieldColumnName() {
		return $this->_endDateDonationsCustomFieldColumnName;
	}
	
	/**
	 * Getter for the id of the custom group team_data.
	 */
	public function getTeamDataCustomGroupId() {
		return $this->_teamDataCustomGroupId;
	}
	
	/**
	 * Getter for the table name of the custom group team_data.
	 */
	public function getTeamDataCustomGroupTableName() {
		return $this->_teamDataCustomGroupTableName;
	}
	
	/**
	 * Getter for the id of the custom field team_nr.
	 */
	public function getTeamNrCustomFieldId() {
		return $this->_teamNrCustomFieldId;
	}
	
	/**
	 * Getter for the column name of the custom field team_nr.
	 */
	public function getTeamNrCustomFieldColumnName() {
		return $this->_teamNrCustomFieldColumnName;
	}
	
	/**
	 * Getter for the id of the custom field team_name.
	 */
	public function getTeamNameCustomFieldId() {
		return $this->_teamNameCustomFieldId;
	}
	
	/**
	 * Getter for the column name of the custom field team_name.
	 */
	public function getTeamNameCustomFieldColumnName() {
		return $this->_teamNameCustomFieldColumnName;
	}
	
	/**
	 * Getter for custom group id of donated towards.
	 */
	public function getDonatedTowardsCustomGroupId() {
		return $this->_donatedTowardsCustomGroupId;
	}
	
	/**
	 * Getter for custom group table name of donated towards.
	 */
	public function getDonatedTowardsCustomGroupTableName() {
		return $this->_donatedTowardsCustomGroupTableName;
	}
	
	/**
	 * Getter for custom field id of towards team.
	 */
	public function getTowardsTeamCustomFieldId() {
		return $this->_towardsTeamCustomFieldId;
	}
	
	/**
	 * Getter for custom field column name of towards teams.
	 */
	public function getTowardsTeamCustomFieldColumnName() {
		return $this->_towardsTeamCustomFieldColumnName;
	}
	
	/**
	 * Getter for custom field id of towards team member.
	 */
	public function getTowardsTeamMemberCustomFieldId() {
		return $this->_towardsTeamMemberCustomFieldId;
	}
	
	/**
	 * Getter for custom field column name of towards team member.
	 */
	public function getTowardsTeamMemberCustomFieldColumnName() {
		return $this->_towardsTeamMemberCustomFieldColumnName;
	}
	
	/**
	 * Getter for custom group id donor information.
	 */
	public function getDonorInformationCustomGroupdId() {
		return $this->_donorInformationCustomGroupId;
	}
	
	/**
	 * Getter for custom group table name of donor information.
	 */
	public function getDonorInformationCustomGroupTableName() {
		return $this->_donorInformationCustomGroupTableName; 
	}
	
	/**
	 * Getter for custom field if for donate anonymous
	 */
	public function getDonateAnonymousCustomFieldId() {
		return $this->_donateAnoymousCustomFieldId;
	}
	
	/**
	 * Getter for the custom group id of custom group team_member_data.
	 */
	public function getTeamMemberDataCustomGroupId() {
		return $this->_teamMemberDataCustomGroupId;
	}
	
	/**
	 * Getter for the table name of the custom group team_member_data.
	 */
	public function getTeamMemberDataCustomGroupTableName() {
		return $this->_teamMemberDataCustomGroupTableName;
	}
	
	/**
	 * Getter for the custom field id of the custom field team_member_of_team.
	 */
	public function getMemberOfTeamCustomFieldId() {
		return $this->_memberOfTeamCustomFieldId;
	}
	
	/**
	 * Getter for the column name of the custom field team_member_of_team.
	 */
	public function getMemberOfTeamCustomFieldColumnName() {
		return $this->_memberOfTeamCustomFieldColumnName;
	}
	
	/**
	 * Getter for the custom field id of the custom field team_role.
	 */
	public function getTeamRoleCustomFieldId() {
		return $this->_teamRoleCustomFieldId;
	}
	
	/**
	 * Getter for the column name of the custom field team_role.
	 */
	public function getTeamRoleCustomFieldColumnName() {
		return $this->_teamRoleCustomFieldColumnName;
	}
	
	/**
	 * Getter for custom field column name for donate anonymous.
	 */
	public function getDonateAnonymousCustomFieldColumnName() {
		return $this->_donateAnonymousCustomFieldColumnName;
	}
	
	/**
	 * Getter for the option value donate anonymous.
	 */
	public function getDonateAnonymousOptionValue() {
		return $this->_donateAnonymousOptionValue;
	}
	
	/**
	 * Getter for the main website type id.
	 */
	public function getWebsiteWebsiteTypeId() {
		return $this->_websiteWebsiteTypeId;
	}
	/**
	 * Getter for the facebook website type id.
	 */
	public function getFacebookWebsiteTypeId() {
		return $this->_facebookWebsiteTypeId;
	}
	/**
	 * Getter for the Google+ website type id.
	 */
	public function getGooglePlusWebsiteTypeId() {
		return $this->_googlePlusWebsiteTypeId;
	}
	/**
	 * Getter for the instagram website type id.
	 */
	public function getInstagramWebsiteTypeId() {
		return $this->_instagramWebsiteTypeId;
	}
	/**
	 * Getter for the LinkedIn website type id.
	 */
	public function getLinkedInWebsiteTypeId() {
		return $this->_linkedinWebsiteTypeId;
	}
	/**
	 * Getter for the myspace website type id.
	 */
	public function getMySpaceWebsiteTypeId() {
		return $this->_myspaceWebsiteTypeId;
	}
	/**
	 * Getter for the pinterest website type id.
	 */
	public function getPinterestWebsiteTypeId() {
		return $this->_pinterestWebsiteTypeId;
	}
	/**
	 * Getter for the snapchat website type id.
	 */
	public function getSnapChatWebsiteTypeId() {
		return $this->_snapchatWebsiteTypeId;
	}
	/**
	 * Getter for the tumblr website type id.
	 */
	public function getTumblrWebsiteTypeId() {
		return $this->_tumblrWebsiteTypeId;
	}
	/**
	 * Getter for the twitter website type id.
	 */
	public function getTwitterWebsiteTypeId() {
		return $this->_twitterWebsiteTypeId;
	}
	/**
	 * Getter for the vine website type id.
	 */
	public function getVineWebsiteTypeId() {
		return $this->_vineWebsiteTypeId;
	}
	
	/**
	 * Getter for completed contribution status id.
	 */
	public function getCompletedContributionStatusId() {
		return $this->_completedContributionStatusId;
	}
	
	/**
	 * Getter for donation financial type id.
	 */
	public function getDonatieFinancialTypeId() {
		return $this->_donatieFinancialTypeId;
	}
	
	/**
	 * Getter for collecte financial type id.
	 */
	public function getCollecteFinancialTypeId() {
		return $this->_collecteFinancialTypeId;
	}
	
	/**
	 * Getter for loterij financial type id.
	 */
	public function getLoterijFinancialTypeId() {
		return $this->_loterijFinancialTypeId;
	}
	
	/**
	 * Getter for role id of team.
	 */
	public function getTeamParticipantRoleId() {
		return $this->_teamParticipantRoleId;
	}
	
	/**
	 * Getter for role id of team member.
	 */
	public function getTeamMemberParticipantRoleId() {
		return $this->_teammemberParticipantRoleId;
	}
	
	private function loadWebsiteTypes() {
		try {
			$this->_websiteWebsiteTypeId = civicrm_api3('OptionValue', 'getvalue', array(
				'return' => 'value',
				'name' => 'Main',
				'option_group_id' => 'website_type',
			));
		} catch (exception $ex) {
			throw new Exception ('Could not retrieve Main website type id');
		}	
		try {
			$this->_facebookWebsiteTypeId = civicrm_api3('OptionValue', 'getvalue', array(
				'return' => 'value',
				'name' => 'Facebook',
				'option_group_id' => 'website_type',
			));
		} catch (exception $ex) {
			throw new Exception ('Could not retrieve Facebook website type id');
		}
		
		try {
			$this->_googlePlusWebsiteTypeId = civicrm_api3('OptionValue', 'getvalue', array(
				'return' => 'value',
				'name' => 'Google_',
				'option_group_id' => 'website_type',
			));
		} catch (exception $ex) {
			throw new Exception ('Could not retrieve Google+ website type id');
		}
		try {
			$this->_instagramWebsiteTypeId = civicrm_api3('OptionValue', 'getvalue', array(
				'return' => 'value',
				'name' => 'Instagram',
				'option_group_id' => 'website_type',
			));
		} catch (exception $ex) {
			throw new Exception ('Could not retrieve Instagram website type id');
		}
		try {
			$this->_linkedinWebsiteTypeId = civicrm_api3('OptionValue', 'getvalue', array(
				'return' => 'value',
				'name' => 'LinkedIn',
				'option_group_id' => 'website_type',
			));
		} catch (exception $ex) {
			throw new Exception ('Could not retrieve LinkedIn website type id');
		}
		try {
			$this->_myspaceWebsiteTypeId = civicrm_api3('OptionValue', 'getvalue', array(
				'return' => 'value',
				'name' => 'MySpace',
				'option_group_id' => 'website_type',
			));
		} catch (exception $ex) {
			throw new Exception ('Could not retrieve MySpace website type id');
		}
		try {
			$this->_pinterestWebsiteTypeId = civicrm_api3('OptionValue', 'getvalue', array(
				'return' => 'value',
				'name' => 'Pinterest',
				'option_group_id' => 'website_type',
			));
		} catch (exception $ex) {
			throw new Exception ('Could not retrieve Pinterest website type id');
		}
		try {
			$this->_snapchatWebsiteTypeId = civicrm_api3('OptionValue', 'getvalue', array(
				'return' => 'value',
				'name' => 'SnapChat',
				'option_group_id' => 'website_type',
			));
		} catch (exception $ex) {
			throw new Exception ('Could not retrieve SnapChat website type id');
		}
		try {
			$this->_tumblrWebsiteTypeId = civicrm_api3('OptionValue', 'getvalue', array(
				'return' => 'value',
				'name' => 'Tumblr',
				'option_group_id' => 'website_type',
			));
		} catch (exception $ex) {
			throw new Exception ('Could not retrieve Tumblr website type id');
		}
		try {
			$this->_vineWebsiteTypeId = civicrm_api3('OptionValue', 'getvalue', array(
				'return' => 'value',
				'name' => 'Vine',
				'option_group_id' => 'website_type',
			));
		} catch (exception $ex) {
			throw new Exception ('Could not retrieve Vinc website type id');
		}
		try {
			$this->_twitterWebsiteTypeId = civicrm_api3('OptionValue', 'getvalue', array(
				'return' => 'value',
				'name' => 'Twitter',
				'option_group_id' => 'website_type',
			));
		} catch (exception $ex) {
			throw new Exception ('Could not retrieve Twitter website type id');
		}
	}

	private function loadFinancialTypes() {
		try {
			$this->_donatieFinancialTypeId = civicrm_api3('FinancialType', 'getvalue', array(
				'name' => 'Donatie',
				'return' => 'id',
			));
		} catch (Exception $e) {
			throw new Exception('Could not retrieve financial type Donatie');
		}
		try {
			$this->_collecteFinancialTypeId = civicrm_api3('FinancialType', 'getvalue', array(
				'name' => 'Opbrengst collecte',
				'return' => 'id',
			));
		} catch (Exception $e) {
			throw new Exception('Could not retrieve financial type Opbrengst collecte');
		}
		try {
			$this->_loterijFinancialTypeId = civicrm_api3('FinancialType', 'getvalue', array(
				'name' => 'Opbrengst lotterij',
				'return' => 'id',
			));
		} catch (Exception $e) {
			throw new Exception('Could not retrieve financial type Opbrengst lotterij');
		}
	}

	private function loadCustomGroups() {
		try {
			$_roparunEventCustomGroup = civicrm_api3('CustomGroup', 'getsingle', array('name' => 'roparun_event'));
			$this->_roparunEventCustomGroupId = $_roparunEventCustomGroup['id'];
			$this->_roparunEventCustomGroupTableName = $_roparunEventCustomGroup['table_name'];
		} catch (Exception $ex) {
			throw new Exception('Could not find custom group for roparun events');
		}
		try {
			$_roparunEndDateDonationsCustomField = civicrm_api3('CustomField', 'getsingle', array('name' => 'end_date_donations', 'custom_group_id' => $this->_roparunEventCustomGroupId));
			$this->_endDateDonationsCustomFieldColumnName = $_roparunEndDateDonationsCustomField['column_name'];
			$this->_endDateDonationsCustomFieldId = $_roparunEndDateDonationsCustomField['id'];
		} catch (Exception $ex) {
			throw new Exception('Could not find custom field End Date Donations');
		}

		try {
			$_teamDataCustomGroup = civicrm_api3('CustomGroup', 'getsingle', array('name' => 'team_data'));
			$this->_teamDataCustomGroupId = $_teamDataCustomGroup['id'];
			$this->_teamDataCustomGroupTableName = $_teamDataCustomGroup['table_name'];
		} catch (Exception $ex) {
			throw new Exception('Could not find custom group for Team data');
		}
		try {
			$_teamNrCustomField = civicrm_api3('CustomField', 'getsingle', array('name' => 'team_nr', 'custom_group_id' => $this->_teamDataCustomGroupId));
			$this->_teamNrCustomFieldColumnName = $_teamNrCustomField['column_name'];
			$this->_teamNrCustomFieldId = $_teamNrCustomField['id'];
		} catch (Exception $ex) {
			throw new Exception('Could not find custom field Team NR');
		}
		try {
			$_teamNameCustomField = civicrm_api3('CustomField', 'getsingle', array('name' => 'team_name', 'custom_group_id' => $this->_teamDataCustomGroupId));
			$this->_teamNameCustomFieldColumnName = $_teamNameCustomField['column_name'];
			$this->_teamNameCustomFieldId = $_teamNameCustomField['id'];
		} catch (Exception $ex) {
			throw new Exception('Could not find custom field Team Name');
		}
		
		try {
			$_donatedTowardsCustomGroup = civicrm_api3('CustomGroup', 'getsingle', array('name' => 'donated_towards'));
			$this->_donatedTowardsCustomGroupId = $_donatedTowardsCustomGroup['id'];
			$this->_donatedTowardsCustomGroupTableName = $_donatedTowardsCustomGroup['table_name'];
		} catch (Exception $ex) {
			throw new Exception('Could not find custom group for Donated Towards');
		}
		try {
			$_towardsTeamCustomField = civicrm_api3('CustomField', 'getsingle', array('name' => 'towards_team', 'custom_group_id' => $this->_donatedTowardsCustomGroupId));
			$this->_towardsTeamCustomFieldColumnName = $_towardsTeamCustomField['column_name'];
			$this->_towardsTeamCustomFieldId = $_towardsTeamCustomField['id'];
		} catch (Exception $ex) {
			throw new Exception('Could not find custom field Towards Team');
		}
		try {
			$_towardsTeamMemberCustomField = civicrm_api3('CustomField', 'getsingle', array('name' => 'towards_team_member', 'custom_group_id' => $this->_donatedTowardsCustomGroupId));
			$this->_towardsTeamMemberCustomFieldColumnName = $_towardsTeamMemberCustomField['column_name'];
			$this->_towardsTeamMemberCustomFieldId = $_towardsTeamMemberCustomField['id'];
		} catch (Exception $ex) {
			throw new Exception('Could not find custom field Towards Team Member');
		}
		
		try {
			$_donorInformationCustomGroup = civicrm_api3('CustomGroup', 'getsingle', array('name' => 'donor_information'));
			$this->_donorInformationCustomGroupId = $_donorInformationCustomGroup['id'];
			$this->_donorInformationCustomGroupTableName = $_donorInformationCustomGroup['table_name'];
		} catch (Exception $ex) {
			throw new Exception('Could not find custom group for Donor Information');
		}
		try {
			$_anonymousCustomField = civicrm_api3('CustomField', 'getsingle', array('name' => 'anonymous', 'custom_group_id' => $this->_donorInformationCustomGroupId));
			$this->_donateAnonymousCustomFieldColumnName = $_anonymousCustomField['column_name'];
			$this->_donateAnoymousCustomFieldId = $_anonymousCustomField['id'];
		} catch (Exception $ex) {
			throw new Exception('Could not find custom field Anonymous');
		}
		
		try {
			$_teamMemberCustomGroup = civicrm_api3('CustomGroup', 'getsingle', array('name' => 'team_member_data'));
			$this->_teamMemberDataCustomGroupId = $_teamMemberCustomGroup['id'];
			$this->_teamMemberDataCustomGroupTableName = $_teamMemberCustomGroup['table_name'];
		} catch (Exception $ex) {
			throw new Exception('Could not find custom group for Team Member Data');
		}
		try {
			$_memberOfTeamCustomField = civicrm_api3('CustomField', 'getsingle', array('name' => 'team_member_of_team', 'custom_group_id' => $this->_teamMemberDataCustomGroupId));
			$this->_memberOfTeamCustomFieldColumnName = $_memberOfTeamCustomField['column_name'];
			$this->_memberOfTeamCustomFieldId = $_memberOfTeamCustomField['id'];
		} catch (Exception $ex) {
			throw new Exception('Could not find custom field Member of Team');
		}
		try {
			$_teamRoleCustomField = civicrm_api3('CustomField', 'getsingle', array('name' => 'team_role', 'custom_group_id' => $this->_teamMemberDataCustomGroupId));
			$this->_teamRoleCustomFieldColumnName = $_teamRoleCustomField['column_name'];
			$this->_teamRoleCustomFieldId = $_teamRoleCustomField['id'];
		} catch (Exception $ex) {
			throw new Exception('Could not find custom field Team role');
		}
	}
	
}
