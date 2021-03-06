<?php

namespace app\controllers;

use app\lib\helpers\DateHelper;
use app\models\User;
use app\lib\services\PagesUserService;
use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
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
                        'actions' => ['logout', 'chat'],
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
        $pages = (new PagesUserService())->findPagesByQueryUnionFromTarantool( 10, $q);
        return $this->render('index_tarantool', compact('pages'));
    }

    public function actionPage($id)
    {
        if ($news = (new PagesUserService())->savePosts(Yii::$app->request->post(), $id)) {
            return $news;
        }

        $model = User::findIdentity($id);
        $messages = (new Query())
            ->select('message')
            ->from('posts')
            ->where(['iduser' => $id])
            ->orderBy('idposts DESC')
            ->all()
        ;
        return $this->render('page', compact('model', 'messages'));
    }

    public function actionNews($id)
    {
        $news = (new PagesUserService())->renderNews($id, $this);
        return $this->render('news', compact('news'));
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionSubscribe()
    {
        $id = Yii::$app->request->post('id');
        return $this->asJson(['yes' => $id]);
    }

    public function actionChat()
    {
        $id = Yii::$app->request->get('id');
        $author = Yii::$app->getUser()->getIdentity();
        $recipient = User::findIdentity($id);

        $messages = (new PagesUserService())->findChatMessagesWithRecipient($id);
        $usersInfo = json_encode([
            "author" => [
                "id" => $author->getId(),
                "first_name" => $author->first_name,
                "surname" => $author->surname
            ],
            "recipient" => [
                "id" => $recipient->getId(),
                "first_name" => $recipient->first_name,
                "surname" => $recipient->surname
            ]
        ], JSON_UNESCAPED_UNICODE);
        return $this->render('chat', compact('messages', 'author', 'recipient', 'usersInfo'));
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
