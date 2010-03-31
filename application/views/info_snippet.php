<div id="info_snippet">

    <div class="height_chart">
        <img src="<?php echo site_url("main/chart/{$active}");?>" width="500" height="200">
    </div>

    <div>
    <table id="info_table">
        <caption>Information on Track</caption>
        <tr><th>Started:</th><td><?php echo date('r', $gps->track[0]->time); ?></td></tr>
        <tr><th>Distance:</th><td><?php echo number_format($gps->distance / 1000, 2); ?> km</td></tr>
        <tr><th>Speed:</th><td><?php echo number_format($gps->speed * 3.6, 2); ?> km/h</td></tr>
        <tr><th>Time Taken:</th><td><?php echo date_diff($gps->total_time_taken); ?></td></tr>
    </table>
    <div>

</div>

