<?php

/* @var $this yii\web\View
 * @var $model \app\models\User
 * @var $messages array
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->registerJsFile('@web/js/page.js', ['depends' => ['yii\web\YiiAsset']]);

?>

<div class="form-group">
    <p>Ваш Email: <strong><?= Html::encode($model->email) ?></strong></p><hr>
    <p>Ваша Фамилия: <strong ><?= Html::encode($model->surname) ?></strong></p><hr>
    <p>Ваше Имя: <strong ><?= Html::encode($model->first_name) ?></strong></p><hr>
    <p>Ваш Город: <strong ><?= Html::encode($model->city) ?></strong></p><hr>
    <p>Ваш Интересы: <strong ><?= Html::encode($model->interests) ?></strong></p><hr>
    <p>Ваши Возраст: <strong ><?= Html::encode($model->age) ?></strong></p><hr>
    <p>Ваш Пол: <strong><?= Html::encode($model->sex) ? 'Мужской' : 'Женский' ?></strong></p>
</div>
<?php if (Yii::$app->getUser()->getId() === $model->getId()): ?>
<form class="row message-edit-block" method="post">
    <input type="hidden" name="author" value="<?= $model->getId() ?>">
    <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
    <label class="col-xs-12">
        <textarea class="form-control" name="message"></textarea>
    </label>
    <div class="col-xs-4 col-sm-2">
    <button class="btn btn-success col-xs-12" name="submit-message" value="1" type="submit">Написать</button>
    </div>
</form>
<?php endif; ?>
<?php foreach ($messages as $item): ?>
<hr/>
<div class="row message-block">
    <div class="col-xs-12 text-justify">
        <?= $item['message'] ?>
    </div>
</div>
<?php endforeach;?>
