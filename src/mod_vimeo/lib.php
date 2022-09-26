<?php

defined('MOODLE_INTERNAL') || die();

function vimeo_supports($feature)
{
    switch ($feature) {
        case FEATURE_COMPLETION_TRACKS_VIEWS:
            return true;
        case FEATURE_BACKUP_MOODLE2:
            return true;
        case FEATURE_MOD_ARCHETYPE:
            return MOD_ARCHETYPE_RESOURCE;
        case FEATURE_MOD_INTRO:
            return true;
        default:
            return null;
    }
}
function get_vimeo_id($vimeo_url)
{
    preg_match('/vimeo.com\/([0-9]+).?/i', $vimeo_url, $matches);
    if (count($matches) > 1) {
        return $matches[1];
    }
    return "";
}

function vimeo_add_instance($moduleinstance)
{
    global $DB;
    $moduleinstance->timecreated = time();
    $vimeo_id = get_vimeo_id($moduleinstance->vimeourl);
    $moduleinstance->vimeoid = $vimeo_id;
    $id = $DB->insert_record('vimeo', $moduleinstance);
    return $id;
}
function vimeo_update_instance($moduleinstance)
{
    global $DB;
    $moduleinstance->timemodified = time();
    $moduleinstance->id = $moduleinstance->instance;
    $vimeo_id = get_vimeo_id($moduleinstance->vimeourl);
    $moduleinstance->vimeoid = $vimeo_id;
    return $DB->update_record('vimeo', $moduleinstance);
}
function vimeo_delete_instance($id)
{
    global $DB;
    $exists = $DB->get_record('vimeo', array('id' => $id));
    if (!$exists) {
        return false;
    }
    $DB->delete_records('vimeo', array('id' => $id));
    return true;
}
