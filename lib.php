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
 *Ipay enrolment plugin
 *main libray with the funtions.
 *
 *this plugin allows you to set up paid courses
 *
 * @package    enrol_ipay
 * @copyright  2015 Ipay Ltd, Kiarie Mburu
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();
/*Ipay enrolment plugin implementation*/
/**
* 
*/
class enrol_ipay_plugin extends enrol_plugin
{
	public function get_currencies() {
        $codes = array('KES','EUR', 'GBP', 'USD');
        $currencies = array();
        foreach ($codes as $c) {
            $currencies[$c] = new lang_string($c, 'core_currencies');
        }

        return $currencies;
    }
	
	function get_info_icons(array $instances)
	{
		$found = false;
		foreach ($instances as $instance) {
			if($instance->enrolstartdate != 0 && $instance->enrolstartdate > time()){
				continue;
			}
			if($instance->enrolenddate != 0 && $instace->enrolenddate < time()){
				continue;
			}
			$found =true;
			break;
		}
		if($found){
			return array(new pix_icon('icon', get_string('pluginname','enrol_ipay'),'enrol_ipay'));
		}
		return array();
	}
	public function roles_protected()
	{// users with roles assign cap(permission) may tweak the roles later
		return false;
	}
	public function allow_unenrol(stdClass $instance)
	{//users with unenroll cap may unenerol users manually - requires enrol/ipay:unenrole
		return true;
	}
	public function allow_manage(stdClass $instance)
	{//users with manage cap can tweak period and status
		return true;
	}
	public function show_enrolme_link(stdClass $instance)
	{
		return ($instance->status == ENROL_INSTANCE_ENABLED);
	}/**
     * Adds navigation links into course admin block.
     *
     * By defaults looks for manage links only.
     *
     * @param navigation_node $instancesnode
     * @param stdClass $instance
     * @return void
     */
	public function add_course_navigation($instancesnode, stdClass $instance){
		if($instance->enrol !== 'ipay'){
			throw new coding_exception('invalid enrol instane');
		}
		$context = context_course::instance($instance->courseid);
		if(has_capability('enrol/ipay:config', $context)){
			$managelink = new moodle_url('/enrol/ipay/edit.php', array('courseid' =>$instance->courseid, 'id'=>$instance->id));
			$instancesnode->add($this->get_instance_name($instance),$managelink, navigation_node::TYPE_SETTING);

		}
	}
	/**
     * Returns edit icons for the page with list of instances
     * @param stdClass $instance
     * @return array
     */
	public function get_action_icons(stdClass $instance){
        global $OUTPUT;
		if($instance->enrol !== 'ipay'){
			throw new coding_exception('invalid enrol instance');
		}
	
		$context = context_course::instance($instance->courseid);
		
		$icons = array();

		if(has_capability('enrol/ipay:config', $context)){
			$editlink = new moodle_url('/enrol/ipay/edit.php', array('courseid' => $instance->courseid, 'id'=> $instance->id ));
			$icons[] = $OUTPUT->action_icon($editlink, new pix_icon('t/edit', get_string('edit'), 'core',
                array('class' => 'iconsmall')));

		}
		return $icons;
	}
	
    /**
     * Returns link to page which may be used to add new instance of enrolment plugin in course.
     * @param int $courseid
     * @return moodle_url page url
     */
    public function get_newinstance_link($courseid){
    	$context = context_course::instance($courseid, MUST_EXIST);

    	if(!has_capability('moodle/course:enrolconfig', $context) or !has_capability('enrol/ipay:config', $context)){
    		return null;
    	}
    	return new moodle_url('/enrol/ipay/edit.php', array('courseid' => $courseid));

    }

/**
     * Creates course enrol form, checks if form submitted
     * and enrols user if necessary. It can also redirect.
     * It basically controls what the user sees it generates the enrollform e.t.c
     *
     *
     * @param stdClass $instance
     * @return string html text, usually a form in a text box
     */
	function enrol_page_hook(stdClass $instance){
		global $CFG, $USER, $OUTPUT, $PAGE, $DB;

		ob_start();
		if($DB->record_exists('user_enrolments',array('userid' => $USER->id , 'enrolid'=>$instance->id ))){
			return ob_get_clean();
		}
		if($instance->enrolstartdate != 0 && $instance->enrolstartdate > time()){
			return ob_get_clean();
		}
		if($instance->enrolenddate != 0 && $instance->enrolenddate < time()){
			return ob_get_clean();
		}


        $course = $DB->get_record('course', array('id'=>$instance->courseid));
        $context = context_course::instance($course->id);
 
        $shortname = format_string($course->shortname, true, array('context' => $context));
        $strloginto = get_string("loginto", "", $shortname);
        $strcourses = get_string("courses");
  // Pass $view=true to filter hidden caps if the user cannot see them.
      	if($users = get_users_by_capability($context, 'moodle/course:update', 'u.*','u.id ASC',
					'','','','',false,true)){
			$users = sort_by_roleassignment_authority($users,$context);
			$teacher = array_shift($users); //returns the first element in the array shifting the
			//rest one position lower
		}else{
			$teacher = false;
		}

		if((float) $instance->cost <= 0){
			$cost = (float) $this->get_config('cost');//get_config fetches from the original configuration settings
		} else {
			$cost = (float) $instance->cost;
		}
		if(abs($cost) < 0.01){
		 echo '<p>'.get_string('nocost', 'ipay').'</p>';
        } else {

		//Calculate localised cost and "." cost, make sure we send Ipay the same value
		//please note ipay expects ammount with 2 decimal places and "." seperator.
		$localisedcost = format_float($cost,2,true);

		if(isguestuser()){
			if (empty($CFG->loginhttps)){
				$wwwroot = $CFG->wwwroot;
			}else{
				//This is not so secure ;-) cause we're
				//in unencrypted connection
				$wwwroot = str_replace("http://", "https://", $CFG->wwwroot);
			}
			echo '<div class="mdl_align"><p>'.get_string('paymentrequired'). '</p>';
			echo '<p><b>'.get_string('cost').": $instance->currency $localisedcost".'</b></p>';
			echo '<p><a href="'.$wwwroot.'/login/">'.get_string('loginsite').'</a></p>';
			echo "</div>";
		} else {
			$coursefullname = format_string($course->fullname, true, array('context'=>$context));
			$courseshortname = $shortname;
			$userfullname = fullname($USER);
			$userfirstname = $USER->firstname;
			$userlastname = $USER->lastname;
			$useremail = $USER->email;
			$userphonenumber = $USER->phone2;
			$instancename= $this->get_instance_name($instance);
            $businessname = $this->get_config('businessname'); //get businessname from configuration

			include($CFG->dirroot.'/enrol/ipay/enrol.html');
			}
		}
		return $OUTPUT->box(ob_get_clean());
	}
	


	public function restore_instance(restore_enrolments_structure_step $step, stdClass $data, $course, $oldid) {
        global $DB;
        if ($step->get_task()->get_target() == backup::TARGET_NEW_COURSE) {
            $merge = false;
        } else {
            $merge = array(
                'courseid' => $data->courseid,
                'enrol' => $this->get_name(),
                'roleid' => $data->roleid,
                'cost' => $data->cost,
                'currency' => $data->currency,
            );
        }
        if ($merge and $instances = $DB->get_records('enrol', $merge, 'id')) {
            $instance = reset($instances);
            $instanceid = $instance->id;
        } else {
            $instanceid = $this->add_instance($course, (array)$data);
        }
        $step->set_mapping('enrol', $oldid, $instanceid);
    }
  /**
     * Restore user enrolment.
     *
     * @param restore_enrolments_structure_step $step
     * @param stdClass $data
     * @param stdClass $instance
     * @param int $oldinstancestatus
     * @param int $userid
     */
    public function restore_user_enrolment(restore_enrolments_structure_step $step, $data, $instance, $userid, $oldinstancestatus) {
        $this->enrol_user($instance, $userid, null, $data->timestart, $data->timeend, $data->status);
    }

    /**
     * Gets an array of the user enrolment actions
     *
     * @param course_enrolment_manager $manager
     * @param stdClass $ue A user enrolment object
     * @return array An array of user_enrolment_actions
     */
    public function get_user_enrolment_actions(course_enrolment_manager $manager, $ue) {
        $actions = array();
        $context = $manager->get_context();
        $instance = $ue->enrolmentinstance;
        $params = $manager->get_moodlepage()->url->params();
        $params['ue'] = $ue->id;
        if ($this->allow_unenrol($instance) && has_capability("enrol/ipay:unenrol", $context)) {
            $url = new moodle_url('/enrol/unenroluser.php', $params);
            $actions[] = new user_enrolment_action(new pix_icon('t/delete', ''), get_string('unenrol', 'enrol'), $url, array('class'=>'unenrollink', 'rel'=>$ue->id));
        }
        if ($this->allow_manage($instance) && has_capability("enrol/ipay:manage", $context)) {
            $url = new moodle_url('/enrol/editenrolment.php', $params);
            $actions[] = new user_enrolment_action(new pix_icon('t/edit', ''), get_string('edit'), $url, array('class'=>'editenrollink', 'rel'=>$ue->id));
        }
        return $actions;
    }

    public function cron() {
        $trace = new text_progress_trace();
        $this->process_expirations($trace);
    }

    /**
     * Execute synchronisation.
     * @param progress_trace $trace
     * @return int exit code, 0 means ok
     */
    public function sync(progress_trace $trace) {
        $this->process_expirations($trace);
        return 0;
    }

    /**
     * Is it possible to delete enrol instance via standard UI?
     *
     * @param stdClass $instance
     * @return bool
     */
    public function can_delete_instance($instance) {
        $context = context_course::instance($instance->courseid);
        return has_capability('enrol/ipay:config', $context);
    }

    /**
     * Is it possible to hide/show enrol instance via standard UI?
     *
     * @param stdClass $instance
     * @return bool
     */
    public function can_hide_show_instance($instance) {
        $context = context_course::instance($instance->courseid);
        return has_capability('enrol/ipay:config', $context);
    }
	

}