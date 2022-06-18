<?php

namespace App\Traits;

use GuzzleHttp\Client;

trait ConsumerExternalServices {

    public function makeRequest($method, $requestUrl, $queryParams = [],$formParams = [], $headers = [], $isJson = false){

        $client = new Client([
            'base_uri' => $this->baseUri
        ]);

        if(method_exists($this, 'resolveAuthorizacion')){
            $this->resolveAuthorizacion($queryParams,$formParams,$headers);
        }

        $response = $client->request($method,$requestUrl,[
            $isJson ? "json" : "form_params" => $formParams,
            "headers" => $headers,
            "query" => $queryParams
        ]);

        $response = $response->getBody()->getContents();

        if(method_exists($this, 'decodeResponse')){
            $response = $this->decodeResponse($response);
        }

        return $response;
    }
}
