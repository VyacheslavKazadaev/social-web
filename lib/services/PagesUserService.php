<?php

namespace app\lib\services;

use Yii;
use yii\base\BaseObject;
use yii\db\Query;
use yii\web\Controller;

class PagesUserService extends BaseObject
{
    public function findPagesByQuery($length, $q = null): array
    {
        $query = (new Query())->select(['id', 'first_name', 'surname', 'city', 'auth_key'])
            ->from('user');
        if (!empty($q)) {
            $query->where(['or', ['like', 'first_name', $q . '%', false], ['like', 'surname', $q . '%', false]]);
        }
        return $query
            ->limit($length)
            ->orderBy('id')
            ->all();
    }

    public function findPagesByQueryUnion($length, $q): array
    {

        $queryOne = (new Query())->select(['id', 'first_name', 'surname', 'city', 'auth_key'])
            ->from('user')
            ->where(['like', 'first_name', $q . '%', false])

            ->limit($length)
        ;
        $queryTwo = (new Query())->select(['id', 'first_name', 'surname', 'city', 'auth_key'])
            ->from('user')
            ->where(['like', 'surname', $q . '%', false])

            ->limit($length)
        ;
        $queryOne->union($queryTwo);
        return (new Query())->select('*')
            ->from($queryOne)
            ->orderBy('first_name')
            ->all();
    }

    public function findSubscribeUsers($id)
    {
        if (!$id) {
            return null;
        }
        return array_column((new Query())->select(['iduser'])
            ->from('subscriber')
            ->where(['idsubscriber' => $id])
            ->limit(500)
            ->all(), 'iduser');
    }

    public function savePosts($post, $id)
    {
        if (!isset($post['submit-message'])) {
            return false;
        }

        Yii::$app->db->createCommand()->insert('posts', [
            'message' => $post['message'],
            'iduser' => $id,
        ])->execute();

        $user = Yii::$app->getUser()->getIdentity();
        $messages = [['surname' => $user->surname, 'first_name' => $user->first_name, 'message' => $post['message']]];
        $news = $this->renderPartial('_news_block', compact('messages'));

        $subscribers = (new Query())->select(['idsubscriber'])
            ->from('subscriber')
            ->where(['iduser' => $id])
            ->all();
        foreach ($subscribers as $idUser) {
            CacheService::prependNewsToCachesSubscribers($idUser['idsubscriber'], $news);
        }
        return true;
    }

    public function renderNews(int $id, Controller $controller)
    {
        return CacheService::getOrSetNews($id, function () use ($controller, $id) {
            $messages = (new Query())->select(['p.message', 'u.surname', 'u.first_name'])
                ->from([ 'p' => 'posts'])
                ->innerJoin(['s' => 'subscriber'], 'p.iduser = s.iduser')
                ->innerJoin(['u' => 'user'], 'u.id = s.iduser')
                ->where(['s.idsubscriber' => $id])
                ->limit(1000)
                ->orderBy('p.idposts DESC')
                ->all();
            return $controller->renderPartial('_news_block', compact('messages'));
        });
    }

}
