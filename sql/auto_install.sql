DROP TABLE IF EXISTS `civicrm_membership_period`;
-- /*******************************************************
-- *
-- * civicrm_membership_period
-- *
-- * Entity storing membership period record 
-- *
-- *******************************************************/
 CREATE TABLE `civicrm_membership_period` (
      `id` int unsigned NOT NULL AUTO_INCREMENT  COMMENT 'Unique MembershipPeriod ID',
      `start_date` date    COMMENT 'Start date of membership period',
      `end_date` date    COMMENT 'End date of membership period',
      `membership_id` int unsigned    COMMENT 'FK to Membership',
      `created_at` datetime    COMMENT 'End date of membership period',

      PRIMARY KEY (`id`)
 ,    CONSTRAINT FK_civicrm_membership_period_membership_id FOREIGN KEY (`membership_id`) REFERENCES `civicr     m_membership`(`id`) ON DELETE CASCADE
 )  ENGINE=InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci  ;
