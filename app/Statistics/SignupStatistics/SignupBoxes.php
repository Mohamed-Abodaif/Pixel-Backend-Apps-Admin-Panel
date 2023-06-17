<?php
namespace App\Statistics\SignupStatistics;


use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SignupBoxes{
    public static function query($context_view){
        $query = DB::table('users')->select([
            DB::raw("COUNT(*) as 'Total $context_view'"),
            DB::raw("COUNT(*) as 'Total Added $context_view'"),
            DB::raw("SUM(if(status = 0,1,0)) as 'Total Pending $context_view'"),
            DB::raw("sum(if(status = 3, 1, 0))  as 'Total Blocked $context_view'"),
        ])->where('user_type', 'signup');
        return $query;
    }
}