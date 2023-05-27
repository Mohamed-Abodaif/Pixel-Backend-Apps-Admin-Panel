<?php

namespace App\Services\WorkSector\UsersModule\ResetPasswordService;


use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use App\Models\WorkSector\UsersModule\User;
use App\Models\WorkSector\UsersModule\ResetPassword;
use App\Services\WorkSector\UsersModule\ResetPasswordService\BaseService;
use App\Notifications\UserNotifications\ResetPasswordFormLinkNotification;

class SendResetPasswordNotificationService
{

    protected User $user;
    public function generateResetToken(): string
    {
        return substr(md5(rand(0, 9) . $this->user->email . time()), 0, 32);
    }

    /**
     * @param array $data
     * @return ResetPassword
     * @throws Exception
     */
    private function createPasswordResetModel(array $data): ResetPassword
    {
        $data["token"] = $this->generateResetToken();
        $resetPasswordModel = ResetPassword::create($data);
        if ($resetPasswordModel) {
            return $resetPasswordModel;
        }
        throw new Exception("Failed To Create Reset Password Token For The Given User");
    }

    /**
     * @param string $email
     * @return $this
     * @throws Exception
     */
    protected function setUser(string $email)
    {
        $user = User::where("email", $email)->first();
        if ($user) {
            $this->user = $user;
            return $user;
        }
        throw new Exception("User Not Found In Our Databases ... Check Your Info Then Try Again");
    }

    public function send($request): JsonResponse
    {
        try {
            $data = $request->all();
            $user = $this->setUser($data["email"]);
            $resetPasswordToken = $this->createPasswordResetModel($data);
            $tokenLink = resetPasswordLink($resetPasswordToken['token']);
            $subject = "forget your password";
            $message = "<br>kindly click this link to reset your password
                 account <br><p> $tokenLink </p> <br>Sincerely <br> ------------ <br> IGS Support Team ";
            sendEmailVerification($user, $subject, $message, null, "email");
            return Response::success([], ["Password Reset Link Has Been Sent Successfully"], 200);
        } catch (Exception $e) {
            return Response::error([$e->getMessage()]);
        }
    }
}
