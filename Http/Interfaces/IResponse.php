<?php

namespace Framework\Http\Interfaces;

interface IResponse
{
    public function __construct(mixed $data = null);
    public function getData(): mixed;
    public function json(mixed $data = null): static;
    public function setStatusCode(int $status): static;
}