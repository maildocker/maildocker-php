<?php

require_once "Message.php";

class MaildockerClient 
{
    const VERSION = 'v1';

    public
        $apiUser,
        $apiKey,
        $host,
        $port,
        $endpoint,
        $proxy,
        $version = self::VERSION;

    public function __construct($apiKey, $apiSecret, $options = array())
    {
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;

        $this->host = isset($options['host']) ? $options['host'] : 'https://ecentry.io';
        $this->port = isset($options['port']) ? $options['port'] : '443';
        $this->endpoint = isset($options['endpoint']) ? $options['endpoint'] : '/api/maildocker/' . $this->version . '/mail/';
        $this->mail_url = $this->host . ':' . $this->port . $this->endpoint;
        $this->proxy = isset($options['proxy']) ? $options['proxy'] : null;
    }

    protected function build_body(Maildocker\Mail $message)
    {
        $values = array();
        foreach((array) $message as $key => $value)
        {
            if($value) $values[$key] = $value;
        }
        return $values;
    }

    public function send(Maildocker\Mail $message)
    {
        $data = json_encode($this->build_body($message));

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "Authorization: Basic " . base64_encode($this->apiKey . ":" . $this->apiSecret)
        ));
        if($this->proxy)
        {
            curl_setopt($ch, CURLOPT_PROXY, $proxy[0]);
            curl_setopt($ch, CURLOPT_PROXYPORT, $proxy[1]);
        }
        curl_setopt_array($ch, array(
            CURLOPT_URL => $this->mail_url,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $data
        ));
        $response = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);

        return array($info['http_code'], $response);
    }
}
