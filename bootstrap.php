<?php

require_once __DIR__.'/lib/limonade.php';
require_once __DIR__.'/lib/stuff.php';
require_once __DIR__.'/lib/geocalc.php';
require_once __DIR__.'/lib/gpsparser.php';

function configure() 
{
    $config = parse_ini_file(__DIR__."/config.ini");

    option('google_maps_key', $config['google_maps_key']);
    option('tracks_per_page', $config['tracks_per_page']);
    option('tracks_per_page_home', $config['tracks_per_page_home']);

    $gpsparser = new GPSParser();
    $gpsparser->set_temp_directory($config['temp_directory']);
    $gpsparser->set_gpsbabel($config['gpsbabel']);
    $gpsparser->setup($config['gps_directory']);
    
    option('gpsparser', $gpsparser);
    option('debug', false);
    option('views_dir', __DIR__.'/views');
    option('controllers_dir', __DIR__.'/controllers');
    option('lib_dir', __DIR__.'/lib');
    option('public_dir', __DIR__.'/public');
}

function not_found($errno, $errstr, $errfile=null, $errline=null)
{
    list ($_file) = explode('?', __DIR__.'/public' . $_SERVER['QUERY_STRING']);
    if ( file_exists($_file) ) 
    {
        status(200);
        return render_file($_file, null);
    }
    else
    {
        set('errno', $errno);
        set('errstr', $errstr);
        set('errfile', $errfile);
        set('errline', $errline);
        set('title', "{$errno} - {$errstr}");
        return html("404.html.php");
    }
}

layout('base.html.php');

dispatch_get('/', 'main_index');
dispatch_get('/:offset', 'main_index');
dispatch_get('/main/ajax/:file', 'main_ajax');

dispatch_get('/track/index/:file', 'track_index');

run();


