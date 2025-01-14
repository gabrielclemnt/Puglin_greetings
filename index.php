<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle. If not, see <https://www.gnu.org/licenses/>.

/**
 * @package     local_greetings
 * @copyright   2025 Antonio Gabriel <antonio.clemente@ufpe.br>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php'); // Inclui o arquivo de configuração do Moodle.
require_once($CFG->dirroot. '/local/greetings/lib.php'); // Inclui o arquivo lib.php.

$context = context_system::instance();// Obtém o contexto do sistema.
$PAGE->set_context($context);// Define o contexto da página.
$PAGE->set_url('/local/greetings/index.php'); // Define a URL da página.
$PAGE->set_pagelayout('standard');// Define o layout da página.
$PAGE->set_title(get_string('pluginname' , 'local_greetings'));
$PAGE->set_heading(get_string('pluginname' , 'local_greetings'));
require_login(); // Verifica se o usuário está logado.
if (isguestuser()) { // Verifica se o usuário é um convidado.
    throw new moodle_exception('noguest');
}

$messageform = new \local_greetings\form\message_form(); // Instancia um objeto da classe message_form.
$allowpost = has_capability('local/greetings:postmessages', $context);
$deleteanypost = has_capability('local/greetings:deleteanymessage', $context);
$action = optional_param('action', '', PARAM_TEXT);

if ($action == 'del') {
    require_sesskey(); // Verifica se a sessão é válida.
    $id = required_param('id', PARAM_TEXT);

    if ($deleteanypost) {
        $params = ['id' => $id];

        $DB->delete_records('local_greetings_messages', $params);
        redirect($PAGE->url); // Redireciona para a página atual.
    }
}

// salvar a mensagem no banco de dados
if ($data = $messageform->get_data()) {
    require_capability('local/greetings:postmessages', $context);
    $message = required_param('message', PARAM_TEXT);

    if (!empty($message)) {
        $record = new stdClass;
        $record->message = $message;
        $record->timecreated = time();
        $record->userid = $USER->id;
        $DB->insert_record('local_greetings_messages', $record);
    }
}

echo $OUTPUT->header(); // Imprime o cabeçalho da página.

// echo '<h3>Saudações, meu Rei</h3>'; // Imprime uma mensagem de saudação.
if (isloggedin()) {
    // echo '<h3>Boas vindas meu rei, ' . fullname($USER) . '</h3>'; // Imprime uma mensagem de saudação personalizada.
    // echo get_string('greetingloggedinuser', 'local_greetings', fullname($USER));
    echo local_greetings_get_greeting($USER);
} else {
    // echo '<h3>Boas vidas jovem! user</h3>';
    echo get_string('greetinguser', 'local_greetings');
}

// $messageform->display(); // Exibe o formulário.
if ($allowpost) {
    $messageform->display();
}
// $messages = $DB->get_records('local_greetings_messages');
$userfields = \core_user\fields::for_name()->with_identity($context);
$userfieldssql = $userfields->get_sql('u');

$sql = "SELECT m.id, m.message, m.timecreated, m.userid {$userfieldssql->selects}
          FROM {local_greetings_messages} m
     LEFT JOIN {user} u ON u.id = m.userid
      ORDER BY timecreated DESC";

$messages = $DB->get_records_sql($sql);

echo $OUTPUT->box_start('card-columns');

foreach ($messages as $m) { // Itera sobre as mensagens.
    echo html_writer::start_tag('div', ['class' => 'card']);
    echo html_writer::start_tag('div', ['class' => 'card-body']);
    // echo html_writer::tag('p', $m->message, ['class' => 'card-text']);
    echo html_writer::tag('p', format_text($m->message, FORMAT_PLAIN), ['class' => 'card-text']);
    echo html_writer::tag('p', get_string('postedby', 'local_greetings', $m->firstname), ['class' => 'card-text']);
    echo html_writer::start_tag('p', ['class' => 'card-text']);
    echo html_writer::tag('small', userdate($m->timecreated), ['class' => 'text-muted']);
    echo html_writer::end_tag('p');
    if ($deleteanypost) { // link da URL de exclusão da mensagem
        echo html_writer::start_tag('p', ['class' => 'card-footer text-center']);
        echo html_writer::link(
            new moodle_url(
                '/local/greetings/index.php',
                ['action' => 'del', 'id' => $m->id, 'sesskey' => sesskey()] // implementar sesskey
            ),
            $OUTPUT->pix_icon('t/delete', '') . get_string('delete')
        );
        echo html_writer::end_tag('p');
    }
    echo html_writer::end_tag('div');
    echo html_writer::end_tag('div');
}

echo $OUTPUT->box_end();


/*if ($data = $messageform->get_data()) { // Verifica se o formulário foi submetido.

    $message = required_param('message', PARAM_TEXT); // Obtém o valor do campo message.

    echo $OUTPUT->heading($message, 4); // Imprime o valor do campo message.
}
*/
echo $OUTPUT->footer(); // Imprime o rodapé da página.
