<?php

namespace App\Http\Controllers\Purchase\Supplier;

use App\Http\Controllers\Controller;

use App\Models\County;
use App\Models\Expense;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
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

class SupplierController extends Controller
{
  public function __construct(Request $request)
  {
    $this->request = $request;
  }

  public function index()
  {
    $page_title = 'Tedarikçi Listesi';
    $page_description = 'Tedarikçi listesini görüntüleyip işlem yapabilirsiniz';

    $purchases = Supplier::select('id as value', 'title as name')->get();

    return view('purchase.supplier.index', compact('page_title', 'page_description', 'purchases'));
  }
  public function upload(Request $request): \Illuminate\Http\JsonResponse
  {
    $d = $this->request->all();

      $file = $request->file('file');
      $filename = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))."_".time() . "." . $file->getClientOriginalExtension();
      $filePath = 'snap/purchase';
      Storage::disk('s3')->putFileAs($filePath, $file, $filename, ['visibility' => 'public']);
      return response()->json($filename);
  }

  public function suppliers(): \Illuminate\Http\JsonResponse
  {
      $d = $this->request->all();
      $query = $d['query'];

      $data = Supplier::where(function($q) use ($query){
        $q->where('title', 'like', $query.'%');
      })->pluck('title')->toArray();

      return response()->json($data);
  }
  public function select2(): \Illuminate\Http\JsonResponse
  {
    $d = $this->request->all();
    $query = $d['query'];

    $suppliers = Supplier::where('title', 'like', '%'.$query.'%')->orWhere('code', 'like', '%'.$query.'%')->select('id', ('title as text'))->get();

    $result = array(
      'results' => $suppliers
    );
    return response()->json($result);
  }

  public function json(): \Illuminate\Http\JsonResponse
  {
    $user = $this->request->user();

    $status_allowed = $user->power('supplier', 'status') ? true : false;
    $edit_allowed = $user->power('supplier', 'edit') ? true : false;
    $delete_allowed = $user->power('supplier', 'delete') ? true : false;
    $detail_allowed = $user->power('supplier', 'detail') ? true : false;

    $suppliers = Supplier::where('id', '>', 0)->withCount([
      'waiting_expenses' => function ($query) {
        $query->select(DB::raw("sum(price+vat) as paidsum"));
      },
      'accepted_expenses' => function ($query) {
        $query->select(DB::raw("sum(price+vat) as paidsum"));
      },
      'paid_expenses' => function ($query) {
        $query->select(DB::raw("sum(price+vat) as paidsum"));
      }]);

    return Datatables::of($suppliers)
    ->addColumn('status_allowed', function() use ($status_allowed){
        return $status_allowed;
    })->addColumn('edit_allowed', function() use ($edit_allowed){
        return $edit_allowed;
    })->addColumn('delete_allowed', function() use ($delete_allowed){
        return $delete_allowed;
    })->addColumn('detail_allowed', function() use ($detail_allowed){
        return $detail_allowed;
    })->make(true);
  }
  public function add()
  {
    $d = $this->request->all();
    $parameters = $this->request->query();
    
    $page_title = 'Yeni Tedarikçi Ekle';
    $page_description = 'Yeni tedarikçi ekleyebilirsiniz.';

    return view('purchase.supplier.add', compact('page_title', 'page_description'));
  }
  public function update(int $supplier_id)
  {
    $d = $this->request->all();
    $parameters = $this->request->query();

    $page_title = 'Tedarikçiyi Düzenle';
    $page_description = 'Tedarikçiyi düzenleyebilirsiniz.';

    $detail = Supplier::find($supplier_id);
    $counties = County::select('id as value', 'county as name')->where('city_id', $detail->city_id)->get();

    return view('purchase.supplier.add', compact('page_title', 'page_description', 'detail', 'counties'));
  }

  public function detail(int $supplier_id)
  {
    $d = $this->request->all();
    $parameters = $this->request->query();
    $detail = Supplier::find($supplier_id);

    $page_title = $detail->title." Bilgileri";
    $page_description = $detail->title." için detaylı bilgiler";
    
    return view('purchase.Supplier.detail', compact('page_title', 'page_description', 'detail'));
  }

  public function save(Request $request)
  {
    $data = $this->request->all();
    $parameters = $this->request->query();
    
    $validator = Validator::make($data, [
        'title' => 'required',
        'name' => 'required',
        'surname' => 'required',
        'phone' => 'required',
        'email' => 'nullable|email',
        'city_id' => 'required'
    ]);

    $niceNames = array(
      'title' => 'Unvan',
      'name' => 'İsim',
      'surname' => 'Soyisim',
      'phone' => 'Telefon',
      'email' => 'Email',
      'city_id' => 'Şehir'
    );

    $validator->setAttributeNames($niceNames); 


    if ($validator->fails()) {
        return response()->json([
            'message' => error_formatter($validator),
            'errors' => $validator->errors(),
        ]);
    }

    $file = $request->file('profile_avatar');
    
    if(isset($data['profile_avatar'])){
      $filename = time() . "." . $file->getClientOriginalExtension();
      $filePath = 'uploads/company';
      Storage::disk('s3')->putFileAs($filePath, $file, $filename, ['visibility' => 'public']);
    }


    if(isset($data['id'])){
        $supplier = Supplier::find($data['id']);
        $bak = Supplier::where('code', $data['code'])->where('id', '!=', $supplier->id)->first();
        if($bak){
            return response()->json([
                'message' => 'Bu kısa kod ile başka bir firma kayıtlı',
                'errors' => $validator->errors(),
            ]);
        }
    }else{
        $validator = Validator::make($data, [
            'code' => 'unique:suppliers',
        ]);
        $supplier = new Supplier();
        if ($validator->fails()) {
            return response()->json([
                'message' => error_formatter($validator),
                'errors' => $validator->errors(),
            ]);
        }
    }
    if(isset($data['profile_avatar'])){
      $supplier->logo = $filename;
    }
    $supplier->title = $data['title'];
    $supplier->name = $data['name'] ?? null;
    $supplier->surname = $data['surname'] ?? null;
    $supplier->code = $data['code'] ?? null;
    $supplier->phone = $data['phone'] ?? null;
    $supplier->email = $data['email'] ?? null;
    $supplier->city_id = $data['city_id'] ?? null;
    $supplier->county_id = $data['county_id'] ?? null;
    $supplier->address = $data['address'] ?? null;
    $supplier->tax_office = $data['tax_office'] ?? null;
    $supplier->tax_no = $data['tax_no'] ?? null;
    $supplier->save();

    $result = array(
        'status' => 1,
        'message' => 'Başarıyla kaydettiniz.',
        'redirect' => route('suppliers'),
        'data' => $supplier
    );
    return response()->json($result);
  }

  public function delete(int $supplier_id)
  {
    $bul = PurchaseItem::where('supplier_id', $supplier_id)->first();
    $bul2 = Expense::where('supplier_id', $supplier_id)->first();
    if($bul||$bul2){
      $result = array(
          'status' => 0,
          'message' => 'İşlem bulunan tedarikçiyi silemezsiniz.'
      );
      return response()->json($result);
    }
    $supplier = Supplier::find($supplier_id);
    $supplier->delete();
    $result = array(
        'status' => 1,
        'message' => 'Başarıyla sildiniz.'
    );
    return response()->json($result);
  }
}
