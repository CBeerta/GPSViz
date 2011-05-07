<div id="map_snippet">

<script type="text/javascript">

function initialize() {
    var map = new GMap2(document.getElementById("map_canvas"));
    map.setMapType(G_NORMAL_MAP);

    map.enableScrollWheelZoom();

    // Center on startpoint
    map.setCenter(new GLatLng(<?php echo "$midpoint";?>), 10);
    var bds = new GLatLngBounds(new GLatLng(<?php echo "{$gps->boundaries->west}, {$gps->boundaries->south}"; ?>), new GLatLng(<?php echo "{$gps->boundaries->east}, {$gps->boundaries->north}"; ?>))
    map.setZoom(map.getBoundsZoomLevel(bds));

    // Load controls
    var mapControl = new GMapTypeControl();
    map.addControl(mapControl);
    map.addControl(new GSmallMapControl());
    map.addControl(new GScaleControl());

    var mini=new GOverviewMapControl(new GSize(100, 100))
    map.addControl(mini);

    // Add The route
    var polyline = new GPolyline([
            <?php foreach ($gps->track as $trkpt):?>
            new GLatLng(<?php echo "{$trkpt->lat}, {$trkpt->lon}";?>),
            <?php endforeach; ?>
            ], "#FF0000", 5);
    map.addOverlay(polyline);
}
</script>

<h2>File: <?php echo $gps->name; ?></h2>
<div id="map_canvas"></div></div>
