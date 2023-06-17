<?php
        namespace App\Statistics\Vendor;
        use Illuminate\Support\Facades\DB;
        use App\Statistics\Interfaces\BoxesInterface;

        class VendorBoxes implements BoxesInterface
        {
            public static function query($context_view){
                $query = DB::table('table_name');
                return $query;
            }
        }

        