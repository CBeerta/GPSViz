<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Directory holding the GPS files
 */
$config['gps_directory'] = "/var/www/gps-files";

/**
 * Where gpsbabel is installed
 */
$config['gpsbabel'] = '/usr/bin/gpsbabel';

/**
 * Your Google Maps api key
 *
 * Found at: http://code.google.com/intl/en/apis/maps/signup.html
 */
$config["google_maps_key"] = 'ABQIAAAAOXwIs0kAMCTT4R_LT2qceBQ2GADgm1ezMFVJ6cO3aik9EkAcBRRENvrod5uF0B-dTwVPde0g0By6Cg';




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




























/**********************************************************************************************************************************************************/
/* You can safely remove this, it's for my development.                                                                                                   */
if ($_SERVER['SERVER_NAME'] == 'anaea.local')
{
    $config["google_maps_key"] = 'ABQIAAAAOXwIs0kAMCTT4R_LT2qceBT1J3d04cTIINafQvOpmWXrTarkoRT_B51w-AVaXrCGTxWcK_zP0JiZkw';
}

?>
