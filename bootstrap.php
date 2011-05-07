<?php

require_once __DIR__.'/lib/limonade.php';
require_once __DIR__.'/lib/geocalc.php';
require_once __DIR__.'/lib/gpsparser.php';

/**
* FIXME: ARGH in BOOTSTRAP ARGH
**/
function seconds_to_words($seconds)
{
    if ( $seconds < 0 )
    {
        $seconds *= -1;
        $ret = "-";
    }
    else
        $ret = "";
    

    $hours = intval(intval($seconds) / 3600);
    $ret .= "$hours";
    
    $minutes = bcmod((intval($seconds) / 60),60);
    $ret .= sprintf(":%02d", $minutes);
    
    /*      
    $seconds = bcmod(intval($seconds),60);
    $ret .= ":$seconds";
    */
    return $ret;    
}


function configure() 
{
    // FIXME: should probalby put these into an ini file 
    $gps_directory = '/home/claus/Documents/GPS-Files';
    $temp_directory = '/var/tmp';
    $gpsbabel = '/usr/bin/gpsbabel';

    option('google_maps_key', 'ABQIAAAAOXwIs0kAMCTT4R_LT2qceBQ2GADgm1ezMFVJ6cO3aik9EkAcBRRENvrod5uF0B-dTwVPde0g0By6Cg');
    option('google_maps_key', 'ABQIAAAAOXwIs0kAMCTT4R_LT2qceBQxyyC-DsmYy5NhFzDjM_TNT5FDNhSMPkJ7bWPqApF0OMojBtpbhf6oSQ');


    /**
     * How many tracks to show in the pagination on the detail track view
     */
    option('tracks_per_page', 5);

    /**
     * How many tracks to show on the home page
     */
    option('tracks_per_page_home', 10);

    $gpsparser = new GPSParser();
    $gpsparser->set_temp_directory('/var/tmp');
    $gpsparser->set_gpsbabel('/usr/bin/gpsbabel');
    $gpsparser->setup($gps_directory);

    option('gpsparser', $gpsparser);
    option('debug', true);
    option('views_dir', __DIR__.'/views');
    option('controllers_dir', __DIR__.'/controllers');
    option('lib_dir', __DIR__.'/lib');
    option('base_uri', '/');
}

function not_found($errno, $errstr, $errfile=null, $errline=null)
{
    set('errno', $errno);
    set('errstr', $errstr);
    set('errfile', $errfile);
    set('errline', $errline);
    set('title', "{$errno} - {$errstr}");
    return html("404.html.php");
}

function after($output) 
{
    $time = number_format( (float)substr(microtime(), 0, 10) - LIM_START_MICROTIME, 6);
    $output .= "<!-- page rendered in $time sec., on " . date(DATE_RFC822)."-->";
    return $output;
}

layout('base.html.php');

dispatch_get('/', 'main_index');
dispatch_get('/:offset', 'main_index');
dispatch_get('/main/ajax/:file', 'main_ajax');

dispatch_get('/track/index/:offset/:file', 'track_index');


run();
