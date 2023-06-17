<?php
namespace App\Statistics\EmployeeStatistics;

use Illuminate\Support\Facades\DB;
use App\Statistics\Interfaces\BoxesInterface;

class EmployeeBoxes implements BoxesInterface{
    public static function query($context_view){
        $query = DB::table('users')->select([
            DB::raw("COUNT(*) as 'Total $context_view'"),
            DB::raw("COUNT(*) as 'Total Added $context_view'"),
            DB::raw("SUM(if(status = 1,1,0)) as 'Total Active $context_view'"),
            DB::raw("sum(if(status = 2, 1, 0))  as 'Total Inactive $context_view'"),
        ])->where('user_type', 'employee');
        return $query;
    }
}