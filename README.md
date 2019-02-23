# Laravel Philips Hue

## Introduction

I created this package for my company Ploi (https://ploi.io) to control our office
lights. For example, when we receive a support ticket, our lights briefly light up green (Ploi's green color), fade out and in a bit.

This package makes it possible to easily manage your lights. Easily trigger your lights
whenever a support ticket comes in, or if a new user signs up.

Syntax is as easy as this;

```php
(new HueClient)->lights()->on(1)
```

## Installation

Require the package first:

```
$ composer require dennissmink/laravel-philips-hue
```

Add this to the `config/services.php` file:

```php
...

'philips-hue' => [
    'client_id' => env('PHILIPS_HUE_CLIENT_ID'),
    'client_secret' => env('PHILIPS_HUE_CLIENT_SECRET'),
    'app_id' => env('PHILIPS_HUE_APP_ID'),
    'device_id' => env('PHILIPS_HUE_DEVICE_ID'),
    'user' => env('PHILIPS_HUE_USERNAME')
]

...
```

Sign up (or login) to Philips Hue developer:

https://developers.meethue.com/register/

Next create a new Philips Hue App:

https://developers.meethue.com/add-new-hue-remote-api-app/

Fill in the fields accordingly to their form.

**Callback URL**: You will have to fill in a valid callback URL (certainly in testing enviroment, or you'd have to do this in production).
This is because we will get the access and refresh tokens right away and store these in your application.
In case if you are using valet, run `valet share` in your terminal to get an ngrok URL. Use this URL as callback in that case:
`{NGROK_HOST}/hue` (Example: `http://aa0515c9.ngrok.io/hue`)

After that, note these variables:

- AppId
- ClientId
- ClientSecret

We still need 2 variables, the device ID and username we will be using for your bridge.

**Make sure you are on the computer on the same network as your Philips Hue bridge.**
Visit this URL and note the device ID: https://www.meethue.com/api/nupnp (or visit https://account.meethue.com/bridge, you will see the bridge ID there too)

Fill in the details accordingly:

```
PHILIPS_HUE_CLIENT_ID=
PHILIPS_HUE_CLIENT_SECRET=
PHILIPS_HUE_APP_ID=
PHILIPS_HUE_DEVICE_ID=
PHILIPS_HUE_USERNAME= <-- We will get this in below step
```

Next we will have to create a user to be able to authenticate with your bridge.

Quickly add this to your web.php routes file (this is temporary, you can delete later) so we can get a username:

```php
Route::get('start-hue', function(){
    $hue = new HueClient();

    return redirect($hue->startOAuth());
});

Route::get('hue', function () {
    $hue = new HueClient();
    
    if ($code = request('code')) {
        $hue->getAccessTokenForTheFirstTime(request('code'));
        
        dd($hue->users()->create('your-username'));
    }
    
    dd($hue->lights()->all());
});
```

⚠ **NOTE:** To request a username, you will have to physically press the 'link' button on your bridge.
After you pressed it, you have 30 seconds to get your username.

Now visit your application with /start-hue at the end so oAuth2 login can begin.
You will be prompted to allow your own application permissions, accept this, you will be redirected to your own application.
This is the point when you redirect that you will receive the username in the `dd()` method.

Grab this username (this is saved in your bridge), and also enter this in your `.env` file:

```
PHILIPS_HUE_USERNAME=
```

This is it! Now you should be able to execute the methods which we describe below.

All the access data is saved in the `storage/app/hue.json` file, this contains the keys to access Philips Hue API.

## Methods

```php
    $hue = new HueClient();

    $hue->groups()->all();
    $hue->lights()->all();
    $hue->lights()->get(1);
    $hue->lights()->on(1);
    $hue->lights()->off(1);
    $hue->lights()->customState(1, [
        "hue" => 25500,
        "bri" =>200,
        "alert" => 'select'
    ]);
```

## TODO

- [ ] Ability to use without physically pressing the link button
- [ ] Add exceptions
- [ ] Add testing

## Helpful links
https://www.meethue.com/api/nupnp

https://developers.meethue.com/develop/hue-api/

https://developers.meethue.com/my-apps/

https://account.meethue.com/apps

https://account.meethue.com/bridge
