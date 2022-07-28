<?php

namespace App\Http\Controllers;

use App\Models\Deal;

use App\Models\Order;
use App\Models\Business;
use App\Models\DispenseryProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;
use ArielMejiaDev\LarapexCharts\LarapexChart;

class AdminController extends Controller {

    public function login() {

        if(Session::has('business_id')) {
            return redirect()->route('index');
        } else {
            return view('login');
        }
    }

    public function index() {

        $deals = Deal::whereBetween('created_at', [
            Carbon::now()->startOfYear(),
            Carbon::now()->endOfYear(),
        ])->selectRaw('COUNT(*) as count, YEAR(created_at) year, MONTH(created_at) month')
        ->groupBy('year','month')
        ->pluck('count')
        ->toArray();

        $sales = [];

            for ($month = 1; $month <= date('m'); $month++) {
                $date = Carbon::create(date('Y'), $month);
                $date_end = $date->copy()->endOfMonth();

                $sale = Order::where('retailer_id', session('business_id'))
                    ->where('created_at', '>=', $date)
                    ->where('created_at', '<=', $date_end)
                    ->sum('price');
                array_push($sales, (int) $sale);
            }

        $totalSales = Order::where('retailer_id', session('business_id'))
            ->sum('price');

        $chart = (new LarapexChart)->lineChart()
                   ->setTitle('Sales')
                   ->setSubtitle('Total sales this year')
                   ->addData('Sales', $sales)
                   ->setXAxis(['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']);

        $customers = Order::where('retailer_id', session('business_id'))
                            ->select('customer_id')
                            ->groupBy('customer_id')
                            ->get();
        $products = DispenseryProduct::where('dispensery_id', session('business_id'))->count();

        $pieChart = (new LarapexChart)->pieChart()
        ->setTitle('Statistics')
        ->setSubtitle('Year 2022')
        ->addData([count($deals), $products, $customers->count()])
        ->setLabels(['Deals', 'Products', 'Customers']);

        return view('index', [
            'deals' => $deals,
            'chart' => $chart,
            'customers' => $customers->count(),
            'products' => $products,
            'totalSales' => $totalSales,
            'pieChart' => $pieChart
        ]);

    }

    public function checkLogin(Request $request) {

        $business = Business::where('email', $request->email)->where('business_type', 'dispensary')->get();

        if (count($business) > 0) {

            $savedpassword = $business[0]['password'];

            $newpass = $request->password;

            if (\Hash::check($newpass, $savedpassword)) {

                if ($business[0]['status'] == 0) {

                    return back()->with('error', 'Your account is under review by our account mangement team. Please have patience until you are approched by our account management team.');

                } elseif ($business[0]['status'] == 1) {

                    $business_id = $business[0]['id'];
                    $business_name = $business[0]['name'];

                    $business_id = $request->session()->put('business_id', $business_id);
                    $business_name = $request->session()->put('business_name', $business_name);
                    $business_type = $request->session()->put('business_type', $business[0]['business_type']);

                    if(Session::has("prevUrl")) {
                        return redirect()->to(Session::get('prevUrl'));
                    } else {
                        return redirect()->route('index');
                    }

                } elseif ($business[0]['status'] == 2) {

                    return back()->with('error', 'Your account is blocked due to some reason. For further information please contact us.');

                }

            } else {

                return back()->with('error','Password you entered is invalid!');
            }

        } else {

            return back()->with('error','Email you entered is invalid!');

        }

    }

    public function logoutadmin() {

        session()->forget('business_id');
        session()->forget('business_name');

        return redirect()->route('login');

    }

}
