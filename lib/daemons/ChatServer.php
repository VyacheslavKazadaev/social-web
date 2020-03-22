<?php
namespace app\lib\daemons;
use app\lib\services\PagesUserService;
use app\models\User;
use consik\yii2websocket\events\WSClientEvent;
use consik\yii2websocket\events\WSClientMessageEvent;
use consik\yii2websocket\WebSocketServer;
use Ratchet\ConnectionInterface;
use yii\helpers\FileHelper;
use yii\web\View;

class ChatServer extends WebSocketServer
{
    public function init()
    {
        parent::init();

        $this->on(self::EVENT_CLIENT_CONNECTED, function(WSClientEvent $e) {
            $e->client->author = null;
            $e->client->recipient = null;
        });
    }

    protected function getCommand(ConnectionInterface $from, $msg)
    {
        $request = json_decode($msg, true);
        return !empty($request['action']) ? $request['action'] : parent::getCommand($from, $msg);
    }

    public function commandChat(ConnectionInterface $client, $msg)
    {
        $request = json_decode($msg, true);

        if (!empty($request['message']) && $message = trim($request['message']) ) {

            (new PagesUserService())->writeToChat($message, (int)$request['author'], (int)$request['recipient']);
            $author = User::findIdentity($request['author']);
            $recipient = User::findIdentity($request['recipient']);
            $messages = [['message' => $message, 'idauthor' => $author->getId()]];

            $view = new View();
            $handledClients = 0;
            $messageChat = '';
            foreach ($this->clients as $chatClient) {
                if ($handledClients == 2) {
                    break;
                }

                if ($chatClient == $client) {
                    $messageChat = $view->render('//site/_chat_message', compact('messages', 'author', 'recipient'));
                } elseif ($chatClient->author == $recipient->getId()) {
                    $messageChat = $view->render('//site/_chat_message', [
                        'messages'  => $messages,
                        'author'    => $recipient,
                        'recipient' => $author
                    ]);
                }

                if ($chatClient == $client || $chatClient->author == $recipient->getId()) {
                    ++$handledClients;
                    $chatClient->send(json_encode([
                        'type' => 'chat',
                        'message' => $messageChat
                    ]));
                }
            }
        }
    }

    public function commandPing(ConnectionInterface $client, $msg)
    {
        $request = json_decode($msg, true);
        $result = ['message' => 'pong'];

        if (!empty($request['author']) && !empty($request['recipient'])) {
            $client->author = $request['author'];
            $client->recipient = $request['recipient'];
        }

        $client->send( json_encode($result) );
    }
}
