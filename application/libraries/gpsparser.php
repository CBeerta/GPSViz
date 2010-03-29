<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


// GeoCALC
// http://imaginerc.com/software/GeoCalc/

// GPX Docu
// http://www.topografix.com/gpx_manual.asp#hdop

class GPS_Track
{
    /**
     * Total Distance of Track
     */
    public $distance = Null;
    
    /** 
     * AVG Speed of Track
     */
    public $avg_speed = Null;

    /**
     * Entire Track with all Track Points
     */
    public $track = array();

    /**
     * In What directory is this track stored 
     */
	private $directory = Null;
	
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
     * FIXME: Location of gpsbabel needs to go into a config file
     */	
	private $gpsbabel = '/usr/bin/gpsbabel';
	
	/**
	 * Constructor checks if the gps file is loadable
	 *
	 * @param string $directory 
	 * @param string $file
	 * @param string $type
	 **/
    public function __construct($directory, $file, $type)
    {
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
		$this->stat = (object)stat($this->filename);
		$this->date = date('c', $this->stat->ctime);
    }
	
	public function load()
	{
		$cmd = "{$this->gpsbabel} -i {$this->file_type} -f '{$this->filename}' -c UTF-8 -o gpx,gpxver=1.1 -F -";
		exec($cmd, $output, $ret_var);
		if (!($xml = @simplexml_load_string(implode("\n", $output))))
		{
			throw new Exception("Unable to load {$this->filename}");
		}

        $children = $xml->children();

        if (!isset($children->trk->trkseg->trkpt[0]->time[0]))
        {
            throw new Exception("Not a valid track {$this->filename}");
        }
	   
        // Save the first trackpoints date_time as time for our track
        $this->date = (string) $children->trk->trkseg->trkpt[0]->time[0];

        $track_points =  array();
        foreach ($children->trk->trkseg->trkpt as $trkpt)
        {
            $attr = $trkpt->attributes();
            $track_points[] = (object) array(
                    'lat' => (float) $attr->lat, 
                    'lon' => (float) $attr->lon,
                    'time' => (string) $trkpt->time,
                    'ele' => (float) $trkpt->ele,
                    'fix' => (string) $trkpt->fix,
                    'sat' => (int) $trkpt->sat,
                );
        }

        $this->track = $track_points;

        unset($children);
		unset($xml);
	}
}

class GPSParser 
{
	// FIXME: Needs to be in config or something
	private $directory = "/home/claus/www/gps-files";
	
	private $recognized_files = array(
	        /* 'type as recognized by gpsbabel' => 'file extension' */
			'nmea' => 'nmea',
			'gpx' => 'gpx',
		);
		
	private $file_list = array();
	
	private $tracks = array();
	
	public function __construct()
	{
        print '<pre>';
		
		$this->load_directory();

        print "Before: ".number_format(memory_get_usage())."\n";
		$this->file_list[0]->load();
        print "After: ".number_format(memory_get_usage())."\n";

		print_r($this->file_list[0]);
		return;
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
		if ($dh = opendir($this->directory)) 
		{
			while (($file = readdir($dh)) !== false) 
			{
				if (!is_file("{$this->directory}/{$file}"))
				{
					continue;
				}
				else if (!($type = $this->_is_gps_file("{$this->directory}/{$file}")))
				{
				    continue;
			    }
				$this->file_list[] =& new GPS_Track($this->directory, $file, $type);
			}
            closedir($dh);
        }
    }

}


?>
