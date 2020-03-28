<?php
namespace app\queue\jobs;

use app\lib\services\CacheService;
use mikemadisonweb\rabbitmq\components\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use yii\db\Query;

class AddPostJob implements ConsumerInterface
{
    /**
     * @param AMQPMessage $msg
     * @return bool
     */
    public function execute(AMQPMessage $msg)
    {
        $data = unserialize($msg->body);

        $subscribers = (new Query())->select(['idsubscriber'])
            ->from('subscriber')
            ->where(['iduser' => $data['idAuthor']])
            ->all();
        foreach ($subscribers as $idUser) {
            CacheService::prependNewsToCachesSubscribers($idUser['idsubscriber'], $data['message']);
        }

        // Apply your business logic here

        return ConsumerInterface::MSG_ACK;
    }
}
