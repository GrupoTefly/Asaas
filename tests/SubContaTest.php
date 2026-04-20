<?php

use Grupo\Tefly\SubConta;

class SubContaTest extends BaseTest
{
    private SubConta $subConta;

    protected function setUp(): void
    {
        parent::setUp();
        $this->subConta = new SubConta(self::$conn);
    }

    public function testGetAll()
    {
        $res = $this->subConta->getAll();

        $this->assertIsObject($res);
        $this->assertObjectNotHasProperty('errors', $res);
        $this->assertObjectHasProperty('data', $res);
        $this->assertIsArray($res->data);
    }
}
