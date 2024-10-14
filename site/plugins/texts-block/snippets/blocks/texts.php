<!--what to display -->
<div class="texts">
<label><?= $block->question()->html(); ?></label>
<input type="text" data-src-score="<?= $block->correctAnswerScore()->html();?>" data-src-score-wrong="<?= $block->wrongAnswerScore()->html();?>" data-src-value="<?= $block->correctAnswerValue()->html() ?>" />
<span class="error"></span>
</div>

