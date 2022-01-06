<?php declare(strict_types = 1);

namespace App\Http\Controllers\Workflow\Contract;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Customer;
use App\Models\CustomerPersonel;
use App\Models\Project;
use App\Models\Offer;
use App\Models\OfferFile;
use App\Models\OfferMessage;
use App\Models\Bill;
use App\Models\Notification;
use App\Models\Log;
use App\Core\LogSet;
use App\Notifications\NewVendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use DB;
use DataTables;
use Khsing\World\World;
use Khsing\World\Models\Continent;
use Khsing\World\Models\Country;

class ContractController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index(): \Illuminate\Contracts\View\View
    {
      $parameters = $this->request->query();

      $page_title = 'Sözleşme Listesi';
      $page_description = 'Tüm sözleşme görüntüleyip işlem yapabilirsiniz';

      if(isset($parameters['waiting'])){
        $page_title = 'Onay Bekleyen Sözleşmeler';
        $page_description = 'Onayınızı bekleyen sözleşmeleri listeleyebilirsiniz.';
      }

      $fairs = Project::select('id as value', 'title as name')->get();
      $firms = Customer::select('id as value', 'title as name')->get();
      $statuses = Offer::select('contract_status as value', 'contract_status as name')->groupBy('contract_status')->get();

      $responsibles = User::selectRaw('id as value, CONCAT(name, " ", surname) as name')->get();

      return view('workflow.contracts.index', compact('page_title', 'page_description', 'fairs', 'firms', 'responsibles', 'statuses'));
    }

    public function json(): \Illuminate\Http\JsonResponse
    {
        $d = $this->request->all();
        $parameters = $this->request->query();
        $user = $this->request->user();
        $edit_allowed = $user->power('contracts', 'edit') ? true : false;
        $delete_allowed = $user->power('contracts', 'delete') ? true : false;
        $detail_allowed = $user->power('contracts', 'detail') ? true : false;

        $projelerim = $this->request->user()->projelerim();

        $data = Offer::whereIn('project_id', $projelerim)->whereNotNull('contract_status')->with(['customer', 'project', 'responsible', 'brief']);
        if(isset($parameters['project_id'])){
            $data = $data->where('project_id', $parameters['project_id']);
        }
        if(isset($parameters['customer_id'])){
            $data = $data->where('customer_id', $parameters['customer_id']);
        }
        if(isset($parameters['status'])){
            $data = $data->where('status', $parameters['status']);
        }

        if(isset($parameters['start_at'])){
          $data = $data->where('deadline', '>', date('Y-m-d', strtotime($parameters['start_at'])));
        }

        if(isset($parameters['end_at'])){
          $data = $data->where('deadline', '<', date('Y-m-d', strtotime($parameters['end_at'])));
        }
  
        if(isset($parameters['user_id'])){
          $data = $data->where('user_id', $parameters['user_id']);
        }

        if(isset($parameters['waiting'])&&$user->group_id==1){
          $data = $data->where('contract_status', 'Yönetici Onayında');
        }

        if(isset($parameters['waiting'])&&$user->group_id==4){
          $data = $data->where('contract_status', 'Yönetici Onayladı')->where('user_id', $user->id);
        }

        return Datatables::of($data)
        ->addColumn('messages', function($q) use ($user){
            return Notification::where('is_read', 0)->where('user_id', $user->id)->where('redirect', '/contracts/detail/'.$q->id)->count();
        })
        ->addColumn('edit_allowed', function() use ($edit_allowed){
            return $edit_allowed;
        })->addColumn('delete_allowed', function() use ($delete_allowed){
            return $delete_allowed;
        })->addColumn('detail_allowed', function() use ($detail_allowed){
            return $detail_allowed;
        })->make(true);
    }

    public function upload(Request $request): \Illuminate\Http\JsonResponse
    {
        $d = $this->request->all();

          $file = $request->file('file');
          $filename = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))."_".time() . "." . $file->getClientOriginalExtension();
          $filePath = 'snap/brief';
          Storage::disk('s3')->putFileAs($filePath, $file, $filename, ['visibility' => 'public']);
          return response()->json($filename);
    }



    public function add(): \Illuminate\Contracts\View\View
    {
      $data = $this->request->all();
      $page_title = 'Yeni Sözleşme Ekle';
      $page_description = '';
      $redirect = url()->previous();

      $currencies = array(
        array('value' => 'TRY', 'name' => 'TRY'),
        array('value' => 'USD', 'name' => 'USD'),
        array('value' => 'EUR', 'name' => 'EUR'),
      );
      
      $vats = array(
        array('value' => '0', 'name' => '0'),
        array('value' => '1', 'name' => '1'),
        array('value' => '8', 'name' => '8'),
        array('value' => '18', 'name' => '18'),
      );

      $detail = new \stdClass();
      $fairs = new \stdClass();
      $firms = null;
      if(isset($data['customer_id'])){
          $detail->customer_id = $data['customer_id'];
          $firms = Customer::select('id as value', 'title as name')->where('id', $data['customer_id'])->get();
          
          $fairs = Project::selectRaw('id as value, title as name')->where('customer_id', $data['customer_id'])->get();
      }
      if(isset($data['project_id'])){
          $detail->project_id = $data['project_id'];
      }

      return view('workflow.contracts.add', compact('page_title', 'page_description', 'redirect', 'firms', 'currencies', 'vats', 'detail', 'fairs'));
    }

    public function update(int $offer_id): \Illuminate\Contracts\View\View
    {
      $detail = Offer::find($offer_id);
      $page_title = $detail->customer->title." ".$detail->project->title." Sözleşme Düzenle";
      $page_description = '';
      $redirect = url()->previous();

      $firms = Customer::select('id as value', 'title as name')->get();

      $fairs = Project::selectRaw('id as value, title as name')->where('customer_id', $detail['customer_id'])->get();
      $bills = Bill::where('offer_id', $offer_id)->get();

      $offer_files = Offerfile::where('offer_id', $offer_id)->where('type', 'contract')->get();
      $currencies = array(
        array('value' => 'TRY', 'name' => 'TRY'),
        array('value' => 'USD', 'name' => 'USD'),
        array('value' => 'EUR', 'name' => 'EUR'),
      );
      
      $vats = array(
        array('value' => '0', 'name' => '0'),
        array('value' => '1', 'name' => '1'),
        array('value' => '8', 'name' => '8'),
        array('value' => '18', 'name' => '18'),
      );

      $personels = CustomerPersonel::select('id as value', DB::raw('CONCAT(name, " ", surname) as name'))->where('customer_id', $detail->customer_id)->get();

      return view('workflow.contracts.add', compact('page_title', 'page_description', 'redirect', 'firms', 'currencies', 'vats', 'detail', 'fairs', 'offer_files', 'bills', 'personels'));
    }
    public function detail(int $brief_id): \Illuminate\Contracts\View\View
    {
      $detail = Offer::with(['comments' => function($q){
          $q->where('type', 'contract');
      }, 'files' => function($q){
        $q->where('type', 'contract');
      }])->find($brief_id);
      $page_title = $detail->customer->title." ".$detail->project->title." Sözleşme Detayları";
      $page_description = '';
      $redirect = url()->previous();

      $firms = Customer::select('id as value', 'title as name')->get();
      $currencies = array(
        array('value' => 'TRY', 'name' => 'TRY'),
        array('value' => 'USD', 'name' => 'USD'),
        array('value' => 'EUR', 'name' => 'EUR'),
      );
      
      $vats = array(
        array('value' => '0', 'name' => '0'),
        array('value' => '1', 'name' => '1'),
        array('value' => '8', 'name' => '8'),
        array('value' => '18', 'name' => '18'),
      );

      $logs = Log::where('offer_id', $brief_id)->get();
      $bills = Bill::where('offer_id', $brief_id)->get();

      return view('workflow.contracts.detail', compact('page_title', 'page_description', 'redirect', 'firms', 'currencies', 'vats', 'detail', 'logs', 'bills'));
    }

    public function message(int $offer_id): \Illuminate\Contracts\View\View
    {
      $detail = Offer::with(['files' => function($q){
        $q->where('type', 'contract');
      }])->find($offer_id);
      $page_title = $detail->customer->title." ".$detail->project->title." Sözleşme Müşteri Yazışmaları";
      $page_description = '';
      $redirect = route('contract-detail', ['id' => $offer_id]);

      $messages = OfferMessage::where('offer_id', $offer_id)->where('type', 'contract')->orderBy('id', 'desc')->get();

      return view('workflow.contracts.message', compact('page_title', 'page_description', 'redirect', 'detail', 'messages'));
    }

    public function save(Request $request): \Illuminate\Http\JsonResponse
    {
      $data = $this->request->all();

      $validator = Validator::make($data, [
          'customer_id' => 'required',
          'price' => 'required',
          'currency' => 'required',
          'vat' => 'required',
      ]);

      $niceNames = array(
        'customer_id' => 'Müşteri',
        'price' => 'Tutar',
        'currency' => 'Para birimi',
        'start_at' => 'Başlangıç tarihi',
        'vat' => 'KDV',
      );

    $validator->setAttributeNames($niceNames);
    
      if ($validator->fails()) {
          return response()->json([
            'message' => error_formatter($validator),
              'errors' => $validator->errors(),
          ]);
      }
      if(!isset($data['contract'])){
        return response()->json([
            'message' => 'Sözleşme nüshasını eklemeden devam edemezsiniz.',
            'errors' => $validator->errors(),
        ]);
      }
      $total = 0;
      if(isset($data['odeme'])){
        foreach ($data["odeme"] as $d) {
          if($d['price']&&$d['price']!='0.00'){
            $total += money_deformatter($d['price']);
          }
        }
      }

      if($total!=money_deformatter($data['price'])){
        return response()->json([
            'message' => 'Sözleşme tutarı ile, ödeme vadelerindeki tutarlar uyuşmamaktadır.'
        ]);
      }

      $user_id = $this->request->user()->id;
      
      $file = $request->file('contract');
      if(isset($data['contract'])){
        $file = $request->file('contract');
        $filename = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))."_".time() . "." . $file->getClientOriginalExtension();
        $filePath = 'snap/contract';
        Storage::disk('s3')->putFileAs($filePath, $file, $filename, ['visibility' => 'public']);
      }

      if(isset($data['id'])){
        $offer = Offer::find($data['id']);
        $offer->contract_status = 'Hazırlanıyor';
        if($offer->contract_status=='Yönetici Onayladı'||$offer->contract_status=='yonetici reddetti'){
            //$offer->contract_status = 'Yönetici Onayında';
        }else{
            //$offer->contract_status = $offer->contract_status ?? 'Hazırlanıyor';
        }
        if($offer->user_id!=$user_id && !$this->request->user()->isAdmin()){
          return response()->json([
              'message' => 'Düzenleme yetkiniz bulunmamaktadır!'
          ]);
        }
      }else{
          $offer = new Offer();
          $offer->contract_status = $offer->contract_status ?? 'Hazırlanıyor';
      }
      $offer->user_id = $user_id;
      $offer->customer_id = $data['customer_id'];
      $offer->customer_personel_id = $data['customer_personel_id'] ?? null;
      $offer->project_id = $data['project_id'] ?? null;
      $offer->user_id = $user_id;
      $offer->price = str_replace(",", ".", str_replace(".", "", $data['price']));
      $offer->currency = $data['currency'];
      $offer->vat = $data['vat'] ?? null;
      $offer->contract = $filename ?? null;
      $offer->contract_status = $offer->contract_status ?? 'Hazırlanıyor';
      $offer->deadline = date("Y-m-d", strtotime($data['deadline'])) ?? NULL;
      $offer->save();
      
      if(isset($data['files'])){
        $ar = array();
        foreach ($data["files"] as $d) {
            $offer_file = new OfferFile();
            $offer_file->offer_id = $offer->id;
            $offer_file->filename = $d;
            $offer_file->type = 'contract';
            $offer_file->save();
        }
      }

      $arr = array();
      if(isset($data['odeme'])){
        $ar = array();
        foreach ($data["odeme"] as $d) {
          if($d['price']&&$d['price']!='0.00'){
            if(isset($d['id'])){
                $bill = Bill::find($d['id']);
            }else{
                $bill = new Bill();
            }
            $bill->customer_id = $data['customer_id'];
            $bill->project_id = $data['project_id'] ?? null;
            $bill->user_id = $user_id;
            $bill->offer_id = $offer->id;
            $bill->price = money_deformatter($d['price']);
            $bill->vat = $data['vat'] ?? null;
            $bill->bill_date = date_deformatter($d['vade']) ?? NULL;
            $bill->status = "Ödeme Planı";
            $bill->save();

            $arr[] = $bill->id;
          }
        }
        $silabi = Bill::where('offer_id', $offer->id)->whereNotIn('id', $arr)->delete();
      }
      
      $title = isset($data['id']) ? 'Sözleşme Güncelleme' : 'Yeni sözleşme kaydı';
      $description = isset($data['id']) ? 'sözleşmede güncelleme gerçekleştirdi' : 'yeni sözleşme kaydı oluşturdu';
      
      $log = new LogSet();
      $log->statusUpdates([
          'customer_id' => $offer->customer_id,
          'project_id' => $offer->project_id ?? null,
          'offer_id' => $offer->id,
          'type' => 'contract',
          'title' => $title,
          'description' => $description,
          'status' => 'info'
      ]);


      $result = array(
          'status' => 1,
          'message' => 'Başarıyla kaydedildi. Yönlendiriliyorsunuz.',
          'redirect' => route('contract-detail', ['id' => $offer->id])
      );
      return response()->json($result);
    }
    
    public function delete(string $brief_id): \Illuminate\Http\JsonResponse
    {
        $brief_id = (int) $brief_id;
        $user_id = $this->request->user()->id;

        $brief = Offer::find($brief_id);
        if($brief->user_id!=$user_id && !$this->request->user()->isAdmin()){
          return response()->json([
              'message' => 'Düzenleme yetkiniz bulunmamaktadır!'
          ]);
        }
        if($brief->contract_status=='Hazırlanıyor'||$brief->contract_status==''){
          $brief->delete();

          $title = 'Sözleşme silindi!';
          $description = 'sözleşmeyi sildi!';
          
          $log = new LogSet();
          $log->statusUpdates([
              'customer_id' => $brief->customer_id,
              'project_id' => $brief->project_id ?? null,
              'offer_id' => $brief->id,
              'type' => 'contract',
              'title' => $title,
              'description' => $description,
              'status' => 'info'
          ]);

        }else{
          $result = array(
              'status' => 0,
              'message' => 'Sözleşme işlemde olduğu için silinemez.'
          );
          return response()->json($result);
        }
        
        $result = array(
            'status' => 1,
            'message' => 'Sözleşme Başarıyla silindi.'
        );
        return response()->json($result);
    }

    public function updateStatus(string $offer_id): \Illuminate\Http\JsonResponse
    {
        $offer_id = (int) $offer_id;
        $user_id = $this->request->user()->id;
        $data = $this->request->all();

        $offer = Offer::find($offer_id);
        if($offer->user_id == $user_id || $this->request->user()->isAdmin()){
          $offer->contract_status = $data['status'];
          $offer->save();

          $title = 'Sözleşme statüsü güncellendi!';
          $description = 'sözleşme statüsünü '.$data['status'].' olarak güncelledi.';
          
          $log = new LogSet();
          $log->statusUpdates([
              'customer_id' => $offer->customer_id,
              'project_id' => $offer->project_id ?? null,
              'offer_id' => $offer->id,
              'type' => 'contract',
              'title' => $title,
              'description' => $description,
              'status' => 'primary'
          ]);

          if($data['status'] == 'müşteri onayladı'){
            $yoket = Bill::where('offer_id', $offer->id)->update(array('status' => 'Hazırlanıyor'));

          $title = 'Yeni fatura!';
          $description = 'sözleşmeyi faturaya taşıdı.';
          
          $log = new LogSet();
          $log->statusUpdates([
              'customer_id' => $offer->customer_id,
              'project_id' => $offer->project_id ?? null,
              'bill_id' => null,
              'type' => 'contract',
              'title' => $title,
              'description' => $description,
              'status' => 'primary'
          ]);

          }

          $result = array(
              'status' => 1,
              'message' => 'Güncellendi.'
          );
          return response()->json($result);
        }else{
          $result = array(
              'status' => 0,
              'message' => 'Düzenlemeye yetkiniz yok!.'
          );
          return response()->json($result);
        }
        
    }
}