<?php

namespace App\Http\Controllers\WorkSector\FinancesModule\SalesInvoices;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Models\WorkSector\FinanceModule\SalesInvoices\SalesInvoice;

class SalesInvoiceStatusController extends Controller
{
    //['draft', 'approved internally','sent','approved in portal','due','overdue','cancelled','closed']
    public function approve($id)
    {
        $sales_invoice = SalesInvoice::find($id);
        $sales_invoice->update(['invoice_status' => 'approved internally']);

        $response = [
            "message" => "Aprroved Successfully",
            "status" => "success"
        ];
        return response()->json($response, 200);
    }

    public function sendInvoice($id)
    {
        $sales_invoice = SalesInvoice::find($id);
        $sales_invoice->update(['invoice_status' => 'sent']);
        $response = [
            "message" => "Invoice Sent Successfully",
            "status" => "success"
        ];
        return response()->json($response, 200);
    }

    public function approveInPortal($id)
    {
        $sales_invoice = SalesInvoice::find($id);
        $sales_invoice->update(['invoice_status' => 'approved in portal']);
        $response = [
            "message" => "Invoice Approved In Portal Successfully",
            "status" => "success"
        ];
        return response()->json($response, 200);
    }


    public function handleDueDate($id): void
    {
        //TODO: update all over due and due invoices
        /* $now = Carbon::now();
        $sales_invoice=SalesInvoice::find($id);
        $payment_date=Carbon::parseOrNow($sales_invoice->payment_date);

        $days = $payment_date->diffInDays($now ,false);
        $status = ($days > 2) ? "overdue" : ($days < -2 ? "due" : null);
        
        if ($status == null) {
            return;
        }
        SalesInvoice::where()->update([
            'invoice_status' => $status
        ]); */
    }
}
