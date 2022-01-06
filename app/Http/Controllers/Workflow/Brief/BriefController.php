<?php declare(strict_types = 1);

namespace App\Http\Controllers\Workflow\Brief;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Brief;
use App\Models\BriefType;
use App\Models\BriefFile;
use App\Models\BriefDesignDetail;
use App\Models\BriefDesign;

use App\Models\Project;
use App\Models\Offer;
use App\Models\OfferFile;
use App\Models\Customer;
use App\Models\CustomerPersonel;
use App\Models\Notification;
use App\Models\Log;
use App\Core\LogSet;
use App\Models\StandType;
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
use Mail;
use Khsing\World\World;
use Khsing\World\Models\Continent;
use Khsing\World\Models\Country;

class BriefController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index(): \Illuminate\Contracts\View\View
    {
      $parameters = $this->request->query();

      $page_title = 'Brief Listesi';
      $page_description = 'Tüm briefleri görüntüleyip işlem yapabilirsiniz';

        if(isset($parameters['waiting'])){
            $page_title = 'Onay Bekleyen Briefler';
            $page_description = 'Onayınızı bekleyen briefleri listeleyebilirsiniz.';
        }

      $fairs = Project::select('id as value', 'title as name')->get();
      $firms = Customer::select('id as value', 'title as name')->get();
      $statuses = Brief::select('status as value', 'status as name')->groupBy('status')->get();

      $responsibles = User::selectRaw('id as value, CONCAT(name, " ", surname) as name')->get();
      $designers = User::selectRaw('id as value, CONCAT(name, " ", surname) as name')->where('group_id', 7)->whereIn('group_id', [1,2,3,4])->get();

      return view('workflow.briefs.index', compact('page_title', 'page_description', 'fairs', 'firms', 'responsibles', 'statuses', 'designers'));
    }

    public function json(): \Illuminate\Http\JsonResponse
    {
        $d = $this->request->all();
        $user = $this->request->user();
        $edit_allowed = $user->power('briefs', 'edit') ? true : false;
        $delete_allowed = $user->power('briefs', 'delete') ? true : false;
        $detail_allowed = $user->power('briefs', 'detail') ? true : false;

        $parameters = $this->request->query();

        $projelerim = $this->request->user()->projelerim();

        $data = Brief::whereIn('project_id', $projelerim)->with(['customer', 'project', 'responsible', 'designer']);
        
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

        if(isset($parameters['designer_id'])){
          $data = $data->where('designer_id', $parameters['designer_id']);
        }
        
        if(isset($parameters['waiting'])&&$user->group_id==4){
          $data = $data->where(function($q){
            $q->where('status', 'MT Onayında')->orWhere('status', 'Revize MT Onayında');
          })->where('user_id', $user->id);
        }

        if(isset($parameters['waiting'])&&$user->group_id==7){
          $data = $data->where('designer_status', '!=', 'Kabul Edildi')->where('designer_status', '!=', 'Reddedildi');
        }

        return Datatables::of($data)
        ->addColumn('messages', function($q) use ($user){
            return Notification::where('is_read', 0)->where('user_id', $user->id)->where('redirect', '/briefs/detail/'.$q->id)->count();
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
        $page_title = 'Yeni Brief Ekle';
        $page_description = '';
        $redirect = url()->previous();

      $detail = new \stdClass();
      $fairs = new \stdClass();

      $firms = null;
      $getfairs = null;
      $officers = null;

      if(isset($data['customer_id'])){
          $detail->customer_id = $data['customer_id'];
          $firms = Customer::select('id as value', 'title as name')->where('id', $data['customer_id'])->get();

          $fairs = Project::selectRaw('id as value, title as name')->where('customer_id', $data['customer_id'])->get();
      }
      if(isset($data['project_id'])){
          $detail->project_id = $data['project_id'];
      }

      $types = StandType::with(['parents.values'])->groupBy('title')->get();
      $designers = User::selectRaw('id as value, CONCAT(name, " ", surname) as name')->where('group_id', 7)->get();

      return view('workflow.briefs.add', compact('page_title', 'page_description', 'redirect', 'firms', 'types', 'designers', 'detail', 'fairs', 'officers'));
    }

    public function update(int $brief_id): \Illuminate\Contracts\View\View
    {
      $detail = Brief::find($brief_id);
      $page_title = $detail->customer->title." ".$detail->project->title." Brief Düzenle";
      $page_description = '';
      $redirect = url()->previous();

      $fairs = Project::selectRaw('id as value, title as name')->where('customer_id', $detail['customer_id'])->get();
      $type = StandType::with(['brieftype' => function($q) use ($brief_id){
        $q->where('brief_id', $brief_id)->pluck('value');
      }])->get()->keyBy('name');
      
      $firms = Customer::select('id as value', 'title as name')->get();
      $types = StandType::with(['parents.values'])->groupBy('title')->orderBy('priority')->get();
      $designers = User::selectRaw('id as value, CONCAT(name, " ", surname) as name')->where('group_id', 7)->get();

      $brief_file = BriefFile::where('brief_id', $brief_id)->get();
      $personels = CustomerPersonel::select('id as value', DB::raw('CONCAT(name, " ", surname) as name'))->where('customer_id', $detail->customer_id)->get();

      return view('workflow.briefs.add', compact('page_title', 'page_description', 'redirect', 'firms', 'types', 'designers', 'detail', 'fairs', 'type', 'brief_file', 'personels'));
    }
    public function detail(int $brief_id): \Illuminate\Contracts\View\View
    {
      $detail = Brief::find($brief_id);
      $page_title = $detail->customer->title." ".$detail->project->title." Brief Detayları";
      $page_description = '';
      $redirect = url()->previous();

      $fairs = Project::selectRaw('id as value, title as name')->where('customer_id', $detail['customer_id'])->get();
      $type = StandType::with(['brieftype' => function($q) use ($brief_id){
        $q->where('brief_id', $brief_id)->pluck('value');
      }])->get()->keyBy('name');

      $logs = Log::where('brief_id', $brief_id)->get();

      $firms = Customer::select('id as value', 'title as name')->get();
      $types = StandType::with(['parents.values'])->groupBy('title')->orderBy('priority')->get();
      $designers = User::selectRaw('id as value, CONCAT(name, " ", surname) as name')->get();

      $brief_file = BriefFile::where('brief_id', $brief_id)->get();

      return view('workflow.briefs.detail', compact('page_title', 'page_description', 'redirect', 'firms', 'types', 'designers', 'detail', 'fairs', 'type', 'brief_file', 'logs'));
    }

    public function designComments(int $design_id, int $comment_id): \Illuminate\Contracts\View\View
    {
      $d = BriefDesign::with(['comments' => function($q) use ($comment_id){
          $q->where('id', $comment_id);
      }])->find($design_id);

      $detail = Brief::find($d->brief_id);

      return view('workflow.briefs.brief-design-comment', compact('d', 'detail'));
    }
    public function comments(int $brief_id, int $comment_id): \Illuminate\Contracts\View\View
    {
      $detail = Brief::with(['comments' => function($q) use ($comment_id){
          $q->where('id', $comment_id);
      }])->find($brief_id);

      return view('workflow.briefs.brief-comment', compact('detail'));
    }

    public function save(Request $request): \Illuminate\Http\JsonResponse
    {
      $data = $this->request->all();

      $validator = Validator::make($data, [
          'customer_id' => 'required',
          'designer_id' => 'required',
          'hall_no' => 'required',
          'stand_no' => 'required',
          'deadline' => [
              'after_or_equal:today'
          ]
      ]);

      $niceNames = array(
        'customer_id' => 'Müşteri',
        'designer_id' => 'Tasarımcı',
        'deadline' => 'Tarih',
      );
      $validator->setAttributeNames($niceNames); 

      if(isset($data['files'])){
      if(!count($data['files'])){
        return response()->json([
            'message' => 'Kaydedebilmek için mutlaka dosya eklemelisiniz.'
        ]);
      }
    }

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
        $brief = Brief::find($data['id']);
        if($brief->user_id!=$user_id && !$this->request->user()->isAdmin()){
          return response()->json([
              'message' => 'Düzenleme yetkiniz bulunmamaktadır!'
          ]);
        }
        if($brief->designer_id!=$data['designer_id']){
            $brief->status = 'Bekliyor';
            $brief->designer_status = null;
        }else{
            $brief->status = $brief->status ?? 'Bekliyor';
        }
      }else{
          $brief = new Brief();
          $brief->status = 'Bekliyor';
      }

      $brief->user_id = $user_id;
      $brief->customer_id = $data['customer_id'];
      $brief->customer_personel_id = $data['customer_personel_id'] ?? null;
      $brief->designer_id = $data['designer_id'];
      $brief->project_id = $data['project_id'] ?? null;
      $brief->hall_no = $data['hall_no'];
      $brief->stand_no = $data['stand_no'];
      $brief->description = $data['description'] ?? null;
      $brief->deadline = date("Y-m-d", strtotime($data['deadline'])) ?? NULL;
      $brief->save();
      
      $title = isset($data['id']) ? 'Brief Güncelleme' : 'Yeni brief kaydı';
      $description = isset($data['id']) ? 'briefte güncelleme gerçekleştirdi' : 'yeni brief kaydı oluşturdu';
      
      $log = new LogSet();
      $log->statusUpdates([
          'customer_id' => $brief->customer_id,
          'project_id' => $brief->project_id ?? null,
          'brief_id' => $brief->id,
          'type' => 'brief',
          'title' => $title,
          'description' => $description,
          'status' => 'info'
      ]);

      
      $ar = array();
      if(isset($data['type'])){
        foreach ($data["type"] as $key => $value) {
          $bul = BriefType::where('brief_id', $brief->id)->where('key', $key)->where('value', $value)->first();
          if(!$bul){
            $brief_type = new BriefType();
            $brief_type->brief_id = $brief->id;
            $brief_type->key = $key;
            $brief_type->value = $value;
            $brief_type->save();
            $ar[] = $brief_type->id;
          }else{
            $ar[] = $bul->id;
          }
        }
        $yoket = BriefType::whereNotIn('id', $ar)->where('brief_id', $brief->id)->delete();
      }

      if(isset($data['files'])){
        $ar = array();
        foreach ($data["files"] as $d) {
            $brief_file = new BriefFile();
            $brief_file->brief_id = $brief->id;
            $brief_file->filename = $d;
            $brief_file->save();
        }
      }
      
      
      $result = array(
          'status' => 1,
          'message' => 'Başarıyla kaydedildi. Yönlendiriliyorsunuz.',
          'redirect' => route('brief-detail', ['id' => $brief->id])
      );
      return response()->json($result);
    }
    
    public function saveDesign(Request $request): \Illuminate\Http\JsonResponse
    {
      $data = $this->request->all();

      $validator = Validator::make($data, [
          'id' => 'required',
      ]);

      if ($validator->fails()) {
          return response()->json([
              'message' => 'Lütfen tüm zorunlu alanları doldurun',
              'errors' => $validator->errors(),
          ]);
      }

      $user_id = $this->request->user()->id;

      $briefbul = Brief::find($data['id']);
      if($briefbul['status']=='revize'){
        $briefbul->status = 'tasarım iletildi';
        $briefbul->save();
      }

      $brief = new BriefDesign();
      $brief->designer_id = $user_id;
      $brief->brief_id = $data['id'];
      $brief->comment = $data['comment'] ?? null;
      $brief->is_active = 1;
      $brief->save();

      $yoket = BriefDesign::where('brief_id', $data['id'])->update(array('is_active' => 0));

      if(isset($data['files'])){
        $ar = array();
        foreach ($data["files"] as $d) {
            $brief_file = new BriefDesignDetail();
            $brief_file->brief_id = $data['id'];
            $brief_file->design_id = $brief->id;
            $brief_file->file = $d;
            $brief_file->save();
        }
      }
      
      $result = array(
          'status' => 1,
          'message' => 'Başarıyla kaydedildi. Yönlendiriliyorsunuz.'
      );
      return response()->json($result);
    }
    public function delete(string $brief_id): \Illuminate\Http\JsonResponse
    {
        $brief_id = (int) $brief_id;
        $user_id = $this->request->user()->id;

        $brief = Brief::find($brief_id);
        if($brief->user_id!=$user_id && !$this->request->user()->isAdmin()){
          return response()->json([
              'message' => 'Düzenleme yetkiniz bulunmamaktadır!'
          ]);
        }
        if($brief->status=='Bekliyor' && $brief->designer_status==NULL){
          $brief->delete();

          $title = 'Brief Silindi!';
          $description = 'brief kaydını sildi!';
          
          $log = new LogSet();
          $log->statusUpdates([
              'customer_id' => $brief->customer_id,
              'project_id' => $brief->project_id ?? null,
              'brief_id' => $brief->id,
              'type' => 'brief',
              'title' => $title,
              'description' => $description,
              'status' => 'danger'
          ]);

        }else{
          $result = array(
              'status' => 0,
              'message' => 'Brief işlemde olduğu için silinemez.'
          );
          return response()->json($result);
        }
        
        $result = array(
            'status' => 1,
            'message' => 'Firma Başarıyla silindi.'
        );
        return response()->json($result);
    }

    public function deleteFile(string $file_id): \Illuminate\Http\JsonResponse
    {
        $file_id = (int) $file_id;

        $find_file = BriefFile::find($file_id);
        $find_file->delete();
        
        $result = array(
            'status' => 1,
            'message' => 'Dosya Başarıyla silindi.'
        );
        return response()->json($result);
    }

    public function designerStatus(string $brief_id): \Illuminate\Http\JsonResponse
    {
        $brief_id = (int) $brief_id;
        $user_id = $this->request->user()->id;
        $data = $this->request->all();

        $brief = Brief::find($brief_id);
        if($brief->designer_id == $user_id){
          $brief->designer_status = $data['status'];
          $brief->save();

          $title = 'Brief statüsü güncellendi!';
          $description = "briefi ".$data['status'];
          
          $log = new LogSet();
          $log->statusUpdates([
              'customer_id' => $brief->customer_id,
              'project_id' => $brief->project_id ?? null,
              'brief_id' => $brief->id,
              'type' => 'brief',
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
    public function updateStatus(string $brief_id): \Illuminate\Http\JsonResponse
    {
        $brief_id = (int) $brief_id;
        $user_id = $this->request->user()->id;
        $data = $this->request->all();

        $brief = Brief::find($brief_id);
        if($brief->user_id == $user_id || $this->request->user()->isAdmin()|| $this->request->user()->isDesigner()){
          $brief->status = $data['status'];
          $brief->save();


          if($data['status'] == 'Onaylandı'){
            $offer = new Offer();
            $offer->customer_id = $brief->customer_id;
            $offer->project_id = $brief->project_id ?? null;
            $offer->user_id = $brief->user_id;
            $offer->brief_id = $brief->id;
            $offer->status = "Hazırlanıyor";
            $offer->save();

            $title = 'Yeni kayıt!';
            $description = 'brieften sözleşmeye taşıdı.';
            
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

            $brief_file = BriefDesign::where('brief_id', $brief->id)->where('is_active', 1)->first();
            $designs = BriefDesignDetail::where('design_id', $brief_file->id)->get();
            foreach($designs as $d){
              $offer_file = new OfferFile();
              $offer_file->offer_id = $offer->id;
              $offer_file->filename = $d->file;
              $offer_file->type = 'design';
              $offer_file->save();
            }

            $title = 'Brief statüsü güncellendi!';
            $description = 'brief statüsünü '.$data['status'].' olarak güncelledi';
            
            $log = new LogSet();
            $log->statusUpdates([
                'customer_id' => $brief->customer_id,
                'project_id' => $brief->project_id ?? null,
                'brief_id' => $brief->id,
                'type' => 'brief',
                'title' => $title,
                'description' => $description,
                'status' => 'success'
            ]);

          }else{
            $title = 'Brief statüsü güncellendi!';
            $description = 'brief statüsünü '.$data['status'].' olarak güncelledi';
            
            $log = new LogSet();
            $log->statusUpdates([
                'customer_id' => $brief->customer_id,
                'project_id' => $brief->project_id ?? null,
                'brief_id' => $brief->id,
                'type' => 'brief',
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