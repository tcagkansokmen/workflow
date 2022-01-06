<?php

namespace App\Http\Controllers\Purchase\Product;

use App\Http\Controllers\Controller;

use App\Models\Product;
use App\Models\PurchaseItem;
use App\Models\ProductCategory;
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

class ProductController extends Controller
{
  public function __construct(Request $request)
  {
    $this->request = $request;
  }

  public function index()
  {
    $page_title = 'Ürün Listesi';
    $page_description = 'Tüm ürünleri görüntüleyip işlem yapabilirsiniz';

    $products = Product::select('id as value', 'title as name')->get();

    return view('purchase.product.index', compact('page_title', 'page_description', 'products'));
  }
  public function upload(Request $request): \Illuminate\Http\JsonResponse
  {
    $d = $this->request->all();

      $file = $request->file('file');
      $filename = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))."_".time() . "." . $file->getClientOriginalExtension();
      $filePath = 'snap/product';
      Storage::disk('s3')->putFileAs($filePath, $file, $filename, ['visibility' => 'public']);
      return response()->json($filename);
  }

  public function select2(): \Illuminate\Http\JsonResponse
  {
    $d = $this->request->all();
    $query = $d['query'];

    $products = Product::where('title', 'like', '%'.$query.'%')->orWhere('code', 'like', '%'.$query.'%')->select('id', ('title as text'))->get();

    $result = array(
      'results' => $products
    );
    return response()->json($result);
  }

  public function categories(): \Illuminate\Http\JsonResponse
  {
    $d = $this->request->all();
    $query = $d['query'];

    $products = ProductCategory::where('title', 'like', '%'.$query.'%')->select('id', ('title as text'))->get();

    $result = array(
      'results' => $products
    );
    return response()->json($result);
  }

  public function json(): \Illuminate\Http\JsonResponse
  {
    $user = $this->request->user();

    $status_allowed = $user->power('product', 'status') ? true : false; 
    $edit_allowed = $user->power('product', 'edit') ? true : false;
    $delete_allowed = $user->power('product', 'delete') ? true : false;
    $detail_allowed = $user->power('product', 'detail') ? true : false;

    $products = Product::with(['category']);

    return Datatables::of($products)
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
    
    $page_title = 'Yeni Ürün';
    $page_description = 'Yeni ürün ekleyebilirsiniz.';

    return view('purchase.product.add', compact('page_title', 'page_description'));
  }
  public function addInside()
  {
    $d = $this->request->all();
    $parameters = $this->request->query();
    
    $page_title = 'Yeni Ürün';
    $page_description = 'Yeni ürün ekleyebilirsiniz.';

    return view('purchase.product.add-inside', compact('page_title', 'page_description'));
  }

  public function update(int $product_id)
  {
    $d = $this->request->all();
    $parameters = $this->request->query();

    $page_title = 'Ürün Düzenle';
    $page_description = 'ürünü düzenleyebilirsiniz.';

    $detail = Product::find($product_id);

    return view('purchase.product.add', compact('page_title', 'page_description', 'detail'));
  }

  public function detail(int $product_id)
  {
    $d = $this->request->all();
    $parameters = $this->request->query();
    $detail = Product::find($product_id);

    $page_title = $detail->title." Bilgileri";
    $page_description = $detail->title." için detaylı bilgiler";
    
    return view('purchase.product.detail', compact('page_title', 'page_description', 'detail'));
  }

  public function save(Request $request)
  {
    $data = $this->request->all();
    $parameters = $this->request->query();
    $user_id = $this->request->user()->id;

    $validator = Validator::make($data, [
      'photo' => 'nullable',
      'title' => 'required',
      'category_id' => 'required',
      'code' => 'nullable',
    ]);

    $niceNames = array(
      'photo' => 'Fotoğraf',
      'title' => 'Ürün adı',
      'category_id' => 'Kategori adı',
      'code' => 'Ürün kodu',
    );

    $validator->setAttributeNames($niceNames); 

    if ($validator->fails()) {
        return response()->json([
            'message' => error_formatter($validator),
            'errors' => $validator->errors(),
        ]);
    }

    if(isset($data['id'])){
        $product = Product::find($data['id']);
    }else{
        $product = new Product();
    }
    $product->title = $data['title'];
    $product->description = $data['description'] ?? null;
    $product->category_id = $data['category_id'];
    $product->code = $data['code'] ?? null;
    $product->price = isset($data['price']) ? money_deformatter($data['price']) : null;
    $product->save();

    $product_code = $product->find($product->id);
    $product_code->code = mb_strtoupper(mb_substr($product->category->title, 0, 3)).date('y').$product->id;
    $product_code->save();
    
    $result = array(
        'status' => 1,
        'message' => 'Başarıyla kaydettiniz.',
        'redirect' => route('products'),
        'data' => $product
    );
    return response()->json($result);
  }

  public function saveCategory(Request $request)
  {
    $data = $this->request->all();
    $parameters = $this->request->query();
    $user_id = $this->request->user()->id;

    $validator = Validator::make($data, [
      'title' => 'required',
    ]);

    $niceNames = array(
      'title' => 'Kategori adı',
    );

    $validator->setAttributeNames($niceNames); 

    if ($validator->fails()) {
        return response()->json([
            'message' => error_formatter($validator),
            'errors' => $validator->errors(),
        ]);
    }

    if(isset($data['id'])){
        $category = ProductCategory::find($data['id']);
    }else{
        $category = new ProductCategory();
    }
    $category->title = $data['title'];
    $category->save();

    $result = array(
        'status' => 1,
        'message' => 'Başarıyla kaydettiniz.',
        'data' => $category
    );
    return response()->json($result);
  }

  public function delete(int $product_id)
  {
    $bul = PurchaseItem::where('product_id', $product_id)->count();
    if($bul){
      $result = array(
          'status' => 0,
          'message' => 'Üstünde işlem yapılan ürünü silemezsiniz..',
      );
      return response()->json($result);
    }
    $product = Product::find($product_id);
    $product->delete();
    $result = array(
        'status' => 1,
        'message' => 'Başarıyla pasife aldınız.'
    );
    return response()->json($result);
  }
}
