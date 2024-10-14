<!--what to display -->
<div class="textareas">
<label><?= $block->question()->html(); ?></label>
<textarea data-src-score="<?= $block->correctAnswerScore()->html();?>" data-src-score-wrong="<?= $block->wrongAnswerScore()->html();?>" data-src-value="<?= $block->correctAnswerValue()->html() ?>"></textarea>
<span class="error"></span>
</div>

