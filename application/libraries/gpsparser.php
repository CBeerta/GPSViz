<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

DEFINE("TMP_FILE_VER", "0.0.3");

// GeoCALC
// http://imaginerc.com/software/GeoCalc/

// GPX Docu
// http://www.topografix.com/gpx_manual.asp#hdop

// Let all units be meters and seconds, convert in the controller


/**
 * GPS_Track is a representation of a single Track
 *
 * @param $directory directory holding the gps files
 * @param $file filename to open
 * @param $type type of the gpstrack
 *
 */
class GPS_Track
{
    /**
     * Total Distance of Track
     */
    public $distance = 0.0;
    
    /** 
     * Speed of Track
     */
    public $speed = 0.0;

    /** 
     * Top Speed of Track
     */
    public $top_speed = 0.0;
    /**
     * Total time taken for Track (in seconds)
     **/
    public $total_time_taken = 0;

    /**
     * Entire Track with all Track Points
     */
    public $track = array();

    /**
     * In What directory is this track stored 
     */
    private $directory = Null;
    
    /**
     * Name of the Track ( = Filename without extension)
     */
    public $name = Null;
    
    /**
     * The filename (without directory)
     */
    public $file = Null;

    /**
     * The filename, including directory
     */
    private $filename = Null;
    
    /**
     * What type of gps file is it
     */ 
    private $file_type = Null;
    
    /**
     * stat() info on $file
     */
    public $stat = Null;
    
    /** 
     * Date of the Track
     */
    public $date = Null;

    /** 
     * How many coords does this track have
     */
    public $coordinates = 0;

    /** 
     * lon/lat boundaries of the track as west,east,north,south
     */
    public $boundaries = Null;

    /**
     * is_loaded tells parent class if the file has actually been parsed
     */
    public $is_loaded = False;
    
    /**
     * Constructor checks if the gps file is loadable
     *
     * @param string $directory 
     * @param string $file
     * @param string $type
     **/
    public function __construct($directory, $file, $type)
    {
        $CI =& get_instance();

        $this->filename = "{$directory}/{$file}";
        if (is_dir($directory) && is_readable($this->filename))
        {
            $this->file = $file;
            $this->directory = $directory;
        }
        else
        {
            throw new Exception("Unable to load {$file}");
        }
        $this->file_type = $type;

        preg_match("#^(.*)\.[a-z]{2,}$#i", $file, $matches);
        $this->name = $matches[1];

        $this->stat = (object)stat($this->filename);
        $this->date = $this->stat->ctime;

        $tmp_file = $CI->config->item('tmp_directory')."/{$file}.info";

        if (file_exists($tmp_file))
        {
            $data = @file_get_contents($tmp_file);
            $data = @unserialize($data);
            if (!$data || $data['file_ver'] != TMP_FILE_VER)
            {
                // If the file could not be read, or is outdated, remove it so it can be recreated
                @unlink($tmp_file);
            }
            else
            {
                foreach (array('date', 'total_time_taken', 'speed', 'distance', 'coordinates', 'top_speed') as $var)
                {
                    $this->$var = $data[$var];
                }
            }
        }
    }

    /**
     * Does all the heavy lifting: convert input to gpx, load xml, and construct all the variables we need
     *
     */
    public function load()
    {
        $CI =& get_instance();
        $gpsbabel = $CI->config->item('gpsbabel');

        $cmd = "{$gpsbabel} -i {$this->file_type} -f '{$this->filename}' -c UTF-8 -o gpx,gpxver=1.1 -t -F -";
        exec($cmd, $output, $ret_var);
        if (!($xml = @simplexml_load_string(implode("\n", $output))))
        {
            throw new Exception("Unable to load {$this->filename}. Is {$gpsbabel} available?");
        }

        $children = $xml->children();

        if (!isset($children->trk->trkseg->trkpt[0]->time[0]))
        {
            throw new Exception("Not a valid track {$this->filename}.");
        }
       
        // Save the first trackpoints date_time as time for our track
        $this->date = strtotime((string) $children->trk->trkseg->trkpt[0]->time[0]);

        $trk =  array();
        $index = 0;
        $this->distance = $this->speed = $this->total_time_taken = 0;

        $attr = $children->trk->trkseg->trkpt[0]->attributes();
        $left = $right = (float) $attr->lat;
        $top = $bottom = (float) $attr->lon;

        foreach ($children->trk->trkseg->trkpt as $trkpt)
        {
            $attr = $trkpt->attributes();
            $trk[$index] = (object) array(
                    'lat' => (float) $attr->lat, 
                    'lon' => (float) $attr->lon,
                    'time' => strtotime((string) $trkpt->time),
                    'hrtime' => date('c', strtotime((string) $trkpt->time)),
                    'ele' => (float) $trkpt->ele,
                    'fix' => (string) $trkpt->fix,
                    'sat' => (int) $trkpt->sat,
                    'distance_to_prev' => 0.0,
                    'time_to_prev' => 0,
                    'speed_to_prev' => 0,
                );

            if ($index > 0)
            {
                $trk[$index]->distance_to_prev = $CI->geocalc->EllipsoidDistance(
                                                    $trk[$index-1]->lat,
                                                    $trk[$index-1]->lon,
                                                    $trk[$index]->lat,
                                                    $trk[$index]->lon) * 1000;
                $trk[$index]->time_to_prev = $trk[$index]->time - $trk[$index-1]->time;
                $this->distance += $trk[$index]->distance_to_prev;
                
                if ($trk[$index]->time_to_prev != 0)
                {
                    $trk[$index]->speed_to_prev = $trk[$index]->distance_to_prev / $trk[$index]->time_to_prev;
                    if ($trk[$index]->speed_to_prev > $this->top_speed)
                    {
                        $this->top_speed = $trk[$index]->speed_to_prev;
                    }
                }


                // Find the Boundaries
                if ($trk[$index]->lat > $right) 
                {
                    $right = $trk[$index]->lat;
                }
                else if ($trk[$index]->lat < $left)
                {
                    $left = $trk[$index]->lat;
                }
                if ($trk[$index]->lon > $top) 
                {
                    $top = $trk[$index]->lon;
                }
                else if ($trk[$index]->lon < $bottom)
                {
                    $bottom = $trk[$index]->lon;
                }

            }
            $index++;
        }
        $this->boundaries = (object) array('west' => $left, 'east' => $right, 'north' => $top, 'south' => $bottom);
        $this->total_time_taken = $trk[count($trk)-1]->time - $trk[0]->time;
        $this->speed = $this->distance / $this->total_time_taken;

        $this->track = $trk;

        unset($children);
        unset($xml);

        $this->is_loaded = True;

        $tmp_dir = $CI->config->item('tmp_directory');
        @file_put_contents("{$tmp_dir}/{$this->file}.info", serialize(array(
                                'file_ver' => TMP_FILE_VER,
                                'date' => $this->date,
                                'total_time_taken' => $this->total_time_taken,
                                'speed' => $this->speed,
                                'top_speed' => $this->top_speed,
                                'distance' => $this->distance,
                                'coordinates' => count($this->track),
                                )));
        return;
    }

    /**
     * Textual representation of this track
     *
     **/
    public function __tostring()
    {
        $str  = '<pre>';
        $str .= "Filename: {$this->filename}\n";
        $str .= "Date: ".date('r', $this->date)."\n";
        $str .= "Speed: ".number_format($this->speed * 3.6,4)." km/h\n";
        $str .= "Distance: ".number_format($this->distance / 1000, 4)." km\n";

        $str .= "Total Time Taken: ".date_diff($this->total_time_taken)."\n";
        $str .= "Trackpoints: ".count($this->track)."\n";

        $str .= "First Trackpoint:\n".print_r($this->track[0], True);
        $str .= "Last Trackpoint:\n".print_r($this->track[count($this->track)-1], True);
        $str .= '</pre>';

        return ($str);
    }
}





/**
 * GPSParser represents all the tracks found in the configured directory
 *
 *
 */
class GPSParser 
{
    /**
     * List with recognized file types
     * FIXME: needs extending
     */
    private $recognized_files = array(
            /* 'type as recognized by gpsbabel' => 'file extension' */
            'nmea' => 'nmea',
            'gpx' => 'gpx',
        );
        
    /**
     * List with files, containing names and dates
     */
    public $file_list = array();

    /**
     * Containts all GPSTrack Objects
     */
    public $tracks = array();
    
    /**
     * Constructor that reads the directory given in appconfig.php
     *
     * @return bool
     **/
    public function __construct()
    {
        try
        {
            $this->load_directory();
        }
        catch (Exception $e)
        {
            $CI =& get_instance();
            show_error($e->getMessage(), 500);
            die();
        }
    }

    /**
     * Case insensitive array_search
     * from: http://de2.php.net/manual/en/function.array-search.php#96533
     *
     * @param mixed $needle
     * @param array $haystack
     * @return mixed      
     **/
    private function array_nsearch($needle, array $haystack) 
    {
        $it = new IteratorIterator(new ArrayIterator($haystack));
        foreach($it as $key => $val) 
        {
            if(strcasecmp($val,$needle) === 0) 
            {
                return $key;
            }
        }
        return false;
    }   
    
    /**
     * Checks if $filename is a file that we understand
     *
     * @param mixed $filename
     * @return mixed
     */
    private function _is_gps_file($filename)
    {
        preg_match('|^.*\.([\w]+)$|', $filename, $matches);
                
        if (isset($matches[1]) && ($type = $this->array_nsearch($matches[1], $this->recognized_files)))
        {
            return $type;
        }
        return False;
    }
    
    /**
     * Loads source directory of gps files and loads the basic stuff
     *
     */
    public function load_directory()
    {
        $CI =& get_instance();

        $directory = $CI->config->item('gps_directory');

        if ($dh = @opendir($directory)) 
        {
            while (($file = readdir($dh)) !== false) 
            {
                if (!is_file("{$directory}/{$file}"))
                {
                    continue;
                }
                else if (!($type = $this->_is_gps_file("{$directory}/{$file}")))
                {
                    continue;
                }
                $this->tracks[md5($file)] =& new GPS_Track($directory, $file, $type);
                $this->file_list[md5($file)] = array(
                                                'directory' => $directory,
                                                'file' => $file,
                                                'type' => $type,
                                                'date' => $this->tracks[md5($file)]->date,
                                                'name' => $this->tracks[md5($file)]->name,
                                                'track' => Null,
                                                );
            }
            closedir($dh);
        }
        else
        {
            throw new Exception("GPS Directory could not be read: '{$directory}'. Check your appconfig.php.");
        }
        masort($this->file_list, array('date'));
    }

    /**
     * Return the list of gps files
     *
     */
    public function get_files($offset = 0, $amount = Null)
    {
        return (array_slice($this->file_list, $offset, $amount));
    }

    /**
     * Return $file, or False if not actually found
     *
     * @param string Name of the file
     */
     public function get($name, $do_load = True)
     {
        if (isset($this->file_list[$name]))
        {
            $file = $this->file_list[$name];
            if (!isset($this->tracks[$name]))
            {
                $this->tracks[$name] =& new GPS_Track($file['directory'], $file['file'], $file['type']);
            }

            if ($this->tracks[$name]->is_loaded == False && $do_load == True)
            {
                $this->tracks[$name]->load();
            }
            return $this->tracks[$name];
        }
        throw new Exception("File does not exist: {$name}.");
     }
}

?>
