<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Directory holding the GPS files
 */
$config['gps_directory'] = "/home/claus/www/gps-files";

/**
 * Where gpsbabel is installed
 */
$config['gpsbabel'] = '/usr/bin/gpsbabel';

/**
 * Your Google Maps api key
 *
 * Found at: http://code.google.com/intl/en/apis/maps/signup.html
 */
$config["google_maps_key"] = 'ABQIAAAAOXwIs0kAMCTT4R_LT2qceBT1J3d04cTIINafQvOpmWXrTarkoRT_B51w-AVaXrCGTxWcK_zP0JiZkw';

/**
 * Directory where the webserver can write and read temporary files (should be outside the Document Root)
 */
$config['tmp_directory'] = "/var/tmp";

/**
 * How many tracks to show in the pagination on the detail track view
 */
$config['tracks_per_page'] = 5;

/**
 * How many tracks to show on the home page
 */
$config['tracks_per_page_home'] = 10;

?>
