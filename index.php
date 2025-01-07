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

echo $OUTPUT->footer(); // Imprime o rodapé da página.
