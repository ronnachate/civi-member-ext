<?php

require_once 'memberperiod.civix.php';
use CRM_Memberperiod_ExtensionUtil as E;

define('MEMBERSHIP_OBJ_NAME', 'Membership', true);
define('MEMBERSHIP_PAYMENT_OBJ_NAME', 'MembershipPayment', true);
define('MEMBERSHIP_PERIOD_IDS_SESSION', 'membership_period_id', true);
define('MEMBERSHIP_PERIOD_SESSION_DELIMITER', ',', true);
define('DEFAULT_DATETIME_FORMAT', 'YmdHis', true);
define('PREVIUOS_MEMBERSHIP_END_DATE', 'YmdHis', true);
define('MYSQL_DATE_FORMAT', 'Y-m-d H:i:s', true);
define('DATE_DIFF_PATCHING', 5, true);
define('OP_CREATE', 'create', true);
define('OP_EDIT', 'edit', true);

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function memberperiod_civicrm_config(&$config) {
  _memberperiod_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function memberperiod_civicrm_xmlMenu(&$files) {
  _memberperiod_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function memberperiod_civicrm_install() {
  _memberperiod_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_postInstall
 */
function memberperiod_civicrm_postInstall() {
  _memberperiod_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function memberperiod_civicrm_uninstall() {
  _memberperiod_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function memberperiod_civicrm_enable() {
  _memberperiod_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function memberperiod_civicrm_disable() {
  _memberperiod_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function memberperiod_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _memberperiod_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function memberperiod_civicrm_managed(&$entities) {
  _memberperiod_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function memberperiod_civicrm_caseTypes(&$caseTypes) {
  _memberperiod_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_angularModules
 */
function memberperiod_civicrm_angularModules(&$angularModules) {
  _memberperiod_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function memberperiod_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _memberperiod_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_pre().
 * Hooking when membership is created/edited/renewed.
 * Clear period id session and get prevouos membership if exist
 *
 */
function memberperiod_civicrm_pre($op, $objectName, $id, &$params) {
    if( $objectName == MEMBERSHIP_OBJ_NAME) {
        if( $op == OP_CREATE || $op == OP_EDIT ) {
            $session = CRM_Core_Session::singleton();
            //clear membeperiod id session
            $session->set(MEMBERSHIP_PERIOD_IDS_SESSION, NULL);
            $session->set(PREVIUOS_MEMBERSHIP_END_DATE, NULL);
            if( $id ) {
                $get_params = array('id' => $id);
                $values = array();
                $previous_membership_details = CRM_Member_BAO_Membership::getValues($get_params, $values);
                if($previous_membership_details) {
                    $previous_membership_detail = $previous_membership_details[$id];
                    $session->set(PREVIUOS_MEMBERSHIP_END_DATE, $previous_membership_detail->end_date);
                }
            }
        }
    }
}

/**
 * Implements hook_civicrm_post().
 * Hooking when membership is created/edited/renewed.
 * Create membership period base on membership data
 */
function memberperiod_civicrm_post($op, $objectName, $objectId, &$objectRef) {
    switch ($objectName) {
        case MEMBERSHIP_OBJ_NAME:
            if( $op == OP_CREATE || $op == OP_EDIT  ) {
                $session = CRM_Core_Session::singleton();
                //calculate term and date by membership_type_id
                //get membership type
                $params = array('id' => $objectRef->membership_type_id);
                $values = array();
                $membership_type = CRM_Member_BAO_MembershipType::retrieve($params, $values);
                $duration = $membership_type->duration_interval;
                $duration_unit = $membership_type->duration_unit;
                $terms = 0;
                if( $objectRef->end_date )
                {
                    $term_end_date = new DateTime();
                    $terms_start_date = new DateTime($objectRef->start_date);
                    $previuod_endate_str = CRM_Core_Session::singleton()->get(PREVIUOS_MEMBERSHIP_END_DATE);
                    if( $previuod_endate_str ) {
                        $terms_start_date = new DateTime($previuod_endate_str);
                    }
                    $membership_end_date = new DateTime($objectRef->end_date);
                    //fix attr y in date diff function
                    $membership_end_date->add(new DateInterval('P'.DATE_DIFF_PATCHING.'D'));
                    $diff = date_diff($membership_end_date,  $terms_start_date);
                    if( $duration_unit == 'year') {
                        $terms = $diff->y / $duration;
                    }
                    else if( $duration_unit == 'month') {
                        $terms = $diff->m / $duration;
                    }
                    $term_end_date = $terms_start_date;
                    $perios_ids = array();
                    for ($i = 0; $i < $terms; $i++) {
                        $mysql_start_date = $term_end_date->format(MYSQL_DATE_FORMAT);
                        //calculate end date of current term
                        $interval_str = 'P'.$duration;
                        if( $duration_unit == 'year') {
                            $interval_str .= 'Y';
                        }
                        else if( $duration_unit == 'month') {
                            $interval_str .= 'M';
                        }
                        $term_end_date->add(new DateInterval($interval_str));
                        $mysql_end_date = $term_end_date->format(MYSQL_DATE_FORMAT);
                        //store period record
                        $now = date(DEFAULT_DATETIME_FORMAT);
                        $membership_period_obj = array(
                            'membership_id' => $objectRef->id,
                            'start_date' => $mysql_start_date,
                            'end_date' => $mysql_end_date,
                            'created_at' => CRM_Utils_Date::isoToMysql($now)
                        );
                        $membership_period = CRM_Memberperiod_BAO_MembershipPeriod::createOrUpdate($membership_period_obj);
                        array_push($perios_ids, $membership_period->id);
                    }
                }
                else {
                    //single term with no end_date
                    $membership_period_obj = array(
                        'membership_id' => $objectRef->id,
                        'start_date' => CRM_Utils_Date::isoToMysql($objectRef->start_date),
                        'end_date' => CRM_Utils_Date::isoToMysql($objectRef->end_date),
                        'created_at' => CRM_Utils_Date::isoToMysql($now)
                    );
                    $membership_period = CRM_Memberperiod_BAO_MembershipPeriod::createOrUpdate($membership_period_obj);
                    array_push($perios_ids, $membership_period->id);
                }
                if( count($perios_ids) > 0 ) {
                    $session->set(MEMBERSHIP_PERIOD_IDS_SESSION, implode(MEMBERSHIP_PERIOD_SESSION_DELIMITER, $perios_ids));
                }
            }
            break;
        case MEMBERSHIP_PAYMENT_OBJ_NAME:
            // not use paymnet due to if not member payment not create membersip will not link to contribution
            // and make membership period not math with membership data
            if( $op == OP_CREATE ) {
                $session = CRM_Core_Session::singleton();
                //if found period id then update with contribution id
                $membership_period_ids_str = $session->get(MEMBERSHIP_PERIOD_IDS_SESSION);
                if ($membership_period_ids_str) {
                    $membership_period_ids = explode(MEMBERSHIP_PERIOD_SESSION_DELIMITER, $membership_period_ids_str);
                    CRM_Memberperiod_BAO_MembershipPeriod::updateWithContribution($membership_period_ids, $objectRef->contribution_id);
                }
            }
            break;
    }
}

/**
 * Implements hook_memberperiod_civicrm_tabset().
 * Create membership period tab on contact view page.
 */
function memberperiod_civicrm_tabset($tabsetName, &$tabs, $context) {
    if ($tabsetName == 'civicrm/contact/view') {
        $contactId = $context['contact_id'];
        $url = CRM_Utils_System::url( 'civicrm/contact/membership/period', "reset=1&cid=$contactId" );
        $tabs[] = array( 'id' => 'memberperiodTab',
          'url'   => $url,
          'title' => 'Membership history',
          'weight' => 300,
        );
    }
}