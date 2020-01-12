<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\RegistrationForm */
/* @var $form ActiveForm */
?>
<div class="text-center panel panel-primary">
    <div class="panel-heading"><h1 class="h3 mb-3 font-weight-normal"><?= Html::encode($this->title) ?></h1></div>
    <div class="panel-body">
    <?php $form = ActiveForm::begin([
        'id' => 'sing-up-form',
        'layout' => 'horizontal',
//        'fieldConfig' => [
//            'template' => "{label}\n{input}\n<div class=\"col-lg-8\">{error}</div>",
//            'labelOptions' => ['class' => 'col-md-12'],
//        ],
    ]); ?>

        <?= $form->field($model, 'email')->textInput() ?>
        <?= $form->field($model, 'password')->passwordInput() ?>
        <?= $form->field($model, 'surname')->textInput() ?>
        <?= $form->field($model, 'first_name')->textInput() ?>
        <?= $form->field($model, 'city')->textInput() ?>
        <?= $form->field($model, 'interests')->textarea() ?>
        <?= $form->field($model, 'age')->textInput() ?>
        <?= $form->field($model, 'sex')->dropDownList(
            ['М', 'Ж'],
        ) ?>

        <div class="form-group">
            <?= Html::submitButton('Создать', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>
    </div>
</div><!-- auth-registration -->
