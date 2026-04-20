<?php

use PHPUnit\Framework\Attributes\Depends;
use Grupo\Tefly\Cliente;
use Grupo\Tefly\CobrancaResumo;

class CobrancaResumoTest extends BaseTest
{
    private static ?string $customerId = null;
    private CobrancaResumo $cobrancaResumo;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        if (self::$conn === null) return;

        $res = (new Cliente(self::$conn))->create([
            'name'    => 'Cliente Teste CobrancaResumo',
            'cpfCnpj' => '52998224725',
            'email'   => 'phpunit.cobrancaresumo@teste.com',
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
        $this->cobrancaResumo = new CobrancaResumo(self::$conn);
    }

    public function testGetAll()
    {
        $res = $this->cobrancaResumo->getAll();

        $this->assertIsObject($res);
        $this->assertObjectNotHasProperty('errors', $res);
        $this->assertObjectHasProperty('data', $res);
        $this->assertIsArray($res->data);
    }

    public function testGetAllWithFilters()
    {
        $res = $this->cobrancaResumo->getAll([
            'limit'  => 10,
            'offset' => 0,
        ]);

        $this->assertIsObject($res);
        $this->assertObjectHasProperty('data', $res);
    }

    public function testCreate(): string
    {
        $res = $this->cobrancaResumo->create([
            'customer'    => self::$customerId,
            'billingType' => 'BOLETO',
            'value'       => 15.00,
            'dueDate'     => '2026-12-31',
            'description' => 'CobrancaResumo Teste PHPUnit',
        ]);

        $this->assertIsObject($res);
        $this->assertObjectNotHasProperty('errors', $res);
        $this->assertObjectHasProperty('id', $res);

        return $res->id;
    }

    #[Depends('testCreate')]
    public function testGetById(string $id): string
    {
        $res = $this->cobrancaResumo->getById($id);

        $this->assertIsObject($res);
        $this->assertObjectHasProperty('id', $res);
        $this->assertEquals($id, $res->id);

        return $id;
    }

    #[Depends('testGetById')]
    public function testUpdate(string $id): string
    {
        $res = $this->cobrancaResumo->update($id, [
            'value'       => 25.00,
            'description' => 'CobrancaResumo Atualizada',
        ]);

        $this->assertIsObject($res);
        $this->assertObjectNotHasProperty('errors', $res);
        $this->assertObjectHasProperty('id', $res);

        return $id;
    }

    #[Depends('testUpdate')]
    public function testReceiveInCash(string $id): string
    {
        $res = $this->cobrancaResumo->receiveInCash($id, [
            'paymentDate'    => date('Y-m-d'),
            'value'          => 25.00,
            'notifyCustomer' => false,
        ]);

        $this->assertIsObject($res);
        $this->assertObjectNotHasProperty('errors', $res);

        return $id;
    }

    #[Depends('testReceiveInCash')]
    public function testUndoReceivedInCash(string $id): string
    {
        $res = $this->cobrancaResumo->undoReceivedInCash($id);

        $this->assertIsObject($res);
        $this->assertObjectNotHasProperty('errors', $res);

        return $id;
    }

    #[Depends('testUndoReceivedInCash')]
    public function testDelete(string $id): string
    {
        $res = $this->cobrancaResumo->delete($id);

        $this->assertIsObject($res);
        $this->assertObjectHasProperty('deleted', $res);
        $this->assertTrue($res->deleted);

        return $id;
    }

    #[Depends('testDelete')]
    public function testRestore(string $id)
    {
        $res = $this->cobrancaResumo->restore($id);

        $this->assertIsObject($res);
        $this->assertObjectNotHasProperty('errors', $res);
        $this->assertObjectHasProperty('id', $res);
    }
}
