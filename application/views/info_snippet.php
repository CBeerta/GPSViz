<div id="info_snippet">
    <?php if (isset($draw_chart)): ?>
    <div>
        <div id="combined_chart" class="chart" style="width:500px;height:200px;"></div>
    </div>
    <script id="source" language="javascript" type="text/javascript">

    $(function() {
        var chart_options = { 
                points: { show: true},
                lines: { show: true },
                grid: { backgroundColor: '#fafafa' },
                legend: { position: 'ne' },
            };

        $.plot($("#combined_chart"), 
            [ { data: <?php echo $speed_chart_data; ?>, label: "Speed" },
              { data: <?php echo $height_chart_data; ?>, label: "Height", yaxis: 2 }],
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
        <tr><th>Top Speed:</th><td><?php echo number_format($gps->top_speed * 3.6, 2); ?> km/h</td></tr>
        <tr><th>Time Taken:</th><td><?php echo date_diff($gps->total_time_taken); ?></td></tr>
        <tr><th>Coordinates:</th><td><?php echo $gps->coordinates; ?></td></tr>
    </table>
    <div>

</div>


