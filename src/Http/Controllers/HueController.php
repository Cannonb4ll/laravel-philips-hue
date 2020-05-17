<?php

namespace Philips\Hue\Http\Controllers;

use Philips\Hue\HueClient;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class HueController extends Controller
{
    public function auth()
    {
        $hue = new HueClient;

        $hue->startOAuth();
    }

    public function receive(Request $request)
    {
        $hue = new HueClient;

        if ($code = $request->input('code')) {
            $hue->getAccessTokenForTheFirstTime($code);

            $username = $hue->users()->create();

            return redirect()->action('\Philips\Hue\Http\Controllers\HueController@receive', ['username' => $username]);
        }

        // This is being triggered after we created our first user, so the developer can enter the username
        // in the .env file.
        if ($username = $request->input('username')) {
            return view('hue::auth', compact('username'));
        }

        // If all is well, we display a default view to show its working.
        $lights = $hue->lights()->all();

        return view('hue::lights', compact('lights'));
    }
}
