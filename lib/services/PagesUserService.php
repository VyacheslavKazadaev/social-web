<?php

namespace app\lib\services;

use app\lib\helpers\DateHelper;
use app\models\User;
use Tarantool\Client\Client;
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

    public function findPagesByQueryUnionFromTarantool($length, $q = null): array
    {
        $client = Client::fromDsn('tcp://127.0.0.1:3301');
        $client->evaluate('function select_by_prefix(prefix)
            local ret = {}
            local limit = '.$length.'
            for _, tuple in box.space.UserCache.index.secondary_surname:pairs(prefix, {iterator = \'GE\'}) do
              ' . ( isset($q) ? '
              if string.startswith(tuple[6], prefix, 1, -1) then
                table.insert(ret, tuple)
              end
              ' : '').'
              if table.maxn(ret) >= limit then
                break
              end
            end
            for _, tuple in box.space.UserCache.index.secondary_first_name:pairs(prefix, {iterator = \'GE\'}) do
              ' . ( isset($q) ? '
              if string.startswith(tuple[7], prefix, 1, -1) then
                table.insert(ret, tuple)
              end
              ' : '').'
              if table.maxn(ret) >= limit then
                break
              end
            end
            return ret
        end');
        $result = $client->evaluate('return select_by_prefix(...)', $q)[0];
        return $result;
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

        $user = User::findIdentity($id);
        $messages = [['surname' => $user->surname, 'first_name' => $user->first_name, 'message' => $post['message']]];
        $news = (new \yii\web\View())->render('//site/_news_block', compact('messages'));
        $producer = \Yii::$app->rabbitmq->getProducer('posts');
        $msg = serialize([
            'message' => $news,
            'idAuthor' => $id,
        ]);
        $producer->publish($msg, 'exch_posts', 'post_key');

        return $news;
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

    public function findChatMessagesWithRecipient(int $recipientId)
    {
        $in = [Yii::$app->getUser()->getId(), $recipientId];
        return (new Query())
            ->select(['message', 'idauthor'])
            ->from(['c' => 'chat'])
            ->where([
                'idauthor' => $in,
                'idrecipient' => $in
            ])
            ->limit(30)
            ->orderBy('date_write')
            ->all()
        ;
    }

    public function writeToChat(string $message, int $authorID, int $recipientID)
    {
        Yii::$app->getDb()->createCommand()->insert('chat', [
            'message'     => $message,
            'date_write'  => (new DateHelper())->formatDB(),
            'idauthor'    => $authorID,
            'idrecipient' => $recipientID,
        ])->execute();
    }
}
