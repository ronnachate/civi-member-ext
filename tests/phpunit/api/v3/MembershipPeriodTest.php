<?php

use CRM_Memberperiod_ExtensionUtil as E;
use Civi\Test\HeadlessInterface;
use Civi\Test\HookInterface;
use Civi\Test\TransactionalInterface;

/**
 * api_v3_MemberperiodTest - test for Memberperiod ฟยร อ3
 *
 * @group headless
 */
class api_v3_MemberperiodTest extends CiviUnitTestCase implements HeadlessInterface {

    private $_contactId;
    private $_membershipId;

    /**
     * To do this need hack code in 'Civi\Test.php' to not thrown error on test environment
     */
    public function setUpHeadless() {
        // Civi\Test has many helpers, like install(), uninstall(), sql(), and sqlFile().
        // See: https://github.com/civicrm/org.civicrm.testapalooza/blob/master/civi-test.md
        return \Civi\Test::e2e()
        ->sqlFile(__DIR__ . '/../../../../sql/auto_install.sql')
        ->installMe(__DIR__. '/../../..')
        ->apply();
    }

    public function setUp() {
        parent::setUp();
        //set up test contact
        $this->_contactId = $this->organizationCreate();
        $this->_membershipId = $this->contactMembershipCreate(array('contact_id' => $this->_contactId));
    }

    public function tearDown() {
        parent::tearDown();
        $this->contactDelete($this->_contactID);
        $this->_memberId = NULL;
    }

    /**
    * Test create member ship period
    */
    public function testCreate() {
        $values = array(
            'membership_id' => $this->_membershipId ,
            'start_date' => date('Ymd', strtotime('2018-02-08')),
            'end_date' => date('Ymd', strtotime('2018-02-08')),
            'created_at' => date('Ymd', strtotime('2018-02-08'))
        );
        $membership_period = $this->callAPISuccess('MembershipPeriod', 'create', $values);
        error_log( json_encode( $membership_period));
        $this->assertDBRowExist('MembershipPeriod', $membership_period['id']);
    }

    /**
    * Test create member ship period
    */
    public function testDelete() {
        $values = array(
            'membership_id' => $this->_membershipId ,
            'start_date' => date('Ymd', strtotime('2018-02-08')),
            'end_date' => date('Ymd', strtotime('2018-02-08')),
            'created_at' => date('Ymd', strtotime('2018-02-08'))
        );
        $membership_period = $this->callAPISuccess('MembershipPeriod', 'create', $values);
        $this->assertDBRowExist('MembershipPeriod', $membership_period['id']);
        $params = array(
            'id' => $membership_period['id']
        );
        $membership_period = $this->callAPISuccess('MembershipPeriod', 'create', $params);
        $this->assertDBRowNotExist('MembershipPeriod', $membership_period['id']);
    }
}


