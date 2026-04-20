<?php

namespace Grupo\Tefly;

class CobrancaResumo {
    public $http;

    public function __construct(Connection $connection)
    {
        $this->http = $connection;
    }

    public function getAll(array $filtros = [])
    {
        $filtro = '';
        if ($filtros) {
            $filtro = '?' . http_build_query($filtros);
        }
        return $this->http->get('/lean/payments' . $filtro);
    }

    public function create(array $dados)
    {
        return $this->http->post('/lean/payments', $dados);
    }

    public function createCartaoCredito(array $dados)
    {
        return $this->http->post('/lean/payments/creditCard', $dados);
    }

    public function capturePreAutorizacao($id)
    {
        return $this->http->post("/lean/payments/{$id}/captureAuthorizedPayment", []);
    }

    public function getById($id)
    {
        return $this->http->get("/lean/payments/{$id}");
    }

    public function update($id, array $dados)
    {
        return $this->http->put("/lean/payments/{$id}", $dados);
    }

    public function delete($id)
    {
        return $this->http->get("/lean/payments/{$id}", '', 'DELETE');
    }

    public function restore($id)
    {
        return $this->http->post("/lean/payments/{$id}/restore", []);
    }

    public function receiveInCash($id, array $dados)
    {
        return $this->http->post("/lean/payments/{$id}/receiveInCash", $dados);
    }

    public function undoReceivedInCash($id)
    {
        return $this->http->post("/lean/payments/{$id}/undoReceivedInCash", []);
    }
}
