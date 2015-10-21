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
 * this is the settings page of the plugin with the various settings that
 *the admin will see while configuring the enrolment plugin.
 *
 * @package    enrol_ipay
 * @copyright  2015 Ipay Ltd, Kiarie Mburu
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if($ADMIN->fulltree)
{
$settings->add(new admin_setting_heading('enrol_ipay_settings','',get_string('plugin_desc', 'enrol_ipay') ));//Adds the description at the top of the page
//get merchant name to be used by ipay gateway
$settings->add(new admin_setting_configtext('enrol_ipay/businessname',get_string('businessname','enrol_ipay'), get_string('businessname_desc', 'enrol_ipay'), '',PARAM_TEXT));
$settings->add(new admin_setting_configtext('enrol_ipay/vendorid', get_string('vendorid', 'enrol_ipay'), get_string('vendorid_desc','enrol_ipay'), '', PARAM_TEXT));
$options = array(
        ENROL_EXT_REMOVED_KEEP           => get_string('extremovedkeep', 'enrol'),
        ENROL_EXT_REMOVED_SUSPENDNOROLES => get_string('extremovedsuspendnoroles', 'enrol'),
        ENROL_EXT_REMOVED_UNENROL        => get_string('extremovedunenrol', 'enrol'),
				 );
//admin_settings take [args] as follows $name, $visiblename, $desc, $defaultsetting
$settings->add(new admin_setting_configselect('enrol_ipay/expiredaction',
	get_string('expiredaction', 'enrol_ipay'),
	get_string('expiredaction_help','enrol_ipay'), ENROL_EXT_REMOVED_SUSPENDNOROLES, $options));


//the role is set here for the user who can enroll with this instance
if(!during_initial_install()){
	$options = get_default_enrol_roles(context_system::instance());
	$student = get_archetype_roles('student');
	$student = reset($student);
	$settings->add(new admin_setting_configselect('enrol_ipay/roleid',//creates a dropdown menu populated by the $options
		get_string('defaultrole','enrol_ipay'), get_string('defaultrole_desc','enrol_ipay'), $student->id,$options));
	}
	// duration for enrollment the default one below
$settings->add(new admin_setting_configduration('enrol_ipay/enrolperiod', 
	get_string('enrolperiod', 'enrol_ipay'), get_string('enrolperiod_desc', 'enrol_ipay'),0));

}
