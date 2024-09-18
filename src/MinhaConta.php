<?php

namespace Grupo\Tefly;
class MinhaConta {

    public $http;

    public function __construct(Connection $connection)
    {
        $this->http = $connection;
    }


    public function get(){
        return $this->http->get('/myAccount/');
    }

    public function getConf(){
        return $this->http->get('/myAccount/paymentCheckoutConfig/');
    }

    public function getTaxas()
    {
        return $this->http->get('/myAccount/fees');
    }

}
