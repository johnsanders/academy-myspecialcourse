<?php

defined('MOODLE_INTERNAL') || die();

$capabilities = [
    'mod/vimeo:view' => [
        'captype' => 'read',
        'contextlevel' => CONTEXT_MODULE,
        'archetypes' => [
            'user' => CAP_ALLOW,
        ],
    ],
    'mod/vimeo:edit' => [
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
        'archetypes' => [],
    ],
];
