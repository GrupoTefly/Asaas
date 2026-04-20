<?php

use Grupo\Tefly\Cliente;
use Grupo\Tefly\Notificacao;

class NotificacaoTest extends BaseTest
{
    private static ?string $customerId = null;
    private Notificacao $notificacao;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        if (self::$conn === null) return;

        $res = (new Cliente(self::$conn))->create([
            'name'    => 'Cliente Teste Notificacao',
            'cpfCnpj' => '52998224725',
            'email'   => 'phpunit.notificacao@teste.com',
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
        $this->notificacao = new Notificacao(self::$conn);
    }

    public function testGetByCustomer()
    {
        $res = $this->notificacao->getByCustomer(self::$customerId);

        $this->assertIsObject($res);
        $this->assertObjectNotHasProperty('errors', $res);
        $this->assertObjectHasProperty('data', $res);
        $this->assertIsArray($res->data);
    }

    public function testGetAll()
    {
        $res = $this->notificacao->getAll();

        $this->assertIsObject($res);
        $this->assertObjectNotHasProperty('errors', $res);
        $this->assertObjectHasProperty('data', $res);
    }
}
