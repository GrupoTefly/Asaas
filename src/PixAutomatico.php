<?php

namespace Grupo\Tefly;

class PixAutomatico
{
    public $http;

    public function __construct(Connection $connection)
    {
        $this->http = $connection;
    }

    /**
     * Cria uma nova autorização de Pix Automático
     *
     * @param array $dados
     *   Campos obrigatórios:
     *     - frequency (string): WEEKLY | MONTHLY | QUARTERLY | SEMIANNUALLY | ANNUALLY
     *     - contractId (string, max 35): Identificador do objeto da autorização
     *     - startDate (date): Início da vigência
     *     - customerId (string): Identificador único do cliente
     *     - immediateQrCode (array): Cobrança imediata atrelada à ativação
     *   Campos opcionais:
     *     - finishDate (date): Fim da vigência (omitir para prazo indeterminado)
     *     - value (float): Valor fixo para cobranças periódicas
     *     - description (string, max 35): Descrição
     *     - minLimitValue (float): Valor mínimo (apenas para autorizações sem valor fixo)
     * @return array
     */
    public function create(array $dados)
    {
        return $this->http->post('/pix/automatic/authorizations', $dados);
    }

    /**
     * Lista autorizações de Pix Automático
     *
     * @param array $filtros
     *   - offset (int): Elemento inicial da lista
     *   - limit (int, max 100): Número de elementos da lista
     *   - status (string): CREATED | ACTIVE | CANCELLED | REFUSED | EXPIRED
     *   - customerId (string): Filtrar pelo identificador único do cliente
     * @return array
     */
    public function getAll(array $filtros = [])
    {
        $filtro = '';
        if ($filtros) {
            foreach ($filtros as $key => $f) {
                if (!empty($f)) {
                    if ($filtro) {
                        $filtro .= '&';
                    }
                    $filtro .= $key . '=' . $f;
                }
            }
            $filtro = '?' . $filtro;
        }

        return $this->http->get('/pix/automatic/authorizations' . $filtro);
    }

    /**
     * Recupera uma única autorização de Pix Automático
     *
     * @param string $id Identificador único da autorização
     * @return array
     */
    public function getById($id)
    {
        return $this->http->get('/pix/automatic/authorizations/' . $id);
    }

    /**
     * Cancela uma autorização de Pix Automático
     *
     * @param string $id Identificador único da autorização
     * @return array
     */
    public function cancel($id)
    {
        return $this->http->get('/pix/automatic/authorizations/' . $id, '', 'DELETE');
    }

    /**
     * Recupera uma única instrução de pagamento de Pix Automático
     *
     * @param string $id Identificador único da instrução de pagamento
     * @return array
     */
    public function getPaymentInstructionById($id)
    {
        return $this->http->get('/pix/automatic/paymentInstructions/' . $id);
    }

    /**
     * Lista instruções de pagamento de Pix Automático
     *
     * @param array $filtros
     *   - authorizationId (string): Filtrar pelo identificador único da autorização
     *   - customerId (string): Filtrar pelo identificador único do cliente
     *   - paymentId (string): Filtrar pelo identificador único da cobrança
     *   - status (string): AWAITING_REQUEST | SCHEDULED | DONE | CANCELLED | REFUSED
     * @return array
     */
    public function getPaymentInstructions(array $filtros = [])
    {
        $filtro = '';
        if ($filtros) {
            foreach ($filtros as $key => $f) {
                if (!empty($f)) {
                    if ($filtro) {
                        $filtro .= '&';
                    }
                    $filtro .= $key . '=' . $f;
                }
            }
            $filtro = '?' . $filtro;
        }

        return $this->http->get('/pix/automatic/paymentInstructions' . $filtro);
    }
}