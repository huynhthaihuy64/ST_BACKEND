<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\PasswordReset;
use App\Mail\PasswordResetMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class AuthRepo
{

    /**
     * @param string $email
     *
     * @return array [$message, $status]
     */
    public function resetPasswordToMail(string $email)
    {
        try {
            $user = User::where('email', $email)->firstOrFail();

            $token = rand(100000, 999999);

            PasswordReset::updateOrCreate([
                'email' => $user->email,
            ],[
                'token' => $token
            ]);

            Mail::to($email)->send(new PasswordResetMail($token));

        } catch (\Throwable $th) {
            return [
                "status" => false,
                "message" => __('passwords.reset_fail')
            ];
        }

        return [
            "status" => true,
            "message" => __('passwords.sent')
        ];
    }

    /**
     * @param object $request
     *
     * @return array [$message, $status]
     */
    public function resetPassword(object $request)
    {
        $resetFail = [
            "message" => __('passwords.reset_fail'),
            "status" => false
        ];

        $token = $request->token;

        try {
            $passwordReset = PasswordReset::where('token', $token)->firstOrFail();
        } catch (\Exception $e) {
            return $resetFail;
        }

        if(Carbon::parse($passwordReset->update_at)->addMinutes(10)->isPast()){
            $passwordReset->delete();
            return $resetFail;
        }

        try {
            $user = User::where('email', $passwordReset->email)->firstOrFail();
            $user->update([
                'password' => bcrypt($request->password)
            ]);
            $passwordReset->delete();

            return [
                "message" => __('passwords.reset'),
                "status" => true
            ];
        } catch (\Throwable $th) {
            return $resetFail;
        }
    }
}
