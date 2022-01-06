<?php declare(strict_types = 1);

namespace App\Http\Controllers\Workflow\Bill;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Customer;
use App\Models\CustomerPersonel;
use App\Models\Project;
use App\Models\Offer;
use App\Models\Bill;
use App\Models\BillFile;
use App\Models\BillProject;
use App\Models\Notification;

use App\Models\Assembly;
use App\Models\Production;
use App\Models\Printing;

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
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;

use App\Imports\BillImport;

class BillController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index(): \Illuminate\Contracts\View\View
    {
      $parameters = $this->request->query();

      $page_title = 'Fatura Listesi';
      $page_description = 'Tüm faturaları görüntüleyip işlem yapabilirsiniz';

      if(isset($parameters['waiting'])){
        $page_title = 'Onay Bekleyen Faturalar';
        $page_description = 'Onayınızı bekleyen faturaları listeleyebilirsiniz.';
      }

      $firms = Customer::select('id as value', 'title as name')->get();
      $fairs = Project::select('id as value', 'title as name')->get();
      $statuses = Offer::select('status as value', 'status as name')->groupBy('status')->get();

      $responsibles = User::selectRaw('id as value, CONCAT(name, " ", surname) as name')->get();

      return view('workflow.bills.index', compact('page_title', 'page_description', 'fairs', 'firms', 'responsibles', 'statuses'));
    }

    public function json(): \Illuminate\Http\JsonResponse
    {
        $d = $this->request->all();
        $parameters = $this->request->query();
        $user_id = $this->request->user()->id;
        $user = $this->request->user();
        $edit_allowed = $user->power('bills', 'edit') ? true : false;
        $delete_allowed = $user->power('bills', 'delete') ? true : false;
        $detail_allowed = $user->power('bills', 'detail') ? true : false;

        $projelerim = $this->request->user()->projelerim();
        
        $data = Bill::whereIn('project_id', $projelerim)->with(['customer', 'project', 'responsible'])->where('status', '!=', 'Ödeme Planı');
        if(isset($parameters['customer_id'])){
            $data = $data->where('customer_id', $parameters['customer_id']);
        }
        if(isset($parameters['project_id'])){
            $data = $data->where('project_id', $parameters['project_id']);
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

        if($this->request->user()->isAdmin()||$this->request->user()->isAccountant()){
          
        }else{
          $data = $data->where('user_id', $user_id);
        }
        $data = $data->where('status', '!=', 'taslak');

        if(isset($parameters['waiting'])&&$user->group_id==1){
          $data = $data->where('status', 'Yönetici Onayında');
        }

        if(isset($parameters['waiting'])&&$user->group_id==4){
          $data = $data->where('status', 'Fatura Kesildi');
        }

        if(isset($parameters['waiting'])&&$user->group_id==5){
          $data = $data->where('status', 'Yönetici Onayladı');
        }

        return Datatables::of($data)
        ->addColumn('money_formatted', function($dt) use ($edit_allowed){
            return money_formatter($dt->price);
        })
        ->addColumn('messages', function($q) use ($user){
            return Notification::where('is_read', 0)->where('user_id', $user->id)->where('redirect', '/bills/detail/'.$q->id)->count();
        })
        ->addColumn('edit_allowed', function($dt) use ($edit_allowed){
          if($dt->status=='Yönetici Onayında'||$dt->status=='Hazırlanıyor'){
            return $edit_allowed;
          }
          return false;
        })->addColumn('delete_allowed', function($dt) use ($delete_allowed){
          if($dt->status=='Yönetici Onayında'||$dt->status=='Hazırlanıyor'){
            return $delete_allowed;
          }
          return false;
        })->addColumn('detail_allowed', function() use ($detail_allowed){
            return $detail_allowed;
        })->make(true);
    }

    public function upload(Request $request): \Illuminate\Http\JsonResponse
    {
        $d = $this->request->all();

          $file = $request->file('file');
          $filename = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))."_".time() . "." . $file->getClientOriginalExtension();
          $filePath = 'snap/bill';
          Storage::disk('s3')->putFileAs($filePath, $file, $filename, ['visibility' => 'public']);
          return response()->json($filename);
    }


    public function add(): \Illuminate\Contracts\View\View
    {
      $data = $this->request->all();
      $page_title = 'Yeni Fatura Ekle';
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

      $detail = new \stdClass();
      $fairs = new \stdClass();
      if(isset($data['customer_id'])){
          $detail->customer_id = $data['customer_id'];
          $fairs = Project::selectRaw('id as value, title as name')->where('customer_id', $data['customer_id'])->get();
      }
      if(isset($data['project_id'])){
          $detail->project_id = $data['project_id'];
      }

      return view('workflow.bills.add', compact('page_title', 'page_description', 'redirect', 'firms', 'currencies', 'vats', 'detail', 'fairs'));
    }

    public function update(int $bill_id): \Illuminate\Contracts\View\View
    {
      $detail = Bill::find($bill_id);
      $page_title = $detail->customer->title." ".$detail->project->title." Fatura Düzenle";
      $page_description = '';
      $redirect = url()->previous();

      $firms = Customer::select('id as value', 'title as name')->get();

      $fairs = Project::selectRaw('id as value, title as name')->where('customer_id', $detail->customer_id)->get();

      $bill_files = BillFile::where('bill_id', $bill_id)->get();
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

      $assemblies = Assembly::select('id as value', 'title as name')->where('customer_id', $detail->customer_id)->where('status', 'Tamamlandı')->get();
      $printings = Printing::select('id as value', 'title as name')->where('customer_id', $detail->customer_id)->where('status', 'Tamamlandı')->get();
      $productions = Production::select('id as value', 'title as name')->where('customer_id', $detail->customer_id)->where('status', 'Tamamlandı')->get();

      $personels = CustomerPersonel::select('id as value', DB::raw('CONCAT(name, " ", surname) as name'))->where('customer_id', $detail->customer_id)->get();
      return view('workflow.bills.add', compact('page_title', 'page_description', 'redirect', 'firms', 'currencies', 'vats', 'detail', 'fairs', 'bill_files', 'personels', 'assemblies', 'printings', 'productions'));
    }
    public function detail(int $bill_id): \Illuminate\Contracts\View\View
    {
      $detail = Bill::with(['files'])->find($bill_id);
      $page_title = $detail->customer->title." ".$detail->project->title." Fatura Detayları";
      $page_description = '';
      $redirect = url()->previous();

      $logs = Log::where('bill_id', $bill_id)->get();

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

      return view('workflow.bills.detail', compact('page_title', 'page_description', 'redirect', 'firms', 'currencies', 'vats', 'detail', 'logs'));
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
      $user = $this->request->user();
      
      if(isset($data['id'])){
        $bill = Bill::find($data['id']);
        if($bill->status=='Yönetici Onayladı'||$bill->status=='yonetici reddetti'){
            $bill->status = 'Yönetici Onayında';
        }else{
          if($user->group_id==1){
            $bill->status = $bill->status ?? 'Yönetici Onayladı';
          }else{
            $bill->status = $bill->status ?? 'Yönetici Onayında';
          }
        }
        if($bill->user_id!=$user_id && !$this->request->user()->isAdmin() && !$this->request->user()->isAccountant()){
          return response()->json([
              'message' => 'Düzenleme yetkiniz bulunmamaktadır!'
          ]);
        }
      }else{
          $bill = new Bill();
          $bill->status = $bill->status ?? 'Yönetici Onayında';
      }
      $bill->user_id = $user_id;
      $bill->customer_id = $data['customer_id'];
      $bill->bill_no = $data['bill_no'] ?? null;
      $bill->customer_personel_id = $data['customer_personel_id'] ?? null;
      $bill->project_id = $data['project_id'] ?? null;
      $bill->price = str_replace(",", ".", str_replace(".", "", $data['price']));
      $bill->vat = $data['vat'] ?? null;
      $bill->term = $data['term'] ?? null;
      $bill->description = $data['description'] ?? null;
      $bill->notes = $data['notes'] ?? null;
      $bill->status = $bill->status ?? 'Hazırlanıyor';
      $bill->bill_date = date("Y-m-d", strtotime($data['bill_date'])) ?? NULL;
      $bill->save();
      
      if(isset($data['files'])){
        $ar = array();
        foreach ($data["files"] as $d) {
            $bill_file = new BillFile();
            $bill_file->bill_id = $bill->id;
            $bill_file->filename = $d;
            $bill_file->save();
        }
      }

      $arr = [];
      if(isset($data['assembly'])){
        $ar = array();
        foreach ($data["assembly"] as $d) {
            $bill_file = new BillProject();
            $bill_file->bill_id = $bill->id;
            $bill_file->assembly_id = $d;
            $bill_file->save();

            $arr[] = $d;
        }
      }
      $sil = BillProject::where('bill_id', $bill->id)->whereNotNull('assembly_id')->whereNotIn('assembly_id', $arr)->delete();

      $arr = [];
      if(isset($data['production'])){
        $ar = array();
        foreach ($data["production"] as $d) {
            $bill_file = new BillProject();
            $bill_file->bill_id = $bill->id;
            $bill_file->production_id = $d;
            $bill_file->save();

            $arr[] = $d;
        }
      }
      $sil = BillProject::where('bill_id', $bill->id)->whereNotNull('production_id')->whereNotIn('assembly_id', $arr)->delete();

      $arr = [];
      if(isset($data['printing'])){
        $ar = array();
        foreach ($data["printing"] as $d) {
            $bill_file = new BillProject();
            $bill_file->bill_id = $bill->id;
            $bill_file->printing_id = $d;
            $bill_file->save();

            $arr[] = $d;
        }
      }
      $sil = BillProject::where('bill_id', $bill->id)->whereNotNull('printing_id')->whereNotIn('assembly_id', $arr)->delete();
      
      $title = isset($data['id']) ? 'Fatura Güncelleme' : 'Yeni fatura kaydı';
      $description = isset($data['id']) ? 'faturada güncelleme gerçekleştirdi' : 'yeni fatura kaydı oluşturdu';
      
      $log = new LogSet();
      $log->statusUpdates([
        'customer_id' => $data['customer_id'],
        'project_id' => $data['project_id'] ?? null,
        'bill_id' => $bill->id,
        'type' => 'bill',
        'title' => $title,
        'description' => $description,
        'status' => 'success'
      ]);

      $result = array(
          'status' => 1,
          'message' => 'Başarıyla kaydedildi. Yönlendiriliyorsunuz.',
          'redirect' => route('bill-detail', ['id' => $bill->id])
      );
      return response()->json($result);
    }
    public function billFiles($bill_id): \Illuminate\Http\JsonResponse
    {
      $data = $this->request->all();

      
      if(isset($data['files'])){
        $ar = array();
        foreach ($data["files"] as $d) {
            $bill_file = new BillFile();
            $bill_file->bill_id = $bill_id;
            $bill_file->filename = $d;
            $bill_file->save();
        }
      }else{
        return response()->json([
          'status' => 0,
          'message' => 'Fatura dosyası yükelmeden devam edemezsiniz',
        ]);
      }

      $bill = Bill::find($bill_id);
      $bill->status = 'Fatura Kesildi';
      $bill->save();
      
      $title = 'Fatura kopyaları eklendi';
      $description = 'Faturaya ait kopyalar oluşturuldu ve yüklendi.';
      
      $log = new LogSet();
      $log->statusUpdates([
        'customer_id' => $bill->customer_id,
        'project_id' => $bill->project_id ?? null,
        'bill_id' => $bill->id,
        'type' => 'bill',
        'title' => $title,
        'description' => $description,
        'status' => 'success'
      ]);

      $result = array(
          'status' => 1,
          'message' => 'Başarıyla kaydedildi. Yönlendiriliyorsunuz.',
          'redirect' => route('bill-detail', ['id' => $bill->id])
      );
      return response()->json($result);
    }

    public function sendCustomer($bill_id): \Illuminate\Http\JsonResponse
    {
      $data = $this->request->all();

      $user_id = $this->request->user()->id ?? null;

      $compose_to = json_decode($data['to'], true);
      $compose_to = implode(",", Arr::pluck($compose_to, 'value'));

      if(isset($data['cc'])){
        $compose_cc = json_decode($data['cc'], true);
        $compose_cc = implode(",", Arr::pluck($compose_cc, 'value'));
      }else{
        $compose_cc = null;
      }

      if(isset($data['bcc'])){
        $compose_bcc = json_decode($data['bcc'], true);
        $compose_bcc = implode(",", Arr::pluck($compose_bcc, 'value'));
      }else{
        $compose_bcc = null;
      }

        $bill = Bill::find($bill_id);
        $bill->status = 'Müşteriye Gönderildi';
        $bill->save();

        $title = $data['title'] ?? 'Fatura Bilgilendirme';

        $message = $data['message'] ?? null;
        $subject = $data['title'] ?? 'Fatura Bilgilendirme';
        
        $ddd = array('name'=>$subject, 'body' => $message);

        Mail::send('emails.mail', $ddd, function($message) use ($compose_to, $compose_cc, $compose_bcc, $title, $subject, $bill) {
          $message->to(explode(',', $compose_to));
          isset($compose_cc) ? $message->cc(explode(',', $compose_cc)) : '';
          isset($compose_bcc) ? $message->bcc(explode(',', $compose_bcc)) : '';
          
          foreach($bill->files as $bb){
            $alo = Storage::url('snap/brief/').$bb->filename;
            $content = file_get_contents($alo);

            File::put(public_path($bb->filename), $content);
            $message->attach(public_path($bb->filename));
          }

          $message->subject($subject);
          
          $message->from('hello@b166er.co', env('APP_NAME'));
        });
        

      $result = array(
        'status' => 1,
        'message' => 'Başarıyla kaydedildi'
      );
      return response()->json($result);
    }
    
    public function delete(string $bill_id): \Illuminate\Http\JsonResponse
    {
        $bill_id = (int) $bill_id;
        $user_id = $this->request->user()->id;

        $bill = Bill::find($bill_id);
        if($bill->user_id!=$user_id&&!$this->request->user()->isAdmin()&&!$this->request->user()->isAccountant()){
          return response()->json([
              'message' => 'Düzenleme yetkiniz bulunmamaktadır!'
          ]);
        }
        if($bill->status=='Hazırlanıyor'||$bill->status=='Yönetici Onayında'){
          $bill->delete();

          $title = 'Kayıt silindi!';
          $description = 'ilgili kaydı sildi.';
          
          $log = new LogSet();
          $log->statusUpdates([
            'customer_id' => $bill->customer_id,
            'project_id' => $bill->project_id ?? null,
            'bill_id' => $bill->id,
            'type' => 'bill',
            'title' => $title,
            'description' => $description,
            'status' => 'danger'
          ]);

        }else{
          $result = array(
              'status' => 0,
              'message' => 'Fatura işlemde olduğu için silinemez.'
          );
          return response()->json($result);
        }
        
        $result = array(
            'status' => 1,
            'message' => 'Fatura Başarıyla silindi.'
        );
        return response()->json($result);
    }

    public function deleteFile(string $bill_id): \Illuminate\Http\JsonResponse
    {
        $bill_id = (int) $bill_id;
        $user_id = $this->request->user()->id;

        $bill = BillFile::find($bill_id);
        if($bill->user_id!=$user_id&&!$this->request->user()->isAdmin()&&!$this->request->user()->isAccountant()){
          return response()->json([
              'message' => 'Düzenleme yetkiniz bulunmamaktadır!'
          ]);
        }
          $bill->delete();
          
        $result = array(
            'status' => 1,
            'message' => 'Fatura Başarıyla silindi.'
        );
        return response()->json($result);
    }

    public function updateStatus(string $bill_id): \Illuminate\Http\JsonResponse
    {
        $bill_id = (int) $bill_id;
        $user_id = $this->request->user()->id;
        $data = $this->request->all();

        $bill = Bill::find($bill_id);
        if($bill->user_id == $user_id || $this->request->user()->isAdmin()|| $this->request->user()->isAccountant()){
          $bill->status = $data['status'];
          $bill->save();

          $title = 'Statü güncellendi!';
          $description = 'statüyü '.$data['status'].' olarak güncelledi';
          
          $log = new LogSet();
          $log->statusUpdates([
            'customer_id' => $bill->customer_id,
            'project_id' => $bill->project_id ?? null,
            'bill_id' => $bill->id,
            'type' => 'bill',
            'title' => $title,
            'description' => $description,
            'status' => 'primary'
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
    public function upload2(Request $request)
    {
        $file = $request->file('file');
     
        //Move Uploaded File
        $destinationPath = 'uploads/bills';
        $filename = uniqid().".".$file->getClientOriginalExtension();
        $file->move($destinationPath,$filename);

        return response()->json(array('file' => $filename));
    }
    public function import(Request $request) 
    {
        $user_id = $this->request->user()->id;
        $data = $this->request->all();
        
        $file = $data['file'];
        $filename = 'uploads/bills/'.$file;

        Excel::import(new BillImport(), $filename);

        return response()->json(array('status' => 1));

    }
}