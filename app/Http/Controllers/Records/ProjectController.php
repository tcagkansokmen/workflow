<?php

namespace App\Http\Controllers\Records;

use App\Http\Controllers\Controller;

use App\Models\County;
use App\Models\Customer;
use App\Models\Project;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Core\InventoryLog;
use DataTables;
use DB;

class ProjectController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {
        $page_title = 'Proje Listesi';
        $page_description = 'Tüm projeleri görüntüleyip işlem yapabilirsiniz';

        return view('records.project.index', compact('page_title', 'page_description'));
    }

    public function list(): \Illuminate\Http\JsonResponse
    {
        
        $d = $this->request->all();
        $query = $d['query'];

        $data = Project::select('id as value', 'title as name')->where('customer_id', $query)->get();

        return response()->json($data);
    }
    public function select2(): \Illuminate\Http\JsonResponse
    {
        $d = $this->request->all();
        $query = $d['query'];

        $projects = Project::where('title', 'like', '%'.$query.'%')->select('id', 'title as text')->get();

        $result = array(
          'results' => $projects
        );
        return response()->json($result);
    }

    public function json(): \Illuminate\Http\JsonResponse
    {
        $parameters = $this->request->query();
        $user = $this->request->user();
        $edit_allowed = $user->power('projects', 'edit') ? true : false;
        $delete_allowed = $user->power('projects', 'delete') ? true : false;
        $detail_allowed = $user->power('projects', 'detail') ? true : false;

        $projelerim = $this->request->user()->projelerim();

        $products = Project::whereIn('id', $projelerim)->withCount(['productions', 'assemblies', 'printings'])->with(['customer.personel']);

        if(isset($parameters['customer_id'])){
            $products = $products->where('customer_id', $parameters['customer_id']);
        }

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
        

        $page_title = 'Yeni Proje Ekleme';
        $page_description = 'Yeni proje ekleyebilirsiniz.';

        return view('records.project.add', compact('page_title', 'page_description'));
    }

    public function update(int $customer_id)
    {
        $d = $this->request->all();
        $parameters = $this->request->query();
        

        $page_title = 'Proje Düzenle';
        $page_description = 'Projeyi düzenleyebilirsiniz.';

        $detail = Project::find($customer_id);
        $counties = County::select('id as value', 'county as name')->where('city_id', $detail->city_id)->get();

        return view('records.project.add', compact('page_title', 'page_description', 'detail', 'counties'));
    }

    public function detail(int $product_id)
    {
        $d = $this->request->all();
        $parameters = $this->request->query();

        $detail = Product::find($product_id);

        $page_title = $detail->title." Bilgileri";
        $page_description = $detail->title." için detaylı bilgiler";
        

        $vat = $this->vat;

        $metas = ProductMeta::where('type', 'index')->where(function($q) use ($product_id){
            $q->whereNull('product_id');
            $q->orWhere('product_id', $product_id); 
         })->get();

        $detail_meta = ProductMeta::where('product_id', $product_id)->where('type', 'content')
        ->groupBy('key')
        ->get()->keyBy('key');

        return view('management.products.product.detail', compact('page_title', 'page_description', 'detail', 'vat', 'parameters', 'metas', 'detail_meta'));
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
        $user_id = $this->request->user()->id;
        

        $validator = Validator::make($data, [
            'title' => 'required',
            'customer_id' => 'required',
            'start_at' => 'required|date',
            'end_at' => 'required|date|after_or_equal:start_at',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => error_formatter($validator),
                'errors' => $validator->errors(),
            ]);
        }
  
        if(isset($data['id'])){
            $project = Project::find($data['id']);
        }else{
            $project = new Project();
        }
        $project->user_id = $user_id;
        $project->title = $data['title'];
        $project->customer_id = $data['customer_id'];
        $project->description = $data['description'] ?? null;
        $project->address = $data['address'] ?? null;
        $project->city_id = $data['city_id'] ?? null;
        $project->county_id = $data['county_id'] ?? null;
        $project->start_at = isset($data['start_at']) ? date_deformatter($data['start_at']) : null;
        $project->end_at = isset($data['end_at']) ? date_deformatter($data['end_at']) : null;
        $project->save();

        $result = array(
            'status' => 1,
            'message' => 'Başarıyla kaydettiniz.',
            'redirect' => route('projects'),
            'data' => $project
        );
        return response()->json($result);
    }

    public function delete(int $project_id)
    {
        $project = Project::find($project_id);
        if(count($project->productions)||count($project->assemblies)||count($project->printings)){
            $result = array(
                'status' => 0,
                'message' => 'İlgili projeye ait işlemler bulunduğu için silemezsiniz.'
            );
            return response()->json($result);
        }
        $project->delete();
        $result = array(
            'status' => 1,
            'message' => 'Başarıyla pasife aldınız.'
        );
        return response()->json($result);
    }
    public function done(int $project_id)
    {
        $project = Project::find($project_id);
        $project->status = 'tamamlandı';
        $project->save();
        $result = array(
            'status' => 1,
            'message' => 'Başarıyla pasife aldınız.'
        );
        return response()->json($result);
    }
}
