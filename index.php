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

$messageform = new \local_greetings\form\message_form(); // Instancia um objeto da classe message_form.

// salvar a mensagem no banco de dados
if ($data = $messageform->get_data()) {
    $message = required_param('message', PARAM_TEXT);

    if (!empty($message)) {
        $record = new stdClass;
        $record->message = $message;
        $record->timecreated = time();

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

$messageform->display(); // Exibe o formulário.
$messages = $DB->get_records('local_greetings_messages');
echo $OUTPUT->box_start('card-columns');

foreach ($messages as $m) {
    echo html_writer::start_tag('div', ['class' => 'card']);
    echo html_writer::start_tag('div', ['class' => 'card-body']);
    echo html_writer::tag('p', $m->message, ['class' => 'card-text']);
    echo html_writer::start_tag('p', ['class' => 'card-text']);
    echo html_writer::tag('small', userdate($m->timecreated), ['class' => 'text-muted']);
    echo html_writer::end_tag('p');
    echo html_writer::end_tag('div');
    echo html_writer::end_tag('div');
}

echo $OUTPUT->box_end();


/*if ($data = $messageform->get_data()) {

    $message = required_param('message', PARAM_TEXT);

    echo $OUTPUT->heading($message, 4);
}
*/
echo $OUTPUT->footer(); // Imprime o rodapé da página.
