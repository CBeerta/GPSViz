<?php

/**
 * Main index page, displays a blog style list of tracks sorted by date
 *
 * @param int offset for pagination
 */
function main_index()
{
    $offset = params("offset") ? params("offset") : 0;
    
    $per_page_home = option('tracks_per_page_home');
    $per_page = option('tracks_per_page');
    
    $gpsparser = option('gpsparser');
    
	$files = $gpsparser->get_files($offset, $per_page_home);
	
    set ('total_rows', count($gpsparser->file_list));
    set ('offset', $offset);
    set ('per_page', $per_page_home);
    
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

