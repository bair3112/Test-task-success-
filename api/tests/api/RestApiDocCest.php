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

    public function ViewDocumentListTest(ApiTester $I)
    {
        $I->sendGET('/api/v1/document/849da756-8b92-4c5d-8d29-9f757ab100c8');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }
    public function CheckingNonexistentDocumentTest(ApiTester $I)
    {
        $I->sendGET('/api/v1/document/849da756-8b92-4c5d-8d29-9f757ab100c1');
        $I->seeResponseCodeIs(404);
        $I->seeResponseIsJson();
    }

    public function VerifyСreatedDraftDocumentTest(ApiTester $I){
        $I->sendPOST('/api/v1/document');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }
    public function MissingPayloadBodyTest(ApiTester $I){
        $I->sendPATCH('/api/v1/document/849da756-8b92-4c5d-8d29-9f757ab100c9');
        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
    }
    public function DocumentEditingTest(ApiTester $I){
        $I->sendPATCH('/api/v1/document/849da756-8b92-4c5d-8d29-9f757ab100c9', [
            'payload' => 'Hi'
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }
    public function EditingAnAlreadyPublishedDocumentTest(ApiTester $I){
        $I->sendPATCH('/api/v1/document/cd4a055f-bc72-4cf9-9d24-25f60f676780', [
            'payload' => 'Hi'
        ]);
        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
    }
    public function PublishDocumentTest(ApiTester $I){
        $I->sendPOST('/api/v1/document/849da756-8b92-4c5d-8d29-9f757ab100c9/publish');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }
    public function AlreadyPublishedDocumentTest(ApiTester $I){
        $I->sendPOST('/api/v1/document/cd4a055f-bc72-4cf9-9d24-25f60f676780/publish');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }
}
