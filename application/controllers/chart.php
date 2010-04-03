<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Chart extends Controller
{

    private function _draw_chart($title, $chartdata, $goalline = Null, $min = Null, $max = Null)
    {
        $this->load->library('phpgraphlib', array('width' => 500,'height' => 200));
        if ($min != Null && $max != Null && $min >= 0)
        {
            $this->phpgraphlib->setRange($max, $min);
        }
        $this->phpgraphlib->addData($chartdata);
        $this->phpgraphlib->setTitle($title);
        if ($goalline != Null)
        {
            $this->phpgraphlib->setGoalLine($goalline);
        }

        $this->phpgraphlib->setGradient("red", "maroon");
        $this->phpgraphlib->setGridColor("silver");
        $this->phpgraphlib->setGoalLineColor("red");
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
        //$this->phpgraphlib->setBackgroundColor("76,76,76");
        //$this->phpgraphlib->setTitleColor("200,200,200");

        $this->phpgraphlib->createGraph();
        exit;
    }


    public function height($file)
    {
        try
        {
            $gps = $this->gpsparser->get($file);
        }
        catch (Exception $e)
        {
            show_error($e->getMessage(), 500);
            die();
        }

        $resolution = round(count($gps->track) / 15, 0);
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

        $this->_draw_chart("Height Chart", $chartdata, Null, $min, $max);
    }


    public function speed($file)
    {
        try
        {
            $gps = $this->gpsparser->get($file);
        }
        catch (Exception $e)
        {
            show_error($e->getMessage(), 500);
            die();
        }

        $resolution = round(count($gps->track) / 15, 0);
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

        $this->_draw_chart("Speed Chart", $chartdata, $gps->speed * 3.6);
    }


}

?>
