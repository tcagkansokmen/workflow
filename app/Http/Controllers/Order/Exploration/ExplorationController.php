<?php

namespace App\Http\Controllers\Order\Exploration;

use App\Http\Controllers\Controller;

use App\Models\County;
use App\Models\Customer;
use App\Models\CustomerPersonel;
use App\Models\Exploration;
use App\Models\ExplorationExtra;
use App\Models\Project;
use App\Models\User;
use App\Models\Notification;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Core\LogSet;
use DataTables;
use DB;

class ExplorationController extends Controller
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
                'value' => "Kabul Edildi",
                'name' => "Kabul Edildi"
            ),
            array(
                'value' => "Keşif Bekliyor",
                'name' => "Keşif Bekliyor"
            ),
            array(
                'value' => "Keşif Tamamlandı",
                'name' => "Keşif Tamamlandı"
            ),
        );
        $statuses = json_decode(json_encode($statuses));
        $this->statuses = $statuses;
    }

    public function index()
    {
        $parameters = $this->request->query();
        $page_title = 'Keşif Listesi';
        $page_description = 'Tüm keşifleri görüntüleyip işlem yapabilirsiniz';

        if(isset($parameters['waiting'])){
            $page_title = 'Onay Bekleyen Keşifler';
            $page_description = 'Onayınızı bekleyen keşifleri listeleyebilirsiniz.';
        }

        $customers = Customer::select('id as value', 'title as name')->get();
        $statuses = $this->statuses;

        return view('order.exploration.index', compact('page_title', 'page_description', 'customers', 'statuses'));
    }
    public function upload(Request $request): \Illuminate\Http\JsonResponse
    {
        $d = $this->request->all();

        $file = $request->file('file');
        $filename = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))."_".time() . "." . $file->getClientOriginalExtension();
        $filePath = 'snap/exploration';
        Storage::disk('s3')->putFileAs($filePath, $file, $filename, ['visibility' => 'public']);
        return response()->json($filename);
    }

    public function json(): \Illuminate\Http\JsonResponse
    {
        $user = $this->request->user();
        $parameters = $this->request->query();

        $status_allowed = $user->power('exploration', 'status') ? true : false;
        $edit_allowed = $user->power('exploration', 'edit') ? true : false;
        $delete_allowed = $user->power('exploration', 'delete') ? true : false;
        $detail_allowed = $user->power('exploration', 'detail') ? true : false;

        $projelerim = $this->request->user()->projelerim();

        $products = Exploration::whereIn('project_id', $projelerim)->with(['customer', 'project', 'user']);

        if(isset($parameters['waiting'])){
            $products->where('user_id', $user->id)->where('status', 'Talep Açıldı');
        }

        if(isset($parameters['customer_id'])){
            $products->where('customer_id', $user->id)->where('customer_id', $parameters['customer_id']);
        }

        return Datatables::of($products)
        ->filterColumn('status', function($query, $keyword) {
            $query->where('status', $keyword);
        })
        ->filterColumn('customer_id', function($query, $keyword) {
            $query->where('customer_id', $keyword);
        })
        ->addColumn('messages', function($q) use ($user){
            return Notification::where('is_read', 0)->where('user_id', $user->id)->where('redirect', '/exploration/detail/'.$q->id)->count();
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
        
        $page_title = 'Yeni Keşif';
        $page_description = 'Yeni keşif ekleyebilirsiniz.';

        $statuses = $this->statuses;

        $users = User::select('id as value', DB::raw('CONCAT(name, " ", surname) as name'))->whereIn('group_id', [2,4])->get();

        return view('order.exploration.add', compact('page_title', 'page_description', 'statuses', 'users'));
    }

    public function update(int $exploration_id)
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
                'value' => "Keşif Bekliyor",
                'name' => "Keşif Bekliyor"
            ),
            array(
                'value' => "Keşif Tamamlandı",
                'name' => "Keşif Tamamlandı"
            ),
        );
        $statuses = json_decode(json_encode($statuses));

        $detail = Exploration::with(['extras' => function($q){
            $q->whereNotNull('message');
        }])->find($exploration_id);
        $projects = Project::where('customer_id', $detail->customer_id)->select('id as value', 'title as name')->get();

        $photos = ExplorationExtra::where('exploration_id', $exploration_id)->whereNotNull('filename')->get();

        $users = User::select('id as value', DB::raw('CONCAT(name, " ", surname) as name'))->where('group_id', 4)->get();

        $personels = CustomerPersonel::select('id as value', DB::raw('CONCAT(name, " ", surname) as name'))->where('customer_id', $detail->customer_id)->get();

        return view('order.exploration.add', compact('page_title', 'page_description', 'detail', 'projects', 'photos', 'statuses', 'users', 'personels'));
    }

    public function detail(int $exploration_id)
    {
        $d = $this->request->all();
        $parameters = $this->request->query();
        $detail = Exploration::with(['extras' => function($q){
            $q->whereNotNull('message');
        }])->find($exploration_id);

        $page_title = $detail->title." Bilgileri";
        $page_description = $detail->title." için detaylı bilgiler";
        
        $projects = Project::where('customer_id', $detail->customer_id)->select('id as value', 'title as name')->get();

        $photos = ExplorationExtra::where('exploration_id', $exploration_id)->whereNotNull('filename')->get();

        return view('order.exploration.detail', compact('page_title', 'page_description', 'detail', 'projects', 'photos'));
    }

    public function save(Request $request)
    {
        $data = $this->request->all();
        $parameters = $this->request->query();
        $user_id = $this->request->user()->id;

        $validator = Validator::make($data, [
            'title' => 'required',
            'customer_id' => 'required',
            'user_id' => 'required',
            'project_id' => 'nullable',
            'start_at' => 'required|date',
            'end_at' => 'required|date|after_or_equal:start_at',
            'email' => 'nullable|email',
        ]);
        
        $niceNames = array(
            'title' => 'Başlık',
            'customer_id' => 'Müşteri',
            'user_id' => 'Sorumlu',
            'project_id' => 'Proje',
            'start_at' => 'Başlangıç tarihi',
            'end_at' => 'Bitiş tarihi',
            'email' => 'E-mail'
        );

        $validator->setAttributeNames($niceNames); 

        if ($validator->fails()) {
            return response()->json([
                'message' => error_formatter($validator),
                'errors' => $validator->errors(),
            ]);
        }
  
        if(isset($data['id'])){
            $exploration = Exploration::find($data['id']);
        }else{
            $exploration = new Exploration();
        }
        $exploration->title = $data['title'];
        $exploration->customer_id = $data['customer_id'];
        $exploration->customer_personel_id = $data['customer_personel_id'] ?? null;
        $exploration->project_id = $data['project_id'] ?? null;
        $exploration->user_id = $data['user_id'] ?? null;
        $exploration->description = $data['description'] ?? null;
        $exploration->start_at = isset($data['start_at']) ? date_deformatter($data['start_at']) : null;
        $exploration->end_at = isset($data['end_at']) ? date_deformatter($data['end_at']) : null;
        $exploration->status = $data['status'] ?? 'Talep Açıldı';
        $exploration->city_id = $data['city_id'] ?? null;
        $exploration->county_id = $data['county_id'] ?? null;
        $exploration->address = $data['address'] ?? null;
        $exploration->company = $data['company'] ?? null;
        $exploration->name = $data['name'] ?? null;
        $exploration->phone = $data['phone'] ?? null;
        $exploration->email = $data['email'] ?? null;
        $exploration->save();

        $title = isset($data['id']) ? 'Keşif Güncelleme' : 'Yeni keşif kaydı';
        $description = isset($data['id']) ? 'keşifte güncelleme gerçekleştirdi' : 'yeni keşif kaydı oluşturdu';

        $log = new LogSet();
        $log->statusUpdates([
          'customer_id' => $data['customer_id'],
          'project_id' => $data['project_id'] ?? null,
          'exploration_id' => $exploration->id,
          'type' => 'exploration',
          'title' => $title,
          'description' => $description,
          'status' => 'success'
        ]);

        if(isset($data['extra'])){
            $arr = array();
            foreach($data['extra'] as $xt){
                if(isset($xt['id'])){
                    $extra = ExplorationExtra::find($xt['id']);
                }else{
                    $extra = new ExplorationExtra();
                }
                $extra->user_id = $user_id;
                $extra->exploration_id = $exploration->id;
                $extra->message = $xt['message'];
                $extra->save();

                $arr[] = $extra->id;
            }
            $sil = ExplorationExtra::whereNotIn('id', $arr)->where('exploration_id', $exploration->id)->delete();
        }

        if(isset($data['files'])){
          $ar = array();
          foreach ($data["files"] as $d) {
              $extra = new ExplorationExtra();
              $extra->user_id = $user_id;
              $extra->exploration_id = $exploration->id;
              $extra->filename = $d;
              $extra->save();
          }
        }
        

        $result = array(
            'status' => 1,
            'message' => 'Başarıyla kaydettiniz.',
            'redirect' => route('explorations'),
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
            $exploration = Exploration::find($data['id']);
        }else{
            $exploration = new Exploration();
        }
        $exploration->status = $data['val'];
        $exploration->save();

        $title = 'Statü güncellendi!';
        $description = 'statüyü '.$data['val'].' olarak güncelledi';

        $log = new LogSet();
        $log->statusUpdates([
            'customer_id' => $exploration->customer_id ?? null,
            'project_id' => $exploration->project_id ?? null,
          'assembly_id' => $exploration->id,
          'type' => 'exploration',
          'title' => $title,
          'description' => $description,
          'status' => 'info'
        ]);

        $result = array(
            'status' => 1,
            'message' => 'Başarıyla kaydettiniz.',
            'redirect' => route('explorations'),
        );
        return response()->json($result);
    }

    public function delete(int $exploration_id)
    {
        $exploration = Exploration::find($exploration_id);
        $exploration->delete();
        $result = array(
            'status' => 1,
            'message' => 'Başarıyla pasife aldınız.'
        );
        return response()->json($result);
    }

    public function deleteExtra(int $exploration_extra_id)
    {
        $exploration = ExplorationExtra::find($exploration_extra_id);
        $exploration->delete();
        $result = array(
            'status' => 1,
            'message' => 'Başarıyla pasife aldınız.'
        );
        return response()->json($result);
    }
}
