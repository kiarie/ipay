<?php
function xmldb_qtype_myqtype_upgrade($oldversion) {
    global $DB;
    $dbman = $DB->get_manager();

    /// Add a new column newcol to the mdl_myqtype_options
    if ($oldversion < 2015031200) {
        // Code.
     if ($oldversion < XXXXXXXXXX) {

        // Define field inv to be added to enrol_ipay.
        $table = new xmldb_table('enrol_ipay');
        $field = new xmldb_field('inv', XMLDB_TYPE_INTEGER, '15', null, null, null, null, 'id');

        // Conditionally launch add field inv.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Ipay savepoint reached.
        upgrade_plugin_savepoint(true, XXXXXXXXXX, 'enrol', 'ipay');
    }
    }

    return true;
}
?>