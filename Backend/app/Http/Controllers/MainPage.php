<?php

namespace Backend\Http\Controllers;

use Illuminate\Http\Request;

class MainPage extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        return view('welcome', ['title' => 'Backend Laravel']);
    }
}
