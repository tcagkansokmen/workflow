<?php

namespace App\Http\Controllers\Order\Production;

use App\Http\Controllers\Controller;

use App\Models\Assembly;
use App\Models\County;
use App\Models\Customer;
use App\Models\CustomerPersonel;
use App\Models\Production;
use App\Models\ProductionExtra;
use App\Models\Project;
use App\Models\Notification;

use App\Models\BillProject;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Core\InventoryLog;
use App\Core\LogSet;
use DataTables;
use DB;

class ProductionController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
        $statuses = array(
            array(
                'value' => "Talep Açıldı",
                'name' => "Talep Açıldı"
            ),
            array(
                'value' => "Başlandı",
                'name' => "Başlandı"
            ),
            array(
                'value' => "Tamamlandı",
                'name' => "Tamamlandı"
            ),
        );
        $statuses = json_decode(json_encode($statuses));
        $this->statuses = $statuses;
    }

    public function index()
    {
        $parameters = $this->request->query();
        $page_title = 'Üretim Listesi';
        $page_description = 'Tüm üretimleri görüntüleyip işlem yapabilirsiniz';

        if(isset($parameters['waiting'])){
            $page_title = 'Onay Bekleyen Üretimler';
            $page_description = 'Onayınızı bekleyen üretimleri listeleyebilirsiniz.';
        }

        $customers = Customer::select('id as value', 'title as name')->get();
        $statuses = $this->statuses;

        return view('order.production.index', compact('page_title', 'page_description', 'customers', 'statuses'));
    }
    public function upload(Request $request): \Illuminate\Http\JsonResponse
    {
        $d = $this->request->all();

        $file = $request->file('file');
        $filename = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))."_".time() . "." . $file->getClientOriginalExtension();
        $filePath = 'snap/production';
        Storage::disk('s3')->putFileAs($filePath, $file, $filename, ['visibility' => 'public']);
        return response()->json($filename);
    }

    public function json(): \Illuminate\Http\JsonResponse
    {
        $user = $this->request->user();
        $parameters = $this->request->query();

        $status_allowed = $user->power('production', 'status') ? true : false;
        $edit_allowed = $user->power('production', 'edit') ? true : false;
        $delete_allowed = $user->power('production', 'delete') ? true : false;
        $detail_allowed = $user->power('production', 'detail') ? true : false;

        $projelerim = $this->request->user()->projelerim();

        $products = Production::whereIn('project_id', $projelerim)->with(['customer', 'project'])->withCount(['bills']);

        if(isset($parameters['waiting'])&&$user->group_id==2){
            $products->where('status', 'Talep Açıldı');
        }
        
        return Datatables::of($products)
        ->filterColumn('end_at', function($query, $keyword) {
            $bul = BillProject::whereNotNull('production_id')->pluck('production_id')->toArray();
            if($keyword==1){
                $query->whereIn('id', $bul);
            }elseif($keyword==2){
                $query->whereNotIn('id', $bul);
            }
        })
        ->filterColumn('status', function($query, $keyword) {
            $query->where('status', $keyword);
        })
        ->filterColumn('customer_id', function($query, $keyword) {
            $query->where('customer_id', $keyword);
        })
        ->addColumn('messages', function($q) use ($user){
            return Notification::where('is_read', 0)->where('user_id', $user->id)->where('redirect', '/production/detail/'.$q->id)->count();
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
        $detail = [];
        $customers = [];
        $projects = [];

        if(isset($parameters['customer_id'])){
            $customers = Customer::select('id as value', 'title as name')->where('id', $parameters['customer_id'])->get();
            $detail['customer_id'] = $parameters['customer_id'];
        }

        if(isset($parameters['project_id'])&&isset($parameters['customer_id'])){
            $projects = Project::where('customer_id', $parameters['customer_id'])->select('id as value', 'title as name')->get();
            $detail['project_id'] = $parameters['project_id'];
        }

        $detail = json_decode(json_encode($detail), FALSE);
        $page_title = 'Yeni Üretim';
        $page_description = 'Yeni üretim ekleyebilirsiniz.';

        $statuses = $this->statuses;

        return view('order.production.add', compact('page_title', 'page_description', 'statuses', 'customers', 'detail', 'projects'));
    }

    public function update(int $production_id)
    {
        $d = $this->request->all();
        $parameters = $this->request->query();

        $page_title = 'Proje Düzenle';
        $page_description = 'Projeyi düzenleyebilirsiniz.';

        $statuses = array(
            array(
                'value' => "Talep Açıldı",
                'name' => "Talep Açıldı"
            ),
            array(
                'value' => "Başlandı",
                'name' => "Başlandı"
            ),
            array(
                'value' => "Tamamlandı",
                'name' => "Tamamlandı"
            ),
        );
        $statuses = json_decode(json_encode($statuses));

        $detail = Production::with(['extras' => function($q){
            $q->whereNotNull('message');
        }])->find($production_id);
        $projects = Project::where('customer_id', $detail->customer_id)->select('id as value', 'title as name')->get();

        $photos = ProductionExtra::where('production_id', $production_id)->whereNotNull('filename')->get();
        $personels = CustomerPersonel::select('id as value', DB::raw('CONCAT(name, " ", surname) as name'))->where('customer_id', $detail->customer_id)->get();

        return view('order.production.add', compact('page_title', 'page_description', 'detail', 'projects', 'photos', 'statuses', 'personels'));
    }

    public function detail(int $production_id)
    {
        $d = $this->request->all();
        $parameters = $this->request->query();
        $detail = Production::with(['extras' => function($q){
            $q->whereNotNull('message');
        }])->find($production_id);

        $page_title = $detail->title." Bilgileri";
        $page_description = $detail->title." için detaylı bilgiler";
        

        $projects = Project::where('customer_id', $detail->customer_id)->select('id as value', 'title as name')->get();

        $photos = ProductionExtra::where('production_id', $production_id)->whereNotNull('filename')->get();

        return view('order.production.detail', compact('page_title', 'page_description', 'detail', 'projects', 'photos'));
    }

    public function save(Request $request)
    {
        $data = $this->request->all();
        $parameters = $this->request->query();
        $user_id = $this->request->user()->id;

        $validator = Validator::make($data, [
            'title' => 'required',
            'customer_id' => 'required',
            'project_id' => 'nullable',
            'start_at' => 'required|date',
            'end_at' => 'required|date|after_or_equal:start_at',
            'status' => 'required'
        ]);

        $niceNames = array(
            'title' => 'Başlık',
            'customer_id' => 'Müşteri',
            'project_id' => 'Proje',
            'start_at' => 'Başlangıç tarihi',
            'end_at' => 'Bitiş tarihi',
            'status' => 'Durum'
        );

        $validator->setAttributeNames($niceNames); 

        if ($validator->fails()) {
            return response()->json([
                'message' => error_formatter($validator),
                'errors' => $validator->errors(),
            ]);
        }
  
        if(isset($data['id'])){
            $production = Production::find($data['id']);
        }else{
            $production = new Production();
        }
        $production->title = $data['title'];
        $production->customer_id = $data['customer_id'];
        $production->customer_personel_id = $data['customer_personel_id'] ?? null;
        $production->project_id = $data['project_id'] ?? null;
        $production->description = $data['description'] ?? null;
        $production->start_at = isset($data['start_at']) ? date_deformatter($data['start_at']) : null;
        $production->end_at = isset($data['end_at']) ? date_deformatter($data['end_at']) : null;
        $production->status = $data['status'] ?? 'Talep Açıldı';
        $production->save();

        $title = isset($data['id']) ? 'Üretim Güncelleme' : 'Yeni üretim kaydı';
        $description = isset($data['id']) ? 'üretimde güncelleme gerçekleştirdi' : 'yeni üretim kaydı oluşturdu';

        $log = new LogSet();
        $log->statusUpdates([
          'customer_id' => $data['customer_id'],
          'project_id' => $data['project_id'] ?? null,
          'production_id' => $production->id,
          'type' => 'production',
          'title' => $title,
          'description' => $description,
          'status' => 'success'
        ]);

        if(isset($data['assembly_start_at'])){
            $assembly = new Assembly();
            $assembly->title = $data['title'];
            $assembly->customer_id = $data['customer_id'];
            $assembly->customer_personel_id = $data['customer_personel_id'] ?? null;
            $assembly->project_id = $data['project_id'] ?? null;
            $assembly->description = $data['description'] ?? null;
            $assembly->start_at = isset($data['assembly_start_at']) ? date_deformatter($data['assembly_start_at']) : null;
            $assembly->end_at = isset($data['assembly_end_at']) ? date_deformatter($data['assembly_end_at']) : null;
            $assembly->status = $data['status'] ?? 'Talep Açıldı';
            $assembly->save();
    
            $title = isset($data['id']) ? 'Montaj Güncelleme' : 'Yeni montaj kaydı';
            $description = isset($data['id']) ? 'montajda güncelleme gerçekleştirdi' : 'yeni montaj kaydı oluşturdu';
    
            $log = new LogSet();
            $log->statusUpdates([
              'customer_id' => $data['customer_id'],
              'project_id' => $data['project_id'] ?? null,
              'assembly_id' => $assembly->id,
              'type' => 'assembly',
              'title' => $title,
              'description' => $description,
              'status' => 'success'
            ]);
        }

        if(isset($data['extra'])){
            $arr = array();
            foreach($data['extra'] as $xt){
                if(isset($xt['id'])){
                    $extra = ProductionExtra::find($xt['id']);
                }else{
                    $extra = new ProductionExtra();
                }
                $extra->user_id = $user_id;
                $extra->production_id = $production->id;
                $extra->message = $xt['message'];
                $extra->save();

                $arr[] = $extra->id;
            }
            $sil = ProductionExtra::whereNotIn('id', $arr)->where('production_id', $production->id)->delete();
        }

        if(isset($data['files'])){
          $ar = array();
          foreach ($data["files"] as $d) {
              $extra = new ProductionExtra();
              $extra->user_id = $user_id;
              $extra->production_id = $production->id;
              $extra->filename = $d;
              $extra->save();
          }
        }
        

        $result = array(
            'status' => 1,
            'message' => 'Başarıyla kaydettiniz.',
            'redirect' => route('productions'),
        );
        return response()->json($result);
    }

    public function status(Request $request)
    {
        $data = $this->request->all();
        $parameters = $this->request->query();
        $user_id = $this->request->user()->id;

        $validator = Validator::make($data, [
            'id' => 'required',
            'val' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => error_formatter($validator),
                'errors' => $validator->errors(),
            ]);
        }
  
        if(isset($data['id'])){
            $production = Production::find($data['id']);
        }else{
            $production = new Production();
        }
        $production->status = $data['val'];
        $production->save();

        $title = 'Statü güncellendi!';
        $description = 'statüyü '.$data['val'].' olarak güncelledi';

        $log = new LogSet();
        $log->statusUpdates([
          'customer_id' => $production->customer_id ?? null,
          'project_id' => $production->project_id ?? null,
          'production_id' => $production->id,
          'type' => 'production',
          'title' => $title,
          'description' => $description,
          'status' => 'info'
        ]);

        $result = array(
            'status' => 1,
            'message' => 'Başarıyla kaydettiniz.',
            'redirect' => route('productions'),
        );
        return response()->json($result);
    }

    public function delete(int $production_id)
    {
        $production = Production::find($production_id);
        $production->delete();
        $result = array(
            'status' => 1,
            'message' => 'Başarıyla pasife aldınız.'
        );
        return response()->json($result);
    }

    public function deleteExtra(int $production_extra_id)
    {
        $production = ProductionExtra::find($production_extra_id);
        $production->delete();
        $result = array(
            'status' => 1,
            'message' => 'Başarıyla pasife aldınız.'
        );
        return response()->json($result);
    }
}
