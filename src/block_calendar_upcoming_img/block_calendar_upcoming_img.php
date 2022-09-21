<?php
class block_calendar_upcoming_img extends block_base
{
    public function init()
    {
        $this->title = get_string('pluginname', 'block_calendar_upcoming_img');
    }
    public function hide_header()
    {
        return true;
    }
    public function add_event_images($events)
    {
        foreach ($events as $event) {
            preg_match('/img\s+src="(http.+?)"/', $event->description, $matches);
            if (count($matches) > 1) {
                $event->imageurl = $matches[1];
            }
        }
    }
    public function get_content()
    {
        global $CFG;
        require_once $CFG->dirroot . '/calendar/lib.php';
        if ($this->content !== null) {
            return $this->content;
        }
        $calendar = \calendar_information::create(time(), true, true);
        [$data] = calendar_get_view($calendar, 'upcoming_mini');
        $this->add_event_images($data->events);

        $renderer = $this->page->get_renderer('core_calendar');
        $this->content = new stdClass;
        $this->content->text = $renderer->render_from_template("block_calendar_upcoming_img/main", $data);
        return $this->content;
    }
}
