<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Main extends Controller
{
	public function index($offset = 0, $file = Null, $view = 'map')
	{
        $data['file_list'] = $this->gpsparser->get_files($offset, 5);
        $data['offset'] = $offset;
        $data['active'] = $file;
        $data['google_maps_key'] = $this->config->item('google_maps_key');

        $this->pagination->initialize(array(
                        'base_url' => site_url('main/index'), 
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
        
	    $this->load->view("main", $data);
	}

    public function chart($file)
    {
        if (($gps = $this->gpsparser->get($file)) !== False)
        {
            $resolution = round(count($gps->track) / 15, 0);
            $min = 99999999999;
            $max = 0;
            $distance = $x = 0;

            foreach ($gps->track as $trkpt)
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
            $chartdata[(string) round($distance/1000, 1)] = round($gps->track[count($gps->track)-1]->ele, 0);

            if ($min > 10)
            {
                $min -= 10;
            }

            $this->load->library('phpgraphlib', array('width' => 500,'height' => 200));
            $this->phpgraphlib->setRange($max, $min);
            $this->phpgraphlib->addData($chartdata);

            //$this->phpgraphlib->setBackgroundColor("76,76,76");
            $this->phpgraphlib->setGridColor("silver");
            $this->phpgraphlib->setGrid(true);
            $this->phpgraphlib->setBars(false);
            $this->phpgraphlib->setDataPoints(false);
            $this->phpgraphlib->setDataValues(true);
            $this->phpgraphlib->setLine(true);
            $this->phpgraphlib->setupXAxis(20);
            $this->phpgraphlib->setupyAxis(10);

            //$this->phpgraphlib->setXValuesHorizontal(true);
            //$this->phpgraphlib->setLineColor("black");
            //$this->phpgraphlib->setDataValueColor("200,200,200");
            //$this->phpgraphlib->setLegendColor("200,200,200");
            //$this->phpgraphlib->setTextColor("200,200,200");
            $this->phpgraphlib->setGradient("red", "maroon");
            $this->phpgraphlib->setTitle("Height Chart");
            //$this->phpgraphlib->setTitleColor("200,200,200");

            $this->phpgraphlib->createGraph();
            exit;
        }
        else
        {
            die("<h1>Unable to load {$file}");
        }

        exit;
    }
}


?>
