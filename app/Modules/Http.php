<?php

namespace App\Modules;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class Http
{

    public static function request(): Request
    {
        return new Request(
            $_GET,
            $_POST,
            [],
            $_COOKIE,
            $_FILES,
            $_SERVER
        );
    }

    public static function redirect(string $url): Response
    {
        $response = new RedirectResponse($url);

        return $response->send();
    }

    public static function responseJson(array $data, int $status = 200): Response
    {
        $response = new Response();
        $response->setContent(json_encode($data));
        $response->setStatusCode($status);
        $response->headers->set('Content-Type', 'application/json');


        return $response->send();
    }
}