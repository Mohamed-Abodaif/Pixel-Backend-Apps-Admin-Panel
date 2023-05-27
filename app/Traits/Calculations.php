<?php

namespace App\Traits;

use DateTime;

trait Calculations
{
    use RetrieveQuietly;
    use CalculateBoxes;
    use CalculateTotal;
    use CalculateItems;
    use CalculateQueries;

    /**
     *  Generate Statistics as efficient and implicit we can get.
     */
    public static function getCalculation(string $context, string $period = null, string|DateTime $from = null, string|DateTime $to = null, array $filters = []): array
    {
        $queries = self::getQueries($context) ?? [];
        // dd(self::calculateBoxes($context, $period, $from, $to));
        return [
            'boxes' => self::calculateBoxes($context, $period, $from, $to, $filters),
            'items' => self::calculateItems($period, $from, $to),
            'total' => self::calculateTotal($period, $from, $to, $queries)
        ];
    }
}
