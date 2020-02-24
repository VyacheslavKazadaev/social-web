<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%subscriber}}`.
 */
class m200224_083619_create_subscriber_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%subscriber}}', [
            'idsubscriber' => $this->integer()->unsigned()->notNull(),
            'iduser' => $this->integer()->unsigned()->notNull(),
        ]);
        $this->addPrimaryKey('in_primary', 'subscriber', [
            'idsubscriber',
            'iduser',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%subscriber}}');
    }
}
