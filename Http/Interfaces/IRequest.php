<?php

namespace Framework\Http\Interfaces;

interface IRequest
{
    public function getHeaders(): array;
    public function getHeader(string $name): string;
    public function getMethod(): string;
    public function getCookies(): array;
    public function getCookie(string $name): string;
    public function all(): array;
}