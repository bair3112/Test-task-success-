<?php

namespace api\modules\v1\controllers;

use Yii;
use Ramsey\Uuid\Uuid;
use yii\data\Pagination;
use yii\rest\Controller;
use api\models\Document;

/**
 * Class DocumentController
 * @package api\modules\v1\controllers
 */
class DocumentController extends Controller
{
    /**
     * Допустимые статусы черновика
     */
    public const STATUS_DRAFT = 'draft';
    public const STATUS_PUBLISHED = 'published';

    /**
     * Вывод списка документов с пагинацией
     * @return array
     */
   public function actionIndex()
   {
       $query = Document::find();
       $contentType = Yii::$app->response->acceptMimeType;
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
           'content-type:' => $contentType,
           'document' => $documets,
           'pagination' => [
               "page" => $pagination->page,
               "perPage" => $pagination->defaultPageSize,
               "total" => $pagination->totalCount
           ],
       ];
   }

    /**
     * Создание пустого черновика документа
     * @return array
     * @throws \Exception
     */
    public function actionCreate(){
       $document = new Document();
       $contentType = Yii::$app->response->acceptMimeType;
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
            'content-type:' => $contentType,
            'document' => $document
        ];
    }

    /**
     * Вывод документа по идентификатору
     * @param $id
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionView($id){
        $document = Document::findOne($id);
        $contentType = Yii::$app->response->acceptMimeType;
        if($document)
            return [
                'content-type:' => $contentType,
                'document' => $document
            ];
        else throw new \yii\web\NotFoundHttpException;
   }

    /**
     * Публикация документа
     * @param $id
     * @return array
     */
    public function actionPublication($id){
        $document = Document::findOne($id);
        $contentType = Yii::$app->response->acceptMimeType;
        if($document->status === self::STATUS_DRAFT){
            $document->status = self::STATUS_PUBLISHED;
            if($document->save(false))
                return [
                    'content-type:' => $contentType,
                    'document' => $document
                ];
        }elseif ($document->status === self::STATUS_PUBLISHED){
            Yii::$app->response->statusCode = 200;
            return [
                'document' => $document
            ];
        }
    }

    /**
     * Редактирование черновика документа
     * @param $id
     * @return array|null
     * @throws \yii\web\BadRequestHttpException
     */
    public function actionUpdate($id){
        $document = Document::findOne($id);
        $contentType = Yii::$app->response->acceptMimeType;
        if($document->status === self::STATUS_DRAFT){
            $data = \Yii::$app->request->getBodyParam('payload');
            if($data === null)
                throw new \yii\web\BadRequestHttpException;
            $document->payload = $data;
            $document->modifyAt = (new \DateTime())->format(DATE_ATOM);
            $document->save(false);
            return [
                'content-type:' => $contentType,
                'document' => $document
            ];
        }else if ($document->status === self::STATUS_PUBLISHED){
            throw new \yii\web\BadRequestHttpException;
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
            'view' => ['get', 'post'],
            'create' => ['post'],
            'publication' => ['post'],
            'update' => ['patch']
        ];
    }
}

