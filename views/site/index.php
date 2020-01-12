<?php

/* @var $this yii\web\View
 * @var $model \app\models\User
 */

use yii\helpers\Html; ?>

<div>
    <p>Ваш Email: <strong><?= Html::encode($model->email) ?></strong></p><hr>
    <p>Ваша Фамилия: <strong ><?= Html::encode($model->surname) ?></strong></p><hr>
    <p>Ваше Имя: <strong ><?= Html::encode($model->first_name) ?></strong></p><hr>
    <p>Ваш Город: <strong ><?= Html::encode($model->city) ?></strong></p><hr>
    <p>Ваш Интересы: <strong ><?= Html::encode($model->interests) ?></strong></p><hr>
    <p>Ваши Возраст: <strong ><?= Html::encode($model->age) ?></strong></p><hr>
    <p>Ваш Пол: <strong><?= Html::encode($model->sex) ? 'Мужской' : 'Женский' ?></strong></p>
</div>
