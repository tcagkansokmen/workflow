<?php

namespace App\Http\Controllers\Records;

use App\Http\Controllers\Controller;
use App\Models\County;
use App\Models\Customer;
use App\Models\CustomerPersonel;

use App\Models\Assembly;
use App\Models\Production;
use App\Models\Printing;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Core\InventoryLog;
use DataTables;
use DB;

class CustomerController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {
        $page_title = 'Müşteri Listesi';
        $page_description = 'Tüm müşterileri görüntüleyip işlem yapabilirsiniz';

        return view('records.customer.index', compact('page_title', 'page_description'));
    }

    public function companies(): \Illuminate\Http\JsonResponse
    {
        $d = $this->request->all();
        $query = $d['query'];

        $data = Customer::where(function($q) use ($query){
          $q->where('title', 'like', $query.'%');
        })->pluck('title')->toArray();

        return response()->json($data);
    }
    public function assemblies(): \Illuminate\Http\JsonResponse
    {
        $d = $this->request->all();

        $customers = Assembly::where('status', 'Montaj Tamamlandı');

        if(isset($d['customer_id'])){
            $customers = $customers->where('customer_id', $d['customer_id']);
        }

        if(isset($d['project_id'])){
            $customers = $customers->where('project_id', $d['project_id']);
        }
        
        $customers = $customers->select('id as value', 'title as name')->get();

        return response()->json($customers);
    }
    public function productions(): \Illuminate\Http\JsonResponse
    {
        $d = $this->request->all();

        $customers = Production::where('status', 'Tamamlandı');

        if(isset($d['customer_id'])){
            $customers = $customers->where('customer_id', $d['customer_id']);
        }

        if(isset($d['project_id'])){
            $customers = $customers->where('project_id', $d['project_id']);
        }
        
        $customers = $customers->select('id as value', 'title as name')->get();

        return response()->json($customers);
    }
    public function printings(): \Illuminate\Http\JsonResponse
    {
        $d = $this->request->all();

        $customers = Printing::where('status', 'Baskı Tamamlandı');

        if(isset($d['customer_id'])){
            $customers = $customers->where('customer_id', $d['customer_id']);
        }

        if(isset($d['project_id'])){
            $customers = $customers->where('project_id', $d['project_id']);
        }
        
        $customers = $customers->select('id as value', 'title as name')->get();

        return response()->json($customers);
    }
    public function personels($customer_id): \Illuminate\Http\JsonResponse
    {
        $d = $this->request->all();

        $data = CustomerPersonel::select('id as value', DB::raw('CONCAT(name, " ", surname) as name'))->where('customer_id', $customer_id)->get();

        return response()->json($data);
    }
    public function select2(): \Illuminate\Http\JsonResponse
    {
        $d = $this->request->all();
        $query = $d['query'];

        $customers = Customer::where('title', 'like', '%'.$query.'%')->select('customers.id', ('title as text'))->get();

        $result = array(
          'results' => $customers
        );
        return response()->json($result);
    }

    public function json(): \Illuminate\Http\JsonResponse
    {
        $user = $this->request->user();
        $edit_allowed = $user->power('customers', 'edit') ? true : false;
        $delete_allowed = $user->power('customers', 'delete') ? true : false;
        $detail_allowed = $user->power('customers', 'detail') ? true : false;

        $products = Customer::with(['personel'])->withCount(['projects', 'productions', 'assemblies', 'printings'])
        ->orderBy('id', 'desc');

        return Datatables::of($products)
        ->addColumn('edit_allowed', function() use ($edit_allowed){
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
        

        $page_title = 'Yeni Müşteri Ekleme';
        $page_description = 'Yeni müşteri ekleyebilirsiniz.';

        return view('records.customer.add', compact('page_title', 'page_description'));
    }
    public function addInside()
    {
        $d = $this->request->all();
        $parameters = $this->request->query();
        
        $page_title = 'Yeni Müşteri Ekleme';
        $page_description = 'Yeni müşteri ekleyebilirsiniz.';

        return view('records.customer.add-inside', compact('page_title', 'page_description'));
    }

    public function update(int $customer_id)
    {
        $d = $this->request->all();
        $parameters = $this->request->query();
        

        $page_title = 'Müşteri Düzenle';
        $page_description = 'Müşteriyi düzenleyebilirsiniz.';

        $detail = Customer::find($customer_id);
        $counties = County::select('id as value', 'county as name')->where('city_id', $detail->city_id)->get();

        return view('records.customer.add', compact('page_title', 'page_description', 'detail', 'counties'));
    }

    public function detail(int $product_id)
    {
        $d = $this->request->all();
        $parameters = $this->request->query();

        $detail = Customer::find($product_id);

        $page_title = $detail->title." Bilgileri";
        $page_description = $detail->title." için detaylı bilgiler";


        return view('records.customer.detail', compact('page_title', 'page_description', 'detail'));
    }

    public function upload(Request $request): \Illuminate\Http\JsonResponse
    {
        $d = $this->request->all();

        $title = $d['title'];

        $file = $request->file('file');
        $filename = $title."_".rand(100000,999999) . "." . $file->getClientOriginalExtension();
        $filePath = env('PRODUCT_PHOTO_PATH');
        Storage::disk('s3')->putFileAs($filePath, $file, $filename, ['visibility' => 'public']);
        return response()->json($filename);
    }

    public function save(Request $request)
    {
        $data = $this->request->all();
        $parameters = $this->request->query();
        

        $validator = Validator::make($data, [
            'title' => 'required',
            'personel.*.name' => 'required',
            'personel.*.surname' => 'required',
            'personel.*.phone' => 'required',
            'personel.*.email' => 'nullable|email'
        ]);


        $niceNames = array(
            'title' => 'Şirket adı',
            'personel.*.name' => 'İsim',
            'personel.*.surname' => 'Soyisim',
            'personel.*.phone' => 'Telefon',
            'personel.*.email' => 'E-posta'
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
            $customer = Customer::find($data['id']);
            $bak = Customer::where('code', $data['code'])->where('id', '!=', $customer->id)->first();
            if($bak){
                return response()->json([
                    'message' => 'Bu kısa kod ile başka bir firma kayıtlı',
                    'errors' => $validator->errors(),
                ]);
            }
        }else{
            $validator = Validator::make($data, [
                'code' => 'unique:customers',
            ]);
            $customer = new Customer();
            if ($validator->fails()) {
                return response()->json([
                    'message' => error_formatter($validator),
                    'errors' => $validator->errors(),
                ]);
            }
        }
        if(isset($data['profile_avatar'])){
          $customer->logo = $filename;
        }
        $customer->title = $data['title'];
        $customer->name = $data['name'] ?? null;
        $customer->surname = $data['surname'] ?? null;
        $customer->code = $data['code'] ?? null;
        $customer->phone = $data['phone'] ?? null;
        $customer->email = $data['email'] ?? null;
        $customer->city_id = $data['city_id'] ?? null;
        $customer->county_id = $data['county_id'] ?? null;
        $customer->address = $data['address'] ?? null;
        $customer->tax_office = $data['tax_office'] ?? null;
        $customer->tax_no = $data['tax_no'] ?? null;
        $customer->save();

        if(isset($data['personel'])){
            $ids = [];
            foreach($data['personel'] as $p){
                if(isset($p['id'])){
                    $customer_personel = CustomerPersonel::find($p['id']);
                }else{
                    $customer_personel = new CustomerPersonel();
                }
                $customer_personel->customer_id = $customer->id;
                $customer_personel->name = $p['name'];
                $customer_personel->surname = $p['surname'];
                $customer_personel->phone = $p['phone'] ?? null;
                $customer_personel->email = $p['email'] ?? null;
                $customer_personel->save();
                $ids[] = $customer_personel->id;
            }
            $delete = CustomerPersonel::where('customer_id', $customer->id)->whereNotIn('id', $ids)->delete();
        }

        $result = array(
            'status' => 1,
            'message' => 'Başarıyla kaydettiniz.',
            'redirect' => route('customers'),
            'data' => $customer
        );
        return response()->json($result);
    }

    public function delete(int $customer_id)
    {
        $customer = Customer::find($customer_id);
        if(count($customer->projects)){
            $result = array(
                'status' => 0,
                'message' => 'İlgili müşteriye ait projeler bulunduğu için silemezsiniz.'
            );
            return response()->json($result);
        }
        $customer->delete();
        $result = array(
            'status' => 1,
            'message' => 'Başarıyla pasife aldınız.'
        );
        return response()->json($result);
    }
}
