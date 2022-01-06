<?php declare(strict_types = 1);

namespace App\Http\Controllers\HR\Education;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Education;
use App\Models\EducationCategory;
use App\Models\EducationUser;
use App\Models\EducationQuestion;
use App\Models\EducationRating;

use App\Models\EducationDepartment;
use App\Models\Department;
use Illuminate\Support\Facades\Validator;
use App\Core\OfferLogs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use DataTables;
use \Carbon\Carbon;

class EducationController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index(): \Illuminate\Contracts\View\View
    {
        $page_title = "Eğitimler";
        $page_description = "Tüm eğitimleri görüntüleyebilir ve yeni eğitimler ekleyebilirsiniz";
        return View::make('hr.education.index', compact('page_title', 'page_description'));
    }

    public function json()
    {
        $d = $this->request->all();
        $parameters = $this->request->query();
        $user_id = $this->request->user()->id;
        $user = $this->request->user();
        $send_confirmation_allowed = $user->power('education', 'send_confirmation') ? true : false;
        $confirmation_allowed = $user->power('education', 'confirmation') ? true : false;
        $edit_allowed = $user->power('education', 'edit') ? true : false;
        $delete_allowed = $user->power('education', 'delete') ? true : false;
        $detail_allowed = $user->power('education', 'detail') ? true : false;

        $data = Education::with(['users', 'category']);
        
        return Datatables::of($data)
        ->addColumn('start_at_formatted', function($data){
            return Carbon::parse($data->start_at)->formatLocalized('%d %B %Y');
        })
        ->addColumn('end_at_formatted', function($data){
            return $data->end_at ? Carbon::parse($data->end_at)->formatLocalized('%d %B %Y') : '';
        })
        ->addColumn('send_confirmation_allowed', function() use ($send_confirmation_allowed){
            return $send_confirmation_allowed;
        })->addColumn('confirmation_allowed', function() use ($confirmation_allowed){
            return $confirmation_allowed;
        })->addColumn('edit_allowed', function() use ($edit_allowed){
            return $edit_allowed;
        })->addColumn('delete_allowed', function() use ($delete_allowed){
            return $delete_allowed;
        })->addColumn('detail_allowed', function() use ($detail_allowed){
            return $detail_allowed;
        })->make(true);
    }

    public function autocomplete(): \Illuminate\Http\JsonResponse
    {
        $d = $this->request->all();
        $query = $d['query'];
        
        $data = Education::where(function($q) use ($query){
          $q->where('address', 'like', $query.'%');
        })->pluck('address')->toArray();

        return response()->json($data);
    }


    public function add(): \Illuminate\Contracts\View\View
    {
        $user = User::get();
        $categories = EducationCategory::get();
        $departments = Department::get();

        $page_title = "Yeni Eğitim Ekle";
        $page_description = "Yeni eğitim ekleyebilirsiniz";

        return View::make('hr.education.add',[
            'users' => $user,
            'categories' => $categories,
            'departments' => $departments,
            'page_title' => $page_title,
            'page_description' => $page_description
        ]);
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
    public function total(int $education_id): \Illuminate\Contracts\View\View
    {
      $detail = Education::find($education_id);
      $users = EducationUser::where('education_id', $education_id)->get();
      
      $questions = EducationQuestion::where('type', 'star')->withCount([
        'answers as paid_sum' => function ($query) use ($education_id) {
                    $query->select(DB::raw("AVG(answer) as paidsum"))->where('education_id', $education_id);
                }
      ])->get();

      $yesno = EducationQuestion::where('type', 'yesno')->withCount([
        'answers as yes' => function ($query) use ($education_id) {
                    $query->select(DB::raw("count(answer) as paidsum"))->where('education_id', $education_id)->where('answer', '1');
                },
        'answers as no' => function ($query) use ($education_id) {
                    $query->select(DB::raw("count(answer) as paidsum"))->where('education_id', $education_id)->where('answer', '0');
                },
        'answers as partly' => function ($query) use ($education_id) {
                    $query->select(DB::raw("count(answer) as paidsum"))->where('education_id', $education_id)->where('answer', '2');
                }
      ])->get();

      return View::make('hr.education.total',[
        'detail' => $detail,
        'yesno' => $yesno,
        'questions' => $questions
      ]);
    }
    public function update(int $education_id): \Illuminate\Contracts\View\View
    {
      $detail = Education::find($education_id);
      $user = User::get();
      $categories = EducationCategory::get();
      $selected_departments = EducationDepartment::where('education_id', $education_id)->get()->pluck('department_id');
      $selected_users = EducationUser::where('education_id', $education_id)->get()->pluck('user_id');
      $departments = Department::get();
      
      $page_title = "Eğitim Düzenle";
      $page_description = "Eğitime ait detayları düzenleyebilirsiniz.";

      return View::make('hr.education.add',[
        'detail' => $detail,
        'users' => $user,
        'departments' => $departments,
        'categories' => $categories,
        'selected_departments' => $selected_departments,
        'selected_users' => $selected_users,
        'page_title' => $page_title,
        'page_description' => $page_description
      ]);
    }
    public function updateState(Request $request): \Illuminate\Http\JsonResponse
    {
        $user_id = $this->request->user()->id;
        $data = $this->request->all();

        $document = EducationUser::find($data['id']);
        $document->status = $data['value'];
        $document->save();


        $result = array(
          'status' => 1,
          'message' => 'Başarıyla kaydedildi. Yönlendiriliyorsunuz.'
      );

      return response()->json($result);
    }

    public function save(Request $request): \Illuminate\Http\JsonResponse
    {
        $user_id = $this->request->user()->id;
        $data = $this->request->all();

        $validator = Validator::make($data, [
            'category_id' => 'required',
            'type' => 'required',
            'address' => 'required',
            'foundation' => 'required',
            'description' => 'required',
            'start_at' => 'required',
            'end_at' => 'required',
        ]);
            
        if(!isset($_POST['department_id'])&&!isset($_POST['user_id'])){
            return response()->json([
                'status' => 0,
                'message' => 'Kişi veya departman seçmelisiniz!'
            ]);
        }
        if ($validator->fails()) {
            return response()->json([
                'message' => error_formatter($validator),
                'errors' => $validator->errors(),
            ]);
        }

        $education = new Education;
        if(isset($data['id'])){
            $education = Education::find($data['id']);
        }

        $baslangic = isset($data['start_at']) ? date('Y-m-d H:i', strtotime($data['start_at'])) : date('Y-m-d H:i');
        $bitis = isset($data['end_at']) ? date('Y-m-d H:i', strtotime($data['end_at'])) : date('Y-m-d H:i');

        $education->name = $data['name'] ?? '';
        $education->point = $data['point'] ?? 0;
        $education->description = $data['description'] ?? '';
        $education->type = $data['type'] ?? '';
        $education->end_at = $bitis ?? '';
        $education->category_id = $data['category_id'] ?? 1;
        $education->address = $data['address'] ?? '';
        $education->foundation = $data['foundation'] ?? '';

        $file = $request->file('photo');
        if(isset($data['photo'])){
            $filename = uniqid().".".$file->getClientOriginalExtension();
            $filePath = 'uploads/education';
            Storage::disk('s3')->putFileAs($filePath, $file, $filename, ['visibility' => 'public']);
            $education->photo = $filename ?? $education->filename;
        }

        $file = $request->file('file');
        if(isset($data['file'])){
            $filename = uniqid().".".$file->getClientOriginalExtension();
            $filePath = 'uploads/education';
            Storage::disk('s3')->putFileAs($filePath, $file, $filename, ['visibility' => 'public']);
            $education->file = $filename ?? $education->filename;
        }


        $education->start_at = $baslangic ?? '';
        $education->end_at = $bitis ?? '';
        $education->save();


        $arr = array();
        if(isset($_POST['department_id'])){
            foreach($_POST['department_id'] as $dp){
            $ed_bul = EducationDepartment::where('department_id', $dp)->where('education_id', $education->id)->first();
            $deps = new EducationDepartment();
            if($ed_bul){
                $deps = EducationDepartment::find($ed_bul->id);
            }
            $deps->education_id = $education->id;
            $deps->department_id = $dp;
            $deps->save();
            $arr[] = $deps->id;
            }

        $sil = EducationDepartment::whereNotIn('id', $arr)->where('education_id', $education->id)->delete();


        $users = User::whereIn('department_id', $_POST['department_id'])->get();
        $arr = array();
        foreach($users as $usr){
            $edbul = EducationUser::where('user_id', $usr['id'])->where('education_id', $education->id)->first();
            $eds = new EducationUser();
            if($edbul){
              $eds = EducationUser::find($edbul->id);
            }
            $eds->education_id = $education->id;
            $eds->user_id = $usr['id'];
            $eds->status = "bekleniyor";
            $eds->save();
            $arr[] = $eds->id;
        }
    }
    if(isset($_POST['user_id'])){
        foreach($_POST['user_id'] as $usr){
            $edbul = EducationUser::where('user_id', $usr)->where('education_id', $education->id)->first();
            $eds = new EducationUser();
            if($edbul){
              $eds = EducationUser::find($edbul->id);
            }
            $eds->education_id = $education->id;
            $eds->user_id = $usr;
            $eds->status = "bekleniyor";
            $eds->save();
            $arr[] = $eds->id;
        }
        $sil = EducationUser::whereNotIn('id', $arr)->where('education_id', $education->id)->delete();
    }
        $result = array(
          'status' => 1,
          'redirect' => route('egitimler'),
          'message' => 'Başarıyla kaydedildi. Yönlendiriliyorsunuz.'
      );

      return response()->json($result);
    }


    function delete(int $id){
        $find = Education::find($id);
        $find->delete();


        return redirect()->back()->with('alert', 'Başarıyla silindi.');
    }
    public function ratingDetail($user_id, $education_id): \Illuminate\Contracts\View\View
    {
        $user_id = $this->request->user()->id;

        $user = EducationUser::where('education_id', $education_id)->where('user_id', $user_id)->first();
        $getuser = User::find($user_id);
        if($user){
            $education = Education::find($education_id);
            $questions = EducationQuestion::leftJoin('education_ratings', function($q) use ($user_id, $education_id){
                $q->on('education_questions.id', '=', 'education_ratings.question_id');
                $q->where('education_ratings.education_id', $education_id);
                $q->where('education_ratings.user_id', $user_id);
            })
            ->select('*', 'education_questions.id as nid')
            ->get();
            return View::make('hr.education.result',[
                'questions' => $questions,
                'detail' => $education,
                'user' => $getuser
            ]);
        }else{
        }
    }
}
