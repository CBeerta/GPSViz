<div id="info_snippet">
    <?php if (isset($draw_chart)): ?>
    <div>
        <div id="combined_chart" class="chart" style="width:500px;height:200px;"></div>
    </div>
    <?php endif; ?>

    <div>
    <table id="info_table">
        <!-- <caption>Information on Track</caption> -->
        <tr><th>Started:</th><td><?php echo date('r', $gps->date); ?></td></tr>
        <tr>
            <th>Distance:</th><td><?php echo number_format($gps->distance / 1000, 2); ?> km</td>
            <?php if ($gps->speed != 0): ?>
            <th>Pace:</th><td><?php echo number_format(60 / ($gps->speed * 3.6), 0); ?> min/km</td>
            <?php endif; ?>
        </tr>
        <tr>
            <th>Speed:</th><td><?php echo number_format($gps->speed * 3.6, 2); ?> km/h</td>
            <th>Top Speed:</th><td><?php echo number_format($gps->top_speed * 3.6, 2); ?> km/h</td>
        </tr>
        <tr>
            <th>Time Taken:</th><td><?php echo seconds_to_words($gps->total_time_taken); ?></td>
            <th>Coordinates:</th><td><?php echo $gps->coordinates; ?></td>
        </tr>
    </table>
    <div>

</div>


