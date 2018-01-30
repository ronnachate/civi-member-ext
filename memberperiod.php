<?php

require_once 'memberperiod.civix.php';
use CRM_Memberperiod_ExtensionUtil as E;

define("MEMBERSHIP_OBJ_NAME", "Membership", true);
define("MEMBERSHIP__PAYMENT_OBJ_NAME", "MembershipPayment", true);

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

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_preProcess
 *
function memberperiod_civicrm_preProcess($formName, &$form) {

} // */

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_navigationMenu
 *
 * */
function memberperiod_civicrm_pre($op, $objectName, $id, &$params) {
    error_log("pre hook work-----------------------------------------");
    error_log($objectName);
    error_log(json_encode($params));

    if( $objectName == MEMBERSHIP_OBJ_NAME) {
        //clear membeperiod session if exist
    }
}

function memberperiod_civicrm_post($op, $objectName, $objectId, &$objectRef) {
    error_log("post hook work-----------------------------------------");
    error_log($objectName);
    error_log(json_encode($objectRef));
    if( $objectName == MEMBERSHIP_OBJ_NAME) {
        //create member period
        // do create
        // store period id
    }
    switch ($objectName) {
        case MEMBERSHIP_OBJ_NAME:
            //create member period
            // do create
            // store period id
            break;
        case MEMBERSHIP__PAYMENT_OBJ_NAME:
            //if found period id then update with contribution id
            break;
    }
}
