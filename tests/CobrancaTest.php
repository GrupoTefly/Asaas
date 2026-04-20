<?php

use PHPUnit\Framework\Attributes\Depends;
use Grupo\Tefly\Cliente;
use Grupo\Tefly\Cobranca;

class CobrancaTest extends BaseTest
{
    private static ?string $customerId = null;
    private Cobranca $cobranca;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        if (self::$conn === null) return;

        $res = (new Cliente(self::$conn))->create([
            'name'    => 'Cliente Teste Cobranca',
            'cpfCnpj' => '52998224725',
            'email'   => 'phpunit.cobranca@teste.com',
        ]);
        self::$customerId = $res->id ?? null;
    }

    public static function tearDownAfterClass(): void
    {
        if (self::$conn !== null && self::$customerId) {
            (new Cliente(self::$conn))->delete(self::$customerId);
        }
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->cobranca = new Cobranca(self::$conn);
    }

    public function testGetAll()
    {
        $res = $this->cobranca->getAll();

        $this->assertIsObject($res);
        $this->assertObjectNotHasProperty('errors', $res);
        $this->assertObjectHasProperty('data', $res);
        $this->assertIsArray($res->data);
    }

    public function testGetByCustomer()
    {
        $res = $this->cobranca->getByCustomer(self::$customerId);

        $this->assertIsObject($res);
        $this->assertObjectNotHasProperty('errors', $res);
        $this->assertObjectHasProperty('data', $res);
    }

    public function testCreate(): string
    {
        $res = $this->cobranca->create([
            'customer'    => self::$customerId,
            'billingType' => 'BOLETO',
            'value'       => 10.00,
            'dueDate'     => '2026-12-31',
            'description' => 'Cobrança Teste PHPUnit',
        ]);

        $this->assertIsObject($res);
        $this->assertObjectNotHasProperty('errors', $res);
        $this->assertObjectHasProperty('id', $res);
        $this->assertEquals(10.00, $res->value);

        return $res->id;
    }

    #[Depends('testCreate')]
    public function testGetById(string $id): string
    {
        $res = $this->cobranca->getById($id);

        $this->assertIsObject($res);
        $this->assertObjectHasProperty('id', $res);
        $this->assertEquals($id, $res->id);

        return $id;
    }

    #[Depends('testGetById')]
    public function testUpdate(string $id): string
    {
        $res = $this->cobranca->update($id, [
            'value'       => 20.00,
            'description' => 'Cobrança Atualizada',
        ]);

        $this->assertIsObject($res);
        $this->assertObjectNotHasProperty('errors', $res);
        $this->assertEquals(20.00, $res->value);

        return $id;
    }

    #[Depends('testUpdate')]
    public function testDelete(string $id)
    {
        $res = $this->cobranca->delete($id);

        $this->assertIsObject($res);
        $this->assertObjectHasProperty('deleted', $res);
        $this->assertTrue($res->deleted);
    }
}
