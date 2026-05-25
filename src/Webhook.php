<?php

namespace Grupo\Tefly;

class Webhook
{
    public $http;

    public function __construct(Connection $connection)
    {
        $this->http = $connection;
    }

    public function create(array $dados)
    {
        return $this->http->post('/webhooks', $dados);
    }

    public function getAll($offset = 0, $limit = 10)
    {
        return $this->http->get('/webhooks', "?offset={$offset}&limit={$limit}");
    }

    public function getById($id)
    {
        return $this->http->get('/webhooks/' . $id);
    }

    public function update($id, array $dados)
    {
        return $this->http->put('/webhooks/' . $id, $dados);
    }

    public function delete($id)
    {
        return $this->http->get('/webhooks/' . $id, '', 'DELETE');
    }

    public function removeBackoff($id)
    {
        return $this->http->post('/webhooks/' . $id . '/removeBackoff', []);
    }
}
