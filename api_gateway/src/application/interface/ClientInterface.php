<?php

interface ClientInterface {
    public function get(string $uri): \Psr\Http\Message\ResponseInterface;
    public function post(string $uri, array $options = []): \Psr\Http\Message\ResponseInterface;
}