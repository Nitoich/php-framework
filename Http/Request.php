<?php

namespace Framework\Http;

class Request implements Interfaces\IRequest
{
    protected array $data = [];
    protected string $method = '';
    protected array $headers = [];

    public function __construct()
    {
        $this->method = strtolower($_SERVER['REQUEST_METHOD']);
        $this->data = $_REQUEST;
        $this->headers = getallheaders();
        if($this->method != 'get')
        {
            $this->data = array_merge($this->data, json_decode(file_get_contents('php://input'), true));
        }
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getHeader(string $name): string
    {
        // TODO: Implement getHeader() method.
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getCookies(): array
    {
        // TODO: Implement getCookies() method.
    }

    public function getCookie(string $name): string
    {
        // TODO: Implement getCookie() method.
    }

    public function all(): array
    {
        return $this->data;
    }

    public function __get(string $name)
    {
        return $this->data[$name];
    }
}