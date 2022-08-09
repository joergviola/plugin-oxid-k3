<?php

namespace FATCHIP\K3\Core;

class Request
{
    protected string $configurationEndpoint = 'https://k3-api.objectcode.de/api/v1.0/cfg/{cfg}/shop';

    protected string $orderEndpoint = 'https://k3-api.objectcode.de/api/v1.0/app/{code}/cfg/{cfg}/save-only';


    public function getConfiguration($configurationId) {


    }
}