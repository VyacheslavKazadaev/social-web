<?php
namespace app\lib\daemons;

use app\models\User;
use consik\yii2websocket\events\WSClientEvent;
use consik\yii2websocket\WebSocketServer;
use Ratchet\ConnectionInterface;
use yii\db\Query;

class PostsServer extends WebSocketServer
{
    public function init()
    {
        parent::init();

        $this->on(self::EVENT_CLIENT_CONNECTED, function(WSClientEvent $e) {
            $e->client->author = null;
        });
    }

    protected function getCommand(ConnectionInterface $from, $msg)
    {
        $request = json_decode($msg, true);
        return !empty($request['action']) ? $request['action'] : parent::getCommand($from, $msg);
    }

    public function commandPosts(ConnectionInterface $client, $msg)
    {
        $request = json_decode($msg, true);

        if (empty($request['message']) || !trim($request['message'])) {
            return;
        }

        $subscribers = (new Query())->select(['idsubscriber'])
            ->from('subscriber')
            ->where(['iduser' => $request['author']])
            ->all();

        $user = User::findIdentity($request['author']);
        $messages = [['surname' => $user->surname, 'first_name' => $user->first_name, 'message' => $request['message']]];
        $news = (new \yii\web\View())->render('//site/_news_block', compact('messages'));
        foreach ($this->clients as $chatClient) {
            if ($chatClient == $client) {
                continue;
            }
            $exist = (new Query())
                ->from('subscriber')
                ->where(['idsubscriber' => $chatClient->author])
                ->where(['iduser' => $request['author']])
                ->count();
            if (!$exist) {
                continue;
            }

            $chatClient->send(json_encode([
                'type' => 'posts',
                'message' => $news
            ]));
        }
    }

    public function commandPing(ConnectionInterface $client, $msg)
    {
        $request = json_decode($msg, true);
        $result = ['message' => 'pong'];

        if (!empty($request['author'])) {
            $client->author = $request['author'];
        }

        $client->send( json_encode($result) );
    }
}
