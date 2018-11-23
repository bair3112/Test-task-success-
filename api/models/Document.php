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
    public const STATUS_DRAFT = 'draft';
    public const STATUS_PUBLISHED = 'published';

    /**
     * Document constructor.
     * @param array $config
     * @throws \Exception
     */

   /* public function __construct(array $config = [])
    {
        $this->id = (Uuid::uuid4())->toString();
        $this->status = self::STATUS_DRAFT;
        $this->payload = "{ }";
        $this->createAt = (new \DateTime())->format(\DATE_ATOM);
        if($this->status == self::STATUS_PUBLISHED){
            $this->modifyAt = (new \DateTime())->format(\DATE_ATOM);
        }
        else if($this->status == self::STATUS_DRAFT){
            $this->modifyAt = null;
        }
        parent::__construct($config);
    }*/

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
            [['payload'], 'string', 'max' => 255],
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
