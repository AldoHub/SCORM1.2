<!--what to display -->
<div class="selects">

<label><?= $block->question()->html(); ?></label>
<?php 
$data = $block->answers()->html();
$d = $block->answers()->toArray();
$decoded = json_decode($block->answers()->toArray()['answers'],1);
$_arr = [];
foreach($decoded as $val){
    //echo $val["content"]["answer"];
    array_push( $_arr , $val["content"]["answer"]);
}

?>
<select>
    <option value="">-----</option>
    <?php foreach($_arr as $item): ?>
    <option value="<?= $item ?>"
    <?php if(trim($block->correctAnswerValue()) == trim($item)): ?>
            data-src-points='<?= $block->correctAnswerScore()->html();?>' 
        <?php else: ?>
            data-src-points='<?= $block->wrongAnswerScore()->html();?>' 
        <?php endif; ?>
    ><?= $item ?></option>
  

    <?php endforeach; ?>
</select>
<span class="error"></span>
</div>

