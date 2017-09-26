<?php

/**
 * RoparunTeams.Get API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 * @return void
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC/API+Architecture+Standards
 */
function _civicrm_api3_roparun_team_Get_spec(&$spec) {
	$spec['event_id'] = array(
    'api.required' => false,
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
function civicrm_api3_roparun_team_Get($params) {
	$overview = new CRM_Api_RoparunTeam_Overview();
	$event_id = null;
	if (isset($params['event_id'])) {
		$event_id = $params['event_id'];
	}
	$returnValues = $overview->overview($event_id);
	return civicrm_api3_create_success($returnValues, $params, 'RoparunTeam', 'get');
}
