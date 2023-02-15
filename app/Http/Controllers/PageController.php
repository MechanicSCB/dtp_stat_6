<?php

namespace App\Http\Controllers;


use Inertia\Response;
use Inertia\ResponseFactory;

class PageController extends Controller
{
    public function download(): Response|ResponseFactory
    {
        return inertia('Download');
    }

    public function about(): Response|ResponseFactory
    {
        return inertia('About');
    }
}
