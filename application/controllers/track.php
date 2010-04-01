<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Track extends Controller
{
	public function index($offset = 0, $file = Null, $view = 'map')
	{
        $data['file_list'] = $this->gpsparser->get_files($offset, 5);
        $data['offset'] = $offset;
        $data['active'] = $file;
        $data['google_maps_key'] = $this->config->item('google_maps_key');

        $this->pagination->initialize(array(
                        'base_url' => site_url('track/index'), 
                        'total_rows' => count($this->gpsparser->file_list), 
                        'per_page' => 5, 
                        'num_links' => 3,
                        'cur_tag_open' => '<a href="#" style="font-weight: bold; text-decoration: underline;">',
                        'cur_tag_close' => '</a>',
                        'uri_segment' => 3,
                    ));

        if ($file !== Null && ($gps = $this->gpsparser->get($file)) !== False)
        {
            $data['gps'] = $gps;
            $data['content'] = $this->load->view("map_snippet", $data, True);
            $data['content'] .= $this->load->view("info_snippet", $data, True);
        }
        else
        {
            $data['content'] = '';
        }
        
	    $this->load->view("track_view", $data);
	}
}

?>
