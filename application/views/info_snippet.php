<div id="info_snippet">
    <?php if (isset($draw_chart)): ?>
    <div>
        <img src="<?php echo site_url("chart/height/{$active}");?>" width="500" height="200">
        <img src="<?php echo site_url("chart/speed/{$active}");?>" width="500" height="200">
    </div>
    <?php endif; ?>

    <div>
    <table id="info_table">
        <!-- <caption>Information on Track</caption> -->
        <tr><th>Started:</th><td><?php echo date('r', $gps->date); ?></td></tr>
        <tr><th>Distance:</th><td><?php echo number_format($gps->distance / 1000, 2); ?> km</td></tr>
        <tr><th>Speed:</th><td><?php echo number_format($gps->speed * 3.6, 2); ?> km/h</td></tr>
        <tr><th>Top Speed:</th><td><?php echo number_format($gps->top_speed * 3.6, 2); ?> km/h</td></tr>
        <tr><th>Time Taken:</th><td><?php echo date_diff($gps->total_time_taken); ?></td></tr>
        <tr><th>Coordinates:</th><td><?php echo $gps->coordinates; ?></td></tr>
    </table>
    <div>

</div>

