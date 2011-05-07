<?php

function seconds_to_words($seconds)
{
    if ( $seconds < 0 )
    {
        $seconds *= -1;
        $ret = "-";
    }
    else
        $ret = "";
    

    $hours = intval(intval($seconds) / 3600);
    $ret .= "$hours";
    
    $minutes = bcmod((intval($seconds) / 60),60);
    $ret .= sprintf(":%02d", $minutes);
    
    /*      
    $seconds = bcmod(intval($seconds),60);
    $ret .= ":$seconds";
    */
    return $ret;    
}


