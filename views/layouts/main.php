<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <nav id="w0" class="navbar-fixed-top navbar-dark bg-primary navbar">
        <div class="container">
            <div class="navbar-header">
                <div class="navbar-toggle">
                <?php if(!Yii::$app->user->isGuest): ?>
                <?=
                  Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton(
                    '<i class="fas fa-sign-out-alt fa-2x"></i>',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                ?>
                <?php else: ?>
                    <a href="<?= \yii\helpers\Url::to(['/auth']) ?>" title="Войти"><i class="fa fa-sign-in-alt fa-2x"></i></a>
                <?php endif; ?>
                </div>
                <a class="navbar-brand" href="/">Social-Web</a>
            </div>
            <div id="w0-collapse" class="navbar-collapse collapse" aria-expanded="false" style="height: 1px;">
                <ul id="w1" class="navbar-nav navbar-right nav">

                    <li>
                        <?php if(!Yii::$app->user->isGuest): ?>
                        <?=
                          Html::beginForm(['/site/logout'], 'post')
                        . Html::submitButton(
                            '<i class="fas fa-sign-out-alt fa-2x"></i> (' . Yii::$app->user->identity->email . ')',
                            ['class' => 'btn btn-link logout']
                        )
                        . Html::endForm()
                        ?>
                        <?php else: ?>
                            <a href="<?= \yii\helpers\Url::to(['/auth']) ?>" title="Войти"><i class="fa fa-sign-in-alt fa-2x"></i></a>
                        <?php endif; ?>
                    </li>
                </ul>
            </div>

        </div>
    </nav>

    <div class="container">
        <?php if(!Yii::$app->user->isGuest): ?>
        <div class="panel">
            <a href="<?= \yii\helpers\Url::to(['/site/page', 'id' => Yii::$app->user->getId()]) ?>" >Моя страница</a>
            <span>|</span>
            <a href="<?= \yii\helpers\Url::to(['/site/news', 'id' => Yii::$app->user->getId()]) ?>" >Новости</a>
        </div>
        <?php endif; ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="text-center">&copy; My Company <?= date('Y') ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
