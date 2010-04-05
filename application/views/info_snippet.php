<div id="info_snippet">
    <?php if (isset($draw_chart)): ?>
    <div>
        <div id="combined_chart" class="chart" style="width:500px;height:200px;"></div>
    </div>
    <script id="source" language="javascript" type="text/javascript">

    $(function() {
        var chart_options = { 
                points: { show: false},
                lines: { show: true },
                grid: { backgroundColor: '#fafafa' },
                legend: { position: 'ne' },
                y2axis: { tickFormatter: function(v, axis) { return v.toFixed(axis.tickDecimals) + "m" }},
                xaxis: { tickFormatter: function(v, axis) { return v.toFixed(axis.tickDecimals) + "km" }},
            };

        $.plot($("#combined_chart"), 
            [ { data: <?php echo $height_chart_data; ?>, label: "Elevation", yaxis: 2 },
              { data: <?php echo $speed_chart_data; ?>, label: "Speed" } ],
            chart_options);
    });
    </script>    
    <?php endif; ?>

    <div>
    <table id="info_table">
        <!-- <caption>Information on Track</caption> -->
        <tr><th>Started:</th><td><?php echo date('r', $gps->date); ?></td></tr>
        <tr><th>Distance:</th><td><?php echo number_format($gps->distance / 1000, 2); ?> km</td></tr>
        <tr><th>Speed:</th><td><?php echo number_format($gps->speed * 3.6, 2); ?> km/h</td></tr>
        <tr><th>Pace:</th><td><?php echo number_format(60 / ($gps->speed * 3.6), 0); ?> min/km</td></tr>
        <tr><th>Top Speed:</th><td><?php echo number_format($gps->top_speed * 3.6, 2); ?> km/h</td></tr>
        <tr><th>Time Taken:</th><td><?php echo date_diff($gps->total_time_taken); ?></td></tr>
        <tr><th>Coordinates:</th><td><?php echo $gps->coordinates; ?></td></tr>
    </table>
    <div>

</div>


