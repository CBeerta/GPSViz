<!doctype html>
<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7 ]> <html class="no-js ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]>    <html class="no-js ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]>    <html class="no-js ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
  <meta charset="utf-8">

  <!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame
       Remove this if you use the .htaccess -->
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

  <title><?php echo $title; ?> - GPSViz</title>
  <meta name="description" content="GPSViz">
  <meta name="author" content="Claus Beerta">

  <!-- Mobile viewport optimized: j.mp/bplateviewport -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Place favicon.ico & apple-touch-icon.png in the root of your domain and delete these references -->
  <link rel="shortcut icon" href="<?php echo url_for('favicon.ico') ;?>">
  <link rel="apple-touch-icon" href="<?php echo url_for('apple-touch-icon.png') ;?>">

  <!-- CSS: implied media="all" -->
  <link rel="stylesheet" href="<?php echo url_for('css/style.css') ;?>?v=2">

  <!-- All JavaScript at the bottom, except for Modernizr which enables HTML5 elements & feature detects -->
  <!--script src="<?php echo url_for('js/libs/modernizr-1.7.min.js') ;?>"></script-->

</head>

<body>

  <div id="container">
    <header>
        <h1><a href="<?php echo url_for('/') ;?>">GPSViz</a></h1><small>Activity Log</small>
        <p><?php #echo $this->pagination->create_links(); ?></p>
    </header>
    <div id="main" role="main">
        <?php echo $content; ?>
    </div>
    <div class="clearfix"/>
    <footer>
        <p>GPSViz &copy; 2010-2011 Claus Beerta &lt;<a href="mailto:claus@beerta.de">claus@beerta.de</a>&gt;</p>
    </footer>
    <br />
  </div> <!--! end of #container -->


  <!-- JavaScript at the bottom for fast page loading -->

  <!-- Grab Google CDN's jQuery, with a protocol relative URL; fall back to local if necessary -->
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.js"></script>
  <script>window.jQuery || document.write("<script src='<?php echo url_for('js/libs/jquery-1.5.1.min.js') ;?>'>\x3C/script>")</script>

  <!-- scripts concatenated and minified via ant build script-->
  <script src="<?php echo url_for('flot/jquery.flot.js') ;?>"></script>
  <script src="<?php echo url_for('js/plugins.js') ;?>"></script>
  <script src="<?php echo url_for('js/script.js') ;?>"></script>
  
  <?php if (isset($google_maps_key)): ?>
  <script type="text/javascript" src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=<?php echo $google_maps_key;?>&amp;hl=en"></script>
  <script>
    $(document).ready(function(){
      initialize();
    });
  </script>
  <?php endif; ?>
  <?php if (isset($height_chart_data) && isset($speed_chart_data)): ?>
  <script>
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
  <!-- end scripts-->

</body>
</html>
