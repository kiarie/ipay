<?php
/* Enrollment plugin for Ipay capabilities page
*/
defined('MOODLE_INTERNAL') || die();

$capabilities = array(
					//capabilties here specify the level of control a given user[role] has,
    'enrol/ipay:config' => array(				
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,	//context is heirachical context course is for the courses, CONTEXT_SYSTEM for overall system
        'archetypes' => array(				//archetypes basically the roles manager, student...etc
            'manager' => CAP_ALLOW,
        )
    ),
    'enrol/ipay:manage' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
        'archetypes' => array(
            'manager' => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
        )
    ),
    'enrol/ipay:unenrol' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
        'archetypes' => array(
            'manager' => CAP_ALLOW,
        )
    ),
    'enrol/ipay:unenrolself' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
        'archetypes' => array(
        )
    ),
);

