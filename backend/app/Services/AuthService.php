<?php

namespace App\Services;

use Laravel\Passport\Client as OClient;
use GuzzleHttp\Client;
use App\Repositories\AuthRepo;

class AuthService
{
    /**
     * @var AuthRepo
     */
    private $authRepo;

    /**
     * @param AuthRepo $authRepo
     */
    function __construct(AuthRepo $authRepo)
    {
        $this->authRepo = $authRepo;
    }

    /**
     * @param OClient $oClient
     * @param email $email
     * @param string $password
     *
     * @return $token&refreshtoken
     */
    public function getTokenAndRefreshToken(OClient $oClient, $email, $password)
    {

        $oClient = OClient::where('password_client', 1)->first();

        $http = new Client(config('auth.request'));

        $response = $http->post(config('auth.oauth_url'), [
            'body' => json_encode([
                'grant_type' => 'password',
                'client_id' => $oClient->id,
                'client_secret' => $oClient->secret,
                'username' => $email,
                'password' => $password,
                'scope' => '*',
            ]),
        ]);

        return json_decode((string) $response->getBody());
    }

    /**
     * @param OClient $oClient
     * @param string $refresh_token
     *
     * @return $token
     */
    public function refreshAndGetToken(OClient $oClient, $refresh_token)
    {
        $client = new Client(config('auth.request'));

        try {
            $response = $client->post(
                config('auth.oauth_url'),
                [
                    'body' => json_encode([
                        'grant_type' => 'refresh_token',
                        'refresh_token' => $refresh_token,
                        'client_id' => $oClient->id,
                        'client_secret' => $oClient->secret,
                    ])
                ]
            );

            $result = $response->getBody()->getContents();
        } catch (\Exception $exception) {
            $result = $exception->getMessage();
        }
        return json_decode($result);
    }

    /**
     * Get Profile
     *
     * @return $user
     */
    public function getProfile()
    {
        return auth()->guard('api')->user();
    }

    /**
     * @param object $request
     *
     * @return array [$message, $status]
     */
    public function resetPassword(object $request)
    {
        return $this->authRepo->resetPassword($request);
    }

    /**
     * @param string $email
     *
     * @return boolean
     */
    public function resetPasswordToMail(string $email)
    {
        return $this->authRepo->resetPasswordToMail($email);
    }
}
