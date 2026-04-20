<?php

use PHPUnit\Framework\Attributes\Depends;
use Grupo\Tefly\LinkPagamento;

class LinkPagamentoTest extends BaseTest
{
    private LinkPagamento $link;

    protected function setUp(): void
    {
        parent::setUp();
        $this->link = new LinkPagamento(self::$conn);
    }

    public function testGetAll()
    {
        $res = $this->link->getAll();

        $this->assertIsObject($res);
        $this->assertObjectNotHasProperty('errors', $res);
        $this->assertObjectHasProperty('data', $res);
        $this->assertIsArray($res->data);
    }

    public function testCreate(): string
    {
        $res = $this->link->create([
            'name'               => 'Link Teste PHPUnit',
            'billingType'        => 'UNDEFINED',
            'chargeType'         => 'DETACHED',
            'value'              => 50.00,
            'dueDateLimitDays'   => 5,
            'description'        => 'Link criado em teste automatizado',
        ]);

        $this->assertIsObject($res);
        $this->assertObjectNotHasProperty('errors', $res);
        $this->assertObjectHasProperty('id', $res);

        return $res->id;
    }

    #[Depends('testCreate')]
    public function testGetById(string $id): string
    {
        $res = $this->link->getById($id);

        $this->assertIsObject($res);
        $this->assertObjectHasProperty('id', $res);
        $this->assertEquals($id, $res->id);

        return $id;
    }

    #[Depends('testGetById')]
    public function testUpdate(string $id): string
    {
        $res = $this->link->update($id, [
            'name'  => 'Link Teste Atualizado',
            'value' => 75.00,
        ]);

        $this->assertIsObject($res);
        $this->assertObjectNotHasProperty('errors', $res);

        return $id;
    }

    #[Depends('testUpdate')]
    public function testDelete(string $id)
    {
        $res = $this->link->delete($id);

        $this->assertIsObject($res);
        $this->assertObjectHasProperty('deleted', $res);
        $this->assertTrue($res->deleted);
    }
}
