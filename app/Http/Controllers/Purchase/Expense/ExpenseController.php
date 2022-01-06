<?php

namespace App\Http\Controllers\Purchase\Expense;

use App\Http\Controllers\Controller;

use App\Models\Expense;
use App\Models\Supplier;
use App\Models\User;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Core\InventoryLog;
use App\Core\LogSet;
use DataTables;
use DB;

class ExpenseController extends Controller
{
  protected $request;

  public function __construct(Request $request)
  {
      $this->request = $request;
  }

  public function index(): \Illuminate\Contracts\View\View
  {
    $parameters = $this->request->query();

    $page_title = 'Tedarikçi Faturaları';
    $page_description = 'Tüm faturaları görüntüleyip işlem yapabilirsiniz';

    if(isset($parameters['waiting'])){
      $page_title = 'Onay Bekleyen Tedarikçi Faturaları';
      $page_description = 'Onayınızı bekleyen tedarikçi faturaları listeleyebilirsiniz.';
    }

    $suppliers = Supplier::select('id as value', 'title as name')->get();
    $statuses = Expense::select('status as value', 'status as name')->groupBy('status')->get();

    $responsibles = User::selectRaw('id as value, CONCAT(name, " ", surname) as name')->where('group_id', 3)->get();

    return view('purchase.expense.index', compact('page_title', 'page_description', 'suppliers', 'responsibles', 'statuses'));
  }

  public function json(): \Illuminate\Http\JsonResponse
  {
      $d = $this->request->all();
      $parameters = $this->request->query();
      $user_id = $this->request->user()->id;
      $user = $this->request->user();
      $edit_allowed = $user->power('expense', 'edit') ? true : false;
      $delete_allowed = $user->power('expense', 'delete') ? true : false;
      $confirmation_allowed = $user->power('expense', 'confirmation') ? true : false;
      $paid_allowed = $user->power('expense', 'paid') ? true : false;

      $data = Expense::with(['user', 'supplier']);
      
      if(isset($parameters['waiting'])&&$user->group_id==1){
        $data = $data->where('status', 'Yönetici Onayında');
      }
      if(isset($parameters['waiting'])&&$user->group_id==5){
        $data = $data->where('status', 'Onaylandı');
      }

      return Datatables::of($data)
      ->addColumn('price_formatted', function($dt) use ($edit_allowed){
          return money_formatter($dt->price);
      })
      ->addColumn('vat_formatted', function($dt) use ($edit_allowed){
          return money_formatter($dt->vat);
      })
      ->addColumn('total_formatted', function($dt) use ($edit_allowed){
          return money_formatter($dt->price+$dt->vat);
      })
      ->addColumn('edit_allowed', function($dt) use ($edit_allowed){
        if($dt->status=='Yönetici Onayında'){
          return $edit_allowed;
        }
        return false;
      })->addColumn('delete_allowed', function($dt) use ($delete_allowed){
        if($dt->status=='Yönetici Onayında'){
          return $delete_allowed;
        }
        return false;
      })->addColumn('confirmation_allowed', function($dt) use ($confirmation_allowed){
        if($dt->status=='Yönetici Onayında'){
          return $confirmation_allowed;
        }
        return false;
      })->addColumn('paid_allowed', function($dt) use ($paid_allowed){
        if($dt->status=='Onaylandı'){
          return $paid_allowed;
        }
        return false;
      })->make(true);
  }

  public function upload(Request $request): \Illuminate\Http\JsonResponse
  {
      $d = $this->request->all();

        $file = $request->file('file');
        $filename = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))."_".time() . "." . $file->getClientOriginalExtension();
        $filePath = 'snap/expense';
        Storage::disk('s3')->putFileAs($filePath, $file, $filename, ['visibility' => 'public']);
        return response()->json($filename);
  }


  public function add(): \Illuminate\Contracts\View\View
  {
    $data = $this->request->all();
    $page_title = 'Yeni Tedarikçi Faturası Ekle';
    $page_description = '';

    $suppliers = Supplier::select('id as value', 'title as name')->get();

    return view('purchase.expense.add', compact('page_title', 'page_description', 'suppliers'));
  }

  public function update(int $bill_id): \Illuminate\Contracts\View\View
  {
    $detail = Expense::find($bill_id);
    $page_title = $detail->supplier->title." Fatura Düzenle";
    $page_description = '';
    $redirect = url()->previous();

    $suppliers = Supplier::select('id as value', 'title as name')->get();

    return view('purchase.expense.add', compact('page_title', 'page_description', 'suppliers'));
  }
  public function detail(int $bill_id): \Illuminate\Contracts\View\View
  {
    $detail = Expense::find($bill_id);
    $page_title = $detail->supplier->title." Fatura Detayları";
    $page_description = '';
    
    $suppliers = Supplier::select('id as value', 'title as name')->get();

    return view('purchase.expense.detail', compact('page_title', 'page_description', 'suppliers'));
  }

  public function save(Request $request): \Illuminate\Http\JsonResponse
  {
    $data = $this->request->all();

    $validator = Validator::make($data, [
        'supplier_id' => 'required',
        'price' => 'required',
        'vat' => 'required',
        'bill_date' => 'required|date_format:d-m-Y',
        'bill_no' => 'required'
    ]);

    $niceNames = array(
      'customer_id' => 'Müşteri',
      'price' => 'Tutar',
      'currency' => 'Para birimi',
      'vat' => 'KDV',
      'bill_date' => 'Fatura tarihi',
      'bill_no' => 'Fatura numarası'
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
      $expense = Expense::find($data['id']);
    }else{
        $expense = new Expense();
    }
    $expense->supplier_id = $data['supplier_id'];
    $expense->user_id = $user_id;
    $expense->bill_no = $data['bill_no'] ?? null;
    $expense->bill_date = date_deformatter($data['bill_date']);
    $expense->description = $data['description'] ?? null;
    $expense->price = money_deformatter($data['price']);
    $expense->vat = money_deformatter($data['vat']);
    $expense->status = $bill->status ?? 'Yönetici Onayında';
    $file = $request->file('contract');
    if(isset($data['contract'])){
      $file = $request->file('contract');
      $filename = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))."_".time() . "." . $file->getClientOriginalExtension();
      $filePath = 'snap/expense';
      Storage::disk('s3')->putFileAs($filePath, $file, $filename, ['visibility' => 'public']);
      $expense->file = $filename;
    }
    $expense->save();
    
    $result = array(
        'status' => 1,
        'message' => 'Başarıyla kaydedildi. Yönlendiriliyorsunuz.',
        'redirect' => route('expenses')
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

      $bill = Expense::find($bill_id);
      if($bill->user_id!=$user_id||$bill->status=='Onaylandı'){
        return response()->json([
            'message' => 'Düzenleme yetkiniz bulunmamaktadır!'
        ]);
      }
      if($bill->status=='Hazırlanıyor'||$bill->status=='Yönetici Onayında'){
        $bill->delete();

        $title = 'Kayıt silindi!';
        $description = 'ilgili kaydı sildi.'; 
        
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

  public function status(): \Illuminate\Http\JsonResponse
  {
    $data = $this->request->all();
      $bill_id = (int) $data['id'];
      $user_id = $this->request->user()->id;

      $bill = Expense::find($bill_id);
      if(
          ($this->request->user()->isAdmin()&&$bill->status=='Yönetici Onayında')||
          ($this->request->user()->power('expense', 'paid')&&$bill->status=='Onaylandı')
        ){
        $bill->status = $data['status'];
        $bill->save();
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
