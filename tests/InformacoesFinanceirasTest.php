<?php

use Grupo\Tefly\InformacoesFinanceiras;

class InformacoesFinanceirasTest extends BaseTest
{
    private InformacoesFinanceiras $financeiro;

    protected function setUp(): void
    {
        parent::setUp();
        $this->financeiro = new InformacoesFinanceiras(self::$conn);
    }

    public function testSaldo()
    {
        $res = $this->financeiro->saldo();

        $this->assertIsObject($res);
        $this->assertObjectNotHasProperty('errors', $res);
        $this->assertObjectHasProperty('balance', $res);
    }
}
