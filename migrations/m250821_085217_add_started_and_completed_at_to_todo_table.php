<?php

use yii\db\Migration;

class m250821_085217_add_started_and_completed_at_to_todo_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%todo}}', 'started_at', $this->integer()->null());
        $this->addColumn('{{%todo}}', 'completed_at', $this->integer()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%todo}}', 'started_at');
        $this->dropColumn('{{%todo}}', 'completed_at');
    }

    /*
     * // Use up()/down() to run migration code without a transaction.
     * public function up()
     * {
     *
     * }
     *
     * public function down()
     * {
     *     echo "m250821_085217_add_started_and_completed_at_to_todo_table cannot be reverted.\n";
     *
     *     return false;
     * }
     */
}
