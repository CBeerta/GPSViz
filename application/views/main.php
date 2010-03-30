<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <title>GPS-Man</title>
    <!-- css --> 
    <link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.5.2/build/reset-fonts-grids/reset-fonts-grids.css"> 
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>files/style.css"> 
    <!-- js --> 
    <script type="text/javascript" src="<?php echo base_url();?>files/jquery.js"></script>
    <!-- fancybox -->
    <script type="text/javascript" src="<?php echo base_url();?>files/fancybox/jquery.fancybox-1.2.5.pack.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>files/fancybox/jquery.fancybox-1.2.5.css">

    <script type="text/javascript" src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=ABQIAAAAOXwIs0kAMCTT4R_LT2qceBT1J3d04cTIINafQvOpmWXrTarkoRT_B51w-AVaXrCGTxWcK_zP0JiZkw&amp;hl=de"></script>
    <script type="text/javascript">
    function initialize() {
        var map = new GMap2(document.getElementById("map_canvas"));
        map.setMapType(G_NORMAL_MAP);

        // Center on startpoint FIXME: Center maybe? like the midpoint?
        map.setCenter(new GLatLng(<?php echo "{$gps->track[0]->lat}, {$gps->track[0]->lon}";?>), 14);

        // Load controls
        var mapControl = new GMapTypeControl();
        map.addControl(mapControl);
        map.addControl(new GLargeMapControl());

        // Add The route
        var polyline = new GPolyline([
                <?php foreach ($gps->track as $trkpt):?>
                new GLatLng(<?php echo "{$trkpt->lat}, {$trkpt->lon}";?>),
                <?php endforeach; ?>
                ], "#FF0000", 5);
        map.addOverlay(polyline);
    }
    </script>
</head>
<body onload="initialize()" onunload="GUnload()">

<div id="doc4"> <!-- artificially limit myself to 1000 width -->
    <div id="hd"><!-- Header -->
        <ul>
            <?php foreach ($file_list as $file):?>
            <li><a href="<?php echo site_url("main/index/{$offset}/{$file->file}/");?>"><?php echo date('D, d M Y', $file->date); ?></a></li>
            <?php endforeach; ?>
        </ul>
        <p><?php echo $this->pagination->create_links(); ?></p>
    </div>

    <div id="bd"><!-- Body -->
       <div id="map_canvas" style="width: 950px; height: 700px; border: 2px solid #333;"></div>
        <?php echo $gps; ?> 
   </div>

    <div id="ft"><!-- Footer -->

    </div>

</body>
</html>
