<?php
namespace app\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;

class BaseController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function goLogin()
    {
        return Yii::$app->getResponse()->redirect(Url::to(['auth']));
    }
}
