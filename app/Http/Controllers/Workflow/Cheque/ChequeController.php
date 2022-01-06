<?php declare(strict_types = 1);

namespace App\Http\Controllers\Workflow\Cheque;

use App\Http\Controllers\Controller;

use App\Models\Customer;
use App\Models\Cheque;
use App\Models\Notification;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use \Carbon\Carbon;
use DataTables;

class ChequeController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function send(): \Illuminate\Contracts\View\View
    {
      $page_title = 'Verilen Çekler Listesi';
      $page_description = 'Tüm verilen çekleri görüntüleyip işlem yapabilirsiniz';


      return view('workflow.cheque.send', compact('page_title', 'page_description'));
    }
    public function sendJson(): \Illuminate\Http\JsonResponse
    {
        $d = $this->request->all();
        $user = $this->request->user();
        $edit_allowed = $user->power('cheque', 'edit') ? true : false;
        $delete_allowed = $user->power('cheque', 'delete') ? true : false;
        $detail_allowed = $user->power('cheque', 'detail') ? true : false;

        $parameters = $this->request->query();

        $data = Cheque::where('type', 'send')->with(['customer']);
        
        return Datatables::of($data)
        ->addColumn('deadline_formatted', function($cols) use ($edit_allowed){
          return Carbon::parse($cols->deadline)->formatLocalized('%d %B %Y');
        })
        ->addColumn('price_formatted', function($cols) use ($edit_allowed){
          return money_formatter($cols->price);
        })
        ->addColumn('edit_allowed', function() use ($edit_allowed){
            return $edit_allowed;
        })->addColumn('delete_allowed', function() use ($delete_allowed){
            return $delete_allowed;
        })->addColumn('detail_allowed', function() use ($detail_allowed){
            return $detail_allowed;
        })->make(true);
    }
    public function received(): \Illuminate\Contracts\View\View
    {
      $page_title = 'Alınan Çekler Listesi';
      $page_description = 'Tüm alınan çekleri görüntüleyip işlem yapabilirsiniz';

      return view('workflow.cheque.received', compact('page_title', 'page_description'));
    }
    public function receivedJson(): \Illuminate\Http\JsonResponse
    {
      $d = $this->request->all();
      $user = $this->request->user();
      $edit_allowed = $user->power('cheque', 'edit') ? true : false;
      $delete_allowed = $user->power('cheque', 'delete') ? true : false;
      $detail_allowed = $user->power('cheque', 'detail') ? true : false;

      $parameters = $this->request->query();

      $data = Cheque::where('type', 'received')->with(['customer']);
      
      return Datatables::of($data)
      ->addColumn('deadline_formatted', function($cols) use ($edit_allowed){
        return Carbon::parse($cols->deadline)->formatLocalized('%d %B %Y');
      })
      ->addColumn('price_formatted', function($cols) use ($edit_allowed){
        return money_formatter($cols->price);
      })
      ->addColumn('edit_allowed', function() use ($edit_allowed){
          return $edit_allowed;
      })->addColumn('delete_allowed', function() use ($delete_allowed){
          return $delete_allowed;
      })->addColumn('detail_allowed', function() use ($detail_allowed){
          return $detail_allowed;
      })->make(true);
    }

    public function addSend(): \Illuminate\Contracts\View\View
    {
      $data = $this->request->all();
      $page_title = 'Yeni Verilen Çek Ekle';
      $page_description = '';
      $redirect = url()->previous();

      return view('workflow.cheque.add-send', compact('page_title', 'page_description'));
    }

    public function updateSend($id): \Illuminate\Contracts\View\View
    {
      $data = $this->request->all();
      $page_title = 'Verilen Çek Düzenle';
      $page_description = '';
      $redirect = url()->previous();

      $detail = Cheque::find($id);

      return view('workflow.cheque.add-send', compact('page_title', 'page_description', 'detail'));
    }

    public function addReceived(): \Illuminate\Contracts\View\View
    {
      $data = $this->request->all();
      $page_title = 'Yeni Alınan Çek Ekle';
      $page_description = '';
      
      $customers = Customer::select('id as value', 'title as name')->get();

      return view('workflow.cheque.add-received', compact('page_title', 'page_description', 'customers'));
    }

    public function updateReceived($id): \Illuminate\Contracts\View\View
    {
      $data = $this->request->all();
      $page_title = 'Alınan Çek Düzenle';
      $page_description = '';
      $redirect = url()->previous();

      $detail = Cheque::find($id);
      $customers = Customer::select('id as value', 'title as name')->get();

      return view('workflow.cheque.add-received', compact('page_title', 'page_description', 'detail', 'customers'));
    }

    public function save(Request $request): \Illuminate\Http\JsonResponse
    {
      $data = $this->request->all();

      $validator = Validator::make($data, [
          'customer_id' => 'required_without:supplier',
          'supplier' => 'required_without:customer_id',
          'price' => 'required',
          'deadline' => [
              'after_or_equal:today'
          ]
      ]);
      $niceNames = array(
          'supplier' => 'Tedarikçi',
          'customer_id' => 'Müşteri',
          'price' => 'Tutar',
          'deadline' => 'Vadesi',
      );

      $validator->setAttributeNames($niceNames); 


      if ($validator->fails()) {
          return response()->json([
              'message' => error_formatter($validator),
              'errors' => $validator->errors(),
          ]);
      }

      $user_id = $this->request->user()->id;
      
      if(isset($data['id'])){
        $cheque = Cheque::find($data['id']);
      }else{
          $cheque = new Cheque();
      }
      $cheque->customer_id = $data['customer_id'] ?? null;
      $cheque->supplier = $data['supplier'] ?? null;
      $cheque->price = money_deformatter($data['price']);
      $cheque->deadline = date_deformatter($data['deadline']);
      $cheque->description = $data['description'] ?? null;
      $cheque->type = $data['type'] ?? null;
      $cheque->save();
      
      if($data['type']=='send'){
        $result = array(
            'status' => 1,
            'message' => 'Başarıyla kaydedildi. Yönlendiriliyorsunuz.',
            'redirect' => route('send-cheques')
        );
      }else{
        $result = array(
            'status' => 1,
            'message' => 'Başarıyla kaydedildi. Yönlendiriliyorsunuz.',
            'redirect' => route('received-cheques')
        );
      }
      return response()->json($result);
    }
    
    public function delete(string $brief_id): \Illuminate\Http\JsonResponse
    {
        $brief_id = (int) $brief_id;
        $user_id = $this->request->user()->id;

        $brief = Cheque::find($brief_id);
        if($brief->status||!$this->request->user()->isAdmin()){
          return response()->json([
              'message' => 'Silemezsiniz'
          ]);
        }
        $brief->delete();
        
        $result = array(
            'status' => 1,
            'message' => 'Başarıyla silindi.'
        );
        return response()->json($result);
    }

    public function confirm(string $brief_id): \Illuminate\Http\JsonResponse
    {
        $brief_id = (int) $brief_id;
        $user_id = $this->request->user()->id;

        $brief = Cheque::find($brief_id);
        if($brief->status&&!$this->request->user()->isAdmin()){
          return response()->json([
              'message' => 'Güncelleyemezsiniz'
          ]);
        }
        $brief->status = 1;
        $brief->save();
        
        $result = array(
            'status' => 1,
            'message' => 'Başarıyla silindi.'
        );
        return response()->json($result);
    }

}