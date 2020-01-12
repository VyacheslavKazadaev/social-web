<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;

class BaseController extends Controller
{

    /**
     * @param \yii\base\Action $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        if (Yii::$app->user->isGuest) {
            $this->goLogin();
            return false;
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
