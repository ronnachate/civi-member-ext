<?php
use CRM_Memberperiod_ExtensionUtil as E;
define('MEMBERSHIP_PERIOD_CLASS_NAME', 'CRM_Memberperiod_DAO_MembershipPeriod', true);
define('MEMBERSHIP_PERIOD_ENTITY_NAME', 'MembershipPeriod', true);

class CRM_Memberperiod_BAO_MembershipPeriod extends CRM_Memberperiod_DAO_MembershipPeriod {

  /**
   * Create a new MembershipPeriod based on array-data
   *
   * @param array $params key-value pairs
   * @return CRM_Memberperiod_DAO_MembershipPeriod|NULL
   */

  public static function createOrUpdate($params) {
    $className = MEMBERSHIP_PERIOD_CLASS_NAME;
    $hook = empty($params['id']) ? 'create' : 'edit';

    CRM_Utils_Hook::pre($hook, MEMBERSHIP_PERIOD_ENTITY_NAME, CRM_Utils_Array::value('id', $params), $params);
    $instance = new $className();
    $instance->copyValues($params);
    $instance->save();
    CRM_Utils_Hook::post($hook, MEMBERSHIP_PERIOD_ENTITY_NAME, $instance->id, $instance);

    return $instance;
  }

    /**
   * GET MembershipPeriod by id
   *
   * @param array $params key-value pairs
   * @return bool
   */

  public static function updateWithContribution($membership_period_id, $contribution_id) {
    $className = MEMBERSHIP_PERIOD_CLASS_NAME;
    $member_period_dao = new $className();
    $member_period_dao->id = $membership_period_id;
    $result = false;
    if ($member_period_dao->find(TRUE)) {
      $member_period_dao->contribution_id = $contribution_id;
      $member_period_dao->save();
      $member_period_dao->free();
      $result = true;
    }
    return $result;
  }
}
