<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\Assembly;
use App\Models\Brief;
use App\Models\Bill;
use App\Models\Cheque;
use App\Models\Cost;
use App\Models\Customer;
use App\Models\Exploration;
use App\Models\Expense;
use App\Models\Earnest;
use App\Models\Need;
use App\Models\Offer;
use App\Models\Permission;
use App\Models\Printing;
use App\Models\Project;
use App\Models\Production;
use App\Models\Purchase;
use App\Models\User;
use App\Models\Vehicle;

use DB;

class DashboardController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {
      $d = $this->request->all();
      $parameters = $this->request->query();
      
      $vehicles = Vehicle::where('is_active', 1)->count();

      if(!isset($parameters["start_at"])&&!isset($parameters['end_at'])){
        $tarih_araligi = Carbon::parse(date('Y-m-').'01')->formatLocalized('%d %B %Y')."-".Carbon::parse(date('Y-m-d'))->formatLocalized('%d %B %Y')." dönemi";
      }else{
        $tarih_araligi = Carbon::parse($parameters['start_at'])->formatLocalized('%d %B %Y')."-".Carbon::parse($parameters['end_at'])->formatLocalized('%d %B %Y')." dönemi";
      }

      if(!isset($parameters["start_at"])&&!isset($parameters['end_at'])){
        $page_title = Carbon::now()->formatLocalized('%B %Y')." dönemi genel veriler";
      }else{
        $page_title = Carbon::parse($parameters['start_at'])->formatLocalized('%d %B %Y')."-".Carbon::parse($parameters['end_at'])->formatLocalized('%d %B %Y')." dönemi genel veriler";
      }
      $page_description = 'İncelemek istediğiniz dönemi sağdaki takvimden değiştirebilirsiniz.';

      /* toplam müşteriler */
      $customers = Customer::where('id', '>', '0');
      if(isset($parameters['start_at'])){
        $customers = $customers->where('created_at', '>=', $parameters['start_at']);
      }
      if(isset($parameters['end_at'])){
          $customers = $customers->where('created_at', '<=', Carbon::parse($parameters['end_at'])->addDays(1)->format('Y-m-d'));
      }
      if(!isset($parameters["start_at"])&&!isset($parameters['end_at'])){
      }
      $customers = $customers->count();
      /* toplam müşteriler son */

      /* toplam keşif */
      $exploration = Exploration::where('status', '!=', 'Keşif Tamamlandı');
      if(isset($parameters['start_at'])){
        $exploration = $exploration->where('created_at', '>=', $parameters['start_at']);
      }
      if(isset($parameters['end_at'])){
          $exploration = $exploration->where('created_at', '<=', Carbon::parse($parameters['end_at'])->addDays(1)->format('Y-m-d'));
      }
      if(!isset($parameters["start_at"])&&!isset($parameters['end_at'])){
      }
      $exploration_total = $exploration->count();
      /* toplam keşif son */
      
      /* toplam üretim */
      $production = Production::where('status', '!=', 'tamamlandı');
      if(isset($parameters['start_at'])){
        $production = $production->where('created_at', '>=', $parameters['start_at']);
      }
      if(isset($parameters['end_at'])){
          $production = $production->where('created_at', '<=', Carbon::parse($parameters['end_at'])->addDays(1)->format('Y-m-d'));
      }
      if(!isset($parameters["start_at"])&&!isset($parameters['end_at'])){
      }
      $production_total = $production->count();
      /* toplam üretim son */

      /* toplam montaj */
      $assembly = Assembly::where('status', '!=', 'Montaj Tamamlandı');
      if(isset($parameters['start_at'])){
        $assembly = $assembly->where('created_at', '>=', $parameters['start_at']);
      }
      if(isset($parameters['end_at'])){
          $assembly = $assembly->where('created_at', '<=', Carbon::parse($parameters['end_at'])->addDays(1)->format('Y-m-d'));
      }
      if(!isset($parameters["start_at"])&&!isset($parameters['end_at'])){
      }
      $assembly_total = $assembly->count();
      /* toplam montaj son */

      /* toplam üretim */
      $printing = Printing::where('status', '!=', 'Baskı Tamamlandı');
      if(isset($parameters['start_at'])){
        $printing = $printing->where('created_at', '>=', $parameters['start_at']);
      }
      if(isset($parameters['end_at'])){
          $printing = $printing->where('created_at', '<=', Carbon::parse($parameters['end_at'])->addDays(1)->format('Y-m-d'));
      }
      if(!isset($parameters["start_at"])&&!isset($parameters['end_at'])){
      }
      $printing_total = $printing->count();
      /* toplam üretim son */

      /* toplam brief */
      $brief = Brief::where('status', '!=', 'Onaylandı');
      if(isset($parameters['start_at'])){
        $brief = $brief->where('created_at', '>=', $parameters['start_at']);
      }
      if(isset($parameters['end_at'])){
          $brief = $brief->where('created_at', '<=', Carbon::parse($parameters['end_at'])->addDays(1)->format('Y-m-d'));
      }
      $brief_total = $brief->count();
      /* toplam brief son */

      /* toplam teklif */
      $offer = Offer::where('status', '!=', 'müşteri onayladı');
      if(isset($parameters['start_at'])){
        $offer = $offer->where('created_at', '>=', $parameters['start_at']);
      }
      if(isset($parameters['end_at'])){
          $offer = $offer->where('created_at', '<=', Carbon::parse($parameters['end_at'])->addDays(1)->format('Y-m-d'));
      }
      if(!isset($parameters["start_at"])&&!isset($parameters['end_at'])){
      }
      $offer_total = $offer->count();
      /* toplam teklif son */


      /* aylık üç firma */
      $aylik_uc_firma = Bill::whereIn('status', ['Fatura Kesildi', 'ödendi', 'Müşteriye Gönderildi']);
      if(isset($parameters['start_at'])){
        $aylik_uc_firma = $aylik_uc_firma->where('bill_date', '>=', $parameters['start_at']);
      }
      if(isset($parameters['end_at'])){
          $aylik_uc_firma = $aylik_uc_firma->where('bill_date', '<=', Carbon::parse($parameters['end_at'])->addDays(1)->format('Y-m-d'));
      }
      if(!isset($parameters["start_at"])&&!isset($parameters['end_at'])){
        $aylik_uc_firma = $aylik_uc_firma->whereYear('bill_date', Carbon::now()->year);
        $aylik_uc_firma = $aylik_uc_firma->whereMonth('bill_date', Carbon::now()->month);
      }
      $total_sales_uc_aylik = $aylik_uc_firma->sum('price');
      $aylik_uc_firma = $aylik_uc_firma->select('customer_id', DB::raw('sum(price) as total'));
      $aylik_uc_firma = $aylik_uc_firma->groupBy('customer_id');
      $aylik_uc_firma = $aylik_uc_firma->orderBy('total', 'desc');
      $aylik_uc_firma = $aylik_uc_firma->limit(3)->get();

      /* aylık üç firma son */

      /* yıllık üç firma */
      $yillik_uc_firma = Bill::whereIn('status', ['Fatura Kesildi', 'ödendi', 'Müşteriye Gönderildi']);
      $yillik_uc_firma = $yillik_uc_firma->where('bill_date', '>=', date('Y')."-01-01");
      $total_sales = $yillik_uc_firma->sum('price');
      $yillik_uc_firma = $yillik_uc_firma->select('customer_id', DB::raw('sum(price) as total'));
      $yillik_uc_firma = $yillik_uc_firma->groupBy('customer_id');
      $yillik_uc_firma = $yillik_uc_firma->orderBy('total', 'desc');
      $yillik_uc_firma = $yillik_uc_firma->limit(3)->get();

      /* yıllık üç firma son */


      /* izinler */
      $toplam_alacak = Bill::whereIn('status', ['Fatura Kesildi', 'ödendi', 'Müşteriye Gönderildi']);
      $toplam_alacak = $toplam_alacak->sum('price');

      /* users */
      $users = User::where('is_active', 1)->count();

      $gunluk_checque = Cheque::where('status', 0)->where('type', 'send')->where('deadline', date('Y-m-d'))->sum('price');

      $send_cheque = Cheque::where('status', 0)->where('type', 'send');
      if(isset($parameters['start_at'])){
        $send_cheque = $send_cheque->where('deadline', '>=', $parameters['start_at']);
      }
      if(isset($parameters['end_at'])){
          $send_cheque = $send_cheque->where('deadline', '<=', Carbon::parse($parameters['end_at'])->addDays(1)->format('Y-m-d'));
      }
      if(!isset($parameters["start_at"])&&!isset($parameters['end_at'])){
        $send_cheque = $send_cheque->whereYear('deadline', Carbon::now()->year);
        $send_cheque = $send_cheque->whereMonth('deadline', Carbon::now()->month);
      }
      $send_cheque = $send_cheque->sum('price');

      $received_cheque = Cheque::where('status', 0)->where('type', 'received');
      if(isset($parameters['start_at'])){
        $received_cheque = $received_cheque->where('deadline', '>=', $parameters['start_at']);
      }
      if(isset($parameters['end_at'])){
          $received_cheque = $received_cheque->where('deadline', '<=', Carbon::parse($parameters['end_at'])->addDays(1)->format('Y-m-d'));
      }
      if(!isset($parameters["start_at"])&&!isset($parameters['end_at'])){
        $received_cheque = $received_cheque->whereYear('deadline', Carbon::now()->year);
        $received_cheque = $received_cheque->whereMonth('deadline', Carbon::now()->month);
      }
      $received_cheque = $received_cheque->sum('price');

      $expense_total = Expense::where('status', 'Yönetici Onayında')->select(DB::raw('price+vat as total, count(*) as adet'))->first();

      return view('management.dashboard.dashboard', compact('page_title', 'page_description', 'tarih_araligi', 'production_total', 'assembly_total', 'printing_total', 'exploration_total', 'aylik_uc_firma', 'yillik_uc_firma', 'customers', 'toplam_alacak', 'users', 'send_cheque', 'received_cheque', 'expense_total', 'total_sales', 'total_sales_uc_aylik', 'gunluk_checque', 'offer_total', 'brief_total', 'vehicles'));
    }
    
    public function customerData($customer_id): \Illuminate\Http\JsonResponse
    {
        $d = $this->request->all();
        $parameters = $this->request->query();
        

        $offer = Bill::whereIn('status', ['Fatura Kesildi', 'ödendi', 'Müşteriye Gönderildi'])->where('customer_id', $customer_id);

        $offer = $offer->select(array(
          DB::raw('MONTH(bill_date) as `x`'),
          DB::raw('SUM(price) as `y`')
        ))
        ->groupBy('x')
        ->orderBy('x');

        if(isset($parameters['start_at'])){
          $offer = $offer->where('bill_date', '>=', date('Y', strtotime($parameters['start_at']))."-01-01");
        }else{
          $offer = $offer->where('bill_date', '>=', date('Y')."-01-01");
        }

        $offer = $offer->get();

        foreach($offer as $p){
          $labels[] = $p['x'];
          $data[] = floatval($p['y']);
        }
        $result = array(
          'labels' => $labels,
          'series' => $data
        );
      return response()->json($result);
    }

    public function yillikSatisgenel(): \Illuminate\Http\JsonResponse
    {
        $d = $this->request->all();
        $parameters = $this->request->query();
        

        $offer = Bill::whereIn('status', ['Fatura Kesildi', 'ödendi', 'Müşteriye Gönderildi']);

        $offer = $offer->select(array(
          DB::raw('MONTH(bill_date) as `x`'),
          DB::raw('SUM(price) as `y`')
        ))
        ->groupBy('x')
        ->orderBy('x');

        if(isset($parameters['start_at'])){
          $offer = $offer->where('bill_date', '>=', date('Y', strtotime($parameters['start_at']))."-01-01");
        }else{
          $offer = $offer->where('bill_date', '>=', date('Y')."-01-01");
        }

        $offer = $offer->get();

        $labels = [];
        $data = [];
        foreach($offer as $p){
          $labels[] = $p['x'];
          $data[] = floatval($p['y']);
        }
        $result = array(
          'labels' => $labels,
          'series' => $data
        );
      return response()->json($result);
    }
    public function yillikMTGenel(): \Illuminate\Http\JsonResponse
    {
        $d = $this->request->all();
        $parameters = $this->request->query();
        

        $offer = Bill::whereIn('status', ['Fatura Kesildi', 'ödendi', 'Müşteriye Gönderildi']);

        $offer = $offer->select(array(
          DB::raw('SUM(price) as `y`'),
          'user_id'
        ))
        ->with(['user'])
        ->groupBy('user_id')
        ->orderBy('y');

        if(isset($parameters['start_at'])){
          $offer = $offer->where('bill_date', '>=', date('Y', strtotime($parameters['start_at']))."-01-01");
        }else{
          $offer = $offer->where('bill_date', '>=', date('Y')."-01-01");
        }

        $offer = $offer->get();

        $labels = [];
        $data = [];
        foreach($offer as $p){
          $labels[] = $p->user->name." ".$p->user->surname;
          $data[] = floatval($p['y']);
        }
        $result = array(
          'labels' => $labels,
          'series' => $data
        );
      return response()->json($result);
    }

    public function projects(): \Illuminate\Http\JsonResponse
    {
        $d = $this->request->all();
        $parameters = $this->request->query();
        

        $orders = Project::where('id', '>', '0');

        $orders = $orders->select(array(
          DB::raw('DATE(`created_at`) as `x`'),
          DB::raw('count(id) as `y`')
        ))
        ->groupBy('x')
        ->orderBy('x');

        if(isset($parameters['start_at'])){
          $orders = $orders->where('created_at', '>=', $parameters['start_at']);
        }

        if(isset($parameters['end_at'])){
            $orders = $orders->where('created_at', '<=', Carbon::parse($parameters['end_at'])->addDays(1)->format('Y-m-d'));
        }

        if(!isset($parameters["start_at"])&&!isset($parameters['end_at'])){
          $orders = $orders->whereYear('created_at', Carbon::now()->year);
          $orders = $orders->whereMonth('created_at', Carbon::now()->month);
        }

        $orders = $orders->get();
        return response()->json($orders);
    }

    public function explorationStatus(): \Illuminate\Http\JsonResponse
    {
        $d = $this->request->all();
        $parameters = $this->request->query();
        
        $exploration = Exploration::groupBy('status')->select('status', DB::raw('COUNT(*) as adet'))->get();

        $labels = array();
        $data = array();
        foreach($exploration as $p){
            $labels[] = $p['status']." (".floatval($p['adet']).")";
            $data[] = floatval($p['adet']);
        }
        $result = array(
          'labels' => $labels,
          'series' => $data
        );
        return response()->json($result);
    }
    public function productionStatus(): \Illuminate\Http\JsonResponse
    {
        $d = $this->request->all();
        $parameters = $this->request->query();
        

        $production = Production::groupBy('status')->select('status', DB::raw('COUNT(*) as adet'))->get();

        $labels = array();
        $data = array();
        foreach($production as $p){
            $labels[] = $p['status']." (".floatval($p['adet']).")";
            $data[] = floatval($p['adet']);
        }
        $result = array(
          'labels' => $labels,
          'series' => $data
        );
        return response()->json($result);
    }
    public function assemblyStatus(): \Illuminate\Http\JsonResponse
    {
        $d = $this->request->all();
        $parameters = $this->request->query();
        

        $production = Assembly::groupBy('status')->select('status', DB::raw('COUNT(*) as adet'))->get();

        $labels = array();
        $data = array();
        foreach($production as $p){
            $labels[] = $p['status']." (".floatval($p['adet']).")";
            $data[] = floatval($p['adet']);
        }
        $result = array(
          'labels' => $labels,
          'series' => $data
        );
        return response()->json($result);
    }
    public function printingStatus(): \Illuminate\Http\JsonResponse
    {
        $d = $this->request->all();
        $parameters = $this->request->query();
        

        $production = Printing::groupBy('status')->select('status', DB::raw('COUNT(*) as adet'))->get();

        $labels = array();
        $data = array();
        foreach($production as $p){
            $labels[] = $p['status']." (".floatval($p['adet']).")";
            $data[] = floatval($p['adet']);
        }
        $result = array(
          'labels' => $labels,
          'series' => $data
        );
        return response()->json($result);
    }
    public function briefStatus(): \Illuminate\Http\JsonResponse
    {
        $d = $this->request->all();
        $parameters = $this->request->query();
        

        $production = Brief::groupBy('status')->select('status', DB::raw('COUNT(*) as adet'))->get();

        $labels = array();
        $data = array();
        foreach($production as $p){
            $labels[] = $p['status']." (".floatval($p['adet']).")";
            $data[] = floatval($p['adet']);
        }
        $result = array(
          'labels' => $labels,
          'series' => $data
        );
        return response()->json($result);
    }
    public function offerStatus(): \Illuminate\Http\JsonResponse
    {
        $d = $this->request->all();
        $parameters = $this->request->query();
        

        $production = Offer::groupBy('status')->select('status', DB::raw('COUNT(*) as adet'))->get();

        $labels = array();
        $data = array();
        foreach($production as $p){
            $labels[] = $p['status']." (".floatval($p['adet']).")";
            $data[] = floatval($p['adet']);
        }
        $result = array(
          'labels' => $labels,
          'series' => $data
        );
        return response()->json($result);
    }
    public function monthlySales(): \Illuminate\Http\JsonResponse
    {
        $d = $this->request->all();
        $parameters = $this->request->query();
        $firm_id = $this->request->user()->firm_id;

        $offer = Bill::whereIn('status', ['Fatura Kesildi', 'ödendi', 'Müşteriye Gönderildi']);

        $offer = $offer->select(array(
          DB::raw('DATE(bill_date) as `x`'),
          DB::raw('SUM(price) as `y`')
        ))
        ->groupBy('x')
        ->orderBy('x');

        if(isset($parameters['start_at'])){
          $offer = $offer->where('bill_date', '>=', $parameters['start_at']);
        }

        if(isset($parameters['end_at'])){
            $offer = $offer->where('bill_date', '<=', Carbon::parse($parameters['end_at'])->addDays(1)->format('Y-m-d'));
        }

        if(!isset($parameters["start_at"])&&!isset($parameters['end_at'])){
          $offer = $offer->whereYear('bill_date', Carbon::now()->year);
          $offer = $offer->whereMonth('bill_date', Carbon::now()->month);
        }

        $offer = $offer->get();
        return response()->json($offer);
    }
    public function yearlySales(): \Illuminate\Http\JsonResponse
    {
        $d = $this->request->all();
        $parameters = $this->request->query();
        $firm_id = $this->request->user()->firm_id;

        $offer = Bill::whereIn('status', ['Fatura Kesildi', 'ödendi', 'Müşteriye Gönderildi']);

        $offer = $offer->select(array(
          DB::raw('MONTH(bill_date) as `x`'),
          DB::raw('SUM(price) as `y`')
        ))
        ->groupBy('x')
        ->orderBy('x');

        if(isset($parameters['start_at'])){
          $offer = $offer->where('bill_date', '>=', date('Y', strtotime($parameters['start_at']))."-01-01" );
        }else{
          $offer = $offer->where('bill_date', '>=', date('Y')."-01-01");
        }

        $offer = $offer->get();
        return response()->json($offer);
    }
}
