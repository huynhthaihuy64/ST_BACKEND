<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Passport\Client;
use App\Services\AuthService;
use Illuminate\Support\Facades\Auth;
use App\Services\Common\ResponseService;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\Auth\SendEmailRequest;

class AuthController extends Controller
{
    /**
     * @var AuthService
     */
    private $authService;

    /**
     * @var ResponseService
     */
    private $responseService;

    /**
     * @param AuthService $authService
     * @param ResponseService $responseService
     */
    function __construct(AuthService $authService, ResponseService $responseService){
        $this->authService = $authService;
        $this->responseService = $responseService;
    }

    /**
     * @param LoginRequest $request
     *
     * @return Symfony\Component\HttpFoundation\Response $response
     */
    public function login(LoginRequest $request)
    {
        $email = $request->all()['email'];
        $password = $request->all()['password'];

        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            $oClient = Client::where('password_client', 1)->first();

            $result = $this->authService->getTokenAndRefreshToken(
                $oClient,
                Auth::user()->email,
                $password
            );

            return $this->responseService->response(
                true,
                $result,
                __("auth.login.success")
            );

        } else {
            return $this->responseService->response(false, [], __("auth.login.fail"));
        }
    }

    /**
     * @param Request $request
     *
     * @return Symfony\Component\HttpFoundation\Response $response
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return $this->responseService->response(
            true,
            [],
            __('auth.logout.success')
        );
    }

    /**
     *
     * @return Symfony\Component\HttpFoundation\Response $response
     */
    public function profile()
    {
        $user = $this->authService->getProfile();

        return $this->responseService->response(
            true,
            $user,
            __('auth.profile.success')
        );
    }

    /**
     * @param Request $request
     *
     * @return Symfony\Component\HttpFoundation\Response $response
     */
    public function refreshToken(Request $request)
    {
        $oClient = Client::where('password_client', 1)->first();
        $refresh_token = $request->refresh_token;
        $result = $this->authService->refreshAndGetToken($oClient, json_decode($refresh_token));

        return $this->responseService->response(
            true,
            $result,
            __('auth.refreshToken.success')
        );
    }

    /**
     * Reset password
     *
     * @param ResetPasswordRequest $request
     *
     * @return Symfony\Component\HttpFoundation\Response $response
     */
    public function resetPassword(ResetPasswordRequest $request)
    {
        $result = $this->authService->resetPassword($request);

        return $this->responseService->response(
            $result["status"],
            null,
            $result["message"],
        );
    }

    /**
     * Send a email and create token to reset password
     *
     * @param SendEmailRequest $request
     *
     * @return Symfony\Component\HttpFoundation\Response $response
     */
    public function resetPasswordToMail(SendEmailRequest $request)
    {
        $result = $this->authService->resetPasswordToMail($request->email);

        return $this->responseService->response(
            $result["status"],
            null,
            $result["message"],
        );
    }
}
