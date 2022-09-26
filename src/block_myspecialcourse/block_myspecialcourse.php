<?php
defined('MOODLE_INTERNAL') || die();

class block_myspecialcourse extends block_base
{
    public function init()
    {
        $this->title = "My Special Course";
    }
    public function has_config()
    {
        return false;
    }
    public function instance_allow_multiple()
    {
        return true;
    }
    public function hide_header()
    {
        return true;
    }
    public function get_content()
    {
        global $DB, $OUTPUT;
        if ($this->content !== null) {
            return $this->content;
        }
        if (!$this->config->courseid) {
            return null;
        }
        $course = $DB->get_record('course', array('id' => $this->config->courseid));
        $modulesInfo = get_fast_modinfo($course);
        $templateData = new stdClass;
        $templateData->title = get_string($this->config->title, 'block_myspecialcourse');
        $templateData->courseId = $this->config->courseid;
        $templateData->showCourseLink = false;
        $templateData->items = [];
        foreach ($modulesInfo->cms as $module) {
            if (count($templateData->items) >= $this->config->maxitems) {
                $templateData->showCourseLink = true;
                break;
            }
            if (!$module->uservisible) {
                continue;
            }
            $item = new stdClass;
            $item->id = $module->id;
            $item->name = $module->get_formatted_name();
            if (strlen($item->name) > $this->config->maxnamelength) {
                $item->name = substr($item->name, 0, $this->config->maxnamelength) . '...';
            }
            $item->modName = $module->modname;
            $item->iconUrl = str_replace('24', '96', (string) $module->get_icon_url());
            array_push($templateData->items, $item);
        }
        $this->content = new stdClass;
        $this->content->text = $OUTPUT->render_from_template('block_myspecialcourse/main', $templateData);
        return $this->content;
    }
}
