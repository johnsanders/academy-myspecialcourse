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
    public function create_items_html($items)
    {
        $itemsHtml = '';
        foreach ($items as $item) {
            $itemsHtml .= $this->create_item_html($item);
        }
        $html = '';
        $html .= html_writer::start_tag('section', [
            "class" => "block block_recentlyaccesseditems card mb-3",
        ]);
        $html .= html_writer::start_tag('div', ["class" => "card-body p-3"]);
        $html .= html_writer::start_tag('h5', ["class" => "card-title d-inline"]);
        $html .= get_string($this->config->title, 'block_myspecialcourse');
        $html .= html_writer::end_tag('h5');
        $html .= html_writer::start_tag("div", ["class" => "card-text content mt-3"]);
        $html .= html_writer::start_tag("div", [
            "class" => "block-recentlyaccesseditems block-cards",
            "data-region" => "myspecialcourse",
        ]);
        $html .= html_writer::start_tag("div", ["class" => "container-fluid p-0"]);
        $html .= html_writer::start_tag("div", ["data-region" => "myspecialcourse-view"]);
        $html .= html_writer::start_tag("div", ["data-region" => "myspecialcourse-view-content"]);
        $html .= html_writer::start_tag("div", ["class" => "card-deck dashboard-card-deck", "role" => "list"]);
        $html .= $itemsHtml;
        $html .= html_writer::end_tag("div");
        $html .= html_writer::end_tag("div");
        $html .= html_writer::end_tag("div");
        $html .= html_writer::end_tag("div");
        $html .= html_writer::end_tag("div");
        $html .= html_writer::end_tag("div");
        $html .= html_writer::end_tag("div");
        $html .= html_writer::end_tag("section");
        return $html;
    }
    public function create_item_html($item)
    {
        [$title, $modName, $id, $iconUrl] = $item;
        $itemsHtml = '';
        $itemsHtml .= html_writer::start_tag('a', [
            "class" => "card dashboard-card mb-1 py-2",
            "href" => "/mod/$modName/view.php?id=$id",
        ]);
        $itemsHtml .= html_writer::start_tag('div', ["class" => "card-body course-info-container"]);
        $itemsHtml .= html_writer::start_tag('div', ["class" => "d-flex align-items-center"]);
        $itemsHtml .= html_writer::start_tag('div', ["class" => "d-flex align-self-center"]);
        $itemsHtml .= html_writer::img($iconUrl, "Course Resource Icon", ["class" => "icon"]);
        $itemsHtml .= html_writer::end_tag('div');
        $itemsHtml .= html_writer::start_tag('div', ["class" => "w-100 line-height-3 ml-2"]);
        $itemsHtml .= html_writer::start_tag('h6', ["class" => "mb-0"]);
        $itemsHtml .= $title;
        $itemsHtml .= html_writer::end_tag('h6');
        $itemsHtml .= html_writer::end_tag('div');
        $itemsHtml .= html_writer::end_tag('div');
        $itemsHtml .= html_writer::end_tag('div');
        $itemsHtml .= html_writer::end_tag('a');
        return $itemsHtml;
    }
    public function get_content()
    {
        global $DB;
        if ($this->content !== null) {
            return $this->content;
        }
        if (!$this->config->courseid) {
            return null;
        }
        $title = get_string($this->config->title, 'block_myspecialcourse');
        $course = $DB->get_record('course', array('id' => $this->config->courseid));
        $modulesInfo = get_fast_modinfo($course);
        $items = [];
        foreach ($modulesInfo->cms as $module) {
            if (!$module->uservisible) {
                continue;
            }
            $iconUrl = str_replace('24', '96', (string) $module->get_icon_url());
            array_push($items, [$module->get_formatted_name(), $module->modname, $module->id, $iconUrl]);
        }
        $this->content = new stdClass;
        $this->content->text = $this->create_items_html($items);
        return $this->content;
    }
}
