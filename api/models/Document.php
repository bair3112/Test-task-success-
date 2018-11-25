<?php

namespace app\models;

use Ramsey\Uuid\Uuid;
use Yii;

/**
 * This is the model class for table "document".
 *
 * @property string $id
 * @property string $status
 * @property string $payload
 * @property string $createAt
 * @property string $modifyAt
 */
class Document extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'document';
    }

    /**
     * {@inheritdoc}
     */
   public function rules()
    {
        return [
            [['id', 'payload', 'createAt'], 'required'],
            [['id'], 'string', 'max' => 50],
            [['status'], 'string', 'max' => 10],
            [['payload'], 'json', ],
            [['createAt', 'modifyAt'], 'string', 'max' => 30],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status' => 'Status',
            'payload' => 'Payload',
            'createAt' => 'Create At',
            'modifyAt' => 'Modify At',
        ];
    }
}
