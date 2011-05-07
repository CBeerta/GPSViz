
<?php foreach ($file_list as $k => $v): ?>
    <p id="heading">
        <small id="date"><?php echo date('F j, Y, G:i', $v['date']); ?></small>
        File: <a href="<?php echo url_for("/track/index/".$v['offset']."./".$k."/");?>" title="<?php echo $v['name'];?>"><?php echo $v['name'];?></a>
    </p>
    <div id="info_snippet" name="<?php echo $k; ?>">
    </div>
<?php endforeach; ?>

