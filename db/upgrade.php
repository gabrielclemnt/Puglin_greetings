<?php
// 2025011000
/**
 * Defina as etapas de atualização a serem executadas para atualizar o plugin da versão antiga para a atual.
 *
 * @param int $oldversion Número da versão da qual o plugin está sendo atualizado.
 */
function xmldb_local_greetings_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2025011000) {
        // Defina o campo userid a ser adicionado a local_greetings_messages.
        $table = new xmldb_table('local_greetings_messages');
        $field = new xmldb_field('userid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '1', 'timecreated');

        // Inicie condicionalmente o add field userid.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Defina a chave greetings-user-foreign-key (estrangeira) a ser adicionada a local_greetings_messages.
        $key = new xmldb_key('greetings-user-foreign-key', XMLDB_KEY_FOREIGN, ['userid'], 'user', ['id']);

        // Inicie a adição da chave greetings-user-foreign-key.
        $dbman->add_key($table, $key);

        // Ponto de salvamento de saudações atingido.
        upgrade_plugin_savepoint(true, 2025011000, 'local', 'greetings');
    }

    return true;
}
