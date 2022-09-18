<?php
// This file is part of Moodle - http://moodle.org/
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
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Handles displaying the calendar upcoming events block.
 *
 * @package    block_calendar_upcoming
 * @copyright  2004 Eloy Lafuente (stronk7) {@link http://stronk7.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_calendar_upcoming_img extends block_base {
    public function init() {
        $this->title = get_string('pluginname', 'block_calendar_upcoming_img');
    }
    public function hide_header() {
	    return true;
    }
    public function create_events_html($events) {
		$html = '';
		$html .= html_writer::start_tag('section', [
			"id" => "block_calendar_upcoming_img",
			"class" => "block_calendar_upcoming block card mb-3",
			"role" => "complementary",
			"data-block" => "calendar_upcoming",
			"aria-labelledby" => "block_calendar_upcoming_img_header"
		]);
		$html .= html_writer::start_tag('div', ["class" => "card-body p-3"]);
		$html .= html_writer::start_tag('h5', ["id" => "block_calendar_upcoming_img_header", "class" => "card-title d-inline"]);
		$html .= get_string('upcomingevents', 'calendar');
		$html .= html_writer::end_tag('h5');
		$html .= html_writer::start_tag('div', ["class" => "card-text content calendarwrapper mt-3"]);
		foreach ($events as $event) $html .= $this->create_event_html($event);
		$html .= html_writer::end_tag('div');
		$html .= html_writer::end_tag('div');
		$html .= html_writer::end_tag('section');
		return $html;
			}
			public function create_event_html($event) {
		$imgTag = '';
				preg_match('/img\s+src="(http.+?)"/', $event->description, $matches);
		if (count($matches) > 1) $imgTag = html_writer::img($matches[1], "", ["style" => "max-height: 6em"]);

		$eventHtml = '';
		$eventHtml .= html_writer::start_tag('div', [
			"class" => "event d-flex align-items-center justify-content-between",
			"data-region" => "event-item"
		]);
		$eventHtml .= html_writer::start_tag('div', ["id" => "textContainer"]);
		$eventHtml .= html_writer::link(
			new moodle_url($event->viewurl),
			$event->name,
			[
				"data-type" => "event",
				"data-action" => "view-event",
				"data-event-id" => $event->id,
			]
		);
		$eventHtml .= html_writer::start_tag('div', ["class" => "date"]);
		$eventHtml .= html_writer::link(
			new moodle_url("/calendar/view.php?view=day&amp;time={$event->timestart}"),
			$event->formattedtime,
			[
				"data-type" => "event",
				"data-action" => "view-event",
				"data-event-id" => $event->id,
			]
		);
		$eventHtml .= html_writer::end_tag('div');
		$eventHtml .= html_writer::end_tag('div');
		$eventHtml .= $imgTag;
		$eventHtml .= html_writer::end_tag('div');
		return $eventHtml;
	}
	public function get_content() {
			global $CFG;
			require_once($CFG->dirroot.'/calendar/lib.php');
			if ($this->content !== null) return $this->content;

			$calendar = \calendar_information::create(time(), true, true);
			$calendarEvents = calendar_get_view($calendar, 'upcoming');

			$this->content = new stdClass;
			$this->content->text = $this->create_events_html($calendarEvents[0]->events);
			return $this->content;
	}
}