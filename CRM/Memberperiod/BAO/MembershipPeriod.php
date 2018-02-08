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
     * Update membership period with contribution is
     *
     * @param array $params key-value pairs
     * @return bool
    */

    public static function updateWithContribution($membership_period_ids, $contribution_id) {
        $className = MEMBERSHIP_PERIOD_CLASS_NAME;
        foreach ($membership_period_ids as &$period_id) {
            $member_period_dao = new $className();
            $member_period_dao->id = $period_id;
            if ($member_period_dao->find(TRUE)) {
                $member_period_dao->contribution_id = $contribution_id;
                $member_period_dao->save();
                $member_period_dao->free();
            }
        }
    }

    /**
     * GET MembershipPeriod by membet ship id
     *
     * @param array $params key-value pairs
     * @return bool
    */

    public static function getByMembershipId($membership_id, $is_include_contribution) {
        $member_period_array = Array();
        $className = MEMBERSHIP_PERIOD_CLASS_NAME;
        $member_period_dao = new $className();
        $member_period_dao->membership_id = $membership_id;
        $member_period_dao->find();
        if( $member_period_dao )
        {
            while ($member_period_dao->fetch()) {
                $membership_as_array = (Array)$member_period_dao;
                if( $is_include_contribution ) {
                    $params = array('id' => $member_period_dao->contribution_id);
                    $values = array();
                    $ids = array();
                    $contribution = CRM_Contribute_BAO_Contribution::getValues($params, $values, $ids);
                    if( $contribution ) {
                        $membership_as_array['contribution'] = (Array)$contribution;
                    }
                }
                array_push($member_period_array, $membership_as_array);
            }
            return $member_period_array;
        }
        return NULL;
    }
}
