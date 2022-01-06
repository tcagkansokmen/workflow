<?php declare(strict_types = 1);

namespace App\Http\Controllers\Workflow\Offer;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Customer;
use App\Models\CustomerPersonel;
use App\Models\Project;
use App\Models\Offer;
use App\Models\OfferFile;
use App\Models\OfferMessage;
use App\Models\Notification;
use App\Notifications\NewVendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\Log;
use App\Core\LogSet;
use DB;
use DataTables;
use Mail;
use Khsing\World\World;
use Khsing\World\Models\Continent;
use Khsing\World\Models\Country;

class OfferController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index(): \Illuminate\Contracts\View\View
    {
      $parameters = $this->request->query();
      
      $page_title = 'Teklif Listesi';
      $page_description = 'Tüm teklifleri görüntüleyip işlem yapabilirsiniz';

      if(isset($parameters['waiting'])){
        $page_title = 'Onay Bekleyen Teklifler';
        $page_description = 'Onayınızı bekleyen teklifleri listeleyebilirsiniz.';
      }

      $fairs = Project::select('id as value', 'title as name')->get();
      $firms = Customer::select('id as value', 'title as name')->get();
      $statuses = Offer::select('status as value', 'status as name')->groupBy('status')->get();

      $responsibles = User::selectRaw('id as value, CONCAT(name, " ", surname) as name')->get();

      return view('workflow.offers.index', compact('page_title', 'page_description', 'fairs', 'firms', 'responsibles', 'statuses'));
    }

    public function json(): \Illuminate\Http\JsonResponse
    {
      $d = $this->request->all();
      $parameters = $this->request->query();
      $user = $this->request->user();
      $edit_allowed = $user->power('offers', 'edit') ? true : false;
      $delete_allowed = $user->power('offers', 'delete') ? true : false;
      $detail_allowed = $user->power('offers', 'detail') ? true : false;

      $projelerim = $this->request->user()->projelerim();

      $data = Offer::whereIn('project_id', $projelerim)->with(['customer', 'project', 'responsible', 'brief']);
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
        $data = $data->where('status', 'Yönetici Onayında');
      }

      if(isset($parameters['waiting'])&&$user->group_id==4){
        $data = $data->where('status', 'Yönetici Onayladı')->where('user_id', $user->id);
      }

      return Datatables::of($data)
      ->addColumn('messages', function($q) use ($user){
          return Notification::where('is_read', 0)->where('user_id', $user->id)->where('redirect', '/offers/detail/'.$q->id)->count();
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
      $page_title = 'Yeni Teklif Ekle';
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

      return view('workflow.offers.add', compact('page_title', 'page_description', 'redirect', 'firms', 'currencies', 'vats', 'detail', 'fairs'));
    }

    public function update(int $offer_id): \Illuminate\Contracts\View\View
    {
      $detail = Offer::find($offer_id);
      $page_title = $detail->customer->title." ".$detail->project->title." Teklif Düzenle";
      $page_description = '';
      $redirect = url()->previous();

      $firms = Customer::select('id as value', 'title as name')->get();

      $fairs = Project::selectRaw('id as value, title as name')->where('customer_id', $detail->customer_id)->get();

      $offer_files = OfferFile::where('offer_id', $offer_id)->get();
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
      return view('workflow.offers.add', compact('page_title', 'page_description', 'redirect', 'firms', 'currencies', 'vats', 'detail', 'fairs', 'offer_files', 'personels'));
    }
    public function detail(int $brief_id): \Illuminate\Contracts\View\View
    {
      $detail = Offer::with(['comments' => function($q){
          $q->where('type', 'offer');
      }])->find($brief_id);
      $page_title = $detail->customer->title." ".$detail->project->title." Teklif Detayları";
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

      return view('workflow.offers.detail', compact('page_title', 'page_description', 'redirect', 'firms', 'currencies', 'vats', 'detail', 'logs'));
    }

    public function message(int $offer_id): \Illuminate\Contracts\View\View
    {
      $detail = Offer::find($offer_id);
      $page_title = $detail->customer->title." ".$detail->project->title." Müşteri Yazışmaları";
      $page_description = '';
      $redirect = route('offer-detail', ['id' => $offer_id]);

      $messages = OfferMessage::where('offer_id', $offer_id)->where('type', 'offer')->orderBy('id', 'desc')->get();

      return view('workflow.offers.message', compact('page_title', 'page_description', 'redirect', 'detail', 'messages'));
    }

    public function musteriTeklif(string $hash): \Illuminate\Contracts\View\View
    {
        $explode = explode('.', $hash);

      $detail = Offer::where('customer_id', $explode[0])->where('project_id', $explode[1])->where('id', $explode[2])->first();
      $page_title = $detail->customer->title." ".$detail->project->title." Müşteri Yazışmaları";
      $page_description = '';
      $redirect = route('offer-detail', ['id' => $detail->id]);

      $messages = OfferMessage::where('offer_id', $detail->id)->where('type', 'offer')->get();

      return view('workflow.offers.customer-message', compact('page_title', 'page_description', 'redirect', 'detail', 'messages'));
    }

    public function musteriSozlesme(string $hash): \Illuminate\Contracts\View\View
    {
      $explode = explode('.', $hash);
      $detail = Offer::where('customer_id', $explode[0])->where('project_id', $explode[1])->where('id', $explode[2])->first();
      $page_title = $detail->customer->title." ".$detail->project->title." Müşteri Yazışmaları";
      $page_description = '';
      $redirect = route('contract-detail', ['id' => $detail->id]);

      $messages = OfferMessage::where('offer_id', $detail->id)->where('type', 'contract')->get();

      return view('workflow.offers.message', compact('page_title', 'page_description', 'redirect', 'detail', 'messages'));
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

      $user_id = $this->request->user()->id;
      
      /*$file = $request->file('kt_user_add_user_avatar');
      if(isset($data['kt_user_add_user_avatar'])){
        $filename = time() . "." . $file->getClientOriginalExtension();
        $filePath = 'uploads/event';
        Storage::disk('s3')->putFileAs($filePath, $file, $filename, ['visibility' => 'public']);
      }*/

      if(isset($data['id'])){
        $offer = Offer::find($data['id']);
        $offer->status = 'Hazırlanıyor';
        if($offer->status=='Yönetici Onayladı'||$offer->status=='yonetici reddetti'){
            //$offer->status = 'Yönetici Onayında';
        }else{
            //$offer->status = $offer->status ?? 'Hazırlanıyor';
        }
        if($offer->user_id!=$user_id && !$this->request->user()->isAdmin()){
          return response()->json([
              'message' => 'Düzenleme yetkiniz bulunmamaktadır!'
          ]);
        }
      }else{
          $offer = new Offer();
          $offer->status = $offer->status ?? 'Hazırlanıyor';
      }
      $offer->user_id = $user_id;
      $offer->customer_id = $data['customer_id'];
      $offer->customer_personel_id = $data['customer_personel_id'] ?? null;
      $offer->project_id = $data['project_id'] ?? null;
      $offer->user_id = $user_id;
      $offer->price = str_replace(",", ".", str_replace(".", "", $data['price']));
      $offer->currency = $data['currency'];
      $offer->vat = $data['vat'] ?? null;
      $offer->deadline = date("Y-m-d", strtotime($data['deadline'])) ?? NULL;
      $offer->save();
      
      if(isset($data['files'])){
        $ar = array();
        foreach ($data["files"] as $d) {
            $offer_file = new OfferFile();
            $offer_file->offer_id = $offer->id;
            $offer_file->filename = $d;
            $offer_file->type = 'file';
            $offer_file->save();
        }
      }
      
      $title = isset($data['id']) ? 'Teklif Güncelleme' : 'Yeni teklif kaydı';
      $description = isset($data['id']) ? 'teklifte güncelleme gerçekleştirdi' : 'yeni teklif kaydı oluşturdu';
      
      $log = new LogSet();
      $log->statusUpdates([
          'customer_id' => $offer->customer_id,
          'project_id' => $offer->project_id ?? null,
          'offer_id' => $offer->id,
          'type' => 'offer',
          'title' => $title,
          'description' => $description,
          'status' => 'primary'
      ]);
      
      $result = array(
          'status' => 1,
          'message' => 'Başarıyla kaydedildi. Yönlendiriliyorsunuz.',
          'redirect' => route('offer-detail', ['id' => $offer->id])
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
        if($brief->status=='Hazırlanıyor'||$brief->status==''){
          $brief->delete();

          $title = 'Teklif silindi!';
          $description = 'teklif kaydını sildi.';
          
          $log = new LogSet();
          $log->statusUpdates([
              'customer_id' => $brief->customer_id,
              'project_id' => $brief->project_id ?? null,
              'offer_id' => $brief->id,
              'type' => 'offer',
              'title' => $title,
              'description' => $description,
              'status' => 'danger'
          ]);

        }else{
          $result = array(
              'status' => 0,
              'message' => 'Teklif işlemde olduğu için silinemez.'
          );
          return response()->json($result);
        }
        
        $result = array(
            'status' => 1,
            'message' => 'Teklif Başarıyla silindi.'
        );
        return response()->json($result);
    }

    public function updateStatus(string $offer_id): \Illuminate\Http\JsonResponse
    {
        $offer_id = (int) $offer_id;
        $user_id = $this->request->user()->id;
        $data = $this->request->all();

        $offer = Offer::find($offer_id);
        if($data['status']=='Yönetici Onayında'&&$offer['price']==0){
          $result = array(
              'status' => 0,
              'message' => 'Düzenlemeye yetkiniz yok!.'
          );
          return response()->json($result);
        }
        if($offer->user_id == $user_id || $this->request->user()->isAdmin()){
          $offer->status = $data['status'];

          if($data['status'] == 'müşteri onayladı'){
              $offer->contract_status = 'Hazırlanıyor';
          }
          $offer->save();


          $title = 'Teklif statüsü güncellendi!';
          $description = 'teklif statüsü '.$data['status'].' olarak güncellendi';
          
          $log = new LogSet();
          $log->statusUpdates([
              'customer_id' => $offer->customer_id,
              'project_id' => $offer->project_id ?? null,
              'offer_id' => $offer->id,
              'type' => 'offer',
              'title' => $title,
              'description' => $description,
              'status' => 'info'
          ]);

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