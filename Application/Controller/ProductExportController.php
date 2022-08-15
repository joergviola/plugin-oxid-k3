<?php

namespace FATCHIP\ObjectCodeK3\Application\Controller;

use FATCHIP\ObjectCodeK3\Core\Connector;
use FATCHIP\ObjectCodeK3\Core\Export\Export;
use FATCHIP\ObjectCodeK3\Core\Logger;
use FATCHIP\ObjectCodeK3\Core\Output;
use FATCHIP\ObjectCodeK3\Core\Validation;
use OxidEsales\Eshop\Core\Registry;

class ProductExportController extends \OxidEsales\Eshop\Application\Controller\FrontendController
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
        if (!Registry::getConfig()->getConfigParam('blFcObjectCodeK3Active')) {
            Registry::get(Output::class)->json(['message' => 'Module not active.'], 503);
        }

        $this->outputExport();
    }

    /**
     * Output export
     *
     * @return void
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseErrorException
     */
    protected function outputExport()
    {
        try {
            $connector = oxNew(Connector::class);
            $this->validateSecret($connector);
            $service = oxNew(Export::class);
            Registry::get(Output::class)->json($service->getProducts(), 200);
        } catch (\Exception $e) {
            Registry::get(Logger::class)->error('Could not export articles', [
                $e->getMessage(),
                __METHOD__
            ]);
        }
        exit;
    }

    /**
     * Validate secret
     *
     * @param $connector
     * @return void
     */
    protected function validateSecret($connector)
    {
        if (!Registry::get(Validation::class)->isSecretInHeader($connector->getSecret())) {
            Registry::get(Logger::class)->error('Secret is not valid',
                [__METHOD__]);
            Registry::get(Output::class)->json(['message' => 'Secret is not valid.'], 403);
        }
    }
}