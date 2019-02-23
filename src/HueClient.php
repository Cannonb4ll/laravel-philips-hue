<?php

namespace Philips\Hue;

use Carbon\Carbon;
use GuzzleHttp\Client;

class HueClient
{
    private $guzzle;
    private $baseUrl = 'https://api.meethue.com';
    protected $baseUser;

    public function __construct()
    {
        $this->baseUser = config('services.philips-hue.user');

        $this->guzzle = new Client([
            'allow_redirects' => true
        ]);
    }

    public function send($url, $method = 'get', $params = [])
    {
        $options = [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->refreshAndGetAccessToken(),
                'Content-Type' => 'application/json'
            ]
        ];

        if ($params) {
            $options['json'] = $params;
        }

        $r = $this->guzzle->{$method}($this->baseUrl . $url, $options);

        return json_decode($r->getBody()->getContents());
    }

    public function getAccessTokenForTheFirstTime($code)
    {
        $r = $this->guzzle->post('https://api.meethue.com/oauth2/token', [
            'query' => [
                'code' => $code,
                'grant_type' => 'authorization_code'
            ],
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode(env('PHILIPS_HUE_CLIENT_ID') . ':' . env('PHILIPS_HUE_CLIENT_SECRET')),
                'Content-Type' => 'application/json'
            ]
        ]);

        $tokens = $r->getBody()->getContents();

        $this->setTokenFile($tokens);

        return json_decode($tokens);
    }

    public function refreshAndGetAccessToken()
    {
        $tokens = json_decode(file_get_contents(storage_path('app/hue.json')));

        // Check if the previous access token is still valid
        if (Carbon::createFromTimestamp(strtotime($tokens->expires_at)) > Carbon::now()) {
            return $tokens->access_token;
        }

        // If its not, request a new one
        $r = $this->guzzle->post('https://api.meethue.com/oauth2/refresh?grant_type=refresh_token', [
            'form_params' => [
                'refresh_token' => $tokens->refresh_token,
            ],
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode(env('PHILIPS_HUE_CLIENT_ID') . ':' . env('PHILIPS_HUE_CLIENT_SECRET')),
                'Content-Type' => 'application/x-www-form-urlencoded'
            ]
        ]);

        $tokens = $r->getBody()->getContents();

        $this->setTokenFile($tokens);

        return object_get(json_decode($tokens), 'access_token');
    }

    public function startOAuth()
    {
        $parameters = http_build_query([
            'clientid' => config('services.philips-hue.client_id'),
            'appid' => config('services.philips-hue.app_id'),
            'deviceid' => config('services.philips-hue.device_id'),
            'response_type' => 'code'
        ]);

        header('Location: https://api.meethue.com/oauth2/auth?' . $parameters);
        exit;
    }

    public function setTokenFile($data)
    {
        $data = json_decode($data);
        $data->expires_at = Carbon::now()
            ->addSeconds($data->access_token_expires_in)
            ->format('Y-m-d H:i:s');

        \Storage::put('hue.json', json_encode($data));
    }

    public function lights()
    {
        return new HueLight;
    }

    public function groups()
    {
        return new HueGroups;
    }

    public function users()
    {
        return new HueUser;
    }

    public function schedules()
    {
        return new HueSchedule;
    }
}
