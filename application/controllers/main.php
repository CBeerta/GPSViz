<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Main extends Controller
{
	public function index($offset = 0, $file = Null, $view = 'map')
	{
        $data['file_list'] = $this->gpsparser->get_files();

        $this->load->view("main", $data);
    }
}

?>
