<?php
use CRM_Memberperiod_ExtensionUtil as E;

define('MEMBERSHIP_PERIOD_ENTITY_NAME', 'MembershipPeriod', true);

/**
 * MembershipPeriod.create API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 * @return void
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC/API+Architecture+Standards
 */
function _civicrm_api3_membership_period_create_spec(&$spec) {
    $spec['membership_id']['api.required'] = 1;
    $spec['start_date']['api.required'] = 1;
    $spec['end_date']['api.default'] = NULL;
    $spec['created_at']['api.required'] = 1;
}

/**
 * MembershipPeriod.create API
 *
 * @param array $params
 * @return array API result descriptor
 * @throws API_Exception
 */
function civicrm_api3_membership_period_create($params) {
    $membership_period = CRM_Memberperiod_BAO_MembershipPeriod::createOrUpdate($membership_period_obj);
    $period_array = array();
    _civicrm_api3_object_to_array($membership_period, period_array[$membership_period->id]);
    return civicrm_api3_create_success($period_array, $params, MEMBERSHIP_PERIOD_ENTITY_NAME, 'create');
}

/**
 * MembershipPeriod.delete API
 *
 * @param array $params
 * @return array API result descriptor
 * @throws API_Exception
 */
function civicrm_api3_membership_period_delete($params) {
  return _civicrm_api3_basic_delete(_civicrm_api3_get_BAO(__FUNCTION__), $params);
}

/**
 * MembershipPeriod.get API
 *
 * @param array $params
 * @return array API result descriptor
 * @throws API_Exception
 */

function civicrm_api3_membership_period_get($params) {
  return _civicrm_api3_basic_get(_civicrm_api3_get_BAO(__FUNCTION__), $params);
}
