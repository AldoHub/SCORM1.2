<!--what to display -->
<div class="question">

<p><?= $block->question()->html(); ?></p>
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
    <?php foreach($_arr as $item): ?>
    
    <label>
        <input type="radio" name="<?= $block->radiosName()->html(); ?>" value="<?= $item ?>"  
        
        <?php if(trim($block->correctAnswerValue()) == trim($item)): ?>
            data-src-points='<?= $block->correctAnswerScore()->html();?>' 
        <?php else: ?>
            data-src-points='<?= $block->wrongAnswerScore()->html();?>' 
        <?php endif; ?>
        required="required" /> <?= $item ?>
        
    </label>


    <?php endforeach; ?>
    <span class="error"></span>        

</div>

