<?php

use yii\db\Migration;

/**
 * Handles the creation of table `document`.
 */
class m181122_142911_create_document_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('document', [
            'id' => $this->string(50)->notNull(),
            'status' => $this->string(10)->notNull()->defaultValue('draft'),
            'payload' => $this->string()->notNull(),
            'createAt' => $this->string(30)->notNull(),
            'modifyAt' => $this->string(30)
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('document');
    }
}

