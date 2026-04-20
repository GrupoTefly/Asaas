<?php

use PHPUnit\Framework\Attributes\Depends;
use Grupo\Tefly\Cliente;
use Grupo\Tefly\Cobranca;
use Grupo\Tefly\Pix;

class PixTest extends BaseTest
{
    private static ?string $customerId = null;
    private static ?string $cobrancaId = null;
    private Pix $pix;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        if (self::$conn === null) return;

        $cliente = (new Cliente(self::$conn))->create([
            'name'    => 'Cliente Teste Pix',
            'cpfCnpj' => '52998224725',
            'email'   => 'phpunit.pix@teste.com',
        ]);
        self::$customerId = $cliente->id ?? null;

        if (self::$customerId) {
            $cobranca = (new Cobranca(self::$conn))->create([
                'customer'    => self::$customerId,
                'billingType' => 'PIX',
                'value'       => 10.00,
                'dueDate'     => '2026-12-31',
                'description' => 'Cobrança PIX Teste',
            ]);
            self::$cobrancaId = $cobranca->id ?? null;
        }
    }

    public static function tearDownAfterClass(): void
    {
        if (self::$conn === null) return;

        if (self::$cobrancaId) {
            (new Cobranca(self::$conn))->delete(self::$cobrancaId);
        }
        if (self::$customerId) {
            (new Cliente(self::$conn))->delete(self::$customerId);
        }
    }

    protected function setUp(): void
    {
        parent::setUp();
        if (self::$cobrancaId === null) {
            $this->markTestSkipped('Cobrança PIX não foi criada no setup.');
        }
        $this->pix = new Pix(self::$conn);
    }

    public function testGetAll()
    {
        $res = $this->pix->getAll();

        $this->assertIsObject($res);
        $this->assertObjectNotHasProperty('errors', $res);
        $this->assertObjectHasProperty('data', $res);
        $this->assertIsArray($res->data);
    }

    public function testCreate()
    {
        $res = $this->pix->create(self::$cobrancaId);

        $this->assertIsObject($res);
        $this->assertObjectNotHasProperty('errors', $res);
        $this->assertObjectHasProperty('encodedImage', $res);
        $this->assertObjectHasProperty('payload', $res);
    }

    public function testGet()
    {
        $res = $this->pix->get(self::$cobrancaId);

        $this->assertIsObject($res);
        $this->assertObjectNotHasProperty('errors', $res);
        $this->assertObjectHasProperty('encodedImage', $res);
    }
}
