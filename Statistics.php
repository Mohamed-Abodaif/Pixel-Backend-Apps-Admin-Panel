private static function calculateBoxesByAllTimePeriod($context, array $filters = [])
    {
        $context_view = Str::snakeToTitle($context);
        $query = match(strtolower($context)){
            'employees'=> EmployeeBoxes::query($context_view),
            'clients'=> ClientBoxes::query($context_view),
            'signup_requests'=> SignupBoxes::query($context_view),
            'expenses'=> ExpensesBoxes::query($context_view),
            'bonuses'=> BonusesBoxes::query($context_view),
            'custodies'=> CustodiesBoxes::query($context_view),
            'expenses_currencies'=> ExpensesCurrenciesBoxes::query($context_view),
            'clients_quotations'=> ClientsQuotationsBoxes::query($context_view),
            'clients_orders'=> ClientsOrdersBoxes::query($context_view),
            default =>$query = self::select([
                    DB::raw("COUNT(*) as 'Total $context_view'"),
                    DB::raw("COUNT(*) as 'Total Added $context_view'"),
                ])
        };
        return self::generateFilters($query, $filters);
    }