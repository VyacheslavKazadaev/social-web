<?php

/* @var $this yii\web\View
 * @var $model
 */
?>

<div>
    <p>Ваш Email: <strong><?= $model->email ?></strong></p><hr>
    <p>Ваша Фамилия: <strong ><?= $model->surname ?></strong></p><hr>
    <p>Ваше Имя: <strong ><?= $model->first_name ?></strong></p><hr>
    <p>Ваш Город: <strong ><?= $model->city ?></strong></p><hr>
    <p>Ваш Интересы: <strong ><?= $model->interests ?></strong></p><hr>
    <p>Ваши Возраст: <strong ><?= $model->age ?></strong></p><hr>
    <p>Ваш Пол: <strong><?= $model->sex ? 'Мужской' : 'Женский' ?></strong></p>
</div>
