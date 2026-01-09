<?php

interface ClientInterface {
    public function get(string $uri): \Psr\Http\Message\ResponseInterface;
}