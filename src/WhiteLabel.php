<?php

namespace Grupo\Tefly;

/**
 * WhiteLabel [ MODEL ]
 * Classe responsável pela criação de contas ou subcontas no formato WhiteLabel
 *
 * @copyright (c) year, Érick Dias derickbass4@gmail.com
 */

class WhiteLabel
{
    public $http;

    public function __construct(Connection $connection)
    {
        $this->http = $connection;
    }

    public function create(string $id, array $dados)
    {
        if(is_null($dados)){
            return 'Dados não identificados!';
        }
        if(is_null($id)){
            return 'Id do arquivo não informado!';
        }

        return $this->http->post('/myAccount/documents/'.$id, $dados);
    }
    public function pendentes()
    {
        return $this->http->get('/myAccount/documents');
    }

    public function statusAccount()
    {
        return $this->http->get('/myAccount/status');
    }

}
