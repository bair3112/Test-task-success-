<?php

namespace api\modules\v1\controllers;

use Ramsey\Uuid\Uuid;
use yii\data\Pagination;
use yii\rest\Controller;
use app\models\Document;
/**
 * Default controller for the `v1` module
 */

class DocumentController extends Controller
{
    public const STATUS_DRAFT = 'draft';
    public const STATUS_PUBLISHED = 'published';
    
    
    

   public function actionIndex()
   {
       $query = Document::find();
       $pagination = new Pagination([
           'page' =>  \Yii::$app->request->get('page'),
           'defaultPageSize' => \Yii::$app->request->get('perPage'),
           'totalCount' => $query->count(),
       ]);

       $documets = $query->orderBy('id')
           ->offset($pagination->offset)
           ->limit($pagination->limit)
           ->all();
       return [
           'document' => $documets,
           'pagination' => [
               "page" => $pagination->page,
               "perPage" => $pagination->defaultPageSize,
               "total" => $pagination->totalCount
           ],
       ];
   }

    public function actionCreate(){
       $document = new Document();

       $document->id = (Uuid::uuid4()->toString());
       $document->status = self::STATUS_DRAFT;
       $document->payload = "{ }";
       $document->createAt = (new \DateTime())->format(DATE_ATOM);
        if($document->status == self::STATUS_PUBLISHED){
            $document->modifyAt = (new \DateTime())->format(\DATE_ATOM);
        }
        else if($document->status == self::STATUS_DRAFT){
            $document->modifyAt = null;
        }
         $document->save(false);

        return [
            'document' => $document
        ];
    }

    /**
     * @param $id
     * @return Document|null|string
     */
    public function actionView($id){
        $document = Document::findOne($id);
        if($document){
            return $document;
        }
        elseif (\Yii::$app->response->statusCodeByException){
            throw new yii\web\BadRequestHttpException;
        }
     return  Document::findOne($id) ?  Document::findOne($id) : 'Документ не найден.';
    }


    public function actionPublication($id){
        $document = Document::findOne($id);
        if($document->status === self::STATUS_DRAFT){
            $document->status = self::STATUS_PUBLISHED;
        }elseif ($document->status === self::STATUS_PUBLISHED){
            return 'Ошибка при публикации документа';
        }

        if($document->save(false))
            return [
                'document' => $document
            ];
        else{
            return  'Ошибка при публикации документа';
        }
    }


    public function actionUpdate($id){
        $document = Document::findOne($id);
        if($document->status === self::STATUS_DRAFT){
            $data = \Yii::$app->request->bodyParams['payload'];
            $document->payload = $data;
            $document->modifyAt = (new \DateTime())->format(DATE_ATOM);
            $document->save(false);
            return [
                'document' => $document
            ];
        }else if ($document->status === self::STATUS_PUBLISHED){
            return 'Ошибка редактирования';
        }
        return null;
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
            'publication' => ['post'],
            'update' => ['patch']
        ];
    }
}

