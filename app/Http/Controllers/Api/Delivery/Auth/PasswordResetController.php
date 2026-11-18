<?php

namespace App\Http\Controllers\Api\Delivery\Auth;

use App\Helpers\EmailHelper;
use App\Http\Controllers\Controller;
use App\Models\DeliveryMan;
use App\Models\DeliverymanPasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PasswordResetController extends Controller
{
    public function verifyEmail(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
        ]);

        if ($validator->fails()) {
            return api(['errors' => $validator->errors()->all()])->fails(__('response.fail'));
        }

        $email = strtolower(trim($request->input('email')));
        $deliveryman = DeliveryMan::active()->where('email', $email)->first();

        if (!$deliveryman) {
            return api(['errors' => [translate('Invalid email')]])->fails(__('response.fail'));
        }

        DeliverymanPasswordReset::where('identifier', $deliveryman->email)->delete();

        $resetPassword = DeliverymanPasswordReset::create([
            'identifier' => $deliveryman->email,
            'token' => random_number(),
        ]);

        $mail = EmailHelper::sendSmtpEmail(
            $deliveryman->email,
            'Delivery App Password Reset Code',
            '<h1>Password Reset</h1><p>Your password reset code is: <strong>' . $resetPassword->token . '</strong></p><p>This code expires soon. If you did not request a password reset, you can ignore this email.</p>'
        );

        if (!($mail['success'] ?? false)) {
            $resetPassword->delete();
            return api(['errors' => [translate('Unable to send password reset email')]])->fails(__('response.fail'));
        }

        return api([
            'message' => translate('Check your email password reset code sent successfully'),
        ])->success(__('response.success'));
    }

    public function verifyCode(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'code' => 'required|exists:deliveryman_password_resets,token',
        ]);

        if ($validator->fails()) {
            return api(['errors' => $validator->errors()->all()])->fails(__('response.fail'));
        }

        $email = strtolower(trim($request->input('email')));
        $token = DeliverymanPasswordReset::where('identifier', $email)
            ->where('token', $request->input('code'))
            ->first();

        if (!$token) {
            return api(['errors' => [translate('Invalid OTP code')]])->fails(__('response.fail'));
        }

        return api([
            'message' => translate('OTP verification successfully completed'),
        ])->success(__('response.success'));
    }

    public function passwordReset(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'code' => 'required|exists:deliveryman_password_resets,token',
            'password' => 'required|confirmed|min:6',
        ]);

        if ($validator->fails()) {
            return api(['errors' => $validator->errors()->all()])->fails(__('response.fail'));
        }

        $email = strtolower(trim($request->input('email')));
        $token = DeliverymanPasswordReset::where('identifier', $email)
            ->where('token', $request->input('code'))
            ->first();

        if (!$token) {
            return api(['errors' => [translate('Invalid OTP code')]])->fails(__('response.fail'));
        }

        $deliveryman = DeliveryMan::active()->where('email', $email)->first();

        if (!$deliveryman) {
            return api(['errors' => [translate('Invalid user')]])->fails(__('response.fail'));
        }

        $deliveryman->password = Hash::make($request->input('password'));
        $deliveryman->save();
        $token->delete();

        return api([
            'message' => translate('Password updated'),
        ])->success(__('response.success'));
    }
}
