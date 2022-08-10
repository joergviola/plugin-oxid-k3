<?php

namespace FATCHIP\ObjectCodeK3\Core;

use OxidEsales\Eshop\Core\Registry;

class Request
{
    /**
     * Configuration endpoint
     *
     * @var string
     */
    protected string $configurationEndpoint = 'https://k3-api.objectcode.de/api/v1.0/cfg/{cfg}/shop';

    /**
     * Order endpoint
     *
     * @var string
     */
    protected string $orderEndpoint = 'https://k3-api.objectcode.de/api/v1.0/app/{code}/cfg/{cfg}/save-only';

    /**
     * Return token
     *
     * @return string
     */
    protected function getToken(): string
    {
        $connector = oxNew(Connector::class);
        return $connector->getToken();
    }

    /**
     * Return configuration response
     *
     * @param $configurationId
     * @return bool|string
     * @throws \Exception
     */
    public function getConfiguration($configurationId)
    {
        $url = str_replace('{cfg}', $configurationId, $this->configurationEndpoint);
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 5);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
        $header = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->getToken()
        ];
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        $result = curl_exec($curl);
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if (!$statusCode) {
            throw new \Exception('HTTP Status Code not 200 OK: ' . $statusCode);
        }

        if (!$result) {
            throw new \Exception('Configuration response is empty');
        }

        return $result;
    }

    /**
     * Set configuration as ordered
     *
     * @param $configurationId
     * @param $appCode
     * @return bool|string
     * @throws \Exception
     */
    public function setOrdered($configurationId, $appCode)
    {
        $url = str_replace(['{code}', '{cfg}'], [$appCode, $configurationId], $this->orderEndpoint);
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 5);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
        $header = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->getToken()
        ];
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query([
            'state' => 'ordered',
        ]));
        $result = curl_exec($curl);
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if (!$statusCode) {
            throw new \Exception('HTTP Status Code not 200 OK: ' . $statusCode);
        }

        if (!$result) {
            throw new \Exception('Configuration response is empty');
        }

        return $result;
    }
}