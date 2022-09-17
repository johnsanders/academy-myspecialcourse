<?php
defined('MOODLE_INTERNAL') || die();

class block_myspecialcourse extends block_base {
	function init() {
		$this->title = "My Special Course";
	}
	function has_config() {
		return false;
	}
	function instance_allow_multiple() {
		return true;
	}
	function hide_header() {
		return true;
	}
	// http://ec2-18-195-50-77.eu-central-1.compute.amazonaws.com/theme/image.php/boost/block_timeline/1663168505/activities
	function create_item($item) {
		[$title, $modName, $id, $iconUrl] = $item;
		$url = "/mod/$modName/view.php?id=$id`";
		return "
							<a href='$url' class='card dashboard-card mb-1 py-2'>
								<div class='card-body course-info-container'>
									<div class='d-flex text-truncate align-items-center'>
										<div class='d-flex align-self-center'>
											<img title='Video' alt='Video' class='icon' src=$iconUrl />
										</div>
										<div class='w-100 line-height-3 text-truncate ml-2'>
											<h6 class='mb-0 text-truncate'>
												$title
											</h6>
										</div>
									</div>
								</div>
							</a>
		";
	}
	function get_content() {
		global $DB;
		if ($this->content !== NULL) return $this->content;
		if (!$this->config->courseid) return NULL;
		$title = get_string($this->config->title, 'block_myspecialcourse');
		$course = $DB->get_record('course', array('id' => $this->config->courseid));
		$modulesInfo = get_fast_modinfo($course);
		$items = [];
		foreach ($modulesInfo->cms as $module) {
			if (!$module->uservisible) continue;
			$iconUrl = str_replace('24', '96', (string)$module->get_icon_url());
			array_push($items, [$module->get_formatted_name(), $module->modname, $module->id, $iconUrl]);
		}
		$itemsHtml = '';
		foreach ($items as $item) {
			$itemsHtml .= $this->create_item($item);
		}
		$content = "
			<section class='block block_recentlyaccesseditems card mb-3'>
				<div class='card-body p-3'>
					<h5 class='card-title d-inline'>
							$title
					</h5>
					<div class='card-text content mt-3'>
						<div class='block-recentlyaccesseditems block-cards' data-region='myspecialcourse'>
							<div class='container-fluid' p-0>
								<div data-region='myspecialcourse-view' data-noitemsimgurl='/theme/image.php/cnn/block_recentlyaccesseditems/1663327916/items'>
									<div data-region='myspecialcourse-view-content'>
										<div class='card-deck dashboard-card-deck' role='list'>
											$itemsHtml
										</div>
									</div>
								</div>
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