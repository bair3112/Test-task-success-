<?php

namespace api\modules\v1\controllers;

use app\models\Document;
use Ramsey\Uuid\Uuid;
use yii\data\Pagination;
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
       $query = Document::find();

       $pagination = new Pagination([
           'defaultPageSize' => 5,
           'totalCount' => $query->count(),
       ]);

       $documents = $query->orderBy('id')
           ->offset($pagination->offset)
           ->limit($pagination->limit)
           ->all();

      /* return $this->render('index', [
           'document' => $documents,
           'pagination' => $pagination,
       ]);*/
      //return $this->;
    }

    /**
     * Создаётся пустой черновик
     * @return Document
     * @throws \Exception
     */
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

    /**
     * @param $id
     * @return Document|null|string
     */
    public function actionView($id){
     return  Document::findOne($id) ?  Document::findOne($id) : 'Документ не найден.';
    }

    /**
     * @return array
     */
    protected function verbs()
    {
        return [
            'index' => ['get'],
            'view' => ['get'],
            'create' => ['post'],
        ];
    }
}

