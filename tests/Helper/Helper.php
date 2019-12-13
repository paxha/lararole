<?php

namespace Lararole\Tests\Helper;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Helper
{
    public static function runMiddleware($middleware, $moduleSlug)
    {
        try {
            $request = new Request();

            $request->merge([
                'moduleSlug' => $moduleSlug,
            ]);

            return $middleware->handle($request, function () {
                return (new Response())->setContent('<html lang="en"></html>');
            })->status();
        } catch (HttpException $e) {
            return $e->getStatusCode();
        }
    }
}
