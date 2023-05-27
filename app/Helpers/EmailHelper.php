<?php

use App\Models\WorkSector\CompanyModule\Company;
use App\Services\WorkSector\UsersModule\MailService;


function generateVerificationToken($model, string $email)
{
    $verification_token = substr(md5(rand(0, 9) . $model->$email . time()), 0, 32);
    $model->verification_token = $verification_token;
    $model->save();
    return $verification_token;
}

function codeExists($code, string $column)
{
    return Company::where($column, $code)->exists();
}

function generateVerificationCode($model, string $column)
{

    $code = random_int(100000, 999999);
    if (codeExists($column, $code)) {
        return generateVerificationCode($column, $code);
    }
    $model->verification_code = $code;
    $model->save();

    return $code;
}
function sendEmailVerification($model, $subject = null, $msg, $verification = null, string $email)
{
    $subject = $subject ?? "verify your account";
    $message = $msg ?? "<br>kindly click this link to verify
    your account <br><p> $verification </p> <br>Sincerely <br> ------------ <br> IGS Support Team ";
    $mailService = new MailService;
    $name = match (get_class($model)) {
        "User" => "name",
        "Company" => "first_name",
        default => "undefined this class",
    };
    return $mailService->sendEmailVerification(
        env('SUPPORT_MAIL'),
        $model,
        $subject,
        "Dear $name <br> $message",
        $model->$email
    );
}
function getVerificationLink($verificationToken, $type = 'company')
{
    return urldecode(env("MAIL_LINK") . "/$type-account-verification?token=$verificationToken");
}

function resetPasswordLink($verificationToken)
{
    return urldecode(env("MAIL_LINK") . "/user-reset-password?token=$verificationToken");
}
