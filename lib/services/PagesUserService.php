<?php

namespace app\lib\services;

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

    public function renderNews(int $id, Controller $controller)
    {

        $messages = (new Query())->select(['p.message', 'u.surname', 'u.first_name'])
            ->from([ 'p' => 'posts'])
            ->innerJoin(['s' => 'subscriber'], 'p.iduser = s.iduser')
            ->innerJoin(['u' => 'user'], 'u.id = s.iduser')
            ->where(['s.idsubscriber' => $id])
            ->limit(1000)
            ->all();
        $news = $controller->renderPartial('_news_block', compact('messages'));
        return $news;
    }

}
