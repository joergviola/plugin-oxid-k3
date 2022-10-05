<?php

namespace ObjectCode\K3\Application\Controller;

use ObjectCode\K3\Core\Connector;
use ObjectCode\K3\Core\Logger;
use ObjectCode\K3\Core\Output;
use ObjectCode\K3\Core\Validation;
use OxidEsales\Eshop\Core\Registry;

class ConnectorController extends \OxidEsales\Eshop\Application\Controller\FrontendController
{
    /**
     * render
     *
     * @return string|void
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseErrorException
     */
    public function render()
    {
        if (!Registry::getConfig()->getConfigParam('blOcK3Active')) {
            Registry::get(Output::class)->json(['message' => 'Module not active.'], 503);
        }
        $this->connectShop();
    }

    /**
     * Output export
     *
     * @return void
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseErrorException
     */
    protected function connectShop()
    {
        try {
            list($token,$secret) = $this->getTokenAndSecret();
            $this->validateParameters($token, $secret);
            $connector = oxNew(Connector::class);
            $this->validateSecret($connector);
            $connector->setToken($token);
            $connector->setSecret($secret);
            if ($connector->save()) {
                $output = [
                    'cart' => html_entity_decode($connector->getBasketUrl()),
                    'articles' => html_entity_decode($connector->getProductExportUrl())
                ];
                Registry::get(Output::class)->json($output);
            }
        } catch (\Exception $e) {
            Registry::get(Logger::class)->error('Could not connect to shop', [
                $e->getMessage(),
                __METHOD__
            ]);
            Registry::get(Output::class)->json(['message' => 'Could not connect to shop.'], 500);
        }
        exit;
    }

    /**
     * Validate parameters
     *
     * @param $token
     * @param $secret
     * @return void
     */
    protected function validateParameters($token = null, $secret = null)
    {
        if (!$token || !$secret) {
            Registry::get(Logger::class)->error('No token or secret given', [
                __METHOD__
            ]);
            Registry::get(Output::class)->json(['message' => 'Token or secret not found in request.'], 401);
        }
    }

    /**
     * Validate secret
     *
     * @param $connector
     * @return void
     */
    protected function validateSecret($connector)
    {
        if ($connector->getSecret() && !Registry::get(Validation::class)->isSecretInHeader($connector->getSecret())) {
            Registry::get(Logger::class)->error('Secret is not valid',
                [__METHOD__]);
            Registry::get(Output::class)->json(['message' => 'Secret is not valid.'], 403);
        }
    }

    /**
     * Return token and secret
     *
     * @return array
     */
    protected function getTokenAndSecret(): array
    {
        $request = json_decode(file_get_contents("php://input"), true);
        $token = $request['token'];
        $secret = $request['secret'];
        return [
            $token,
            $secret
        ];
    }
}
