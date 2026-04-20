<?php

use PHPUnit\Framework\TestCase;
use Grupo\Tefly\Connection;

abstract class BaseTest extends TestCase
{
    protected static ?Connection $conn = null;
    private static bool $envLoaded = false;

    public static function setUpBeforeClass(): void
    {
        if (!self::$envLoaded) {
            $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../..');
            $dotenv->safeLoad();
            self::$envLoaded = true;
        }
        if (self::$conn === null) {
            $key = $_ENV['ASAAS_KEY'] ?? '';
            if ($key) {
                self::$conn = new Connection($key, 'homologacao');
            }
        }
    }

    protected function setUp(): void
    {
        if (self::$conn === null) {
            $this->markTestSkipped('ASAAS_KEY não definida.');
        }
    }
}
