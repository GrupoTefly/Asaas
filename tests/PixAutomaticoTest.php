<?php

use Grupo\Tefly\PixAutomatico;

class PixAutomaticoTest extends BaseTest
{
    private PixAutomatico $pixAutomatico;

    protected function setUp(): void
    {
        parent::setUp();
        $this->pixAutomatico = new PixAutomatico(self::$conn);
    }

    public function testGetAll()
    {
        $res = $this->pixAutomatico->getAll();

        $this->assertIsObject($res);
        $this->assertObjectNotHasProperty('errors', $res);
        $this->assertObjectHasProperty('data', $res);
        $this->assertIsArray($res->data);
    }

    public function testGetPaymentInstructions()
    {
        $res = $this->pixAutomatico->getPaymentInstructions();

        $this->assertIsObject($res);
        $this->assertObjectNotHasProperty('errors', $res);
        $this->assertObjectHasProperty('data', $res);
        $this->assertIsArray($res->data);
    }
}
