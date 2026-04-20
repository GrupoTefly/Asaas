<?php

use PHPUnit\Framework\Attributes\Depends;
use Grupo\Tefly\MinhaConta;

class MinhaContaTest extends BaseTest
{
    private MinhaConta $conta;

    protected function setUp(): void
    {
        parent::setUp();
        $this->conta = new MinhaConta(self::$conn);
    }

    public function testGet()
    {
        $res = $this->conta->get();

        $this->assertIsObject($res);
        $this->assertObjectNotHasProperty('errors', $res);
        $this->assertObjectHasProperty('name', $res);
        $this->assertObjectHasProperty('email', $res);
        $this->assertObjectHasProperty('cpfCnpj', $res);
    }

    public function testUpdate()
    {
        $atual = $this->conta->get();
        $this->assertObjectNotHasProperty('errors', $atual);

        $res = $this->conta->update([
            'personType'    => $atual->personType,
            'cpfCnpj'       => $atual->cpfCnpj,
            'companyType'   => $atual->companyType,
            'companyName'   => $atual->companyName,
            'email'         => $atual->email,
            'mobilePhone'   => $atual->mobilePhone,
            'postalCode'    => $atual->postalCode,
            'address'       => $atual->address,
            'addressNumber' => $atual->addressNumber,
            'province'      => $atual->province,
            'incomeValue'   => $atual->incomeValue,
        ]);

        $this->assertIsObject($res);
        $this->assertObjectNotHasProperty('errors', $res);
        $this->assertObjectHasProperty('name', $res);
    }

    public function testGetConf()
    {
        $res = $this->conta->getConf();

        $this->assertIsObject($res);
        $this->assertObjectNotHasProperty('errors', $res);
        $this->assertObjectHasProperty('logoBackgroundColor', $res);
        $this->assertObjectHasProperty('infoBackgroundColor', $res);
        $this->assertObjectHasProperty('fontColor', $res);
        $this->assertObjectHasProperty('enabled', $res);
    }

    public function testSaveConf()
    {
        $res = $this->conta->saveConf([
            'logoBackgroundColor' => '#000000',
            'infoBackgroundColor' => '#ffffff',
            'fontColor'           => '#333333',
            'enabled'             => true,
        ]);

        $this->assertIsObject($res);
        $this->assertObjectNotHasProperty('errors', $res);
        $this->assertObjectHasProperty('logoBackgroundColor', $res);
    }

    public function testGetNumeroConta()
    {
        $res = $this->conta->getNumeroConta();

        $this->assertIsObject($res);
        $this->assertObjectNotHasProperty('errors', $res);
        $this->assertObjectHasProperty('agency', $res);
        $this->assertObjectHasProperty('account', $res);
        $this->assertObjectHasProperty('accountDigit', $res);
    }

    public function testGetTaxas()
    {
        $res = $this->conta->getTaxas();

        $this->assertIsObject($res);
        $this->assertObjectNotHasProperty('errors', $res);
        $this->assertObjectHasProperty('payment', $res);
    }

    public function testGetStatus()
    {
        $res = $this->conta->getStatus();

        $this->assertIsObject($res);
        $this->assertObjectNotHasProperty('errors', $res);
        $this->assertObjectHasProperty('general', $res);
    }

    public function testGetWalletId()
    {
        $res = $this->conta->getWalletId();

        $this->assertIsObject($res);
        $this->assertObjectNotHasProperty('errors', $res);
        $this->assertObjectHasProperty('data', $res);
        $this->assertIsArray($res->data);
        $this->assertNotEmpty($res->data);
        $this->assertObjectHasProperty('id', $res->data[0]);
    }
}
