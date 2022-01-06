<?php

namespace App\Http\Controllers\Order\Assembly;

use App\Http\Controllers\Controller;

use App\Models\County;
use App\Models\Customer;
use App\Models\CustomerPersonel;
use App\Models\Assembly;
use App\Models\AssemblyExtra;
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

class AssemblyController extends Controller
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
                'value' => "Montaja Başlandı",
                'name' => "Montaja Başlandı"
            ),
            array(
                'value' => "Montaj Tamamlandı",
                'name' => "Montaj Tamamlandı"
            ),
        );
        $statuses = json_decode(json_encode($statuses));
        $this->statuses = $statuses;
    }

    public function index()
    {
        $parameters = $this->request->query();
        $page_title = 'Montaj Listesi';
        $page_description = 'Tüm montajları görüntüleyip işlem yapabilirsiniz';

        if(isset($parameters['waiting'])){
            $page_title = 'Onay Bekleyen Montajlar';
            $page_description = 'Onayınızı bekleyen montajları listeleyebilirsiniz.';
        }

        $customers = Customer::select('id as value', 'title as name')->get();
        $statuses = $this->statuses;

        return view('order.assembly.index', compact('page_title', 'page_description', 'customers', 'statuses'));
    }
    public function upload(Request $request): \Illuminate\Http\JsonResponse
    {
        $d = $this->request->all();

          $file = $request->file('file');
          $filename = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))."_".time() . "." . $file->getClientOriginalExtension();
          $filePath = 'snap/assembly';
          Storage::disk('s3')->putFileAs($filePath, $file, $filename, ['visibility' => 'public']);
          return response()->json($filename);
    }

    public function json(): \Illuminate\Http\JsonResponse
    {
        $user = $this->request->user();
        $parameters = $this->request->query();

        $status_allowed = $user->power('assembly', 'status') ? true : false;
        $edit_allowed = $user->power('assembly', 'edit') ? true : false;
        $delete_allowed = $user->power('assembly', 'delete') ? true : false;
        $detail_allowed = $user->power('assembly', 'detail') ? true : false;

        $projelerim = $this->request->user()->projelerim();

        $products = Assembly::whereIn('project_id', $projelerim)->with(['customer', 'project'])->withCount(['bills']);

        if(isset($parameters['waiting'])&&$user->group_id==2){
            $products->where('status', 'Talep Açıldı');
        }

        return Datatables::of($products)
        ->filterColumn('end_at', function($query, $keyword) {
            $bul = BillProject::whereNotNull('assembly_id')->pluck('assembly_id')->toArray();
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
            return Notification::where('is_read', 0)->where('user_id', $user->id)->where('redirect', '/assembly/detail/'.$q->id)->count();
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
        $page_title = 'Yeni Montaj';
        $page_description = 'Yeni montaj ekleyebilirsiniz.';

        $statuses = $this->statuses;

        return view('order.assembly.add', compact('page_title', 'page_description', 'statuses', 'detail', 'customers', 'projects'));
    }

    public function update(int $assembly_id)
    {
        $d = $this->request->all();
        $parameters = $this->request->query();

        $page_title = 'Montaj Düzenle';
        $page_description = 'Montajı düzenleyebilirsiniz.';

        $statuses = $this->statuses;

        $detail = Assembly::with(['extras' => function($q){
            $q->whereNotNull('message');
        }])->find($assembly_id);
        $projects = Project::where('customer_id', $detail->customer_id)->select('id as value', 'title as name')->get();

        $photos = AssemblyExtra::where('assembly_id', $assembly_id)->whereNotNull('filename')->get();

        $personels = CustomerPersonel::select('id as value', DB::raw('CONCAT(name, " ", surname) as name'))->where('customer_id', $detail->customer_id)->get();

        return view('order.assembly.add', compact('page_title', 'page_description', 'detail', 'projects', 'photos', 'statuses', 'personels'));
    }

    public function detail(int $assembly_id)
    {
        $d = $this->request->all();
        $parameters = $this->request->query();
        $detail = Assembly::with(['extras' => function($q){
            $q->whereNotNull('message');
        }])->find($assembly_id);

        $page_title = $detail->title." Bilgileri";
        $page_description = $detail->title." için detaylı bilgiler";
        

        $projects = Project::where('customer_id', $detail->customer_id)->select('id as value', 'title as name')->get();

        $photos = AssemblyExtra::where('assembly_id', $assembly_id)->whereNotNull('filename')->get();

        return view('order.assembly.detail', compact('page_title', 'page_description', 'detail', 'projects', 'photos'));
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
            $assembly = Assembly::find($data['id']);
        }else{
            $assembly = new Assembly();
        }
        $assembly->title = $data['title'];
        $assembly->customer_id = $data['customer_id'];
        $assembly->customer_personel_id = $data['customer_personel_id'] ?? null;
        $assembly->project_id = $data['project_id'] ?? null;
        $assembly->description = $data['description'] ?? null;
        $assembly->start_at = isset($data['start_at']) ? date_deformatter($data['start_at']) : null;
        $assembly->end_at = isset($data['end_at']) ? date_deformatter($data['end_at']) : null;
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

        if(isset($data['extra'])){
            $arr = array();
            foreach($data['extra'] as $xt){
                if(isset($xt['id'])){
                    $extra = AssemblyExtra::find($xt['id']);
                }else{
                    $extra = new AssemblyExtra();
                }
                $extra->user_id = $user_id;
                $extra->assembly_id = $assembly->id;
                $extra->message = $xt['message'];
                $extra->save();

                $arr[] = $extra->id;
            }
            $sil = AssemblyExtra::whereNotIn('id', $arr)->where('assembly_id', $assembly->id)->delete();
        }

        if(isset($data['files'])){
          $ar = array();
          foreach ($data["files"] as $d) {
              $extra = new AssemblyExtra();
              $extra->user_id = $user_id;
              $extra->assembly_id = $assembly->id;
              $extra->filename = $d;
              $extra->save();
          }
        }
        

        $result = array(
            'status' => 1,
            'message' => 'Başarıyla kaydettiniz.',
            'redirect' => route('assemblys'),
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
            $assembly = Assembly::find($data['id']);
        }else{
            $assembly = new Assembly();
        }
        $assembly->status = $data['val'];
        $assembly->save();

        $title = 'Statü güncellendi!';
        $description = 'statüyü '.$data['val'].' olarak güncelledi';

        $log = new LogSet();
        $log->statusUpdates([
            'customer_id' => $assembly->customer_id ?? null,
            'project_id' => $assembly->project_id ?? null,
          'assembly_id' => $assembly->id,
          'type' => 'assembly',
          'title' => $title,
          'description' => $description,
          'status' => 'info'
        ]);

        $result = array(
            'status' => 1,
            'message' => 'Başarıyla kaydettiniz.',
            'redirect' => route('assemblys'),
        );
        return response()->json($result);
    }

    public function delete(int $assembly_id)
    {
        $assembly = Assembly::find($assembly_id);
        $assembly->delete();
        $result = array(
            'status' => 1,
            'message' => 'Başarıyla pasife aldınız.'
        );
        return response()->json($result);
    }

    public function deleteExtra(int $assembly_extra_id)
    {
        $assembly = AssemblyExtra::find($assembly_extra_id);
        $assembly->delete();
        $result = array(
            'status' => 1,
            'message' => 'Başarıyla pasife aldınız.'
        );
        return response()->json($result);
    }
}
