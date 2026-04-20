<?php

use Grupo\Tefly\PagarConta;

class PagarContaTest extends BaseTest
{
    private PagarConta $pagarConta;

    protected function setUp(): void
    {
        parent::setUp();
        $this->pagarConta = new PagarConta(self::$conn);
    }

    public function testGetAll()
    {
        $res = $this->pagarConta->getAll();

        $this->assertIsObject($res);
        $this->assertObjectNotHasProperty('errors', $res);
        $this->assertObjectHasProperty('data', $res);
        $this->assertIsArray($res->data);
    }
}
