<?php

namespace app\lib\services;

use app\queue\jobs\AddPostJob;
use PhpAmqpLib\Connection\AMQPStreamConnection;
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

        Yii::$app->queue->push(new AddPostJob([
            'message' => $post['message'],
            'idAuthor' => $id,
        ]));

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
