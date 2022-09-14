<?php
defined('MOODLE_INTERNAL') || die();

class block_myspecialcourse extends block_base {
	function init() {
		$this->title = "My Special Course";
	}
	function has_config() {
		return false;
	}
	public function instance_allow_multiple() {
		return true;
	}
	public function hide_header() {
		return true;
	}
	function create_item($item) {
		[$title, $modName, $id, $iconUrl] = $item;
		$url = "/mod/$modName/view.php?id=$id`";
		return "
							<div class='card dashboard-card' role='listitem' data-region='course-content'>
								<div class='card-body course-info-container'>
									<div class='text-center w-100 mb-2'>
										<a href='$url'>
											<img src=$iconUrl style='height: 30px;' />
										</a>
									</div>
									<div class='text-center w-100'>
										<a class='aalink coursename text-center mr-2' href='$url' style='font-size: 1.2em'>
												$title
										</a>
									</div>
								</div>
							</div>
		";
	}
	function get_content() {
		global $CFG, $DB;
		if ($this->content !== NULL) return $this->content;
		if (!$this->config->courseid) return NULL;
		$title = get_string($this->config->title, 'block_myspecialcourse');
		$course = $DB->get_record('course', array('id' => $this->config->courseid));
		$modulesInfo = get_fast_modinfo($course);
		$items = [];
		foreach ($modulesInfo->cms as $module) {
			if (!$module->is_visible_on_course_page()) continue;
			array_push($items, [$module->get_formatted_name(), $module->modname, $module->id, (string)$module->get_icon_url()]);
		}
		$itemsHtml = '';
		foreach ($items as $item) {
			$itemsHtml .= $this->create_item($item);
		}
		$content = "
			<section class='block' style='margin: 0 -5px 3em -5px; padding: 0;'>
				<div class='card-body p-3'>
					<h5 class='card-title d-inline'>
							$title
					</h5>
					<div class='content mt-3'>
						<div class='container-fluid p-0'>
							<div class='card-deck dashboard-card-deck' data-region='card-deck' role='list'>
								$itemsHtml
							</div>
						</div>
					</div>
				</div>
			</section>
		";
		$this->content = new stdClass;
		$this->content->text = $content;
		return $this->content;
	}
}