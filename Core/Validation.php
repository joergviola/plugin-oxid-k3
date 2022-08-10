<?php

namespace FATCHIP\ObjectCodeK3\Core;

class Validation
{
    /**
     * Check is secret is valid and in header
     *
     * @param $secret
     * @return bool
     */
    public function isSecretInHeader($secret): bool
    {
        $headers = $this->getHeaders();
        if ((!isset($headers['X-Secret']) || $secret != $headers['X-Secret'])) {
            return false;
        }
        return true;
    }

    /**
     * Return headers
     *
     * @return array|false
     */
    protected function getHeaders()
    {
        return getallheaders();
    }
}