<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <title>GPSViz</title>
    <!-- css --> 
    <link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.5.2/build/reset-fonts-grids/reset-fonts-grids.css"> 
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>files/style.css"> 
    <!-- js --> 
    <script type="text/javascript" src="<?php echo base_url();?>files/jquery.js"></script>
    <!-- fancybox -->
    <script type="text/javascript" src="<?php echo base_url();?>files/fancybox/jquery.fancybox-1.2.5.pack.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>files/fancybox/jquery.fancybox-1.2.5.css">
    <!-- google map -->
    <script type="text/javascript" src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=<?php echo $google_maps_key;?>&amp;hl=de"></script>
</head>
<body onload="initialize()" onunload="GUnload()">

<div id="doc4"> <!-- artificially limit myself to 1000 width -->
    <div id="hd"><!-- Header -->
        <ul>
            <?php foreach ($file_list as $k => $v):?>
            <li <?php if ($active == $k): ?>class="active"<?php endif; ?>>
                <a href="<?php echo site_url("track/index/{$offset}/{$k}/");?>" title="<?php echo $v->file;?>"><?php echo date('D, d M Y', $v->date); ?></a>
            </li>
            <?php endforeach; ?>
        </ul>
        <p><?php echo $this->pagination->create_links(); ?></p>
    </div>

    <div id="bd"><!-- Body -->
        <?php echo $content; ?>
   </div>

    <div id="ft"><!-- Footer -->
        <p>GPSViz &copy; 2010 Claus Beerta &lt;<a href="mailto:claus@beerta.de">claus@beerta.de</a>&gt;</p>
    </div>

</body>
</html>
