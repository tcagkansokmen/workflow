<?php declare(strict_types = 1);

namespace App\Http\Controllers\Personal\Demands;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\PublicHoliday;
use App\Models\Birthday;
use App\Models\Document;
use App\Models\DocumentUser;
use App\Models\User;
use App\Models\Form;
use App\Models\UsefulLink;
use App\Models\EducationQuestion;
use App\Models\EducationRating;
use App\Models\EducationUser;
use App\Models\Education;
use App\Models\ProjectRating;
use App\Models\PersonelDemand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use Feeds;

use AshAllenDesign\LaravelExchangeRates\Classes\ExchangeRate;

class IntranetController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    private function valueMatch($kosul, $val){
        
        if($kosul == 'icerir'){
            return '%'.$val.'%';
        }elseif($kosul == 'ile_baslar'){
            return $val.'%';
        }elseif($kosul == 'esittir'){
            return $val;
        }elseif($kosul == 'kucuktur'){
            return $val;
        }elseif($kosul == 'buyuktur'){
            return $val;
        }
    }

    public function index(): \Illuminate\Contracts\View\View
    {
        $datas = $this->request->all();
        $user_id = $this->request->user()->id;
        $department_id = $this->request->user()->department_id;

        $announcements = Announcement::whereIn('department_id', [0, $department_id])->where('category_id', '!=', 9)
        ->where('is_active', 1)
        ->where('start_at', '<', date('Y-m-d H:i'))
        ->where('end_at', '>', date('Y-m-d H:i'))
        ->orderBy('created_at', 'desc')
        ->limit(5);

        $slider_array = $announcements->pluck('id')->toArray();
        $sliders = $announcements->get();

        $announcements = Announcement::whereIn('department_id', [0, $department_id])->whereNotIn('id', $slider_array)->where('category_id', '!=', 9)
        ->where('is_active', 1)
        ->where('start_at', '<', date('Y-m-d H:i'))
        ->where('end_at', '>', date('Y-m-d H:i'))
        ->orderBy('created_at', 'desc')
        ->limit(15)
        ->get();

        $haber = Announcement::whereIn('category_id', [10])
        ->where('is_active', 1)
        ->where('start_at', '<', date('Y-m-d H:i'))
        ->where('end_at', '>', date('Y-m-d H:i'))
        ->orderBy('created_at', 'desc')
        ->first();

        $shorts = Announcement::whereIn('category_id', [9])
        ->where('is_active', 1)
        ->where('start_at', '<', date('Y-m-d H:i'))
        ->where('end_at', '>', date('Y-m-d H:i'))
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();

        $holidays = PublicHoliday::where('start_at', '>', date('Y-m-d'))
        ->limit(5)
        ->get();

        //$feed = Feeds::make('https://www.ntv.com.tr/rss', 5, true);
        //$spk = array(
        // 'item'     => $feed->get_item(),
        //);

        $useful = UsefulLink::get();

        $date = now();

        $exchangeRates = new ExchangeRate();
        $eur = $exchangeRates->exchangeRate('EUR', 'TRY');
        $usd = $exchangeRates->exchangeRate('USD', 'TRY');
        $gbp = $exchangeRates->exchangeRate('GBP', 'TRY');
        $rub = $exchangeRates->exchangeRate('RUB', 'TRY');
        $jpy = $exchangeRates->exchangeRate('JPY', 'TRY');

        $ar = array(
            array(
                'sign' => 'EUR',
                'name' => 'Euro',
                'value' => $eur
            ),
            array(
                'sign' => 'USD',
                'name' => 'Amerikan Doları',
                'value' => $usd
            ),
            array(
                'sign' => 'GBP',
                'name' => 'İngiliz Sterlini',
                'value' => $gbp
            ),
            array(
                'sign' => 'JPY',
                'name' => 'Japon Yeni',
                'value' => $jpy
            ),
            array(
                'sign' => 'RUB',
                'name' => 'Rus Rublesi',
                'value' => $rub
            )
        );

        $birthdays = User::whereMonth('birthdate', '>', $date->month)
        ->orWhere(function ($query) use ($date) {
            $query->whereMonth('birthdate', '=', $date->month)
                ->whereDay('birthdate', '>=', $date->day);
        })
        ->orderByRaw("DAYOFMONTH('birthdate')",'ASC')
        ->take(3)
        ->get();

        $docs = DocumentUser::where('user_id', $user_id)->pluck('document_id')->toArray();

        $document = Document::whereIn('id', $docs)
        ->where('status', 'Onaylandı')
        ->with(['docuser' => function($q) use ($user_id){
            $q->where('user_id', $user_id);
        }])
        ->orderBy('created_at', 'desc')
        ->get();

        $form = Form::where('start_at', '<', date('Y-m-d H:i'))
        ->where('end_at', '>', date('Y-m-d H:i'))
        ->orderBy('created_at')
        ->get();

        $degerlendirme = ProjectRating::selectRaw('*, sum(answer)/count(*) as toplam')
        ->groupBy('interested_id')
        ->orderBy('toplam', 'desc')
        ->limit(10)
        ->get();

        $ilanlar = PersonelDemand::with(['user'])->withCount(['candidates' => function($q){
            $q->where('candidates.status', 'olumlu');
        }]);
        
        $ilanlar = $ilanlar->where('status', '!=', 'kapatıldı')->where('status', '!=', 'kaldırıldı')->get();

        return View::make('intranet.index', [
          'sliders' => $sliders,
          'announcements' => $announcements,
          'haber' => $haber,
          'shorts' => $shorts,
          'holidays' => $holidays,
          'birthdays' => $birthdays,
          'rate' => $ar,
          'document' => $document,
          'forms' => $form,
          'useful' => $useful,
          'degerlendirme' => $degerlendirme,
          'ilanlar' => $ilanlar,
          'spk' => ""
        ]);
    }

    public function announceDetail(int $announce_id): \Illuminate\Contracts\View\View
    {
        $datas = $this->request->all();
        $user_id = $this->request->user()->id;
        $department_id = $this->request->user()->department_id;

        $announcements = Announcement::whereIn('department_id', [0, $department_id])->where('id', '!=', $announce_id)
        ->where('start_at', '<', date('Y-m-d H:i'))
        ->where('end_at', '>', date('Y-m-d H:i'))
        ->orderBy('created_at', 'desc')
        ->limit(8)->get();

        $announce = Announcement::find($announce_id);



        return View::make('intranet.announcement-detail', [
          'detail' => $announce,
          'announcements' => $announcements
        ]);
    }
    public function announces(): \Illuminate\Contracts\View\View
    {
        $datas = $this->request->all();
        $user_id = $this->request->user()->id;
        $department_id = $this->request->user()->department_id;

        if(isset($datas['q'])){
            $announcements = Announcement::where('title', 'like', '%'.$datas['q'].'%')->paginate(20);
        }else{
            $announcements = Announcement::paginate(20);
        }

        return View::make('intranet.announcements', [
          'announcements' => $announcements
        ]);
    }
    public function rating($education_id): \Illuminate\Contracts\View\View
    {
        $user_id = $this->request->user()->id;

        $user = EducationUser::where('education_id', $education_id)->where('user_id', $user_id)->first();
        if($user){
            $education = Education::find($education_id);
            $questions = EducationQuestion::all();
            return View::make('hr.education.rating',[
                'questions' => $questions,
                'detail' => $education
            ]);
        }else{
        }
    }
    public function saveRating(Request $request): \Illuminate\Http\JsonResponse
    {
        $user_id = $this->request->user()->id;
        $data = $this->request->all();

        $user = EducationUser::where('education_id', $data['id'])->where('user_id', $user_id)->first();
        if(!$user){
            $result = array(
              'status' => 0,
              'message' => 'Yetkisiz erişim.'
          );
          return response()->json($result);
        }

        foreach($data['rating'] as $p){
            if($p['question']){
                $ref = new EducationRating();
                $ref->education_id = $data['id'];
                $ref->user_id = $user_id;
                $ref->question_id = $p['question'] ?? null;
                $ref->answer = $p['answer'] ?? 1;
                $ref->save();

                $arr[] = $ref->id;
            }
        }
        $result = array(
          'status' => 1,
          'message' => 'Başarıyla kaydedildi. Yönlendiriliyorsunuz.'
      );

      return response()->json($result);
    }
}
