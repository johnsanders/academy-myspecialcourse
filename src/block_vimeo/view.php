<?php
require __DIR__ . '/../../config.php';
require_once __DIR__ . '/lib.php';

$id = optional_param('id', 0, PARAM_INT);
$v = optional_param('v', 0, PARAM_INT);

if ($id) {
    $cm = get_coursemodule_from_id('vimeo', $id, 0, false, MUST_EXIST);
    $course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $moduleinstance = $DB->get_record('vimeo', array('id' => $cm->instance), '*', MUST_EXIST);
} else if ($v) {
    $moduleinstance = $DB->get_record('vimeo', array('id' => $v), '*', MUST_EXIST);
    $course = $DB->get_record('course', array('id' => $moduleinstance->course), '*', MUST_EXIST);
    $cm = get_coursemodule_from_instance('vimeo', $moduleinstance->id, $course->id, false, MUST_EXIST);
} else {
    print_error(get_string('missingidandcmid', 'vimeo'));
}

require_login($course, true, $cm);

$modulecontext = context_module::instance($cm->id);

$event = \mod_vimeo\event\course_module_viewed::create(array(
    'objectid' => $moduleinstance->id,
    'context' => $modulecontext,
));
$event->add_record_snapshot('course', $course);
$event->add_record_snapshot('vimeo', $moduleinstance);
$event->trigger();

$PAGE->set_url('/mod/vimeo/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($moduleinstance->name));
$PAGE->set_context($modulecontext);

$templatecontext = [
    'id' => $moduleinstance->vimeoid,
    'name' => $moduleinstance->name,
];

$completion = new completion_info($course);
$completion->set_module_viewed($cm);

echo $OUTPUT->header();

echo $OUTPUT->render_from_template('mod_vimeo/iframe_embed', $templatecontext);

echo $OUTPUT->footer();
