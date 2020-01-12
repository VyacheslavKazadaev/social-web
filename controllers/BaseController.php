<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;

class BaseController extends Controller
{

    public function beforeAction($action)
    {
        if (Yii::$app->user->isGuest) {
            return $this->goLogin();
        }

        return parent::beforeAction($action);
    }

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
        return Yii::$app->getResponse()->redirect(Yii::$app->getHomeUrl() . 'auth');
    }
}
