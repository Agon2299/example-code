<?php

namespace App\Base;

use App\Common\Dictionaries\HttpDictionary;
use Illuminate\Http\Response;


class BaseController
{
    public function success($payload = []): Response
    {
        return \response($payload, 200);
    }

    public function created($payload = []): Response
    {
        return \response($payload, 201);
    }

    public function needsAuthorization($message = ''): Response
    {
        return \response($message ?? HttpDictionary::NEEDS_AUTHORIZATION, 401);
    }

    public function unauthorized(?string $message = ''): Response
    {
        return \response($message ?? HttpDictionary::UNAUTHORIZED, 403);
    }

    public function notFound(?string $message): Response
    {
        return \response($message ?? HttpDictionary::NOT_FOUND, 404);
    }

    public function dataError($message, ?int $code): Response
    {
        return \response($message ?? HttpDictionary::WRONG_INPUT_DATA, $code ?? 400);
    }

    public function dataApiError($response, $code): Response
    {
        return \response($response, $code);
    }

    public function serviceError(?array $payload = []): Response
    {
        return \response($payload, 503);
    }
}
