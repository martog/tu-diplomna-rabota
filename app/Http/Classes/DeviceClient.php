<?php

namespace App\Http\Classes;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Config;
use PhpMqtt\Client\ConnectionSettings;
use PhpMqtt\Client\MQTTClient;
use PHPUnit\Util\Json;

class DeviceClient
{

    private MQTTClient $mqttClient;
    private int $timeout;
    private $response;
    private bool $responseAssocFlag;

    public function __construct($host, $port, $username = null, $password = null, $cleanSessionFlag = true, ConnectionSettings $connectionSettings = null)
    {
        $this->mqttClient = new MQTTClient($host, $port);
        $this->mqttClient->connect($username, $password, $connectionSettings, $cleanSessionFlag);
    }

    public function __destruct()
    {
        // Disconnect and close mqtt connection
        $this->mqttClient->close();
    }

    public function makeRequest($requestTopic, $responseTopic, $message, $responseAssoc = true, $timeout = 15)
    {
        $this->timeout = $timeout;
        $this->responseAssocFlag = $responseAssoc;
        $this->response = [
            "topic" => null,
            "message" => null,
            "code" => null
        ];

        try {
            // Subscribe to responseTopic
            $this->mqttClient->subscribe($responseTopic, \Closure::fromCallable([$this, 'receive']), 0);

            // Publish $message to $requestTopic
            $this->mqttClient->publish($requestTopic, $message);

            // Register LoopEventHandler. This method executes the passed callback function ont each loop() iteration.
            $this->mqttClient->registerLoopEventHandler(\Closure::fromCallable([$this, 'iteration']));

            $this->mqttClient->loop(true);
        } catch (\Exception $e) {
            if ($e->getCode() == 408) {
                $this->response =  [
                    "topic" => $responseTopic,
                    "message" => "Request Timeout",
                    "code" => 408
                ];
            }
        } finally {
            return $this->response;
        }
    }


    private function iteration($mqttc, $elapsedTime)
    {
        // Interrupt mqtt loop if timeout reached.
        if ($elapsedTime >= $this->timeout) {
            $mqttc->interrupt();
            throw new \Exception("Request timed out!", 408);
        }
    }

    private function receive($topic, $message)
    {
        $this->mqttClient->interrupt();
        $this->response = [
            "topic" => $topic,
            "message" => json_decode($message, $this->responseAssocFlag),
            "code" => 200
        ];
    }
}
