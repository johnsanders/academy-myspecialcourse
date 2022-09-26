<?php

class block_myspecialcourse_edit_form extends block_edit_form
{
    protected function specific_definition($mform)
    {
        $titleOptions = [
            "myresources" => get_string("myresources", "block_myspecialcourse"),
            "recordedseminars" => get_string("recordedseminars", "block_myspecialcourse"),
            "coolstuff" => get_string("coolstuff", "block_myspecialcourse"),
        ];

        $mform->addElement("header", "config_header", get_string("blocksettings", "block"));

        $mform->addElement("text", "config_courseid", get_string("courseid", "block_myspecialcourse"));
        $mform->setDefault("config_courseid", "1");
        $mform->setType("config_courseid", PARAM_INT);

        $mform->addElement("select", "config_title", get_string("title", "block_myspecialcourse"), $titleOptions);
        $mform->setDefault("config_title", "myresources");
        $mform->setType("config_courseid", PARAM_TEXT);

        $mform->addElement("text", "config_maxitems", get_string("maxitems", "block_myspecialcourse"));
        $mform->setDefault("config_maxitems", "7");
        $mform->setType("config_maxitems", PARAM_INT);

        $mform->addElement("text", "config_maxnamelength", get_string("maxnamelength", "block_myspecialcourse"));
        $mform->setDefault("config_maxnamelength", "50");
        $mform->setType("config_maxnamelength", PARAM_INT);
    }
}
