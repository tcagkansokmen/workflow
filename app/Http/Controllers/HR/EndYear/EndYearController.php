<?php declare(strict_types = 1);

namespace App\Http\Controllers\HR\EndYear;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Project;
use App\Models\ProjectUser;
use App\Models\Title;
use App\Models\YearlyRating;
use App\Models\YearlyRatingUser;
use App\Models\YearlyRatingQuestion;
use App\Models\YearlyRatingResult;


use Illuminate\Support\Facades\Validator;
use App\Core\OfferLogs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use DataTables;
use \Carbon\Carbon;

class EndYearController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index(): \Illuminate\Contracts\View\View
    {
        $page_title = "Yıl Sonu Değerlendirmeleri";
        $page_description = "Yıl sonu değerlendirmelerini görüntüleyebilir ve düzenleyebilirsiniz.";
        $data = YearlyRating::withCount(['personels'])->get();

        return View::make('hr.end-year-rating.index', compact('page_title', 'page_description', 'data'));
    }
    public function add(): \Illuminate\Contracts\View\View
    {
        $page_title = "Yeni Değerlendirme Ekle";
        $page_description = "Yeni değerlendirme ekleyebilirsiniz";

        return View::make('hr.end-year-rating.add',  compact('page_title', 'page_description'));
    }

    public function detail(int $education_id): \Illuminate\Contracts\View\View
    {
      $detail = Education::find($education_id);
      $users = EducationUser::where('education_id', $education_id)->get();

      $katilan = EducationUser::where('education_id', $education_id)->where('status', 'katıldı')->count();

      $stars = EducationQuestion::where('type', 'star')->pluck('id')->toArray();

      $stars = EducationRating::where('education_id', $education_id)->whereIn('question_id', $stars)->avg('answer');

      return View::make('hr.education.detail',[
        'detail' => $detail,
        'users' => $users,
        'katilan' => $katilan,
        'stars' => $stars
      ]);
    }
    public function update($rating_id): \Illuminate\Contracts\View\View
    {
        $detail = YearlyRating::find($rating_id);
        $page_title = "Değerlendirmeyi Düzenle";
        $page_description = "Bilgileri güncelleyebilirsiniz.";

        return View::make('hr.end-year-rating.add',  compact('page_title', 'page_description', 'detail'));
    }
    public function save(Request $request): \Illuminate\Http\JsonResponse
    {
        $data = $this->request->all();

        $validator = Validator::make($data, [
            'title' => 'required',
            'year' => 'required|numeric',
            'description' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => error_formatter($validator),
                'errors' => $validator->errors(),
            ]);
        }
        
        $rating = new YearlyRating;
        if(isset($data['id'])){
            $rating = YearlyRating::find($data['id']);
        }
        $rating->title = $data['title'] ?? '';
        $rating->year = $data['year'] ?? date('Y');
        $rating->description = $data['description'] ?? '';
        $rating->save();


        if(!isset($data['id'])){
          $users = User::where('is_active', 1)->get();

          foreach($users as $us){
            $user_id = $us->id;
            $departman = $us->department_id;
            $projects = ProjectUser::where('user_id', $user_id)->pluck('project_id')->toArray();
            $others = ProjectUser::whereIn('project_id', $projects)->where('user_id', '!=', $user_id)->pluck('user_id')->toArray();

            $managers = Project::whereIn('id', $projects)->pluck('manager_id')->toArray();
            $partners = Project::whereIn('id', $projects)->pluck('partner_id')->toArray();

            foreach($others as $ot){
              $ekle = new YearlyRatingUser();
              $ekle->yearly_rating_id = $rating->id;
              $ekle->rater_id = $ot;
              $ekle->user_id = $user_id;
              $ekle->save();
            }

            foreach($managers as $ot){
              $ekle = new YearlyRatingUser();
              $ekle->yearly_rating_id = $rating->id;
              $ekle->rater_id = $ot;
              $ekle->user_id = $user_id;
              $ekle->save();
            }

            foreach($partners as $ot){
              $ekle = new YearlyRatingUser();
              $ekle->yearly_rating_id = $rating->id;
              $ekle->rater_id = $ot;
              $ekle->user_id = $user_id;
              $ekle->save();
            }
          }
        }
        $result = array(
          'status' => 1,
          'redirect' => route('user-list-end-year-rating', ['id' => $rating->id]),
          'message' => 'Başarıyla kaydedildi. Yönlendiriliyorsunuz.'
      );

      return response()->json($result);
    }

    public function users(int $rating_id): \Illuminate\Contracts\View\View
    {
        $detail = YearlyRating::find($rating_id);
        $users = YearlyRatingUser::where('yearly_rating_id', $rating_id)
        ->with(['raters' => function($query) use ($rating_id){
          $query->where('yearly_rating_id', $rating_id);
        }])
        ->withCount(['answers as toplam_puan' => function($query) use ($rating_id){
          $query->select(DB::raw("AVG(answer) as paidsum"))->where('yearly_rating_id', $rating_id);
        }])
        ->groupBy('user_id')->get();

        $page_title = $detail->title." - Liste";
        $page_description = "Değerlendirmeye katılan personel";
        return View::make('hr.end-year-rating.users', compact('page_title', 'page_description', 'detail', 'users'));
    }

    public function ratingList(int $rating_id): \Illuminate\Contracts\View\View
    {
      $user_id = $this->request->user()->id;

      $detail = YearlyRating::find($rating_id);
      $users = YearlyRatingUser::where('yearly_rating_id', $rating_id)
      ->withCount(['answers as toplam_puan' => function($query) use ($rating_id, $user_id){
        $query->select(DB::raw("AVG(answer) as paidsum"))
        ->where('yearly_rating_id', $rating_id)
        ->where('rater_id', $user_id);
      }])
      ->where('rater_id', $user_id)
      ->groupBy('user_id')->get();

      $page_title = $detail->title." - Liste";
      $page_description = "Değerlendireceğiniz personeller";
      return View::make('hr.end-year-rating.rate-list', compact('page_title', 'page_description', 'detail', 'users'));
    }

    public function rateNow($yearly_rating_id, $user_id): \Illuminate\Contracts\View\View
    {
        $rater_id = $this->request->user()->id;
        $user = User::find($user_id);

        $titlebul = Title::where('title', $user->title)->first();

        $questions = YearlyRatingQuestion::where('is_active', 1)->with(['rating' => function($q) use ($user_id, $rater_id, $yearly_rating_id){
          $q->where('yearly_rating_results.yearly_rating_id', $yearly_rating_id);
          $q->where('yearly_rating_results.user_id', $user_id);
          $q->where('yearly_rating_results.rater_id', $rater_id);
        }])->get();

        return View::make('hr.end-year-rating.rate', compact('user', 'questions', 'yearly_rating_id', 'user_id'));
    }


    public function saveRate(Request $request): \Illuminate\Http\JsonResponse
    {
        $rater_id = $this->request->user()->id;
        $data = $this->request->all();
        $user_id = $data['user_id'];
        $yearly_rating_id = $data['yearly_rating_id'];

        if(isset($data['rating'])){
            foreach($data['rating'] as $p){
                if($p['rating']){
                    
                    $bul = YearlyRatingResult::where('yearly_rating_id', $yearly_rating_id)
                    ->where('rater_id', $rater_id)
                    ->where('user_id', $user_id)
                    ->where('yearly_rating_question_id', $p['question_id'])
                    ->first();
                    if($bul){
                        $project_rating = YearlyRatingResult::find($bul['id']);
                    }else{
                        $project_rating = new YearlyRatingResult();
                    }
                        
                    $project_rating->yearly_rating_id = $yearly_rating_id;
                    $project_rating->rater_id = $rater_id;
                    $project_rating->user_id = $user_id;
                    $project_rating->yearly_rating_question_id = $p['question_id'];
                    $project_rating->answer = $p['rating'] ?? 0;
                    $project_rating->description = $p['description'] ?? null;
                    $project_rating->save();
                }
            }
        }

            
        $result = array(
          'status' => 1,
          'redirect' => route('end-year-rating-list', ['id' => $yearly_rating_id]),
          'message' => 'Başarıyla kaydedildi. Yönlendiriliyorsunuz.'
      );

      return response()->json($result);
    }


    public function userRaterList(int $yearly_rating_user_id): \Illuminate\Contracts\View\View
    {
        $detail = YearlyRatingUser::find($yearly_rating_user_id);
        $yearly_rating_id = $detail->yearly_rating_id;
        $user_id = $detail->user_id;

        $raters = YearlyRatingUser::where('user_id', $detail->user_id)->where('yearly_rating_id', $detail->yearly_rating_id)->pluck('rater_id')->toArray();

        $users = User::where('is_active', 1)->get();

        return View::make('hr.end-year-rating.rater-list', compact('detail', 'users', 'raters', 'user_id', 'yearly_rating_id'));
    }

    public function addNewRater(Request $request): \Illuminate\Http\JsonResponse
    {
      $user_id = $this->request->user()->id;
      $data = $this->request->all();

      $yearly_rating_id = $data['yearly_rating_id'];
      $user_id = $data['user_id'];
      $rater_id = $data['rater_id'];

      $ara = YearlyRatingUser::where('yearly_rating_id', $yearly_rating_id)
      ->where('rater_id', $rater_id)->where('user_id', $user_id)->first();
      if(!$ara){
        $ekle = new YearlyRatingUser();
        $ekle->yearly_rating_id = $yearly_rating_id;
        $ekle->rater_id = $rater_id;
        $ekle->user_id = $user_id;
        $ekle->save();
      }
      
      $result = array(
        'status' => 1,
        'message' => 'Başarıyla kaydedildi. Yönlendiriliyorsunuz.'
      );

      return response()->json($result);
    }

    public function removeNewRater(Request $request): \Illuminate\Http\JsonResponse
    {
      $user_id = $this->request->user()->id;
      $data = $this->request->all();

      $yearly_rating_id = $data['yearly_rating_id'];
      $user_id = $data['user_id'];
      $rater_id = $data['rater_id'];

      $ara = YearlyRatingUser::where('yearly_rating_id', $yearly_rating_id)
      ->where('rater_id', $rater_id)->where('user_id', $user_id)->delete();
      
      $result = array(
        'status' => 1,
        'message' => 'Başarıyla kaydedildi. Yönlendiriliyorsunuz.'
      );

      return response()->json($result);
    }
}
