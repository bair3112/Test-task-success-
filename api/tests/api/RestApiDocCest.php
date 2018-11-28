<?php
namespace api\tests;
use api\tests\ApiTester;
use api\tests\fixtures\DocumentFixture;

class RestApiDocCest
{
    public function _fixtures(){
        return ['document' => DocumentFixture::class];
    }

    public function _before(ApiTester $I)
    {

    }

    public function _after(ApiTester $I)
    {
    }

    /**
     * Вывод документа по ID
     * @param \api\tests\ApiTester $I
     */
    public function ViewDocumentByIDTest(ApiTester $I)
    {
        $I->sendGET('/api/v1/document/849da756-8b92-4c5d-8d29-9f757ab100c8');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

    /**
     * Вывод несущесвующего докумена
     * @param \api\tests\ApiTester $I
     */
    public function CheckingNonexistentDocumentTest(ApiTester $I)
    {
        $I->sendGET('/api/v1/document/849da756-8b92-4c5d-8d29-9f757ab100c1');
        $I->seeResponseCodeIs(404);
        $I->seeResponseIsJson();
    }

    /**
     * Создание пустого черновика документа
     * @param \api\tests\ApiTester $I
     */
    public function VerifyСreateDraftDocumentTest(ApiTester $I){
        $I->sendPOST('/api/v1/document');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

    /**
     * Редактирование неопубликованного документа. Передан без параметра payload
     * @param \api\tests\ApiTester $I
     */
    public function MissingPayloadBodyTest(ApiTester $I){
        $I->sendPATCH('/api/v1/document/849da756-8b92-4c5d-8d29-9f757ab100c9');
        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
    }

    /**
     * Редактирование неопубликованного документа
     * @param \api\tests\ApiTester $I
     */
    public function DocumentEditingTest(ApiTester $I){
        $I->sendPATCH('/api/v1/document/849da756-8b92-4c5d-8d29-9f757ab100c9', [
            'payload' => 'Hi'
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

    /**
     * Редактирование ОПУБЛИКОВАННОГО документа
     * @param \api\tests\ApiTester $I
     */
    public function EditingAnAlreadyPublishedDocumentTest(ApiTester $I){
        $I->sendPATCH('/api/v1/document/cd4a055f-bc72-4cf9-9d24-25f60f676780', [
            'payload' => 'Hi'
        ]);
        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
    }

    /**
     * Публикация неопубликованного документа
     * @param \api\tests\ApiTester $I
     */
    public function PublishDocumentTest(ApiTester $I){
        $I->sendPOST('/api/v1/document/849da756-8b92-4c5d-8d29-9f757ab100c9/publish');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

    /**
     * Публикация ОПУБЛИКОВАННОГО документа
     * @param \api\tests\ApiTester $I
     */
    public function AlreadyPublishedDocumentTest(ApiTester $I){
        $I->sendPOST('/api/v1/document/cd4a055f-bc72-4cf9-9d24-25f60f676780/publish');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }
}
