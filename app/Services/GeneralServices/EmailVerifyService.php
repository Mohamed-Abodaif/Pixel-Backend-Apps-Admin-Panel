<?php

namespace App\Services\GeneralServices;

use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use App\Models\WorkSector\CompanyModule\Company;

class EmailVerifyService
{
    function requestRules($request)
    {
        return $request->validate([
            "verification_token" => "required|string",
        ]);
    }
    public function checkEmail($request, $model)
    {
        $code = $this->requestRules($request);
        ($model = $model::whereVerificationToken($request->verification_token)->first());
        if (!$model) {
            return response()->json([
                "message" => "Your Verification Token Is Invalid Or Expired",
            ], 422);
        } else {
            $model->email_verified_at = Carbon::now()->toDateTimeString();
            $model->verification_token = null;
            if (get_class($model) == "User") {
                $model->registration_status = "pending";
            }
            $model->save();
            return response()->json([
                "message" => "Email Verifed successfully",
            ]);
        }
    }
}
