<?php

if ($ADMIN->fulltree) {
    $settings->add(new admin_setting_configcheckbox(
        'block_academyresources/shownewsource',
        get_string('shownewsource', 'block_academyresources'),
        get_string('shownewsourcedesc', 'block_academyresources'),
        0
    ));
    $settings->add(new admin_setting_configcheckbox(
        'block_academyresources/showvault',
        get_string('showvault', 'block_academyresources'),
        get_string('showvaultdesc', 'block_academyresources'),
        0
    ));
}
