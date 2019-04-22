<?php

namespace App\Http\Controllers;

class AuthorController extends Controller
{
    public function articles()
    {
        return view('author.articles');
    }
}
