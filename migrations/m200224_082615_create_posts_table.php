<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%posts}}`.
 */
class m200224_082615_create_posts_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%posts}}', [
            'idposts' => $this->primaryKey()->unsigned(),
            'message' => $this->text(),
            'iduser' => $this->integer()->unsigned()->notNull(),
        ]);

        $this->createIndex(
            'in_posts$iduser',
            'posts',
            'iduser',
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%posts}}');
    }
}
