<?php
function xmldb_enrol_ipay_upgrade($oldversion) {
    global $DB;
    $dbman = $DB->get_manager();

    /// Add a new column newcol to the mdl_myqtype_options
    if ($oldversion < 2015100500) {
        // Code.
     if ($oldversion < 2015100500) {

        // Define field inv to be added to enrol_ipay.
        $table = new xmldb_table('enrol_ipay');
        $field = new xmldb_field('inv', XMLDB_TYPE_INTEGER, '15', null, null, null, null, 'id');

        // Conditionally launch add field inv.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Ipay savepoint reached.
        upgrade_plugin_savepoint(true, 2015100500, 'enrol', 'ipay');
    }
    }

    return true;
}
