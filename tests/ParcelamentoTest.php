<?php

use Grupo\Tefly\Parcelamento;

class ParcelamentoTest extends BaseTest
{
    private Parcelamento $parcelamento;

    protected function setUp(): void
    {
        parent::setUp();
        $this->parcelamento = new Parcelamento(self::$conn);
    }

    public function testGetAll()
    {
        $res = $this->parcelamento->getAll();

        $this->assertIsObject($res);
        $this->assertObjectNotHasProperty('errors', $res);
        $this->assertObjectHasProperty('data', $res);
        $this->assertIsArray($res->data);
    }
}
