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
    <script type="text/javascript">
    $(document) .ready(function(){
        $("div#info_snippet").each(function(index) {
            var snippet = $(this);
            var name = $(this).attr("name");
            
            $.get('<?php echo site_url("track/ajax/");?>/' + name, function(data) {
                snippet.html(data);
            });
        });
    });
    </script>
</head>
<body>

<div id="doc4">

    <div id="hd"><!-- Header -->
        <h1>GPSViz</h1>
        <p><?php echo $this->pagination->create_links(); ?></p>
    </div>

    <div id="bd"><!-- Body -->
    <?php foreach ($file_list as $k => $v): ?>
        <p id="heading">
            <small id="date"><?php echo date('F j, Y, G:i', $v['date']); ?></small>
            File: <a href="<?php echo site_url("track/index/{$v['offset']}/{$k}/");?>" title="<?php echo $v['name'];?>"><?php echo $v['name'];?></a>
        </p>
        <div id="info_snippet" name="<?php echo $k; ?>">
        </div>
    <?php endforeach; ?>
   </div>

    <div id="ft"><!-- Footer -->
        <p>GPSViz &copy; 2010 Claus Beerta &lt;<a href="mailto:claus@beerta.de">claus@beerta.de</a>&gt;</p>
    </div>

</body>
</html>
