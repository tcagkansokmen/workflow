<?php

namespace App\Http\Controllers\Purchase\Purchase;

use App\Http\Controllers\Controller;

use App\Models\Product;
use App\Models\ProductCategory;
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

class PurchaseController extends Controller
{
  public function __construct(Request $request)
  {
    $this->request = $request;
    $types = array(
        array(
            'value' => "Adet",
            'name' => "Adet"
        ),
        array(
            'value' => "Koli",
            'name' => "Koli"
        ),
        array(
            'value' => "Top",
            'name' => "Top"
        ),
        array(
            'value' => "Balya",
            'name' => "Balya"
        ),
        array(
            'value' => "kg",
            'name' => "kg"
        ),
        array(
            'value' => "m3",
            'name' => "m3"
        ),
        array(
            'value' => "Ton",
            'name' => "Ton"
        ),
        array(
            'value' => "Litre",
            'name' => "Litre"
        ),
        array(
            'value' => "Kutu",
            'name' => "Kutu"
        ),
        array(
            'value' => "Metretül",
            'name' => "Metretül"
        ),
        array(
            'value' => "Tüp",
            'name' => "Tüp"
        ),
        array(
            'value' => "Rulo",
            'name' => "Rulo"
        ),
        array(
            'value' => "Gram",
            'name' => "Gram"
        ),
        array(
            'value' => "Sandık",
            'name' => "Sandık"
        ),
        array(
            'value' => "Palet",
            'name' => "Palet"
        ),
        array(
            'value' => "Fıçı",
            'name' => "Fıçı"
        ),
    );
    $types = json_decode(json_encode($types));
    $this->types = $types;
  }

  public function index()
  {
    $parameters = $this->request->query();
    $page_title = 'Satın Alma Listesi';
    $page_description = 'Tüm satın alma listesini görüntüleyip işlem yapabilirsiniz';

    if(isset($parameters['waiting'])){
      $page_title = 'Onay Bekleyen Satın Almalar';
      $page_description = 'Onayınızı bekleyen satın almaları listeleyebilirsiniz.';
    }

    $purchases = Purchase::select('id as value', 'title as name')->get();

    return view('purchase.purchase.index', compact('page_title', 'page_description', 'purchases'));
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

  public function json(): \Illuminate\Http\JsonResponse
  {
    $parameters = $this->request->query();
    $user = $this->request->user();

    $status_allowed = $user->power('purchase', 'status') ? true : false;
    $edit_allowed = $user->power('purchase', 'edit') ? true : false;
    $delete_allowed = $user->power('purchase', 'delete') ? true : false;
    $detail_allowed = $user->power('purchase', 'detail') ? true : false;

    $purchases = PurchaseItem::select('*', 'purchase_items.status', 'purchase_items.id as purchase_items_id', 'purchase_items.price as bu_price')->with(['purchase.user', 'product']);

    if(isset($parameters['waiting'])){
      if($user->group_id==1){
        $purchases = $purchases->whereIn('purchase_items.status', ['Yönetici Onayında', 'Revize Edildi']);
      }elseif($user->group_id==3){
        $purchases = $purchases->where('purchase_items.status', 'Onaylandı');
      }
    }

    return Datatables::of($purchases)
    ->addColumn('total_price_formatted', function($data){
        return money_formatter($data->bu_price);
    })
    ->addColumn('start_at_formatted', function($data){
        return $data->purchase->start_at ? Carbon::parse($data->purchase->start_at)->formatLocalized('%d %B %Y') : '';
    })
    ->addColumn('status_update_allowed', function($data) use ($edit_allowed, $user){
      if(($data->status=='Yönetici Onayında'||$data->status=='Revize Edildi')&&$user->isAdmin()){
        return $edit_allowed;
      }
      return false;
    })
    ->addColumn('status_bought_allowed', function($data) use ($edit_allowed){
      if($data->status=='Onaylandı'){
        return $edit_allowed;
      }
      return false;
    })
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

    $types = $this->types;
    
    $page_title = 'Yeni Satın Alma';
    $page_description = 'Yeni satın alma talebi ekleyebilirsiniz.';

    $suppliers = Supplier::select('id as value', 'title as name')->get();
    $products = Product::select('id as value', 'title as name')->get();

    return view('purchase.purchase.add', compact('page_title', 'page_description', 'types', 'suppliers', 'products'));
  }
  public function update(int $purchase_id)
  {
    $d = $this->request->all();
    $parameters = $this->request->query();

    $page_title = 'Satın Alma Talebini Düzenle';
    $page_description = 'Satın alma talebini düzenleyebilirsiniz.';

    $types = $this->types;

    $suppliers = Supplier::select('id as value', 'title as name')->get();
    $products = Product::select('id as value', 'title as name')->get();

    $detail = Purchase::find($purchase_id);

    return view('purchase.purchase.add', compact('page_title', 'page_description', 'detail', 'types', 'suppliers', 'products'));
  }

  public function detail(int $purchase_id)
  {
    $d = $this->request->all();
    $parameters = $this->request->query();
    $detail = Purchase::find($purchase_id);

    $page_title = $detail->title." Bilgileri";
    $page_description = $detail->title." için detaylı bilgiler";
    
    return view('purchase.purchase.detail', compact('page_title', 'page_description', 'detail'));
  }

  public function save(Request $request)
  {
    $data = $this->request->all();
    $parameters = $this->request->query();
    $user_id = $this->request->user()->id;

    $validator = Validator::make($data, [
      'title' => 'nullable',
      'description' => 'nullable',
      'start_at' => 'required',
      'products.*.product_id' => 'required',
      'products.*.supplier_id' => 'required',
      'products.*.quantity' => 'required',
      'products.*.type' => 'required',
      'products.*.price' => 'required'
    ]);

    $niceNames = array(
      'title' => 'Talep adı',
      'description' => 'Açıklama',
      'start_at' => 'Talep tarihi',
      'products.*.product_id' => 'Ürün',
      'products.*.supplier_id' => 'Tedarikçi',
      'products.*.quantity' => 'Adet',
      'products.*.type' => 'Birim',
      'products.*.price' => 'Fiyat'
    );

    $validator->setAttributeNames($niceNames); 

    if ($validator->fails()) {
        return response()->json([
            'message' => error_formatter($validator),
            'errors' => $validator->errors(),
        ]);
    }

    if(!isset($data['products'])||!count($data['products'])){
      return response()->json([
          'message' => 'Lütfen en az bir satın alma kalemi ekleyin.'
      ]);
    }

    if(isset($data['id'])){
        $purchase = Purchase::find($data['id']);
    }else{
        $purchase = new Purchase();
    }
    $purchase->user_id = $user_id;
    $purchase->title = $data['title'] ?? null;
    $purchase->description = $data['description'] ?? null;
    $purchase->start_at = isset($data['start_at']) ? date_deformatter($data['start_at']) : null;
    $purchase->status = isset($data['id'])&&$purchase->status=='Onaylandı' ? 'Revize Edildi' : 'Yönetici Onayında';
    $purchase->save();


    $total = 0;
    if(isset($data['products'])){
      $ar = array();
      foreach ($data["products"] as $d) {
        if($d['price']&&$d['price']!='0.00'){
          if(isset($d['id'])){
              $purchase_item = PurchaseItem::find($d['id']);
          }else{
              $purchase_item = new PurchaseItem();
          }
          $purchase_item->purchase_id = $purchase->id;
          $purchase_item->product_id = $d['product_id'] ?? null;
          $purchase_item->supplier_id = $d['supplier_id'] ?? null;
          $purchase_item->quantity = $d['quantity'] ?? null;
          $purchase_item->type = $d['type'] ?? null;
          $purchase_item->price = isset($d['price']) ? money_deformatter($d['price']) : null;
          $purchase_item->status = isset($data['id'])&&$purchase_item->status=='Onaylandı' ? 'Revize Edildi' : 'Yönetici Onayında';
          $purchase_item->save();
          
          $total += isset($d['price']) ? money_deformatter($d['price']) : 0;
          $arr[] = $purchase_item->id;
        }
      }
      $silabi = PurchaseItem::where('purchase_id', $purchase->id)->whereNotIn('id', $arr)->delete();
    }

    $purchase = Purchase::find($purchase->id);
    $purchase->total_price = $total;
    $purchase->save();

    $result = array(
        'status' => 1,
        'message' => 'Başarıyla kaydettiniz.',
        'redirect' => route('purchases'),
    );
    return response()->json($result);
  }

  public function status(): \Illuminate\Http\JsonResponse
  {
    $user = $this->request->user();
    $user_id = $this->request->user()->id;
    $data = $this->request->all();
    $purchase_id = $data['id'];

    $bul = PurchaseItem::find($purchase_id);
    if($bul->status=='Yönetici Onayında'&&$user->isAdmin()){
      $bul->status = $data['status'];
      $bul->save();
    }elseif($bul->status=='Onaylandı'){
      $bul->status = $data['status'];
      $bul->save();
    }else{
      $result = array(
        'status' => 0,
        'message' => 'Güncelleme yetkiniz bulunmamaktadır.'
      );
      return response()->json($result);
    }

    $result = array(
      'status' => 1,
      'message' => 'Güncellendi.'
    );
    return response()->json($result);
        
  }

  public function delete(int $purchase_id)
  {
    $purchase_item = PurchaseItem::find($purchase_id);
    if($purchase_item->status=='Onaylandı'||$purchase_item->status=='Reddedildi'){
      $result = array(
          'status' => 0,
          'message' => 'Onaylanmış talebi silemezsiniz'
      );
      return response()->json($result);
    }
    $purchase_item->delete();
    $result = array(
        'status' => 1,
        'message' => 'Başarıyla sildiniz.'
    );
    return response()->json($result);
  }
}
