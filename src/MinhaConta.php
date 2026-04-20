<?php

namespace Grupo\Tefly;
class MinhaConta {

    public $http;

    public function __construct(Connection $connection)
    {
        $this->http = $connection;
    }

    public function get()
    {
        return $this->http->get('/myAccount/commercialInfo/');
    }

    public function update(array $params)
    {
        return $this->http->post('/myAccount/commercialInfo/', $params);
    }

    public function getConf()
    {
        return $this->http->get('/myAccount/paymentCheckoutConfig/');
    }

    public function saveConf(array $params)
    {
        return $this->http->post('/myAccount/paymentCheckoutConfig/', $params, true);
    }

    public function getNumeroConta()
    {
        return $this->http->get('/myAccount/accountNumber');
    }

    public function getTaxas()
    {
        return $this->http->get('/myAccount/fees/');
    }

    public function getStatus()
    {
        return $this->http->get('/myAccount/status/');
    }

    public function getWalletId()
    {
        return $this->http->get('/wallets/');
    }

    public function deleteBaas($removeReason = null)
    {
        $query = $removeReason ? '?removeReason=' . urlencode($removeReason) : '';
        return $this->http->get('/myAccount/' . $query, false, 'DELETE');
    }

}
