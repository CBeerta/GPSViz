<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Main extends Controller
{
	public function index()
	{
	    $files = $this->gpsparser->get_files();
	    $per_page = $this->config->item('tracks_per_page');

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
        $this->load->view("main", $data);
    }
}

?>
