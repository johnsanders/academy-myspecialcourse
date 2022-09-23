<?php
require_once __DIR__ . '/../../config.php';
class block_academyresources extends block_base
{
    public function init()
    {
        $this->title = get_string('pluginname', 'block_academyresources');
    }
    public function has_config()
    {
        return true;
    }
    public function instance_allow_multiple()
    {
        return true;
    }
    public function hide_header()
    {
        return true;
    }
    public function select_random($num, $arr)
    {
        $randoms = [];
        for ($i = 0; $i < $num; $i++) {
            $rand = rand(0, count($arr) - 1);
            array_push($randoms, $arr[$rand]);
            array_splice($arr, $rand, 1);
        }
        return $randoms;
    }
    public function get_content()
    {
        global $OUTPUT;
        if ($this->content !== null) {
            return $this->content;
        }
        $content = '';
        $showVault = get_config('block_academyresources', 'showvault');
        $showNewsource = get_config('block_academyresources', 'shownewsource');
        $dataRaw = file_get_contents('https://cnn-academy-resources.s3.eu-central-1.amazonaws.com/externalData.json');
        $data = json_decode($dataRaw);
        $templateData = new stdClass;
        $templateData->rows = [];
        if ($showVault) {
            $randomItems = $this->select_random(4, $data->vault);
            array_push($templateData->rows, ["title" => "From the CNN Archives", "items" => $randomItems]);
        }
        if ($showNewsource) {
            $randomItems = $this->select_random(4, $data->newsourceBlog);
            array_push($templateData->rows, ["title" => "CNN Newsource Industry Insights", "items" => $randomItems]);
        }
        $this->content = new stdClass;
        $this->content->text = $OUTPUT->render_from_template('block_academyresources/main', $templateData);
        return $this->content;
    }
}
