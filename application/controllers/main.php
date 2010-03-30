<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Main extends Controller
{
	public function index($offset = 0, $file = Null)
	{
        $data['file_list'] = $this->gpsparser->get_files($offset, 5);
        $data['offset'] = $offset;

        $this->pagination->initialize(array(
                        'base_url' => site_url('main/index'), 
                        'total_rows' => count($this->gpsparser->file_list), 
                        'per_page' => 5, 
                        'num_links' => 1,
                        'cur_tag_open' => '<a href="#" style="text-decoration: underline;">',
                        'cur_tag_close' => '</a>',
                        'uri_segment' => 3,
                    ));

        $gps = $this->gpsparser->get($file);
        $data['content'] = $gps;
	    $this->load->view("main", $data);
	}
}


?>
