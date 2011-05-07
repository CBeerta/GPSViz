
<div id="paginate">
    <?php if ($offset >= $per_page): ?>
    <a id="prev" href="<?php echo url_for("/". ($offset - $per_page) );?>">&lt; Previous</a>
    <?php else:?>
    Previous
    <?php endif; ?>

    <?php if ($offset <= ($total_rows - $per_page)): ?>
    <a id="next" href="<?php echo url_for("/". ($offset + $per_page) );?>">Next &gt;</a>
    <?php else:?>
    Next
    <?php endif; ?>
</div>
<?php foreach ($file_list as $k => $v): ?>
    <p id="heading">
        <small id="date"><?php echo date('F j, Y, G:i', $v['date']); ?></small>
        File: <a href="<?php echo url_for("/track/index/".$k);?>" title="<?php echo $v['name'];?>"><?php echo $v['name'];?></a>
    </p>
    <div id="info_snippet" name="<?php echo $k; ?>"></div>
<?php endforeach; ?>


