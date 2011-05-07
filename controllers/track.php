<?php

define("CHART_RESOLUTION", 100);

/**
 * Convert a array() to a javascript array for flot
 *
 * @param array array() with $k=>$v data
 * @returns array
 */
function _convert_for_flot($data)
{
    foreach ($data as $k => $v)
    {
        if ($v == Null)
        {
            $flot[] = 'null';
            continue;
        }
        $flot[] = '['.$k.','.$v.']';
    }
    return ("[".implode(',', $flot)."]");
}

/**
 * Generate the speed array
 *
 * @param object $gps object with a loaded track
 * @returns array
 */
function _speed_chart_data($gps)
{
    $resolution = round(count($gps->track) / CHART_RESOLUTION, 0);
    $min = 99999999999;
    $max = 0;
    $distance = $x = 0;

    foreach (array_merge($gps->track, array($gps->track[count($gps->track)-1])) as $trkpt)
    {
        if ($trkpt->speed_to_prev <= 0)
        {
            continue;
        }

        if ($x == 0)
        {
            $chartdata[(string) round($distance/1000, 1)] = round($trkpt->speed_to_prev * 3.6, 2);
        }

        if ($x++ > $resolution)
        {
            $x = 0;
        }

        $distance += round($trkpt->distance_to_prev, 0);
    }
    
    return ($chartdata);
}

/**
 * Generate the height array
 *
 * @param object $gps object with a loaded track
 * @returns array
 */
function _height_chart_data($gps)
{
    $resolution = round(count($gps->track) / CHART_RESOLUTION, 0);
    $min = 99999999999;
    $max = 0;
    $distance = $x = 0;

    foreach (array_merge($gps->track, array($gps->track[count($gps->track)-1])) as $trkpt)
    {
        if ($x == 0)
        {
            $chartdata[(string) round($distance/1000, 1)] = round($trkpt->ele, 0);
            if ($trkpt->ele > $max) 
            {
                $max = round($trkpt->ele + 10, 0);
            }
            if ($trkpt->ele < $min)
            {
                $min = round($trkpt->ele, 0);
            }
        }

        if ($x++ > $resolution)
        {
            $x = 0;
        }

        $distance += round($trkpt->distance_to_prev, 0);
    }
    if ($min > 10)
    {
        $min -= 10;
    }
    return ($chartdata);
}

/**
 * Main Track display page: Displays the GMap, with info snippet
 *
 * @param int offset for pagination
 * @param string What is the gps->name
 */
function track_index()
{
    $per_page = option('tracks_per_page');
    $gpsparser = option('gpsparser');
    
    $file = params('file');

    set('active', $file);
    set('google_maps_key', option('google_maps_key'));

    try
    {
        $gps = $gpsparser->get($file);
    }
    catch (Exception $e)
    {
        die($e->getMessage());
    }

    $mid_point_lat = ($gps->boundaries->west + $gps->boundaries->east) / 2;
    $mid_point_lon = ($gps->boundaries->north + $gps->boundaries->south) / 2;
    set('title', $gps->name);
    set('midpoint', "{$mid_point_lat}, {$mid_point_lon}");
    set('gps', $gps);
    set('draw_chart', True);
    set('speed_chart_data', _convert_for_flot(_speed_chart_data($gps)) );
    set('height_chart_data', _convert_for_flot(_height_chart_data($gps)) );

    $content = render("map_snippet.html.php", null);
    $content .= render("info_snippet.html.php", null);
    
    set('content', $content);
    
    return html('track_view.html.php');
}
