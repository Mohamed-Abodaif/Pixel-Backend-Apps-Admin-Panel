<?php

namespace App\Traits;

trait CalculateQueries
{
    /**
     *  Generate Statistics as efficient and implicit we can get.
     */
    public static function getQueries(string $context): array
    {
        $queries = [];
        switch ($context) {
            case 'clients':
                $queries = [
                    ["condition" => "client_type = 'FREE ZONE'", "message" => "freezone clients"],
                    ["condition" => "client_type = 'LOCAL'", "message" => "local clients"],
                    ["condition" => "client_type = 'INTERNATIONAL'", "message" => "international clients"],
                    ["condition" => "client_type = 'NOT SPECIFIED'", "message" => "not specified clients"],
                ];
                break;
            case 'employees':
                $queries = [
                    ["condition" => "status = '1'", "message" => "active employees"],
                    ["condition" => "status = '0'", "message" => "inactive employees"],
                ];
                break;
            case 'vendors':
                $queries = [
                    ["condition" => "client_type = 'FREE ZONE'", "message" => "freezone vendors"],
                    ["condition" => "client_type = 'LOCAL'", "message" => "local vendors"],
                    ["condition" => "client_type = 'INTERNATIONAL'", "message" => "international vendors"],
                    ["condition" => "client_type = 'NOT SPECIFIED'", "message" => "not specified vendors"],
                ];
                break;
            case 'purchase_invoices':
                $queries = [
                    ["condition" => "status = 'Draft'", "message" => "Draft invoices"],
                    ["condition" => "status = 'Sent'", "message" => "Sent invoices"],
                    ["condition" => "status = 'Paid'", "message" => "Paid invoices"],
                    ["condition" => "status = 'Partially Paid'", "message" => "Partially Paid invoices"],
                ];
                break;
            case 'sales_invoices':
                //'draft','approved internally','sent','approved in portal','due','overdue','cancelled','closed'
                //'not paid','partially paid','paid','refunded'
                $queries = [
                        ["condition" => "invoice_status = 'draft'", "message" => "draft invoices"],
                        ["condition" => "invoice_status = 'approved internally'", "message" => "approved internally invoices"],
                        ["condition" => "invoice_status = 'sent'", "message" => "sent invoices"],
                        ["condition" => "invoice_status = 'approved in portal'", "message" => "approved in portal invoices"],
                        ["condition" => "invoice_status = 'due'", "message" => "due invoices"],
                        ["condition" => "invoice_status = 'overdue'", "message" => "overdue invoices"],
                        ["condition" => "invoice_status = 'cancelled'", "message" => "cancelled invoices"],
                        ["condition" => "invoice_status = 'closed'", "message" => "closed invoices"],
                        ["condition" => "payment_status = 'not paid'", "message" => "not paid invoices"],
                        ["condition" => "payment_status = 'partially paid'", "message" => "partially paid invoices"],
                        ["condition" => "payment_status = 'paid'", "message" => "paid invoices"],
                        ["condition" => "payment_status = 'refunded'", "message" => "refunded invoices"],
                ];
                break;
            case 'clients_quotations':
                $queries = [
                    ["condition" => "status = 'Draft'", "message" => "Draft Quotations"],
                    ["condition" => "status = 'Sent'", "message" => "Sent Quotations"],
                    ["condition" => "status = 'Get PO'", "message" => "Get Po Quotations"],
                    ["condition" => "status = 'No PO'", "message" => "No Po Quotations"],
                ];
                break;
            case 'POs':
            case 'vendors_orders':
                $queries = [
                    ["condition" => "status = 'In Progress'", "message" => "In Progress Po"],
                    ["condition" => "status = 'Delayed'", "message" => "Delayed Po"],
                    ["condition" => "status = 'Invoiced'", "message" => "Invoiced Po"],
                    ["condition" => "status = 'Good Receive'", "message" => "Good Receive Po"],
                ];
                break;
            case 'assets':
                $queries = [
                    ["condition" => "status = 'Available'", "message" => "Available Asset"],
                    ["condition" => "status = 'In Maintenance'", "message" => "In Maintenance Assets"],
                ];
                break;
            case 'expenses':
                $queries = [
                    ["condition" => "category = 'assets'", "message" => "assets"],
                    ["condition" => "category = 'company_operation'", "message" => "company operations"],
                    ["condition" => "category = 'client_po'", "message" => "client PO"],
                    ["condition" => "category = 'marketing'", "message" => "marketing"],
                    ["condition" => "category = 'client_visits_preorders'", "message" => "client visits & preorders"],
                    ["condition" => "category = 'purchase_for_inventory'", "message" => "purchase for inventory"],
                ];

                break;
            case 'signup_requests':
            default:
                $queries = [];
                break;
        }
        return $queries;
    }
}
