<?php
namespace App\Statistics\ClientStatistics;


use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ClientBoxes{
    public static function query($context_view){
        $query = DB::table('clients')->select([
            DB::raw("COUNT(*) as 'Total $context_view'"),
            DB::raw("COUNT(*) as 'Total Added $context_view'"),
            DB::raw("SUM(if(status = 1,1,0)) as 'Total Active $context_view'"),
            DB::raw("SUM(if(status = 0,1,0)) as 'Total Inactive $context_view'"),
        ]);
        return $query;
    }
}