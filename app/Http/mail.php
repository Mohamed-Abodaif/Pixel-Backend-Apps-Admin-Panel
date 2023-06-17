<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\WorkSector\UsersModule\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UsersResource;
use App\Services\MailService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{

    private $mailService;

    public function __construct(MailService $mailService)
    {
        $this->mailService = $mailService;
    }
    //
    public function login(LoginRequest $request)
    {

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if (is_null($user->email_verified_at)) {
                $verification_token = $user->generateVerificationToken($user->id);
                $activation_link = $user->getVerificationLink('https://igs-system.com', $verification_token, $user->email);
                $this->mailService->sendEmailVerification('support@igs-industrial.com', $user, 'Click this Link to verify your eamil', '<p>' . $activation_link . ' </p>', $user->email);
                return response()->json([
                    'status' => "error",
                    'message' => "Your Email Not Verified Yet , a verfication email sent now to your email"
                ], 205);
            }

            if ($user->status == 0) {
                return response()->json([
                    'status' => "error",
                    'message' => "Your Account Not Approved Yet"
                ], 207);
            }
            return new UsersResource($user);
        } else {
            return response()->json([
                'status' => "error",
                'message' => "Worng email or password"
            ], 203);
        }
    }

    public function register(RegisterRequest $request)
    {
        $user_data = $request->safe()->except('password');
        $code = random_int(100000, 999999);
        //generate unique id
        $user = new User($user_data);
        //assign user data
        $user->password = bcrypt($request->password);
        $user->role_id = $this->getDefaultRole();
        $user->verification_code = $code;
        $user->employee_id = $this->generateEmployeeId();

        DB::beginTransaction();
        try {
            $user->save();

            $this->mailService->sendEmailVerification('support@igs-industrial.com', $user, 'Email Verfication', '<p> Code </p>' . $code, $request->email);
            $token = $user->createToken("userToken")->accessToken;
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $response = [
                "message" => $e->getMessage(),
                "status" => "error"
            ];
            return response()->json($response, 400);
        }
        $response = [
            "message" => "Registered Successfully",
            "status" => "success",
            "token" => $token,
        ];
        return response()->json($response, 200);
    }

    public function verifyEmail(Request $request)
    {
        $user = User::where('verification_token', $request->token)->first();
        if (!$user) {
            $response = [
                "message" => "Your Verification Code Is Invalid",
                "status" => "error",
            ];
        } else {
            $user->update([
                'email_verified_at' => Carbon::now()->toDateTimeString(),
                'verification_token' => null
            ]);

            $response = [
                "message" => "Email Verifed successfully",
                "status" => "success",
            ];
        }


        return response()->json($response, 200);
    }

    private function getDefaultRole()
    {
        $role = Role::where('name', 'user')->first();
        if ($role) return $role->id;
        return 0;
    }

    public function generateEmployeeId()
    {
        $code = '#EMP-' . random_int(00000, 99999); // better than rand()

        if ($this->codeExists($code)) {
            return $this->generateEmployeeId();
        }
        return $code;
    }

    function codeExists($code)
    {
        return User::where('employee_id', $code)->exists();
    }
}
