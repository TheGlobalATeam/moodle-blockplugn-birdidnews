<?php

require_once($CFG->libdir . '/pagelib.php');

class block_birdidnews extends block_base {

	private $jsWorkerLoaded = false;

    public function init() {
		GLOBAL $PAGE;

        $this->title = get_string('birdidnews', 'block_birdidnews');




    }

	public function applicable_formats() {
        return array('all' => true);
    }

	public function get_content() {

        global $CFG, $OUTPUT, $USER, $DB, $PAGE;

		//loading js file, while preventing moodle catching. probably a better way somewhere...
		if(!$this->jsWorkerLoaded){
			$this->jsWorkerLoaded = true;
			$PAGE->requires->js('/blocks/birdidnews/main.js?'.rand());
		}

		//not working way
		//$this->page->require->requiresjs('/blocks/birdidnews/main.js');

        if ($this->content !== null) {
          return $this->content;
        }

        $this->content         =  new stdClass;
        //$this->content->text   = 'The content of our birdidnews block!';

        //first element with no .=, just =
        $this->content->text = "<br>Todays best bird is Sparrow";


		$data = file_get_contents('https://hembstudios.no/birdid/notifications.php?lastNewsID=-1&allTime=1');
		$dataDecoded = json_decode ($data);
		//var_dump($dataDecoded);

		$this->content->text .= "<br>Latest news headline from birdid: <b>" . $dataDecoded->headline . '</b>';

		$newsURL = "https://hembstudios.no/birdid/".strtolower($dataDecoded->site_name)."/newsArticle.php?newsID=".$dataDecoded->id;

		$this->content->text .= ' <a href="'.$newsURL.'">URL to news</a>';


        $this->content->footer = 'Footer here...';

        return $this->content;

    }
}
