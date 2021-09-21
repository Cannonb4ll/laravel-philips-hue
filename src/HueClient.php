<?php

namespace Philips\Hue;

use Carbon\Carbon;
use GuzzleHttp\Client;

class HueClient
{
    /**
     * @var Client
     */
    private $guzzle;

    /**
     * @var string
     */
    private $baseUrl = 'https://api.meethue.com';

    /**
     * @var string
     */
    protected $baseUser;

    public function __construct()
    {
        $this->baseUser = config('services.philips-hue.user');

        $this->guzzle = new Client;
    }

    /**
     * Sends a request and returns response from Hue api
     *
     * @param string $url
     * @param string $method
     * @param array  $params
     *
     * @return mixed
     */
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

    /**
     * @param string $code
     *
     * @return object
     */
    public function getAccessTokenForTheFirstTime($code)
    {
        $r = $this->guzzle->post($this->baseUrl . '/oauth2/token', [
            'query' => [
                'code' => $code,
                'grant_type' => 'authorization_code'
            ],
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode(config('services.philips-hue.client_id') . ':' . config('services.philips-hue.client_secret')),
                'Content-Type' => 'application/json'
            ]
        ]);

        $tokens = $r->getBody()->getContents();

        $this->setTokenFile($tokens);

        return json_decode($tokens);
    }

    /**
     * @return mixed
     */
    public function refreshAndGetAccessToken()
    {
        $tokens = json_decode(file_get_contents(storage_path('app/hue.json')));

        // Check if the previous access token is still valid, if its valid then return it (reduces API calls)
        if (Carbon::createFromTimestamp(strtotime($tokens->access_token_expires_at)) > Carbon::now()) {
            return $tokens->access_token;
        }

        // If its not, request a new one
        $r = $this->guzzle->post($this->baseUrl . '/oauth2/refresh?grant_type=refresh_token', [
            'form_params' => [
                'refresh_token' => $tokens->refresh_token,
            ],
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode(config('services.philips-hue.client_id') . ':' . config('services.philips-hue.client_secret')),
                'Content-Type' => 'application/x-www-form-urlencoded'
            ]
        ]);

        $tokens = $r->getBody()->getContents();

        $this->setTokenFile($tokens);

        return object_get(json_decode($tokens), 'access_token');
    }

    /**
     * @return void
     */
    public function startOAuth()
    {
        $parameters = http_build_query([
            'clientid' => config('services.philips-hue.client_id'),
            'appid' => config('services.philips-hue.app_id'),
            'deviceid' => config('services.philips-hue.device_id'),
            'response_type' => 'code',
        ]);

        header('Location: ' . $this->baseUrl . '/oauth2/auth?' . $parameters);
        exit;
    }

    /**
     * @param string $data
     *
     * @return void
     */
    public function setTokenFile($data)
    {
        $data = json_decode($data);
        $data->access_token_expires_at = Carbon::now()
            ->addSeconds($data->access_token_expires_in)
            ->format('Y-m-d H:i:s');

        $data->refresh_token_expires_at = Carbon::now()
            ->addSeconds($data->refresh_token_expires_in)
            ->format('Y-m-d H:i:s');

        \Storage::put('hue.json', json_encode($data));
    }

    /**
     * @return HueLight
     */
    public function lights()
    {
        return new HueLight;
    }

    /**
     * @return HueGroups
     */
    public function groups()
    {
        return new HueGroups;
    }

    /**
     * @return HueUser
     */
    public function users()
    {
        return new HueUser;
    }

    /**
     * @return HueSchedule
     */
    public function schedules()
    {
        return new HueSchedule;
    }
}
