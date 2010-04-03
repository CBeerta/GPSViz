<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

define("CHART_RESOLUTION", 100);

class Track extends Controller
{
    /**
     * Convert a array() to a javascript array for flot
     *
     * @param array array() with $k=>$v data
     * @returns array
     */
    private function _convert_for_flot($data)
    {
        foreach ($data as $k => $v)
        {
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
    private function _speed_chart_data($gps)
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
    private function _height_chart_data($gps)
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
	public function index($offset = 0, $file = Null)
	{
        $per_page = $this->config->item('tracks_per_page');

        try
        {
            $data['file_list'] = $this->gpsparser->get_files($offset, $per_page);
        }
        catch (Exception $e)
        {
            show_error($e->getMessage(), 500);
            die();
        }

        if ($file == Null)
        {
            $file = key($data['file_list']);
        }

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


        try
        {
            $gps = $this->gpsparser->get($file);
        }
        catch (Exception $e)
        {
            show_error($e->getMessage(), 500);
            die();
        }

        $mid_point_lat = ($gps->boundaries->west + $gps->boundaries->east) / 2;
        $mid_point_lon = ($gps->boundaries->north + $gps->boundaries->south) / 2;

        $data['midpoint'] = "{$mid_point_lat}, {$mid_point_lon}";
        $data['gps'] = $gps;
        $data['draw_chart'] = True;
        $data['speed_chart_data'] = $this->_convert_for_flot($this->_speed_chart_data($gps));
        $data['height_chart_data'] = $this->_convert_for_flot($this->_height_chart_data($gps));
        $data['content'] = $this->load->view("map_snippet", $data, True);
        $data['content'] .= $this->load->view("info_snippet", $data, True);
        
	    $this->load->view("track_view", $data);
	}
}

?>
