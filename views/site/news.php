<?php
/** @var $news string */
?>
<div id="block-news">
    <input id="author" type="hidden" value="<?= Yii::$app->getUser()->getId() ?>">
<?= $news; ?>
</div>

