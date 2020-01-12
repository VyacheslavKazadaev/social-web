<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user}}`.
 */
class m200108_111941_create_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'email' => $this->string()->comment('Email'),
            'password' => $this->string()->comment('Пароль'),
            'auth_key' => $this->string(),
            'access_token' => $this->string(),
            'surname'   => $this->string()->comment('Фамилия'),
            'first_name'   => $this->string()->comment('Имя'),
            'age'         => $this->integer(3)->unsigned()->comment('Возраст'),
            'sex'         => $this->boolean()->comment('Пол'),
            'interests'   => $this->string(500)->comment('Интересы'),
            'city'        => $this->string()->comment('Город'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user}}');
    }
}
