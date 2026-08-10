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
     *     - contractId (string, max 35): Identificador do objeto da autorização
     *     - startDate (date): Início da vigência
     *     - customerId (string): Identificador único do cliente
     *     - immediateQrCode (array): Cobrança imediata atrelada à ativação
     *   Campos opcionais (com valor padrão):
     *     - frequency (string): WEEKLY | MONTHLY | QUARTERLY | SEMIANNUALLY | ANNUALLY (padrão: MONTHLY)
     *     - paymentCreationMode (string): SUBSCRIPTION | MANUAL (padrão: SUBSCRIPTION)
     *     - retryPolicy (string): (padrão: ALLOW_THREE_IN_SEVEN_DAYS)
     *   Demais campos opcionais:
     *     - finishDate (date): Fim da vigência (omitir para prazo indeterminado)
     *     - value (float): Valor fixo para cobranças periódicas
     *     - description (string, max 35): Descrição
     *     - minLimitValue (float): Valor mínimo (apenas para autorizações sem valor fixo)
     *
     * ATENÇÃO paymentCreationMode:
     *   - SUBSCRIPTION (padrão): o Asaas cria as cobranças automaticamente, na frequência definida.
     *   - MANUAL: o Asaas NÃO cria nenhuma cobrança sozinho. A autorização fica ativa esperando,
     *     e é responsabilidade da aplicação chamar createPayment() a cada ciclo de cobrança.
     *     Se optar por MANUAL, é necessário ter um controle próprio (ex: rotina/cron) para
     *     disparar as cobranças, senão a autorização nunca será cobrada.
     *   Não existe endpoint de atualização de autorização — para trocar o paymentCreationMode
     *   (ou frequency/value/retryPolicy) de uma autorização já criada é preciso cancel() + create()
     *   de uma nova (o que exige nova aprovação do pagador no banco).
     *
     * @return array
     */
    public function create(array $dados)
    {
        $padroes = [
            'frequency' => 'MONTHLY',
            'paymentCreationMode' => 'SUBSCRIPTION',
            'retryPolicy' => 'ALLOW_THREE_IN_SEVEN_DAYS',
        ];

        $dados = array_merge($padroes, $dados);

        return $this->http->post('/pix/automatic/authorizations', $dados);
    }

    /**
     * Cria uma cobrança vinculada a uma autorização de Pix Automático.
     *
     * Necessário apenas quando a autorização foi criada com
     * paymentCreationMode = MANUAL, já que nesse modo o Asaas não gera
     * as cobranças sozinho — é a aplicação quem deve chamar este método
     * a cada ciclo de cobrança (ex: via cron), senão a autorização nunca
     * é cobrada. Quando paymentCreationMode = SUBSCRIPTION, o Asaas já
     * cria as cobranças automaticamente e este método não deve ser usado.
     *
     * @param string $autorizacaoId Identificador único da autorização de Pix Automático
     * @param array $dados
     *   Campos obrigatórios:
     *     - customer (string): Identificador único do cliente
     *     - value (float): Valor da cobrança
     *     - dueDate (date): Data de vencimento
     *   Campos opcionais:
     *     - billingType (string): padrão PIX
     *     - description (string): Descrição
     * @return array
     */
    public function createPayment($autorizacaoId, array $dados)
    {
        $padroes = [
            'billingType' => 'PIX',
        ];

        $dados = array_merge($padroes, $dados);
        $dados['pixAutomaticAuthorizationId'] = $autorizacaoId;

        return $this->http->post('/payments', $dados);
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