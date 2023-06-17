<?php
namespace App\Statistics\BonusesStatistics;

use App\Statistics\Interfaces\BoxesInterface;
use Illuminate\Support\Facades\DB;

class BonusesBoxes implements BoxesInterface{
    public static function query($context_view){
        $query = DB::table('bonuses')->select([
            DB::raw("COUNT(*) as 'Total $context_view'"),
            DB::raw("COUNT(*) as 'Total Added $context_view'"),
        ])->where('user_id', auth()->user()->id);
        return $query;
    }
}