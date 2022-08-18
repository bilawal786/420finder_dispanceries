<?php

namespace App\Http\Controllers;

use App\Models\Deal;
use Illuminate\Http\Request;
use App\Models\RetailerMenuOrder;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{

    public function __invoke()
    {
        $transaction = DB::table('subscription_details')->orderBy('id', 'DESC')->where('retailer_id', session('business_id'))->paginate(5);
        $dealTransaction =  DB::table('deal_orders')->where('deal_orders.retailer_id', session('business_id'))
        ->join('deals', 'deals.id', '=', 'deal_orders.deal_id')
        ->get();

        return view('transaction.index')->with([
            'transaction' => $transaction,
            'dealTransaction' => $dealTransaction
        ]);
    }
}
