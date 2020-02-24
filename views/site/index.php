<?php

/* @var $this yii\web\View
 * @var $pages array
 */

use yii\helpers\Html; ?>

<?php foreach ($pages as $key => $page): ?>
<?php if (($key % 3) == 0):?>
<div class="row">
<?php endif; ?>
    <div class="col-sm-3 pageuser text-center">
        <img src="/img/avatar_2x.png" class="img-thumbnail"/>
        <p><?= $page['first_name'] . ' ' . $page['surname'] ?></p>
        <p>Город: <?= $page['city'] ?></p>
        <div>
            <a href="#" onclick="return false;" class="subscribe" data-id="<?= $page['id'] ?>"><i class="fa fa-plus-square"></i>Подписаться</a>
        </div>
        <a class="btn btn-success" href="<?= \yii\helpers\Url::to(['site/page', 'id' => $page['id']]) ?>">Просмотр</a>
    </div>
<?php if (($key % 3) == 2):?>
</div>
<?php endif; ?>
<?php endforeach; ?>
