<?php
use CRM_Memberperiod_ExtensionUtil as E;

class CRM_Memberperiod_Page_MemberPeriod extends CRM_Core_Page {

    public function run() {
        $contact_id = CRM_Utils_Request::retrieve('cid', 'Positive',
            $this, FALSE, 0
        );
        //get membership with contact id
        $params = array('contact_id' => $contact_id);
        $values = array();
        $memberships = CRM_Member_BAO_Membership::getValues($params, $values);
        if( $memberships ) {
            // civiCrm warning Duplicated on create multiple membership, so assume we have only 1 membership per contact
            $membership = $this->getFirstMembership($memberships);
            //get membership period
            $membership_periods = CRM_Memberperiod_BAO_MembershipPeriod::getByMembershipId($membership->id);
            if( $membership_periods ) {
                $this->assign( 'membershipPeriods', $membership_periods);
            }
        }
        parent::run();
    }
    function getFirstMembership( $memberships) {
        $firstMembership = new CRM_Member_BAO_Membership();
        foreach($memberships  as $prop) {
            $firstMembership = $prop;
            break;
        }
        return $firstMembership;
    }
}
