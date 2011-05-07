<?php

/**
 * Main index page, displays a blog style list of tracks sorted by date
 *
 * @param int offset for pagination
 */
function main_index($offset = 0)
{
    $per_page_home = option('tracks_per_page_home');
    $per_page = option('tracks_per_page');
    
    $gpsparser = option('gpsparser');
    
	$files = $gpsparser->get_files($offset, $per_page_home);
	
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
    
/*
    FIXME: Yeah, pagination and stuff
    $this->pagination->initialize(array(
                    'base_url' => site_url('main/index'), 
                    'total_rows' => count($this->gpsparser->file_list), 
                    'per_page' => $per_page_home, 
                    'num_links' => 4,
                    'cur_tag_open' => '<a href="#" style="font-weight: bold; text-decoration: underline;">',
                    'cur_tag_close' => '</a>',
                    'uri_segment' => 3,
                ));
*/
    //$this->load->view("main", $data);
    
    set('file_list', $files);
    set('title', 'Overview');
    
    return html('main.html.php');
}

/**
 * Returns the little info snippet loaded by an ajax request
 *
 * @param string $gps->name pointing to the track
 */
function main_ajax()
{
    $gpsparser = option('gpsparser');
    $file = params('file');

    try
    {
        $gps = $gpsparser->get($file, False);
    }
    catch (Exception $e)
    {
        die($e->getMessage());
    }
    //$data['draw_chart'] = False;
    set('gps', $gps);
    //set('draw_chart', False);
    
    return render('info_snippet.html.php', null);    
}

