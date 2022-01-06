<?php declare(strict_types = 1);

namespace App\Http\Controllers\HR\Personel;

use App\Http\Controllers\Controller;
use App\Models\PersonelDemand;
use App\Models\Department;
use App\Models\Title;
use App\Models\Interview;
use App\Models\Candidate;
use App\Models\Iller;
use App\Models\Ilceler;
use App\Core\OfferLogs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

use Carbon\Carbon;
use DataTables;

class NoticeController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index(): \Illuminate\Contracts\View\View
    {
        $datas = $this->request->all();

        $page_title = "Personel Talebi";
        $page_description = "Personel talebi oluşturabilir, oluşturulmuş personel taleplerini işleme alabilirsiniz.";

        return View::make('hr.notice.index', compact('page_title', 'page_description'));
    }

    public function json(): \Illuminate\Http\JsonResponse
    {
        $datas = $this->request->all();
        $parameters = $this->request->query();
        $user_id = $this->request->user()->id;
        $user = $this->request->user();
        $demand_allowed = $user->power('notice', 'demand') ? true : false;
        $edit_allowed = $user->power('notice', 'edit') ? true : false;
        $delete_allowed = $user->power('notice', 'delete') ? true : false;
        $detail_allowed = $user->power('notice', 'detail') ? true : false;

        $data = PersonelDemand::with(['user', 'position', 'department'])->withCount(['candidates', 'candidates as olumlu' => function($q){
            $q->where('candidates.status', 'olumlu');
        }]);

        if(($this->request->user()->isHr())||$this->request->user()->group_id=='4'){
        }else{
          $data = $data->where('personel_demands.department_id', $this->request->user()->department_id);
        }
        
        if(isset($datas['status'])){
            $data = $data->where('status', $datas['status']);
        }

        return Datatables::of($data)
        ->addColumn('date_formatted', function($data){
            return Carbon::parse($data->demand_date)->formatLocalized('%d %B %Y');
        })
        ->addColumn('demand_allowed', function($d) use ($demand_allowed){
            if($d->status == 'talep_edildi'){
                return $demand_allowed;
            }
            return false;
        })
        ->addColumn('edit_allowed', function($d) use ($edit_allowed){
            if($d->status != 'kapatıldı'){
                return $edit_allowed;
            }
            return false;
        })->addColumn('delete_allowed', function($d) use ($delete_allowed){
            if($d->status != 'kapatıldı'){
                return $delete_allowed;
            }
            return false;
        })->addColumn('detail_allowed', function() use ($detail_allowed){
            return $detail_allowed;
        })->make(true);

    }

    public function pool(): \Illuminate\Contracts\View\View
    {
        $datas = $this->request->all();

        $page_title = "Aday Havuzu";
        $page_description = "Geçmiş ilanlarda toplanan adaylara ait bilgiler.";

        return View::make('hr.notice.pool', compact('page_title', 'page_description'));
    }

    public function poolJson(): \Illuminate\Http\JsonResponse
    {
        $datas = $this->request->all();
        $parameters = $this->request->query();
        $user_id = $this->request->user()->id;
        $user = $this->request->user();
        $demand_allowed = $user->power('notice', 'demand') ? true : false;
        $edit_allowed = $user->power('notice', 'edit') ? true : false;
        $delete_allowed = $user->power('notice', 'delete') ? true : false;
        $detail_allowed = $user->power('notice', 'detail') ? true : false;

        $data = Candidate::with(['demand', 'demand.position'])->where('id', '>', 0);

        return Datatables::of($data)
        ->addColumn('date_formatted', function($data){
            return Carbon::parse($data->demand_date)->formatLocalized('%d %B %Y');
        })
        ->addColumn('demand_allowed', function($d) use ($demand_allowed){
            if($d->status == 'talep_edildi'){
                return $demand_allowed;
            }
            return false;
        })
        ->addColumn('edit_allowed', function($d) use ($edit_allowed){
            if($d->status != 'kapatıldı'){
                return $edit_allowed;
            }
            return false;
        })->addColumn('delete_allowed', function($d) use ($delete_allowed){
            if($d->status != 'kapatıldı'){
                return $delete_allowed;
            }
            return false;
        })->addColumn('detail_allowed', function() use ($detail_allowed){
            return $detail_allowed;
        })->make(true);

    }
    public function add(): \Illuminate\Contracts\View\View
    {

        $page_title = "Personel Talebi";
        $page_description = "Personel talebi ve talep edilen personel ilanının yayınlanması.";

        $department_id = $this->request->user()->department_id;
        if(($this->request->user()->isHr()&&$this->request->user()->isManager())||$this->request->user()->group_id=='4'){
          $departmanlar = Department::select('id as value', 'name')->get();
        }else{
          $departmanlar = Department::select('id as value', 'name')->where('id', $department_id)->get();
        }
        $title = Title::select('id as value', 'title as name')->get();

        $types = [
            ['value' => 'Tam Zamanlı', 'name' => 'Tam Zamanlı'],
            ['value' => 'Proje Bazlı', 'name' => 'Proje Bazlı'],
            ['value' => 'Part-time', 'name' => 'Part-time'],
        ];
        $types = json_decode(json_encode($types), FALSE);
        
        return View::make('hr.notice.add', compact('departmanlar', 'title', 'types', 'page_title', 'page_description'));
    }

    public function detail(int $personel_id): \Illuminate\Contracts\View\View
    {
      $detail = PersonelDemand::with(['user'])->find($personel_id);

      $page_title = $detail->title;
      $page_description = "İlana ait aday ve detaylar";

      $departmanlar = Department::get();
      $title = Title::get();
      $cands = Candidate::where('demand_id', $personel_id)->get()->pluck('id');
      $candidates = Interview::whereIn('candidate_id', $cands)
      ->where(function($q){
        $q->where('status', 'olumlu');
        $q->orWhere('status', 'olumsuz');
        $q->orWhere(function($qe){
            $qe->where('status', 'Bekliyor');
            $qe->where('start_at', '>=', date('Y-m-d'));
        });
      })
      ->groupBy('status')->selectRaw('count(id) as value, status as label')->orderBy('value', 'desc')->get();


      $toplam_olumlu = Interview::whereIn('candidate_id', $cands)->where('status', 'olumlu_mulakat')->pluck('candidate_id')->toArray();
      $toplam_olumlu = Candidate::whereIn('id', $toplam_olumlu)->count();
      $toplam = Candidate::whereIn('id', $cands)->count();
      
      return View::make('hr.notice.detail',[
        'detail' => $detail,
        'departmanlar' => $departmanlar,
        'title' => $title,
        'candidates' => $candidates,
        'page_title' => $page_title,
        'page_description' => $page_description,
        'toplam_olumlu' => $toplam_olumlu,
        'toplam' => $toplam
      ]);
    }

    public function update(int $personel_id): \Illuminate\Contracts\View\View
    {
      $detail = PersonelDemand::with(['user'])->find($personel_id);
      $page_title = "Personel Talebi";
      $page_description = "Personel talebi ve talep edilen personel ilanının yayınlanması.";

      $department_id = $this->request->user()->department_id;
      if(($this->request->user()->isHr()&&$this->request->user()->isManager())||$this->request->user()->group_id=='4'){
        $departmanlar = Department::select('id as value', 'name')->get();
      }else{
        $departmanlar = Department::select('id as value', 'name')->where('id', $department_id)->get();
      }
      $title = Title::select('id as value', 'title as name')->get();

      $types = [
          ['value' => 'Tam Zamanlı', 'name' => 'Tam Zamanlı'],
          ['value' => 'Proje Bazlı', 'name' => 'Proje Bazlı'],
          ['value' => 'Part-time', 'name' => 'Part-time'],
      ];
      $types = json_decode(json_encode($types), FALSE);
      
      return View::make('hr.notice.add', compact('detail', 'departmanlar', 'title', 'types', 'page_title', 'page_description'));
      
    }

    public function singleJson(Request $request, int $id): \Illuminate\Http\JsonResponse
    {
        $this->request->validate([
            'kt_user_add_user_avatar' => 'nullable'
        ]);
        $datas = $this->request->all();
        $parameters = $this->request->query();
        $user_id = $this->request->user()->id;
        $user = $this->request->user();
        $edit_allowed = $user->power('candidate', 'edit') ? true : false;
        $delete_allowed = $user->power('candidate', 'delete') ? true : false;
        $detail_allowed = $user->power('candidate', 'detail') ? true : false;

        $data = Candidate::with(['demand', 'demand.position', 'interviews'])->where('demand_id', $id);

        return Datatables::of($data)
        ->addColumn('edit_allowed', function($d) use ($edit_allowed){
            return $edit_allowed;
        })
        ->addColumn('detail_allowed', function($d) use ($detail_allowed){
            return $detail_allowed;
        })
        ->make(true);
    }

    public function save(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->request->validate([
            'kt_user_add_user_avatar' => 'nullable'
        ]);
        $user_id = $this->request->user()->id;
        $data = $this->request->all();
        
        if(!isset($data['url'])){
            $validator = Validator::make($data, [
                'department_id' => 'required',
                'position_id' => 'required',
                'quantity' => 'required|numeric|min:1',
                'type' => 'required',
                'demand_date' => 'required',
                'details' => 'required'
            ]);
        }else{
            $validator = Validator::make($data, [
                'url' => 'required',
                'notice_date' => 'required',
                'notice_end_date' => 'required',
                'title' => 'required'
            ]);
        }
  
        if ($validator->fails()) {
            return response()->json([
                'message' => error_formatter($validator),
                'errors' => $validator->errors(),
            ]);
        }

        
        $demand = new PersonelDemand;
        if(isset($data['id'])){
          $demand = PersonelDemand::find($data['id']);

        }else{
          $demand->user_id = $user_id;

        }

        if(isset($data['is_status'])){
            $status = "ilan yayınlandı";
            $demand->status = $status ?? 'talep edildi';
        }else{
            $demand->status = $demand->status ?? 'talep edildi';
        }

        $demand->department_id = $data['department_id'] ?? $demand->department_id;
        $demand->position_id = $data['position_id'] ?? $demand->position_id;
        $demand->type = $data['type'] ?? $demand->type;
        $demand->is_active = $demand->is_active ?? 1;
        $demand->details = $data['details'] ?? $demand->details;
        $demand->url = $data['url'] ?? NULL;
        $demand->title = $data['title'] ?? NULL;
        $demand->description = $data['description'] ?? NULL;
        $demand->quantity = $data['quantity'] ?? 1;
        $demand->demand_date = isset($data['demand_date']) ? date('Y-m-d', strtotime($data['demand_date'])) : date('Y-m-d');
        $demand->notice_date = isset($data['notice_date']) ? date('Y-m-d', strtotime($data['notice_date'])) : '0000-00-00';
        $demand->notice_end_date = isset($data['notice_end_date']) ? date('Y-m-d', strtotime($data['notice_end_date'])) : '0000-00-00';
        $demand->save();
        
        $result = array(
          'status' => 1,
          'redirect' => route('notice'),
          'message' => 'Başarıyla kaydedildi. Yönlendiriliyorsunuz.'
      );

      return response()->json($result);
    }

    public function updateState(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->request->validate([
            'kt_user_add_user_avatar' => 'nullable'
        ]);


        $user_id = $this->request->user()->id;
        $data = $this->request->all();

        $offer = PersonelDemand::find($data['id']);
        $offer->status = $data['value'];
        $offer->save();

        $result = array(
          'status' => 1,
          'redirect' => route('notice'),
          'message' => 'Başarıyla kaydedildi. Yönlendiriliyorsunuz.'
      );

      return response()->json($result);
    }
    function delete(int $id){

        $find = PersonelDemand::find($id);
        $find->delete();
        $result = array(
          'status' => 1,
          'redirect' => route('notice'),
          'message' => 'Başarıyla kaydedildi. Yönlendiriliyorsunuz.'
        );

        return response()->json($result);
    }
}
