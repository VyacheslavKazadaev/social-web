<?php

namespace app\controllers;

use app\models\User;
use app\lib\services\PagesUserService;
use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\Response;
use yii\filters\VerbFilter;

class SiteController extends BaseController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     * @throws \Throwable
     */
    public function actionIndex()
    {
        $pages = (new PagesUserService())->findPagesByQuery( 10);
        return $this->render('index', compact('pages'));
    }

    public function actionSearch($q)
    {
        $pages = (new PagesUserService())->findPagesByQuery( 10, $q);
        return $this->render('index', compact('pages'));
    }

    public function actionPage($id)
    {
        $model = User::findIdentity($id);
        return $this->render('page', compact('model'));
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }
}
