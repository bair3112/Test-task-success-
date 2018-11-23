<?php

namespace api\modules\v1\controllers;

use app\models\Document;
use Ramsey\Uuid\Uuid;
use yii\rest\ActiveController;
use yii\rest\Controller;
/**
 * Default controller for the `v1` module
 */

class DocumentController extends Controller
{
    public const STATUS_DRAFT = 'draft';
    public const STATUS_PUBLISHED = 'published';

   public function actionIndex(){
        return 'api';
    }

    public function actionCreate(){
       $model = new Document();
       $model->id = (Uuid::uuid4()->toString());
       $model->status = self::STATUS_DRAFT;
       $model->payload = "{ }";
       $model->createAt = (new \DateTime())->format(DATE_ATOM);
        if($model->status == self::STATUS_PUBLISHED){
            $model->modifyAt = (new \DateTime())->format(\DATE_ATOM);
        }
        else if($model->status == self::STATUS_DRAFT){
            $model->modifyAt = null;
        }
         $model->save(false);
        return $model;
    }

    protected function verbs()
    {
        return [
            'index' => ['get'],
            'create' => ['post'],
        ];
    }
}

