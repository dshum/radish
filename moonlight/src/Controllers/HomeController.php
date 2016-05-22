<?php

namespace Moonlight\Controllers;

use Moonlight\Main\LoggedUser;

class HomeController extends Controller
{
    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return Response
     */
    public function show()
    {
        return view('moonlight::home');
    }
}