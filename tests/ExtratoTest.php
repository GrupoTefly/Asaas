<?php

use Grupo\Tefly\Extrato;

class ExtratoTest extends BaseTest
{
    private Extrato $extrato;

    protected function setUp(): void
    {
        parent::setUp();
        $this->extrato = new Extrato(self::$conn);
    }

    public function testGetAll()
    {
        $res = $this->extrato->getAll([
            'startDate'  => date('Y-m-01'),
            'finishDate' => date('Y-m-d'),
        ]);

        $this->assertIsObject($res);
        $this->assertObjectNotHasProperty('errors', $res);
        $this->assertObjectHasProperty('data', $res);
        $this->assertIsArray($res->data);
    }
}
