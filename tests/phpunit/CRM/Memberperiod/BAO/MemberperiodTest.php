<?php

use CRM_Memberperiod_ExtensionUtil as E;
use Civi\Test\HeadlessInterface;
use Civi\Test\HookInterface;
use Civi\Test\TransactionalInterface;

/**
 * CRM_Memberperiod_BAO_MemberperiodTest - test for Memberperiod BAO
 *
 * Tips:
 *  - With HookInterface, you may implement CiviCRM hooks directly in the test class.
 *    Simply create corresponding functions (e.g. "hook_civicrm_post(...)" or similar).
 *  - With TransactionalInterface, any data changes made by setUp() or test****() functions will
 *    rollback automatically -- as long as you don't manipulate schema or truncate tables.
 *    If this test needs to manipulate schema or truncate tables, then either:
 *       a. Do all that using setupHeadless() and Civi\Test.
 *       b. Disable TransactionalInterface, and handle all setup/teardown yourself.
 *
 * @group headless
 */
class CRM_Memberperiod_BAO_MemberperiodTest extends CiviUnitTestCase implements HeadlessInterface {


    /**
     * To do this need hack code in 'Civi\Test.php' to not thrown error on test environment
     */
    public function setUpHeadless() {
        // Civi\Test has many helpers, like install(), uninstall(), sql(), and sqlFile().
        // See: https://github.com/civicrm/org.civicrm.testapalooza/blob/master/civi-test.md
        return \Civi\Test::e2e()
        ->sqlFile(__DIR__ . '/../../../../../sql/auto_install.sql')
        ->installMe(__DIR__. '/../../../..')
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
        $params = array(
            'membership_id' => $this->_membershipId ,
            'start_date' => date('Ymd', strtotime('2018-02-08')),
            'end_date' => date('Ymd', strtotime('2018-02-08')),
            'created_at' => date('Ymd', strtotime('2018-02-08'))
        );
        CRM_Memberperiod_BAO_MembershipPeriod::createOrUpdate($params);
        $membership_period_id = $this->assertDBNotNull('CRM_Memberperiod_BAO_MembershipPeriod', $this->_membershipId, 'id',
            'membership_id', 'Database check for created membership.'
        );
    }

    /**
    * Test update member ship period
    */
    public function testupdateWithContribution() {
        $params = array(
            'membership_id' => $this->_membershipId ,
            'start_date' => date('Ymd', strtotime('2018-02-08')),
            'end_date' => date('Ymd', strtotime('2018-02-08')),
            'created_at' => date('Ymd', strtotime('2018-02-08'))
        );
        CRM_Memberperiod_BAO_MembershipPeriod::createOrUpdate($params);
        $membership_period_id = $this->assertDBNotNull('CRM_Memberperiod_BAO_MembershipPeriod', $this->_membershipId, 'id',
            'membership_id', 'Database check for created membership period'
        );
        $params = array(
            'contact_id' => $this->_contactId,
            'trxn_id' => 67890,
            'invoice_id' => 1234567,
        );
        $contributionId = $this->contributionCreate($params);
        $membership_period_ids = array($membership_period_id);
        CRM_Memberperiod_BAO_MembershipPeriod::updateWithContribution($membership_period_ids, $contributionId);
        $membership_period_id = $this->assertDBNotNull('CRM_Memberperiod_BAO_MembershipPeriod', $contributionId, 'id',
            'contribution_id', 'Database check for update membeship period with contribution_id'
        );
    }

    /**
    * Test update member ship period
    */
    public function testGetByMembershipId() {
        $params = array(
            'membership_id' => $this->_membershipId ,
            'start_date' => date('Ymd', strtotime('2018-02-08')),
            'end_date' => date('Ymd', strtotime('2018-02-08')),
            'created_at' => date('Ymd', strtotime('2018-02-08'))
        );
        CRM_Memberperiod_BAO_MembershipPeriod::createOrUpdate($params);
        $membership_period_id = $this->assertDBNotNull('CRM_Memberperiod_BAO_MembershipPeriod', $this->_membershipId, 'id',
            'membership_id', 'Database check for created membership period'
        );
        $result = CRM_Memberperiod_BAO_MembershipPeriod::getByMembershipId($this->_membershipId, false);
        $this->assertNotNull($result);
    }
}


