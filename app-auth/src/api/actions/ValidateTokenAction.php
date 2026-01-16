<?php
namespace toubilib\api\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ValidateTokenAction
{
    public function __invoke(Request $request, Response $response): Response
    {
        return $response;
    }
}