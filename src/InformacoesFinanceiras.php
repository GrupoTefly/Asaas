<?php

namespace Grupo\Tefly;

use Longinus\Asaas\Connection;

class InformacoesFinanceiras {
    public $http;
    protected $cobranca;

    public function __construct(Connection $connection)
    {
        $this->http = $connection;
    }

    // Retorna o saldo da conta
    public function saldo(){
        return $this->http->get('/finance/balance');
    }

}
