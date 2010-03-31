<div id="map_snippet">

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

<h2>File: <?php echo $gps->file; ?> from <?php echo date('r', $gps->date); ?></h2>
<div id="map_canvas"></div>

</div>
