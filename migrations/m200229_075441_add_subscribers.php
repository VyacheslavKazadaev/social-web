<?php

use yii\db\Migration;

/**
 * Class m200229_075441_add_subscribers
 */
class m200229_075441_add_subscribers extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $users = (new \yii\db\Query())
            ->select('id')
            ->from('{{%user}}')
            ->limit(1000)
            ->all()
        ;
        $rows = array_map(fn($item) => [$item['id'], 1000001], $users);
        Yii::$app->getDb()->createCommand()
            ->batchInsert('{{%subscriber}}', ['idsubscriber', 'iduser'], $rows)
            ->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->truncateTable('{{%subscriber}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200229_075441_add_subscribers cannot be reverted.\n";

        return false;
    }
    */
}
