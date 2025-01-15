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

 defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) { // Verifica se o usuário tem permissão para configurar o site.
    $settings = new admin_settingpage('local_greetings', get_string('pluginname', 'local_greetings')); // Instancia um objeto da classe admin_settingpage.
    $ADMIN->add('localplugins', $settings);  // Adiciona a página de configurações ao menu de administração.

    if ($ADMIN->fulltree) { // Verifica se o menu de administração está totalmente expandido.
        require_once($CFG->dirroot . '/local/greetings/lib.php'); // Inclui o arquivo lib.php.

        $settings->add(new admin_setting_configtext( // Adiciona um campo de texto à página de configurações.
            'local_greetings/messagecardbgcolor',
            get_string('messagecardbgcolor', 'local_greetings'),
            get_string('messagecardbgcolordesc', 'local_greetings'),
            '#FFFFFF',
        ));
    }
}
