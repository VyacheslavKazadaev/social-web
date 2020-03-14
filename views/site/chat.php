<?php
/**
 * @var $this \yii\web\View
 * @var $authUserId integer
 * @var $messages array
 * @var $author User
 * @var $recipient User
 */

use app\models\User;

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
                    <div class="input-group">
                        <input id="btn-input" type="text" class="form-control input-sm" placeholder="Новое сообщение..." />
                        <span class="input-group-btn">
                            <button class="btn btn-warning btn-sm" id="btn-chat">
                                Отправить</button>
                        </span>
                    </div>
                </div>
            </div>
    </div>
</div>
