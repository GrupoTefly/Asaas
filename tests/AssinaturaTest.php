<?php

use PHPUnit\Framework\Attributes\Depends;
use Grupo\Tefly\Cliente;
use Grupo\Tefly\Assinatura;

class AssinaturaTest extends BaseTest
{
    private static ?string $customerId = null;
    private Assinatura $assinatura;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        if (self::$conn === null) return;

        $res = (new Cliente(self::$conn))->create([
            'name'    => 'Cliente Teste Assinatura',
            'cpfCnpj' => '52998224725',
            'email'   => 'phpunit.assinatura@teste.com',
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
        $this->assinatura = new Assinatura(self::$conn);
    }

    public function testGetAll()
    {
        $res = $this->assinatura->getAll();

        $this->assertIsObject($res);
        $this->assertObjectNotHasProperty('errors', $res);
        $this->assertObjectHasProperty('data', $res);
        $this->assertIsArray($res->data);
    }

    public function testGetByCustomer()
    {
        $res = $this->assinatura->getByCustomer(self::$customerId);

        $this->assertIsObject($res);
        $this->assertObjectNotHasProperty('errors', $res);
        $this->assertObjectHasProperty('data', $res);
    }

    public function testCreate(): string
    {
        $res = $this->assinatura->create([
            'customer'    => self::$customerId,
            'billingType' => 'BOLETO',
            'value'       => 29.90,
            'nextDueDate' => '2026-12-31',
            'cycle'       => 'MONTHLY',
            'description' => 'Assinatura Teste PHPUnit',
        ]);

        $this->assertIsObject($res);
        $this->assertObjectNotHasProperty('errors', $res);
        $this->assertObjectHasProperty('id', $res);
        $this->assertEquals('ACTIVE', $res->status);

        return $res->id;
    }

    #[Depends('testCreate')]
    public function testGetById(string $id): string
    {
        $res = $this->assinatura->getById($id);

        $this->assertIsObject($res);
        $this->assertObjectHasProperty('id', $res);
        $this->assertEquals($id, $res->id);

        return $id;
    }

    #[Depends('testGetById')]
    public function testGetByPayment(string $id): string
    {
        $res = $this->assinatura->getByPayment($id);

        $this->assertIsObject($res);
        $this->assertObjectHasProperty('data', $res);
        $this->assertIsArray($res->data);

        return $id;
    }

    #[Depends('testGetByPayment')]
    public function testUpdate(string $id): string
    {
        $res = $this->assinatura->update($id, [
            'value' => 49.90,
        ]);

        $this->assertIsObject($res);
        $this->assertObjectNotHasProperty('errors', $res);
        $this->assertEquals(49.90, $res->value);

        return $id;
    }

    #[Depends('testUpdate')]
    public function testDelete(string $id)
    {
        $res = $this->assinatura->delete($id);

        $this->assertIsObject($res);
        $this->assertObjectHasProperty('deleted', $res);
        $this->assertTrue($res->deleted);
    }
}
