<?php
/**
 * @var $this \yii\web\View
 * @var $messages array
 * @var $author User
 * @var $recipient User
 */

use app\models\User;

$this->registerJsFile('@web/js/chat.js', ['depends' => ['app\assets\AppAsset']]);
$this->registerCssFile('@web/css/chat.css', ['depends' => ['app\assets\AppAsset']]);
?>
<div class="row chat-panel">
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading" id="accordion">
                <span class="glyphicon glyphicon-comment"></span> Chat With
                <span><?= $recipient->first_name . ' ' . $recipient->surname ?></span>
            </div>
                <div class="panel-body">
                    <ul class="chat">
                        <?= $this->render('_chat_message', compact('messages', 'author', 'recipient')) ?>
                    </ul>
                </div>
                <div class="panel-footer">
                    <form class="input-group" id="form-chat">
                        <input id="btn-input" type="text" class="form-control input-sm" placeholder="Новое сообщение..."
                               data-author="<?= $author->getId() ?>" data-recipient="<?= $recipient->getId() ?>"
                        />
                        <span class="input-group-btn">
                            <button class="btn btn-warning btn-sm" id="btn-chat">
                                Отправить</button>
                        </span>
                    </form>
                </div>
            </div>
    </div>
</div>
