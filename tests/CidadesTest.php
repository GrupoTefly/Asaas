<?php

use Grupo\Tefly\Cidades;

class CidadesTest extends BaseTest
{
    private Cidades $cidades;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cidades = new Cidades(self::$conn);
    }

    public function testGetAll(): int
    {
        $res = $this->cidades->getAll(['name' => 'Gaspar']);

        $this->assertIsObject($res);
        $this->assertObjectNotHasProperty('errors', $res);
        $this->assertObjectHasProperty('data', $res);
        $this->assertIsArray($res->data);
        $this->assertNotEmpty($res->data);

        return $res->data[0]->id;
    }

    #[\PHPUnit\Framework\Attributes\Depends('testGetAll')]
    public function testGetById(int $id)
    {
        $res = $this->cidades->getById($id);

        $this->assertIsObject($res);
        $this->assertObjectNotHasProperty('errors', $res);
        $this->assertObjectHasProperty('id', $res);
        $this->assertObjectHasProperty('name', $res);
        $this->assertObjectHasProperty('state', $res);
        $this->assertEquals($id, $res->id);
    }
}
