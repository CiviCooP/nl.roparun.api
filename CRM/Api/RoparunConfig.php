<?php

class CRM_Api_RoparunConfig {
	
	private static $singleton;
	
	private $_facebookWebsiteTypeId;
	private $_twitterWebsiteTypeId;
	private $_instagramWebsiteTypeId;
	private $_websiteWebsiteTypeId;
	private $_teamDataCustomGroupId;
	private $_teamDataCustomGroupTableName;
	private $_teamNrCustomFieldId;
	private $_teamNrCustomFieldColumnName;
	private $_teamNameCustomFieldId;
	private $_teamNameCustomFieldColumnName;
	private $_startLocationCustomFieldId;
	private $_startLocationCustomFieldColumnName;
	private $_startLocationOptions;
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
	private $_showOnWebsiteCustomFieldId;
	private $_showOnWebsiteCustomFieldColumnName;
	private $_donateAnonymousOptionValue;
	private $_teamParticipantRoleId;
	private $_teammemberParticipantRoleId;
	private $_vestigingsLocationTypeId;
	private $_teamCaptainRelationshipTypeId;
	
	private function __construct() {
		$this->loadWebsiteTypes();
		$this->loadCustomGroups();

		try {
		  $this->_teamCaptainRelationshipTypeId = civicrm_api3('RelationshipType', 'getvalue', array('name_b_a' => 'Teamcaptain is', 'return' => 'id'));
    } catch (Exception $e) {
		  throw new Exception('Could not find relationship type team captain');
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
			$this->_donateAnonymousOptionValue = civicrm_api3('OptionValue', 'getvalue', array(
				'return' => 'value',
				'name' => 'anonymous',
				'option_group_id' => 'anonymous_donation',
			));
		} catch (Exception $ex) {
			throw new Exception ('Could not retrieve the option value Anonymous for option group anonymous donation');
		}
		try {
			$this->_vestigingsLocationTypeId = civicrm_api3('LocationType', 'getvalue', array(
				'return' => 'id',
				'name' => 'Vestigingsplaats',
			));
		} catch (Exception $ex) {
			throw new Exception('Could not find Vestigingsadres location type id');
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
	 * Getter for the id of the custom field start_location.
	 */
	public function getStartLocationCustomFieldId() {
		return $this->_startLocationCustomFieldId;
	}
	
	/**
	 * Getter for the column name of the custom field start_location.
	 */
	public function getStartLocationCustomFieldColumnName() {
		return $this->_startLocationCustomFieldColumnName;
	}
	
	/**
	 * Returns the options for the start_location custom field.
	 */
	public function getStartLocationOptions() {
		return is_array($this->_startLocationOptions) ? $this->_startLocationOptions : array();
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
	 * Getter for the id fo the custom field website.
	 */
	public function getShowOnWebsiteCustomField() {
		return $this->_showOnWebsiteCustomFieldId;
	}
	
	/**
	 * Getter for the column name of the custom field website.
	 */
	public function getShowOnWebsiteCustomFieldColumnName() {
		return $this->_showOnWebsiteCustomFieldColumnName;
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
	 * Getter for the instagram website type id.
	 */
	public function getInstagramWebsiteTypeId() {
		return $this->_instagramWebsiteTypeId;
	}
	/**
	 * Getter for the twitter website type id.
	 */
	public function getTwitterWebsiteTypeId() {
		return $this->_twitterWebsiteTypeId;
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
	
	/**
	 * Getter for vestigingsplaats location type id.
	 */
	public function getVestingsplaatsLocationTypeId() {
		return $this->_vestigingsLocationTypeId;
	}

  /**
   * Getter for the relationship type id.
   */
	public function getTeamCaptainRelationshipTypeId() {
	  return $this->_teamCaptainRelationshipTypeId;
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
			$this->_instagramWebsiteTypeId = civicrm_api3('OptionValue', 'getvalue', array(
				'return' => 'value',
				'name' => 'Instagram',
				'option_group_id' => 'website_type',
			));
		} catch (exception $ex) {
			throw new Exception ('Could not retrieve Instagram website type id');
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

	private function loadCustomGroups() {
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
			$_startLocationCustomField = civicrm_api3('CustomField', 'getsingle', array('name' => 'start_location', 'custom_group_id' => $this->_teamDataCustomGroupId));
			$this->_startLocationCustomFieldColumnName = $_startLocationCustomField['column_name'];
			$this->_startLocationCustomFieldId = $_startLocationCustomField['id'];
			$_startLocationOptions = civicrm_api3('Participant', 'getoptions', array('field' => "custom_".$this->_startLocationCustomFieldId));
			$this->_startLocationOptions = $_startLocationOptions['values'];
		} catch (Exception $ex) {
			throw new Exception('Could not find custom field Start Location');
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
		try {
			$websiteCustomField = civicrm_api3('CustomField', 'getsingle', array('name' => 'website', 'custom_group_id' => $this->_teamMemberDataCustomGroupId));
			$this->_showOnWebsiteCustomFieldColumnName = $websiteCustomField['column_name'];
			$this->_showOnWebsiteCustomFieldId = $websiteCustomField['id'];
		} catch (Exception $ex) {
			throw new Exception('Could not find custom field website');
		}
	}
	
}
