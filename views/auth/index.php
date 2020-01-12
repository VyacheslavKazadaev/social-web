<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login SocialWeb';
?>
<div class="text-center panel panel-primary">
    <div class="panel-heading"><h1 class="h3 mb-3 font-weight-normal"><?= Html::encode($this->title) ?></h1></div>
    <div class="panel-body">
    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'layout' => 'horizontal',
        'options' => [
            'class' => 'form-auth',
        ],
        'fieldConfig' => [
            'template' => "{label}\n{input}\n<div class=\"\">{error}</div>",
            'labelOptions' => ['class' => 'sr-only'],

        ],
    ]); ?>

    <?= $form->field($model, 'email')->textInput(['placeholder' => 'Email', 'autofocus' => true]) ?>

    <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Пароль',]) ?>

    <?= $form->field($model, 'rememberMe')->checkbox([
        'template' => "<div class=\"checkbox mb-3\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
    ]) ?>

    <?= Html::submitButton('Login', ['class' => 'btn btn-lg btn-primary', 'name' => 'login-button']) ?>
    <?php ActiveForm::end(); ?>
    <p class="mt-5 mb-3 text-muted">© 2020</p>
    <hr>
    <?= Html::a('Registration', 'auth/registration', []) ?>
    </div>
</div>
