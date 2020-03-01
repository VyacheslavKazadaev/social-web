<?php
namespace app\queue\jobs;

use app\lib\services\CacheService;
use Yii;
use yii\db\Query;

class AddPostJob extends \yii\base\BaseObject implements \yii\queue\JobInterface
{

    public $message;
    public $idAuthor;

    /**
     * @inheritDoc
     */
    public function execute($queue)
    {
        $user = \app\models\User::findIdentity($this->idAuthor);
        $messages = [['surname' => $user->surname, 'first_name' => $user->first_name, 'message' => $this->message]];
        $news = (new \yii\web\View())->render('//site/_news_block', compact('messages'));

        $subscribers = (new Query())->select(['idsubscriber'])
            ->from('subscriber')
            ->where(['iduser' => $this->idAuthor])
            ->all();
        foreach ($subscribers as $idUser) {
            CacheService::prependNewsToCachesSubscribers($idUser['idsubscriber'], $news);
        }
    }
}
