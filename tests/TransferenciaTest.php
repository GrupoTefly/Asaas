<?php

use Grupo\Tefly\Transferencia;

class TransferenciaTest extends BaseTest
{
    private Transferencia $transferencia;

    protected function setUp(): void
    {
        parent::setUp();
        $this->transferencia = new Transferencia(self::$conn);
    }

    public function testGetAll()
    {
        $res = $this->transferencia->getAll();

        $this->assertIsObject($res);
        $this->assertObjectNotHasProperty('errors', $res);
        $this->assertObjectHasProperty('data', $res);
        $this->assertIsArray($res->data);
    }

    public function testConsultaSaldo()
    {
        $res = $this->transferencia->consultaSaldo();

        $this->assertIsObject($res);
        $this->assertObjectNotHasProperty('errors', $res);
        $this->assertObjectHasProperty('totalBalance', $res);
    }

    public function testConsultaWalletId()
    {
        $res = $this->transferencia->consultaWalletId();

        $this->assertIsObject($res);
        $this->assertObjectNotHasProperty('errors', $res);
        $this->assertObjectHasProperty('data', $res);
        $this->assertIsArray($res->data);
        $this->assertNotEmpty($res->data);
        $this->assertObjectHasProperty('id', $res->data[0]);
    }
}
