<?php

class block_myspecialcourse_edit_form extends block_edit_form {
	protected function specific_definition($mform) {
			$titleOptions = ["myresources" => "My Resources", "coolstuff" => "Cool Stuff"];

			$mform->addElement('header', 'config_header', get_string('blocksettings', 'block'));

			$mform->addElement('text', 'config_courseid', get_string('courseid', 'block_myspecialcourse'));
			$mform->setDefault('config_courseid', '1');
			$mform->setType('config_courseid', PARAM_INT);

			$mform->addElement('select', 'config_title', get_string('title', 'block_myspecialcourse'), $titleOptions);
			$mform->setDefault('config_title', 'myresources');
			$mform->setType('config_courseid', PARAM_TEXT);
	}
}