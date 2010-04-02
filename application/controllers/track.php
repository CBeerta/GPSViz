<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Track extends Controller
{
	public function index($offset = 0, $file = Null, $view = 'map')
	{
        $per_page = $this->config->item('tracks_per_page');
        $data['file_list'] = $this->gpsparser->get_files($offset, $per_page);
        $data['offset'] = $offset;
        $data['active'] = $file;
        $data['google_maps_key'] = $this->config->item('google_maps_key');

        $this->pagination->initialize(array(
                        'base_url' => site_url('track/index'), 
                        'total_rows' => count($this->gpsparser->file_list), 
                        'per_page' => $per_page, 
                        'num_links' => 4,
                        'cur_tag_open' => '<a href="#" style="font-weight: bold; text-decoration: underline;">',
                        'cur_tag_close' => '</a>',
                        'uri_segment' => 3,
                    ));

        if ($file !== Null && ($gps = $this->gpsparser->get($file)) !== False)
        {
            $mid_point_lat = ($gps->boundaries->west + $gps->boundaries->east) / 2;
            $mid_point_lon = ($gps->boundaries->north + $gps->boundaries->south) / 2;

            $data['midpoint'] = "{$mid_point_lat}, {$mid_point_lon}";
            $data['gps'] = $gps;
            $data['draw_chart'] = True;
            $data['content'] = $this->load->view("map_snippet", $data, True);
            $data['content'] .= $this->load->view("info_snippet", $data, True);
        }
        else
        {
            $data['content'] = '';
        }
        
	    $this->load->view("track_view", $data);
	}

    public function ajax($file)
    {
        $data['gps'] = $this->gpsparser->get($file, False);
        $data['active'] = $file;
        //$data['draw_chart'] = True;
        print $this->load->view("info_snippet", $data, True);
        exit;
    }
}

?>
