<?php

/**
 * RoparunTeams.Get API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 * @return void
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC/API+Architecture+Standards
 */
function _civicrm_api3_roparun_team_Getdetails_spec(&$spec) {
	$spec['team_id'] = array(
    'api.aliases' => array('id'),
    'api.required' => true,
    'title' => 'Contact ID of the team',
    'type' => CRM_Utils_Type::T_INT,
  );
	$spec['event_id'] = array(
    'api.required' => false,
    'title' => 'Event ID',
    'type' => CRM_Utils_Type::T_INT,
  );
	$spec['include_team_members_with_donations'] = array(
    'api.required' => false,
    'title' => 'Include team members with donations',
    'type' => CRM_Utils_Type::T_BOOLEAN,
  );
}

/**
 * RoparunTeams.Get API
 *
 * @param array $params
 * @return array API result descriptor
 * @see civicrm_api3_create_success
 * @see civicrm_api3_create_error
 * @throws API_Exception
 */
function civicrm_api3_roparun_team_Getdetails($params) {
	$details = new CRM_Api_RoparunTeam_Details();
	$event_id = null;
	if (isset($params['event_id'])) {
		$event_id = $params['event_id'];
	}
	
	$includeTeamMembersWithDonations = true;
	if (isset($params['include_team_members_with_donations'])) {
    $includeTeamMembersWithDonations = $params['include_team_members_with_donations'] ? true : false;
	}
	
	$returnValues = $details->details($params['team_id'], $event_id, $includeTeamMembersWithDonations);
	return civicrm_api3_create_success($returnValues, $params, 'RoparunTeam', 'Details');
}
