<?php

/**
 * RoparunTeams.Get API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 * @return void
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC/API+Architecture+Standards
 */
function _civicrm_api3_roparun_team_Details_spec(&$spec) {
	$spec['team_id'] = array(
    'api.aliases' => array('id'),
    'api.required' => true,
    'title' => 'Contact ID of the team',
    'type' => CRM_Utils_Type::T_INT,
  );
	$spec['event_id'] = array(
    'api.required' => true,
    'title' => 'Event ID',
    'type' => CRM_Utils_Type::T_INT,
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
function civicrm_api3_roparun_team_Details($params) {
	$details = new CRM_Api_RoparunTeam_Details();
	$event_id = null;
	if (isset($params['event_id'])) {
		$event_id = $params['event_id'];
	}
	$returnValues = $details->details($params['team_id'], $event_id);
	return civicrm_api3_create_success($returnValues, $params, 'RoparunTeam', 'Details');
}
