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
        $service = new PagesUserService();
        $pages = $service->findPagesByQuery( 10);
        $subscribers = $service->findSubscribeUsers(Yii::$app->getUser()->getId());
        return $this->render('index', compact('pages', 'subscribers'));
    }

    public function actionSearch($q)
    {
        $pages = (new PagesUserService())->findPagesByQueryUnion( 10, $q);
        return $this->render('index', compact('pages'));
    }

    public function actionPage($id)
    {
        $post = Yii::$app->request->post();
        if (isset($post['submit-message'])) {
            Yii::$app->db->createCommand()->insert('posts', [
                'message' => $post['message'],
                'iduser' => $id,
            ])->execute();

            Yii::$app->response->redirect(Yii::$app->request->getReferrer());
        }

        $model = User::findIdentity($id);
        $messages = (new Query())
            ->select('message')
            ->from('posts')
            ->where(['iduser' => $id])
            ->all()
        ;
        return $this->render('page', compact('model', 'messages'));
    }

    public function actionSubscribe()
    {
        $id = Yii::$app->request->post('id');
        $this->renderContent(json_encode(['yes' => $id]));
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
