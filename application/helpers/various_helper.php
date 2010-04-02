<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Return the Duration from $end to $start
 * 
 * @access public
 * @param  string
 */
function date_diff($end, $start = False) 
{
    $format = '%Y-%m-%d %H:%M:%S';

    if ($start)
    {
        if (is_string($end))
        {
            $end = strtotime($end.' +0000');
        }
        if (is_string($start))
        {
            $start = strtotime($start.' +0000');
        }
        $diff = ($end - $start);
    }
    else
    {
        $diff = $end;
    }

    if ($diff <= 0)
    {
        return("Done: ".date('D, j M Y G:i', $end));
    }
    
    $info = array();
    if ($diff>86400)
    {
        $info['d'] = ($diff - ($diff % 86400)) / 86400;
        $diff = $diff % 86400;
    }
    if ($diff > 3600)
    {
        $info['h'] = ($diff - ($diff % 3600)) / 3600;
        $diff = $diff % 3600;
    }
    if ($diff > 60)
    {
        $info['m'] = ($diff - ($diff % 60)) / 60;
        $diff = $diff % 60;
    }
    $str = '';
    foreach ($info as $k => $v)
    {
        if ($v > 0)
        {
            $str .= "$v$k ";
        }
    }
    return (trim($str));
}


/** 
 * masort: http://de3.php.net/manual/de/function.uasort.php#25882 
 *  
 * Sorts array((array(),array()) by $sortby 
 **/

function masort(&$data, $sortby)
{   
    if(is_array($sortby))   
    {       
        $sortby = join(',',$sortby);    
    }   
    uasort($data,create_function('$a,$b','      
        $skeys = split(\',\',\''.$sortby.'\');      
        foreach($skeys as $key)     
        {           
            if (is_numeric($a[$key]) && is_numeric($b[$key]))           
            {               
                if ($a[$key] == $b[$key])               
                {                   
                    return 0;               
                }               
                return ($a[$key] < $b[$key]) ? 1 : -1;          
            }           
            else if( ($c = strcasecmp($a[$key],$b[$key])) != 0 )            
            {               
                return($c);
            }       
        }       
        return($c); '));
}

?>
