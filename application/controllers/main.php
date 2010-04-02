<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Main extends Controller
{
	public function index($offset = 0)
	{
        $per_page_home = $this->config->item('tracks_per_page_home');
        $per_page = $this->config->item('tracks_per_page');
	    $files = $this->gpsparser->get_files($offset, $per_page_home);

        // Figure out the offsets to be able to jump to the right page on detail view	    
	    $offset = 0;
        foreach (array_chunk($files, $per_page, True) as $block)
        {
            foreach ($block as $k => $v)
            {
                $files[$k] = array_merge($v, array('offset' => $offset));
            }
            $offset+=$per_page;
        }	  
        $data['file_list'] = $files;

        $this->pagination->initialize(array(
                        'base_url' => site_url('main/index'), 
                        'total_rows' => count($this->gpsparser->file_list), 
                        'per_page' => $per_page_home, 
                        'num_links' => 4,
                        'cur_tag_open' => '<a href="#" style="font-weight: bold; text-decoration: underline;">',
                        'cur_tag_close' => '</a>',
                        'uri_segment' => 3,
                    ));

        $this->load->view("main", $data);
    }
}

?>
