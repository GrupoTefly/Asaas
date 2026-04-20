<?php

use PHPUnit\Framework\Attributes\Depends;
use Grupo\Tefly\Cliente;

class ClienteTest extends BaseTest
{
    private Cliente $cliente;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cliente = new Cliente(self::$conn);
    }

    public function testGetAll()
    {
        $res = $this->cliente->getAll();

        $this->assertIsObject($res);
        $this->assertObjectNotHasProperty('errors', $res);
        $this->assertObjectHasProperty('data', $res);
        $this->assertIsArray($res->data);
    }

    public function testCreate(): string
    {
        $res = $this->cliente->create([
            'name'    => 'Cliente Teste PHPUnit',
            'cpfCnpj' => '529.982.247-25',
            'email'   => 'phpunit.cliente@teste.com',
        ]);

        $this->assertIsObject($res);
        $this->assertObjectNotHasProperty('errors', $res);
        $this->assertObjectHasProperty('id', $res);
        $this->assertEquals('Cliente Teste PHPUnit', $res->name);

        return $res->id;
    }

    #[Depends('testCreate')]
    public function testGetById(string $id): string
    {
        $res = $this->cliente->getById($id);

        $this->assertIsObject($res);
        $this->assertObjectHasProperty('id', $res);
        $this->assertEquals($id, $res->id);

        return $id;
    }

    #[Depends('testGetById')]
    public function testUpdate(string $id): string
    {
        $res = $this->cliente->update($id, [
            'name'    => 'Cliente Teste Atualizado',
            'cpfCnpj' => '529.982.247-25',
        ]);

        $this->assertIsObject($res);
        $this->assertObjectNotHasProperty('errors', $res);
        $this->assertEquals('Cliente Teste Atualizado', $res->name);

        return $id;
    }

    #[Depends('testUpdate')]
    public function testDelete(string $id)
    {
        $res = $this->cliente->delete($id);

        $this->assertIsObject($res);
        $this->assertObjectHasProperty('deleted', $res);
        $this->assertTrue($res->deleted);
    }
}
