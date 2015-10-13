<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 *The language package for the enrolment plugin.
 *
 * @package    enrol_ipay
 * @copyright  2015 Ipay Ltd, Kiarie Mburu
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
$string['assignrole'] = 'Assign Role';
$string['pluginname'] = 'Ipay';
$string['plugin_desc'] = 'Configuration of the Ipay Plugin';
$string['businessname'] = 'Ipay business name';
$string['businessname_desc'] = 'Ipay business/Merchant name assigned by Ipay NOTE: It is used to verify details';
$string['cost'] = 'Cost of Enrolling the course';
$string['currency'] = 'Currency';
$string['enrolperiod'] = 'Duration of Enrollment';
$string['enrolperiod_desc'] = 'Default length of time that the enrolment is valid. If set to zero, the enrolment duration will be unlimited by default.';
$string['enrolperiod_help'] = 'Length of time that the enrolment is valid, starting with the moment the user is enrolled. If disabled, the enrolment duration will be unlimited.';
$string['enrolstartdate'] = 'Start date';
$string['enrolstartdate_help'] = 'If enabled, users can be enrolled from this date onward only.';
$string['enrolenddate'] = 'End Date';
$string['enrolenddate_help'] = 'End Date of Enrolment';
$string['expiredaction'] = 'Enrolment expiration action';
$string['expiredaction_help'] = 'Select action to carry out when user enrolment expires. Please note that some user data and settings are purged from course during course unenrolment.';
$string['defaultrole'] = 'Default role assignment';
$string['defaultrole_desc'] = 'Select role which should be assigned to users during Ipay enrolments';
$string['enrolperiod'] = 'Enrolment duration';
$string['enrolperiod_desc'] = 'Default length of time that the enrolment is valid. If set to zero, the enrolment duration will be unlimited by default.';
$string['ipayaccepted'] = 'Ipay payments accepted';
$string['ipay:config'] = 'Configure Ipay enrol instances';
$string['ipay:manage'] = 'Manage enrolled users';
$string['ipay:unenrol'] = 'Unenrol users from course';
$string['ipay:unenrolself'] = 'Unenrol self from the course';
$string['status'] = 'allow Ipay enrolments';
$string['sendpaymentbutton'] = 'Send payment via IPay';



?>