<?php

namespace App\Http\Controllers\Order\Printing;

use App\Http\Controllers\Controller;

use App\Models\County;
use App\Models\Customer;
use App\Models\CustomerPersonel;
use App\Models\Printing;
use App\Models\PrintingExtra;
use App\Models\Project;
use App\Models\PrintingMeta;
use App\Models\User;
use App\Models\Notification;

use App\Models\BillProject;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Core\LogSet;
use DataTables;
use DB;

class PrintingController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
        $statuses = array(array( 
            'value' => 'Baskı Onaylandı',
            'name' => 'Baskı Onaylandı',
        ),
        array(
            'value' => "Talep Açıldı",
            'name' => "Talep Açıldı"
        ),
        array(
            'value' => "Baskı Başladı",
            'name' => "Baskı Başladı"
        ),
        array(
            'value' => "Baskı Tamamlandı",
            'name' => "Baskı Tamamlandı"
        ));
        $statuses = json_decode(json_encode($statuses));
        $this->statuses = $statuses;
    }

    public function index()
    {
        $parameters = $this->request->query();
        $page_title = 'Baskı Listesi';
        $page_description = 'Tüm baskıları görüntüleyip işlem yapabilirsiniz';

        if(isset($parameters['waiting'])){
            $page_title = 'Onay Bekleyen Baskılar';
            $page_description = 'Onayınızı bekleyen baskıları listeleyebilirsiniz.';
        }

        $customers = Customer::select('id as value', 'title as name')->get();
        $statuses = $this->statuses;

        return view('order.printing.index', compact('page_title', 'page_description', 'customers', 'statuses'));
    }
    public function upload(Request $request): \Illuminate\Http\JsonResponse
    {
        $d = $this->request->all();

          $file = $request->file('file');
          $filename = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))."_".time() . "." . $file->getClientOriginalExtension();
          $filePath = 'snap/printing';
          Storage::disk('s3')->putFileAs($filePath, $file, $filename, ['visibility' => 'public']);
          return response()->json($filename);
    }

    public function json(): \Illuminate\Http\JsonResponse
    {
        $user = $this->request->user();
        $parameters = $this->request->query();

        $status_allowed = $user->power('production', 'status') ? true : false;
        $edit_allowed = $user->power('printing', 'edit') ? true : false;
        $delete_allowed = $user->power('printing', 'delete') ? true : false;
        $detail_allowed = $user->power('printing', 'detail') ? true : false;

        $projelerim = $this->request->user()->projelerim();

        $products = Printing::whereIn('project_id', $projelerim)->with(['customer', 'project'])->withCount(['bills']);

        if(isset($parameters['waiting'])&&$user->group_id==6){
            $products->where('status', 'Talep Açıldı');
        }

        return Datatables::of($products)
        ->filterColumn('end_at', function($query, $keyword) {
            $bul = BillProject::whereNotNull('printing_id')->pluck('printing_id')->toArray();
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
            return Notification::where('is_read', 0)->where('user_id', $user->id)->where('redirect', '/printing/detail/'.$q->id)->count();
        })
        ->addColumn('status_allowed', function($d) use ($status_allowed, $user){
            if($d->printer_id==$user->id){
                return $status_allowed;
            }
            return false;
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
        $user = $this->request->user();
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
        $page_title = 'Yeni Baskı';
        $page_description = 'Yeni baskı ekleyebilirsiniz.';

        $metas = PrintingMeta::where('type', 'index')->get();

        if($user->group->name=='baskı operatörü'){
            $statuses = $this->statuses;
        }else{
            $statuses = array(
                array(
                    'value' => "Talep Açıldı",
                    'name' => "Talep Açıldı"
                ),
                array(
                    'value' => "Baskı Başladı",
                    'name' => "Baskı Başladı"
                ),
                array(
                    'value' => "Baskı Tamamlandı",
                    'name' => "Baskı Tamamlandı"
                ),
            );
        }

        $statuses = json_decode(json_encode($statuses));

        $users = User::select('id as value', DB::raw('CONCAT(name, " ", surname) as name'))->where('group_id', 6)->get();

        return view('order.printing.add', compact('page_title', 'page_description', 'statuses', 'metas', 'detail', 'customers', 'projects', 'users'));
    }

    public function update(int $printing_id)
    {
        $d = $this->request->all();
        $parameters = $this->request->query();
        $user = $this->request->user();

        $page_title = 'Proje Düzenle';
        $page_description = 'Projeyi düzenleyebilirsiniz.';

        if($user->group->name=='baskı operatörü'){
            $statuses = $this->statuses;
        }else{
            $statuses = array(
                array(
                    'value' => "Talep Açıldı",
                    'name' => "Talep Açıldı"
                ),
                array(
                    'value' => "Baskı Başladı",
                    'name' => "Baskı Başladı"
                ),
                array(
                    'value' => "Baskı Tamamlandı",
                    'name' => "Baskı Tamamlandı"
                ),
            );
        }
        $statuses = json_decode(json_encode($statuses));

        $detail = Printing::with(['extras' => function($q){
            $q->whereNotNull('message');
        }])->find($printing_id);
        $projects = Project::where('customer_id', $detail->customer_id)->select('id as value', 'title as name')->get();

        $photos = PrintingExtra::where('printing_id', $printing_id)->whereNotNull('filename')->get();
        $metas = PrintingMeta::where('type', 'index')->get();
        $detail_meta = PrintingMeta::where('printing_id', $printing_id)->where('type', 'content')
        ->groupBy('key')
        ->get()->keyBy('key');

        $users = User::select('id as value', DB::raw('CONCAT(name, " ", surname) as name'))->where('group_id', 6)->get();
        $personels = CustomerPersonel::select('id as value', DB::raw('CONCAT(name, " ", surname) as name'))->where('customer_id', $detail->customer_id)->get();

        return view('order.printing.add', compact('page_title', 'page_description', 'detail', 'projects', 'photos', 'statuses', 'metas', 'detail_meta', 'users', 'personels'));
    }

    public function detail(int $printing_id)
    {
        $d = $this->request->all();
        $parameters = $this->request->query();
        $detail = Printing::with(['extras' => function($q){
            $q->whereNotNull('message');
        }])->find($printing_id);

        $page_title = $detail->title." Bilgileri";
        $page_description = $detail->title." için detaylı bilgiler";
        

        $projects = Project::where('customer_id', $detail->customer_id)->select('id as value', 'title as name')->get();

        $photos = PrintingExtra::where('printing_id', $printing_id)->whereNotNull('filename')->get();
        $metas = PrintingMeta::where('type', 'index')->get();
        $detail_meta = PrintingMeta::where('printing_id', $printing_id)->where('type', 'content')
        ->groupBy('key')
        ->get()->keyBy('key');

        return view('order.printing.detail', compact('page_title', 'page_description', 'detail', 'projects', 'photos', 'detail_meta', 'metas'));
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
            'printer_id' => 'required',
            'start_at' => 'required|date',
            'end_at' => 'required|date|after_or_equal:start_at',
            'status' => 'required'
        ]);

        $niceNames = array(
            'title' => 'Başlık',
            'customer_id' => 'Müşteri',
            'project_id' => 'Proje',
            'printer_id' => 'Baskı Operatörü',
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
            $printing = Printing::find($data['id']);
        }else{
            $printing = new Printing();
        }
        $printing->title = $data['title'];
        $printing->customer_id = $data['customer_id'];
        $printing->customer_personel_id = $data['customer_personel_id'] ?? null;
        $printing->project_id = $data['project_id'] ?? null;
        $printing->printer_id = $data['printer_id'] ?? null;
        $printing->description = $data['description'] ?? null;
        $printing->start_at = isset($data['start_at']) ? date_deformatter($data['start_at']) : null;
        $printing->end_at = isset($data['end_at']) ? date_deformatter($data['end_at']) : null;
        $printing->status = $data['status'] ?? 'Talep Açıldı';
        $printing->save();

        $title = isset($data['id']) ? 'Baskı Güncelleme' : 'Yeni baskı kaydı';
        $description = isset($data['id']) ? 'baskıda güncelleme gerçekleştirdi' : 'yeni baskı kaydı oluşturdu';

        $log = new LogSet();
        $log->statusUpdates([
          'customer_id' => $data['customer_id'],
          'project_id' => $data['project_id'] ?? null,
          'printing_id' => $printing->id,
          'type' => 'printing',
          'title' => $title,
          'description' => $description,
          'status' => 'success'
        ]);

        if(isset($data['extra'])){
            $arr = array();
            foreach($data['extra'] as $xt){
                if(isset($xt['id'])){
                    $extra = PrintingExtra::find($xt['id']);
                }else{
                    $extra = new PrintingExtra();
                }
                $extra->user_id = $user_id;
                $extra->printing_id = $printing->id;
                $extra->message = $xt['message'];
                $extra->save();

                $arr[] = $extra->id;
            }
            $sil = PrintingExtra::whereNotIn('id', $arr)->where('printing_id', $printing->id)->delete();
        }

        if(isset($data['files'])){
          $ar = array();
          foreach ($data["files"] as $d) {
              $extra = new PrintingExtra();
              $extra->user_id = $user_id;
              $extra->printing_id = $printing->id;
              $extra->filename = $d;
              $extra->save();
          }
        }
        
        if(isset($data['meta'])){
            $yoket = PrintingMeta::where('printing_id', $printing->id)->delete();
            foreach($data['meta'] as $key => $value){
                if($value){
                    $meta_ekle = new PrintingMeta;
                    $meta_ekle->printing_id = $printing->id;
                    $meta_ekle->key = $key;
                    $meta_ekle->value = $value ?? null;
                    $meta_ekle->type = 'content';
                    $meta_ekle->save();
                }
            }
        }

        $result = array(
            'status' => 1,
            'message' => 'Başarıyla kaydettiniz.',
            'redirect' => route('printings'),
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
            $printing = Printing::find($data['id']);
        }else{
            $printing = new Printing();
        }
        $printing->status = $data['val'];
        $printing->save();

        $title = 'Statü güncellendi!';
        $description = 'statüyü '.$data['val'].' olarak güncelledi';

        $log = new LogSet();
        $log->statusUpdates([
            'customer_id' => $printing->customer_id ?? null,
            'project_id' => $printing->project_id ?? null,
          'printing_id' => $printing->id,
          'type' => 'printing',
          'title' => $title,
          'description' => $description,
          'status' => 'info'
        ]);

        $result = array(
            'status' => 1,
            'message' => 'Başarıyla kaydettiniz.',
            'redirect' => route('printings'),
        );
        return response()->json($result);
    }
    public function delete(int $printing_id)
    {
        $printing = Printing::find($printing_id);
        $printing->delete();
        $result = array(
            'status' => 1,
            'message' => 'Başarıyla pasife aldınız.'
        );
        return response()->json($result);
    }

    public function deleteExtra(int $printing_extra_id)
    {
        $printing = PrintingExtra::find($printing_extra_id);
        $printing->delete();
        $result = array(
            'status' => 1,
            'message' => 'Başarıyla pasife aldınız.'
        );
        return response()->json($result);
    }
}
