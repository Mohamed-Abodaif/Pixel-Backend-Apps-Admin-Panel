<?php
namespace App\Statistics\CustodiesStatistics;

use App\Statistics\Interfaces\BoxesInterface;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CustodiesBoxes implements BoxesInterface{
    public static function query($context_view){
        $query = DB::table('custodies')->select([
            DB::raw("COUNT(*) as 'Total $context_view'"),
            DB::raw("COUNT(*) as 'Total Added $context_view'"),
        ])->where('user_id', auth()->user()->id);
        return $query;
    }
}