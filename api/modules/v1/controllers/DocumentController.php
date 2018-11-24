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

       /*return $this->render('index.php', [
           'documents' => $documents,
           'pagination' => $pagination,
       ]);*/
      return $documents;
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
     * Публикация документа
     * @param $id
     */
    public function actionPublication($id){
        $document = Document::findOne($id);
        if($document->status === self::STATUS_DRAFT){
            $document->status = self::STATUS_PUBLISHED;
        }elseif ($document->status === self::STATUS_PUBLISHED){
            return 'Ошибка при публикации документа';
        }
        return $document->save(false) ? 'Документ опубликован' : 'Ошибка при публикации документа';
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
            'publication' => ['post']
        ];
    }
}

