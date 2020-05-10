<?php

namespace Lararole\Http\Controllers\Api;

use Illuminate\Routing\Controller;

class BaseController extends Controller
{
    private $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = auth()->user();

            return $next($request);
        });
    }

    protected function user()
    {
        return $this->user;
    }
}
