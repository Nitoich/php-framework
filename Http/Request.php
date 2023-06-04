<?php

namespace Framework\Http;

class Request implements Interfaces\IRequest
{
    protected array $data = [];
    protected string $method = '';
    protected array $headers = [];
    protected array $cookies = [];

    public function __construct()
    {
        $this->method = strtolower($_SERVER['REQUEST_METHOD']);
        $this->data = $_REQUEST;
        $this->headers = getallheaders();
        $this->cookies = $_COOKIE;

        if($this->method !== 'get')
        {
            $content = file_get_contents('php://input');
            if (strpos($this->getContentType(), 'application/json') !== false) {
                $this->data = json_decode($content, true);
            } else {
                parse_str($content, $parsedData);
                $this->data = array_merge($this->data, $parsedData);
            }
        }
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getHeader(string $name): string
    {
        return $this->headers[$name] ?? '';
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getCookies(): array
    {
        return $this->cookies;
    }

    public function getCookie(string $name): string
    {
        return $this->cookies[$name] ?? '';
    }

    public function all(): array
    {
        return $this->data;
    }

    public function __get(string $name)
    {
        return $this->data[$name] ?? null;
    }

    public function __set(string $name, mixed $value)
    {
        $this->data[$name] = $value;
    }

    private function getContentType(): string
    {
        foreach ($this->headers as $name => $value) {
            if (strtolower($name) === 'content-type') {
                return $value;
            }
        }

        return '';
    }
}