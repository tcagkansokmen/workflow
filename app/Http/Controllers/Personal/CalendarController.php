<?php declare(strict_types = 1);

namespace App\Http\Controllers\Personal;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Calendar;
use App\Models\Firm;
use App\Models\Project;
use App\Models\ProjectUser;
use App\Models\Permission;
use App\Models\PublicHoliday;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Core\CalculateDays;
use Carbon;

class CalendarController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index(): \Illuminate\Contracts\View\View
    {
        $page_title = "Zaman Yönetimi";
        $page_description = "Planlama ve gerçekleşen etkinliklerinizi tek tablo üzerinden inceleyip yönetebilirsiniz.";
        return View::make('personel.calendar.index', compact('page_title', 'page_description'));
    }

    public function team()
    {
        $page_title = "Ekip Zaman Yönetimi";
        $page_description = "Tüm ekibin zaman yönetimini inceleyebilirsiniz.";
        $datas = $this->request->all();

        if(isset($datas['year'])){
            $month = $datas['year']."-".$datas['month'];
        }else{
            $month = date('Y-m');
        }
        
        $start = Carbon\Carbon::parse($month)->startOfMonth();
        $end = Carbon\Carbon::parse($month)->endOfMonth();

        $holiday = PublicHoliday::where('start_at', '>=', $start->copy()->format('Y-m-d'))->where('end_at', '<=', $end->copy()->addDay()->format('Y-m-d'))->get();
        
        $holidays = array();
        foreach($holiday as $h){
            $st = Carbon\Carbon::parse($h->start_at);
            $en = Carbon\Carbon::parse($h->end_at);

            while ($st->lte($en)) {
                $holidays[$st->copy()->format('Y-m-d')] = $h->name;
                $st->addDay();
            }
        }

        $dates = array();
        $gunler = array();
        while ($start->lte($end)) {
            $carbon = Carbon\Carbon::parse($start);
            $dates[] = $start->copy()->format('Y-m-d');
            $gunler[] = $start->copy()->formatLocalized('%d, %a');
            $start->addDay();
        }

        $users = User::get();
        $rows = array();

        foreach($users as $user){
            $user_id = $user->id;
    
            $result = Calendar::select(array(
                '*',
                DB::raw('DATE(`start_at`) as `x`')
            ))->where('user_id', $user_id)->with(['project'])->get()->groupBy('x')->toArray();

            $rows[$user_id] = $result;
        }

        return View::make('personel.calendar.team', compact('rows', 'dates', 'users', 'gunler', 'holidays', 'page_title', 'page_description'));
    }
    public function onay()
    {
        $datas = $this->request->all();

        if(isset($datas['year'])){
            $month = $datas['year']."-".$datas['month'];
        }else{
            $month = date('Y-m');
        }
        
        $start_date = Carbon\Carbon::parse($month)->startOfMonth();
        $start = Carbon\Carbon::parse($month)->startOfMonth();
        $end = Carbon\Carbon::parse($month)->endOfMonth();

        $holiday = PublicHoliday::where('start_at', '>=', $start->copy()->format('Y-m-d'))->where('end_at', '<=', $end->copy()->addDay()->format('Y-m-d'))->get();
        $holidays = array();
        foreach($holiday as $h){
            $st = Carbon\Carbon::parse($h->start_at);
            $en = Carbon\Carbon::parse($h->end_at);

            while ($st->lte($en)) {
                $holidays[$st->copy()->format('Y-m-d')] = $h->name;
                $st->addDay();
            }
        }
        
        $dates = array();
        $gunler = array();
        $starter = $start;
        while ($starter->lte($end)) {
            $dates[] = $starter->copy()->format('Y-m-d');
            $gunler[] = $starter->copy()->formatLocalized('%d, %a');
            $starter->addDay();
        }

        $user_id = $this->request->user()->id;

        $result = Calendar::select(array(
            '*',
            DB::raw('DATE(`real_start_at`) as `x`')
        ))->where('user_id', $user_id)
        ->where('real_start_at', '>=', $start_date->copy()->format('Y-m-d'))->where('real_end_at', '<=', $start->copy()->addDay()->format('Y-m-d'))
        ->with(['project'])->get()->groupBy('x')->toArray();

        $calculate_days = new CalculateDays;
        $days = $calculate_days->calculate($start_date->copy()->format('Y-m-d'), $end->copy()->format('Y-m-d'));

        $page_title = "Dönem Kapama";
        $page_description = "Dönem kapama işlemi için, aylık çalışma saatinizi doldurmanız gerekmektedir.";

        return View::make('personel.calendar.onay-tablosu', compact('days', 'dates', 'gunler', 'holidays', 'result', 'month', 'page_title', 'page_description'));
    }

    public function onayBekleyen(): \Illuminate\Contracts\View\View
    {
        $user_id = $this->request->user()->id;
        $projeler = $this->request->user()->projelerim();
        $datas = $this->request->all();

        $data = Calendar::with('project', 'user')
        ->whereIn('project_id', $projeler)
        ->where('send_confirm', 1);

        if(isset($datas['year'])){
            $month = $datas['year']."-".$datas['month'];
            $start = Carbon\Carbon::parse($month)->startOfMonth();
            $end = Carbon\Carbon::parse($month)->endOfMonth();

            $data = $data->where('start_at', '>=', $start->copy()->format('Y-m-d'))->where('end_at', '<=', $end->copy()->addDay()->format('Y-m-d'));
        }else{
            $month = date('Y-m');
            $start = Carbon\Carbon::parse($month)->startOfMonth();
            $end = Carbon\Carbon::parse($month)->endOfMonth();

            $data = $data->where('start_at', '>=', $start->copy()->format('Y-m-d'))->where('end_at', '<=', $end->copy()->addDay()->format('Y-m-d'));
        }

        if(isset($datas['status'])){
            if($datas['status']!='all'){
                $data = $data->where('is_allowed', $datas['status']);
            }
        }

        $data = $data->orderBy('id', 'desc')
        ->get();
        
        return View::make('portal.calendar.onay', [
            'data' => $data
        ]);
    }
    public function json(): \Illuminate\Http\JsonResponse
    {
      $data = $this->request->all();

        $user_id = $this->request->user()->id;
        $result = Calendar::selectRaw('calendars.id as myId, 
        IF(calendars.name is NULL, "", calendars.name ) as name, 
        IF(calendars.real_start_at > 0, calendars.real_start_at, calendars.start_at) as start, 
        IF(calendars.real_end_at > 0, calendars.real_end_at, calendars.end_at) as end, 
        calendars.activity as description, 
        IF(calendars.start_at > 0, "ok", "danger") as my_status, 
        IF(calendars.send_confirm, IF(calendars.is_allowed="1", "fc-event-light fc-event-solid-success", IF(calendars.is_allowed="2","fc-event-solid-danger" ,"fc-event-light fc-event-solid-info") ), IF(calendars.real_start_at, "fc-event-light fc-event-solid-warning", "fc-event-light fc-event-solid-primary" ) ) as className, 
        concat(projects.name, " - ", firms.title) as title' )
        ->join('projects', 'projects.id', 'calendars.project_id');

        if(isset($data['project_id'])){
            $result = $result->where('projects.id', $data['project_id']);
        }

        if(isset($data['user_id'])){
            $result = $result->where('calendars.user_id', $data['user_id']);
        }

        $result = $result->join('firms', 'firms.id', 'projects.firm_id')
        ->where('user_id', $user_id)->groupBy('calendars.id')->get();

        $holiday = PublicHoliday::selectRaw("
        '0' as myId,
        name,
        IF(type = 'half', CONCAT(start_at, ' 08:00'), CONCAT(start_at, ' 08:00')) as start, 
        IF(type = 'half', CONCAT(end_at, ' 13:00'), CONCAT(end_at, ' 18:00')) as end, 
        description,
        'danger' as my_status,
        'fc-event-light fc-event-solid-info' as className,
        name as title
        ")
        ->get();

        $permission = Permission::selectRaw("
        '0' as myId,
        name,
        start_at as start, 
        end_at as end, 
        ' ' as description,
        'danger' as my_status,
        'fc-event-light fc-event-solid-info' as className,
        CONCAT(permission_types.name, ' ', permission_types.type) as title
        ")
        ->where('permissions.user_id', $user_id)
        ->where('permissions.status', 'Onaylandı')
        ->join('permission_types', 'permission_types.id', 'permissions.type')
        ->get();

        $c = $result->union($holiday);
        $r = $c->union($permission);

        return response()->json($r);
    }
    public function getByDay($start): \Illuminate\Http\JsonResponse
    {
        $data = $this->request->all();
        $user_id = $this->request->user()->id;
        $arr = array();

        $date = date('Y-m-d', strtotime($start));
        $start = Carbon\Carbon::parse($date);
        $end = Carbon\Carbon::parse($date);

        $calendar = Calendar::where('user_id', $user_id)->where('real_start_at', '>=', $start->copy()->format('Y-m-d'))->where('real_end_at', '<=', $start->copy()->addDay()->format('Y-m-d'))->get();

        if(count($calendar)==1){
            $arr['firm'] = $calendar[0]->project->firm_id;
            $arr['project'] = $calendar[0]->project_id;
        }
        return response()->json($arr);
    }
    public function add(): \Illuminate\Contracts\View\View
    {   
        $projelerim = $this->request->user()->projelerim();
        $firmalar = Project::whereIn('id', $projelerim)->pluck('firm_id')->toArray();
        $project_users = ProjectUser::whereIn('project_id', $projelerim)->pluck('user_id')->toArray();

        $firms = Firm::select('id as value', 'title as name')->whereIn('id', $firmalar)->get();
        $users = User::select('id as value', DB::raw('CONCAT(name, " ", surname) as name'))->whereIn('id', $project_users)->get();

        $data = $this->request->all();
        $calendar['start_at'] = $data['date'];
        $calendar['end_at'] = $data['date'];
        $is_past = $data['is_past'] ?? '';
        return View::make('personel.calendar.add',[
          'firms' => $firms,
          'dates' => $calendar,
          'is_past' => $is_past,
          'users' => $users
        ]);
    }
    public function update(int $calendar_id): \Illuminate\Contracts\View\View
    {   
        $calendar = Calendar::find($calendar_id);
        
        $projelerim = $this->request->user()->projelerim();
        $firmalar = Project::whereIn('id', $projelerim)->pluck('firm_id')->toArray();
        $project_users = ProjectUser::whereIn('project_id', $projelerim)->pluck('user_id')->toArray();

        $firms = Firm::select('id as value', 'title as name')->whereIn('id', $firmalar)->get();
        $users = User::select('id as value', DB::raw('CONCAT(name, " ", surname) as name'))->whereIn('id', $project_users)->get();

        $project = Project::find($calendar->project_id);
        $projects = Project::select('id as value', 'name')->where('firm_id', $project->firm_id)->get();
        $is_past = $data['is_past'] ?? '';
        return View::make('personel.calendar.add',[
          'firms' => $firms,
          'calendar' => $calendar,
          'projects' => $projects,
          'project' => $project,
          'is_past' => $is_past,
          'users' => $users
        ]);
    }

    public function save(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->request->validate([
            'kt_user_add_user_avatar' => 'nullable'
        ]);

        $user_id = $this->request->user()->id;
        $data = $this->request->all();

        if(isset($data['user_id'])&&($this->request->user()->isManager()||$this->request->user()->isPartner())){
            $user_id = $data['user_id'];
        }

        if(isset($data['id'])){
            $offer = Calendar::find($data['id']);
        }else{
            $offer = new Calendar();
        }
        $disabled = $this->request->user()->disabledMonths();
        $disabled_array = explode(',', $disabled);

        if(isset($data['is_done'])){
            $start = date("Y-m-d H:i", strtotime($data['real_start_at']." ".$data['real_start_time']));
            $end = date("Y-m-d H:i", strtotime($data['real_start_at']." ".$data['real_end_time']));
        }else{
            $start = date("Y-m-d H:i", strtotime($data['start_at']." ".$data['start_time']));
            $end = date("Y-m-d H:i", strtotime($data['start_at']." ".$data['end_time']));
        }

        $month_year = date('m-Y', strtotime($start));
        if(in_array($month_year, $disabled_array)){
                $result = array(
                  'status' => 0,
                  'message' => 'İlgili tarihe giriş yapamazsınız. Dönem kapanışı gerçekleştirildi'
                );
                return response()->json($result);
        }

        if($start > $end){
            $result = array(
              'status' => 0,
              'message' => 'Bitiş saati, başlangıç saatinden büyük olmalıdır.'
            );
            return response()->json($result);
        }

        if(!$data['project_id']){
            $result = array(
              'status' => 0,
              'message' => 'Kayıt yapmak için proje seçmelisiniz.'
            );
            return response()->json($result);
        }

        $bul = Calendar::where('user_id', $user_id);
        if(isset($data['id'])){
            $bul = $bul->where('id', '!=', $data['id']);
        }
        $bul = $bul->where(function($q) use ($start, $end){
            $q->where(function($q) use ($start, $end){
                $q->where('start_at', '>', $start);
                $q->where('start_at', '<', $end);
            });
            $q->orWhere(function($q) use ($start, $end){
                $q->where('start_at', '<', $start);
                $q->where('end_at', '>', $start);
            });
        });
        $bul = $bul->with('project')->first();

        if($bul){
            $result = array(
              'status' => 0,
              'message' => 'İlgili tarihler arasında "<strong>'.$bul->project->name.'</strong>" isimli projeye ait kaydınız bulunmaktadır.<br>Başlangıç: <strong>'.date('H:i', strtotime($bul['start_at']))."</strong> - Bitiş: <strong>".date('H:i', strtotime($bul['end_at']))."</strong>"
            );
            return response()->json($result);
        }

        if($offer->is_allowed){
            $offer->real_start_at = date("Y-m-d H:i", strtotime($start));
            $offer->real_end_at = date("Y-m-d H:i", strtotime($end));  
        }else{
            $offer->user_id = $user_id;
            $offer->project_id = $data['project_id'];
            $offer->name = $data['name'];
            $offer->type = "proje";
            $offer->activity = "proje";
            $offer->is_office = $data['is_office'] ?? 0;
            $offer->is_allowed = 0;  
            if(isset($data['is_past'])){
                $offer->real_start_at = date("Y-m-d H:i", strtotime($start));
                $offer->real_end_at = date("Y-m-d H:i", strtotime($end));    
            }else{
                $offer->start_at = date("Y-m-d H:i", strtotime($start));
                $offer->end_at = date("Y-m-d H:i", strtotime($end));  
            }
        }

        $offer->save();

        $result = array(
          'status' => 1,
          'redirect' => route('calendar'),
          'message' => 'Başarıyla kaydedildi. Yönlendiriliyorsunuz.'
        );

      return response()->json($result);
    }

    public function statusUpdate(Request $request): \Illuminate\Http\JsonResponse
    {
        $user_id = $this->request->user()->id;
        $data = $this->request->all();

        if(isset($data['id'])){
            $cost = Calendar::find($data['id']);
            $cost->is_allowed = $data['status'];
            $cost->save();
        }else{
            Calendar::whereIn('id', explode(',', $data['ids']))  // find your user by their email
            ->update(array('is_allowed' => $data['status']));  // update th
        }
        $result = array('status' => 1);
        return response()->json($result);
    }

    public function takvimKapat(Request $request): \Illuminate\Http\JsonResponse
    {
        $user_id = $this->request->user()->id;
        $data = $this->request->all();

        $month = $data['month'];
        $start = Carbon\Carbon::parse($month)->startOfMonth();
        $end = Carbon\Carbon::parse($month)->endOfMonth();

        $calendar = Calendar::where('user_id', $user_id)
        ->where('start_at', '>=', $start->copy()
        ->format('Y-m-d'))->where('end_at', '<=', $end->copy()
        ->addDay()
        ->format('Y-m-d'))
        ->update(array(
            'send_confirm' => 1
        ));

        $result = array(
            'status' => 1,
            'redirect' => route('calendar'),
            'message' => 'Başarıyla kaydedildi. Yönlendiriliyorsunuz.'
        );
  
        return response()->json($result);
    }

    function delete(int $id){
        $find = Calendar::find($id);
        $find->delete();


        $result = array(
            'status' => 1,
            'redirect' => route('calendar'),
            'message' => 'Başarıyla kaydedildi. Yönlendiriliyorsunuz.'
        );
  
        return response()->json($result);
    }
}
