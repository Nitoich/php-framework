<?php

namespace Framework\Http;

class Response implements Interfaces\IResponse
{
    protected int $statusCode = 200;

    public function __construct(
        protected mixed $data = null
    ) {}

    public function getData(): mixed
    {
        return $this->data;
    }

    public function json(mixed $data = null): static
    {
        header('Content-Type: application/json');
        if(empty($data)) {
            $data = $this->data;
        }

        $this->data = json_encode($data);
        return $this;
    }

    public function setStatusCode(int $status): static
    {
        $this->statusCode = $status;
        return $this;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}