<?php declare(strict_types = 1);

namespace App\Http\Controllers\HR\Personel;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\Interview;
use App\Models\User;
use App\Models\UserGroup;
use App\Models\Title;
use App\Models\Wage;
use App\Models\Cost;
use App\Models\UserTitle;
use App\Models\PersonalFamily;
use App\Models\Need;
use App\Models\Department;
use App\Models\Permission;
use App\Models\PermissionType;
use App\Models\UserDocument;
use App\Models\Earnest;
use App\Models\VisaDemand;
use App\Models\Belonging;
use App\Models\EducationUser;
use App\Models\Certificate;
use App\Models\UserCertificate;
use App\Models\ProjectRating;

use App\Core\OfferLogs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

use DataTables;

class PersonelController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    
    public function allbelongings(): \Illuminate\Contracts\View\View
    {
        $datas = $this->request->all();

        $belongings = Belonging::whereNull('end_at')->get();

        return View::make('hr.employee.all-belongings', [
            'belongings' => $belongings
        ]);
    }
    public function index(): \Illuminate\Contracts\View\View
    {
        $datas = $this->request->all();

        $page_title = "Personel Listesi";
        $page_description = "Şirket çalışanlarını listeleyip detaylarına erişebilirsiniz";

        return View::make('hr.employee.index', compact('page_title', 'page_description'));
    }

    public function json(): \Illuminate\Http\JsonResponse
    {
      $datas = $this->request->all();
      $parameters = $this->request->query();
      $user_id = $this->request->user()->id;
      $user = $this->request->user();
      $demand_allowed = $user->power('employee', 'demand') ? true : false;
      $edit_allowed = $user->power('employee', 'edit') ? true : false;
      $delete_allowed = $user->power('employee', 'delete') ? true : false;
      $detail_allowed = $user->power('employee', 'detail') ? true : false;

        $data = User::with(['candidate', 'department']);
        
        if(isset($datas['status'])){
            $data = $data->where('status', $datas['status']);
        }

        return Datatables::of($data)
        ->addColumn('date_formatted', function($data){
          return Carbon::parse($data->check_in_date)->formatLocalized('%d %B %Y');
        })
        ->addColumn('edit_allowed', function($d) use ($edit_allowed){
          return $edit_allowed;
        })->make(true);
    }
    public function add(): \Illuminate\Contracts\View\View
    {
      $group = UserGroup::all();
      $title = Title::all();
      $departments = Department::all();
      $page_title = "Yeni Personel";
      $page_description = "Yeni personel ekleyebilirsiniz.";

        return View::make('hr.employee.add',[
          'group' => $group,
          'title' => $title,
          'departments' => $departments,
          'page_title' => $page_title,
          'page_description' => $page_description
        ]);
    }
    public function addCandidate(int $candidate_id): \Illuminate\Contracts\View\View
    {
      $group = UserGroup::all();
      $titles = Title::all();
      $departments = Department::all();
      $candidate = Candidate::find($candidate_id);
      return View::make('hr.employee.add', [
        'candidate' => $candidate,
        'group' => $group,
        'titles' => $titles,
        'departments' => $departments
        ]);
    }

    public function detail(int $user_id): \Illuminate\Contracts\View\View
    {
      $detail = User::find($user_id);
      $personel_family = PersonalFamily::where('user_id', $user_id)->get();
      $user_documents = UserDocument::where('user_id', $user_id)->get();
      
      return View::make('hr.employee.components.personel',[
        'detail' => $detail,
        'personel_family' => $personel_family,
        'user_documents' => $user_documents
      ]);
    }

    public function personal(int $user_id): \Illuminate\Contracts\View\View
    {
      $detail = User::find($user_id);
      $personel_family = PersonalFamily::where('user_id', $user_id)->get();
      $user_documents = UserDocument::where('user_id', $user_id)->get();
      
      return View::make('hr.employee.components.personel',[
        'detail' => $detail,
        'personel_family' => $personel_family,
        'user_documents' => $user_documents
      ]);
    }

    public function personelAddSave(Request $request): \Illuminate\Http\JsonResponse
    {
        $data = $this->request->all();
        $validator = Validator::make($data, [
            'title' => 'required',
            'department_id' => 'required',
            'group_id' => 'required',
            'name' => 'required',
            'surname' => 'required',
            'tc_no' => 'required',
            'birthdate' => 'required',
            'gender' => 'required',
            'phone' => 'required',
            'type' => 'required',
            'email' => 'required|email|unique:users'
        ]);
  
        if ($validator->fails()) {
            return response()->json([
                'message' => error_formatter($validator),
                'errors' => $validator->errors(),
            ]);
        }

        $titles = Title::find($data['title']);

        $groupbul = UserGroup::where('name', $titles->power)->first();
        

        $data = $this->request->all();
        $file = $request->file('kt_user_add_user_avatar');

        if(isset($data['kt_user_add_user_avatar'])){
          $filename = uniqid().".".$file->getClientOriginalExtension();
          $destinationPath = '/uploads/users';
           Storage::disk('s3')->putFileAs($destinationPath, $file, $filename, ['visibility' => 'public']);
        }

        $user = new User();
        if(isset($data['kt_user_add_user_avatar'])){
          $user->avatar = $filename;
        }
        $user->name = $data['name'] ?? '';
        $user->check_in_date = date("Y-m-d", strtotime($data['check_in_date'])) ?? NULL;
        $user->surname = $data['surname'] ?? '';
        $user->title = $titles->title ?? '';
        $user->group_id = $groupbul['id'] ?? '';
        $user->department_id = $data['department_id'] ?? '';
        $user->tc_no = $data['tc_no'] ?? '';
        $user->birthdate = $data['birthdate'] ? date('Y-m-d', strtotime($data['birthdate'])) : '';
        $user->marital_status = $data['marital_status'] ?? '';
        $user->birth_place = $data['birth_place'] ?? '';
        $user->gender = $data['gender'] ?? '';
        $user->is_disabled = $data['is_disabled'] ?? NULL;
        $user->phone = $data['phone'] ?? '';
        $user->email = $data['email'] ?? '';
        $user->address = $data['address'] ?? '';
        $user->contract_type = $data['type'] ?? '';
        $user->mobil_operator = $data['mobil_operator'] ?? '';
        $user->is_active = 1;
        $user->save();

        $user_title = new UserTitle();
        $user_title->user_id = $user->id;
        $user_title->title_id = $data['title'];
        $user_title->start_at = date("Y-m-d", strtotime($data['check_in_date'])) ?? NULL;
        $user_title->is_active = 1;
        $user_title->save();

        $result = array(
          'status' => 1,
          'redirect' => route('employee-information', ['id' => $user->id]),
          'message' => 'Başarıyla kaydedildi. Yönlendiriliyorsunuz.'
      );

      return response()->json($result);
    }

    public function removeEmployee($user_id): \Illuminate\Http\JsonResponse
    {

      $zimmetbul = Belonging::where('user_id', $user_id)->whereNull('end_at')->get();
      if(count($zimmetbul)){
        $result = array(
          'status' => 0,
          'message' => 'Personele atanmış zimmetler bulunmaktadır. Personelin çıkış işlemlerini tamamlamak için öncelikle zimmetleri teslim almalısınız.'
      );

      return response()->json($result);
      }

      $usersil = User::where('id', $user_id)->update(array(
        'is_active' => 0,
        'check_out_date' => date('Y-m-d')
      ));
      $result = array(
        'status' => 1,
        'message' => 'Başarıyla kaydedildi. Yönlendiriliyorsunuz.'
    );

    return response()->json($result);
    }

    public function activateEmployee($user_id): \Illuminate\Http\JsonResponse
    {

      $usersil = User::where('id', $user_id)->update(array(
        'is_active' => 1,
      ));
      return response()->json($result);
    }
    
    public function personalSave(Request $request, int $user_id): \Illuminate\Http\JsonResponse
    {
        $data = $this->request->all();
        $validator = Validator::make($data, [
            'name' => 'required',
            'surname' => 'required',
            'tc_no' => 'required',
            'birthdate' => 'required',
            'gender' => 'required',
            'phone' => 'required'
        ]);
  
        if ($validator->fails()) {
            return response()->json([
                'message' => error_formatter($validator),
                'errors' => $validator->errors(),
            ]);
        }
        

        $data = $this->request->all();
        $file = $request->file('kt_user_add_user_avatar');

        if(isset($data['kt_user_add_user_avatar'])){
          $filename = uniqid().".".$file->getClientOriginalExtension();
          $destinationPath = '/uploads/users';
           Storage::disk('s3')->putFileAs($destinationPath, $file, $filename, ['visibility' => 'public']);
        }

        $user = User::find($user_id);
        if(isset($data['kt_user_add_user_avatar'])){
          $user->avatar = $filename;
        }
        $user->name = $data['name'] ?? '';
        $user->surname = $data['surname'] ?? '';
        $user->tc_no = $data['tc_no'] ?? '';
        $user->birthdate = $data['birthdate'] ? date('Y-m-d', strtotime($data['birthdate'])) : '';
        $user->marital_status = $data['marital_status'] ?? '';
        $user->birth_place = $data['birth_place'] ?? '';
        $user->gender = $data['gender'] ?? '';
        $user->is_disabled = $data['is_disabled'] ?? NULL;
        $user->phone = $data['phone'] ?? '';
        $user->address = $data['address'] ?? '';
        $user->save();

        $result = array(
          'status' => 1,
          'redirect' => route('employee-information', ['id' => $user_id]),
          'message' => 'Başarıyla kaydedildi. Yönlendiriliyorsunuz.'
      );

      return response()->json($result);
    }
    public function familySave(Request $request, int $user_id): \Illuminate\Http\JsonResponse
    {
        $data = $this->request->all();
        $arr = array();

        foreach($data['family'] as $p){
            if($p['birthdate']){
                if(isset($p['id'])){
                    $family = PersonalFamily::find($p['id']);
                }else{
                    $family = new PersonalFamily();
                }
                $family->user_id = $user_id;
                $family->name = $p['name'];
                $family->birthdate = $p['birthdate'] ? date_formatter($p['birthdate']) : '';
                $family->is_education = $p['is_education'] ?? NULL;
                $family->save();

                $arr[] = $family->id;
            }
        }

        $sil = PersonalFamily::whereNotIn('id', $arr)->where('user_id', $user_id)->delete();

        $result = array(
            'status' => 1,
            'redirect' => route('employee-information', ['id' => $user_id]),
            'message' => 'Başarıyla kaydedildi. Yönlendiriliyorsunuz.'
        );
        
      return response()->json($result);
    }

    public function filesSave(Request $request, int $user_id): \Illuminate\Http\JsonResponse
    {
        $data = $this->request->all();
        $arr = array();
        foreach($data['file'] as $p){
            if($p['title']){
                if(isset($p['id'])){
                    $document = UserDocument::find($p['id']);
                }else{
                    $document = new UserDocument();
                }
                $document->title = $p['title'] ?? '';
                $document->description = $p['description'] ?? '';
                $document->user_id = $user_id;
                $document->file = isset($p['file_input']) ? $p['file_input'] : $document->file;
                $document->save();
    
                $arr[] = $document->id;
            }
        }

        $sil = UserDocument::whereNotIn('id', $arr)->where('user_id', $user_id)->delete();

    
        $result = array(
            'status' => 1,
            'redirect' => route('employee-information', ['id' => $user_id]),
            'message' => 'Başarıyla kaydedildi. Yönlendiriliyorsunuz.'
        );
        
      return response()->json($result);
    }


    public function account(int $user_id): \Illuminate\Contracts\View\View
    {
      $detail = User::find($user_id);
      $departments = Department::get();
      $titles = Title::get();
      $groups = UserGroup::get();
      

      $page_title = "Hesap Bilgileri";
      $page_description = "Personelin e-posta, unvan ve hesap bilgileri";

      return View::make('hr.employee.components.account',[
        'detail' => $detail,
        'departments' => $departments,
        'titles' => $titles,
        'user_groups' => $groups,
        'page_title' => $page_title,
        'page_description' => $page_description
      ]);
    }
    public function accountSave(Request $request, int $user_id): \Illuminate\Http\JsonResponse
    {
        $data = $this->request->all();
        $validator = Validator::make($data, [
            'username' => 'required',
            'email' => 'required|email'
        ]);
  
        if ($validator->fails()) {
            return response()->json([
                'message' => error_formatter($validator),
                'errors' => $validator->errors(),
            ]);
        }

        $user = User::find($user_id);
        $user->username = $data['username'] ?? '';
        $user->email = $data['email'] ?? '';

        if(isset($data['password'])){
            $user->password = Hash::make($data['password']);
        }
        $user->is_active = $data['is_active'] ?? 1;
        $user->save();

        $result = array(
          'status' => 1,
          'redirect' => route('employee-information', ['id' => $user_id]),
          'message' => 'Başarıyla kaydedildi. Yönlendiriliyorsunuz.'
      );

      return response()->json($result);
    }
    public function education(int $user_id): \Illuminate\Contracts\View\View
    {
      $detail = User::find($user_id);
      $education = EducationUser::where('user_id', $user_id)->get();
      $certificates = UserCertificate::where('user_id', $user_id)->with('certificate')->get();
      
      $page_title = "Eğitim ve Sertifikalar";
      $page_description = "Personele ait eğitim ve sertifikaları listeleyip düzenleyebilirsiniz.";

      return View::make('hr.employee.components.education',[
        'detail' => $detail,
        'education' => $education,
        'certificates' => $certificates,
        'page_title' => $page_title,
        'page_description' => $page_description
      ]);
    }
    public function addCertificate(int $user_id): \Illuminate\Contracts\View\View
    {
      $certificates = Certificate::select('id as value', 'name')->get();
      
      return View::make('hr.employee.components.add-certificate',[
        'certificates' => $certificates,
        'user_id' => $user_id
      ]);
    }
    public function deleteCertificate(int $id)
    {
      $certificates = UserCertificate::find($id);
      $certificates->delete();

      $result = array(
        'status' => 1,
        'message' => 'Başarıyla silindi. Yönlendiriliyorsunuz.'
    );

    return response()->json($result);
    }
    public function certificateSave(): \Illuminate\Http\JsonResponse
    {
        $data = $this->request->all();

        $certificate = new UserCertificate();
        $certificate->certificate_id = $data['certificate_id'];
        $certificate->user_id = $data['id'];
        $certificate->start_at = date("Y-m-d", strtotime($data['start_at'])) ?? NULL;
        $certificate->end_at = date("Y-m-d", strtotime($data['end_at'])) ?? NULL;
        $certificate->save();

        $result = array(
          'status' => 1,
          'redirect' => route('employee-information', ['id' => $data['id']]),
          'message' => 'Başarıyla kaydedildi. Yönlendiriliyorsunuz.'
      );

      return response()->json($result);
    }
    public function finance(int $user_id): \Illuminate\Contracts\View\View
    {
      $detail = User::find($user_id);

      $wages = Wage::where('user_id', $user_id)->get()->keyBy('month');
      $costs = Cost::where('user_id', $user_id)
      ->orderBy('doc_date', 'desc')
      ->limit(100)->get();
      
      $page_title = "Personel Finansal Bilgiler";
      $page_description = "Personelin finansal bilgileri";

      return View::make('hr.employee.components.finance',[
        'detail' => $detail,
        'wages' => $wages,
        'costs' => $costs,
        'page_title' => $page_title,
        'page_description' => $page_description
      ]);
    }

    public function rating(int $user_id): \Illuminate\Contracts\View\View
    {
      $detail = User::find($user_id);

      
        $date = new \Carbon\Carbon();
        $last = new \Carbon\Carbon('-3 months');

        $firstOfQuarter = $last->lastOfQuarter();
        $lastOfQuarter = $date->lastOfQuarter();
        $quarter = $date->quarter;

        $ratings = ProjectRating::selectRaw('*, sum(answer)/count(*) as puan')
        ->where('created_at', '>', $firstOfQuarter)
        ->where('created_at', '<=', $lastOfQuarter)
        ->where('interested_id', $user_id)
        ->groupBy('project_id')->get();

        $avg = ProjectRating::where('created_at', '>', $firstOfQuarter)
        ->where('created_at', '<=', $lastOfQuarter)
        ->where('interested_id', $user_id)
        ->groupBy('project_id')->avg('answer');

      $page_title = "Proje Değerlendirme";
      $page_description = "Personelin proje değerlendirme sonuçları";

      return View::make('hr.employee.components.rating',[
        'detail' => $detail,
        'ratings' => $ratings,
        'avg' => $avg,
        'quarter' => $quarter.". Çeyrek",
        'page_title' => $page_title,
        'page_description' => $page_description
      ]);
    }

    public function wageSave(Request $request): \Illuminate\Http\JsonResponse
    {
        $data = $this->request->all();
        
        $wage_find = Wage::where('user_id', $data['id'])->where('month', $data['month'])->where('year', $data['year'])->first();

        $wage = new Wage();
        if($wage_find){
            $wage = Wage::find($wage_find->id);
        }
        $wage->wage = str_replace(",", ".", str_replace(".", "", $data['val']));
        $wage->user_id = $data['id'];
        $wage->month = $data['month'];
        $wage->year = $data['year'];
        $wage->save();
        
        $result = array(
          'status' => 1,
          'message' => 'Başarıyla kaydedildi. Yönlendiriliyorsunuz.'
      );

      return response()->json($result);
    }
    public function demand(int $user_id): \Illuminate\Contracts\View\View
    {
      $detail = User::find($user_id);
      $permissions = Permission::where('user_id', $user_id)
      ->orderBy('start_at')->get();
      $earnests = Earnest::where('user_id', $user_id)->get();
      $visas = VisaDemand::where('user_id', $user_id)->get();

      $total_count = Permission::where('user_id', $user_id)
      ->where('status', 'Onaylandı')
      ->where('start_at', '>', date('Y')."-01-01")
      ->where('start_at', '<', date('Y', strtotime('+1 year'))."-01-01")
      ->where('type', 1)
      ->sum('days');

      $types = PermissionType::where('id', '!=', 1)->withCount([
        'used as paid_sum' => function ($query) use ($user_id) {
                    $query->select(DB::raw("SUM(days) as paidsum"))->where('user_id', $user_id);
                }
            ])
      ->get();
      
      $page_title = "Personel Talepleri";
      $page_description = "Personelin talepleri";

      return View::make('hr.employee.components.demands',[
        'detail' => $detail,
        'permissions' => $permissions,
        'earnests' => $earnests,
        'visas' => $visas,
        'total_count' => $total_count,
        'types' => $types,
        'page_title' => $page_title,
        'page_description' => $page_description
      ]);
    }
    public function belonging(int $user_id): \Illuminate\Contracts\View\View
    {
      $detail = User::find($user_id);
      $belongings = Belonging::where('user_id', $user_id)->get();

      $page_title = "Zimmetler";
      $page_description = "Personelin zimmet bilgileri";
      
      return View::make('hr.employee.components.belonging',[
        'detail' => $detail,
        'belongings' => $belongings,
        'page_title' => $page_title,
        'page_description' => $page_description
      ]);
    }

    public function editBelonging(int $belonging_id): \Illuminate\Contracts\View\View
    {
      $belonging = Belonging::find($belonging_id);
      $detail = User::find($belonging->user_id);
      
      return View::make('hr.employee.components.add-belonging',[
        'detail' => $detail,
        'belonging' => $belonging
      ]);
    }

    public function addBelonging(int $user_id): \Illuminate\Contracts\View\View
    {
      $detail = User::find($user_id);
      $belongings = Belonging::where('user_id', $user_id)->get();
      
      return View::make('hr.employee.components.add-belonging',[
        'detail' => $detail
      ]);
    }

    public function update(int $user_id): \Illuminate\Contracts\View\View
    {
      $detail = User::find($user_id);
      $group = UserGroup::all();
      $titles = Title::all();
      $departments = Department::all();

      $page_title = "Yeni Personel";
      $page_description = "Yeni personel ekleyebilirsiniz.";

        return View::make('hr.employee.add',[
          'detail' => $detail,
          'group' => $group,
          'titles' => $titles,
          'departments' => $departments,
          'page_title' => $page_title,
          'page_description' => $page_description
        ]);
    }

    public function save(Request $request): \Illuminate\Http\JsonResponse
    {
      
        $user_id = $this->request->user()->id;
        
        $user = new User;
        if(isset($data['id'])){
          $user = User::find($data['id']);
        }
        $data = $this->request->all();

        $user->candidate_id = $data['candidate_id'] ?? NULL;
        $user->name = $data['name'] ?? NULL;
        $user->email = $data['email'] ?? NULL;
        $user->title = $data['title'] ?? NULL;
        $user->contract_type = $data['contract_type'] ?? NULL;
        $user->tc_no = $data['tc_no'] ?? NULL;
        $user->birthdate = date("Y-m-d", strtotime($data['birthdate'])) ?? NULL;
        $user->gender = $data['gender'] ?? NULL;
        $user->marital_status = $data['marital_status'] ?? NULL;
        $user->avatar = $data['avatar'] ?? NULL;
        $user->group_id = $data['group_id'] ?? 3;
        $user->department_id = $data['department_id'] ?? NULL;
        $user->check_in_date = date("Y-m-d", strtotime($data['check_in_date'])) ?? NULL;
        if(isset($data['trial_ends'])){
          $user->trial_ends = date("Y-m-d", strtotime($data['trial_ends'])) ?? NULL;
        }
        if(isset($data['second_period_ends'])){
          $user->second_period_ends = date("Y-m-d", strtotime($data['second_period_ends'])) ?? NULL;
        }
        $user->is_orientation = $data['is_orientation'] ?? NULL;
        $user->save();

        if(!isset($data['id'])){
          $user_title = new UserTitle();
          $user_title->user_id = $user->id;
          if(isset($data['title'])){
            $findtitle = Title::where('title', $data['title'])->first();
            $user_title->title_id = $data['title'] ?? null;
          }else{
            $user_title->title_id = $data['title_id'] ?? null;
          }
          $user_title->start_at = date("Y-m-d", strtotime($data['check_in_date'])) ?? NULL;
          $user_title->is_active = 1;
          $user_title->save();
        }

        $arr = array();
        if(isset($data['file'])){
          foreach($data['file'] as $p){
              if($p['title']){
                  if(isset($p['id'])){
                      $user_document = UserDocument::find($p['id']);
                  }else{
                      $user_document = new UserDocument();
                  }
                  $user_document->user_id = $user->id;
                  $user_document->title = $p['title'];
                  $user_document->description = $p['description'];
                  $user_document->file = isset($p['file_input']) ? $p['file_input'] : $user_document->file;
                  $user_document->save();

                  $arr[] = $user_document->id;
              }
          }
        }
        $sil = UserDocument::whereNotIn('id', $arr)->where('user_id', $user->id)->delete();

        $result = array(
          'status' => 1,
          'redirect' => route('employees'),
          'message' => 'Başarıyla kaydedildi. Yönlendiriliyorsunuz.'
      );

      return response()->json($result);
    }

    public function updatePermission(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->request->validate([
            'kt_user_add_user_avatar' => 'nullable'
        ]);

        $user_id = $this->request->user()->id;
        $data = $this->request->all();

        $candidate = Permission::find($data['id']);
        $candidate->status = $data['value'];
        $candidate->save();

        $result = array(
          'status' => 1,
          'message' => 'Başarıyla kaydedildi. Yönlendiriliyorsunuz.'
      );

      return response()->json($result);
    }

    public function updateEarnest(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->request->validate([
            'kt_user_add_user_avatar' => 'nullable'
        ]);

        $user_id = $this->request->user()->id;
        $data = $this->request->all();

        $candidate = Earnest::find($data['id']);
        $candidate->status = $data['value'];
        $candidate->save();

        $result = array(
          'status' => 1,
          'message' => 'Başarıyla kaydedildi. Yönlendiriliyorsunuz.'
      );

      return response()->json($result);
    }
    public function updateEducation(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->request->validate([
            'kt_user_add_user_avatar' => 'nullable'
        ]);

        $user_id = $this->request->user()->id;
        $data = $this->request->all();

        $candidate = EducationUser::find($data['id']);
        $candidate->status = $data['value'];
        $candidate->save();

        $result = array(
          'status' => 1,
          'message' => 'Başarıyla kaydedildi. Yönlendiriliyorsunuz.'
      );

      return response()->json($result);
    }

    public function updateVisa(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->request->validate([
            'kt_user_add_user_avatar' => 'nullable'
        ]);

        $user_id = $this->request->user()->id;
        $data = $this->request->all();

        $candidate = VisaDemand::find($data['id']);
        $candidate->status = $data['value'];
        $candidate->save();

        $result = array(
          'status' => 1,
          'message' => 'Başarıyla kaydedildi. Yönlendiriliyorsunuz.'
      );

      return response()->json($result);
    }
    public function updateNeed(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->request->validate([
            'kt_user_add_user_avatar' => 'nullable'
        ]);

        $user_id = $this->request->user()->id;
        $data = $this->request->all();

        $candidate = Need::find($data['id']);
        $candidate->status = $data['value'];
        $candidate->save();

        $result = array(
          'status' => 1,
          'message' => 'Başarıyla kaydedildi. Yönlendiriliyorsunuz.'
      );

      return response()->json($result);
    }

    public function updateBelonging(Request $request): \Illuminate\Http\JsonResponse
    {
        $user_id = $this->request->user()->id;
        $data = $this->request->all();
        
        $belonging = new Belonging;
        if(isset($data['id'])){
          $belonging = Belonging::find($data['id']);
        }

        $baslangic = date('Y-m-d', strtotime($data['start_at']));

        $belonging->user_id = $data['personel_id'];
        $belonging->category = $data['category'] ?? null;
        $belonging->name = $data['name'] ?? null;
        $belonging->serial_no = $data['serial_no'] ?? null;
        $belonging->description = $data['description'] ?? null;
        $belonging->start_at = $baslangic;
        if(isset($data['end_at'])){
            $bitis = date('Y-m-d', strtotime($data['end_at']));
            $belonging->end_at = $bitis;
        }
        $belonging->save();
        
        $result = array(
          'status' => 1,
          'redirect' => route('employee-belonging', ['id' => $data['personel_id']]),
          'message' => 'Başarıyla kaydedildi. Yönlendiriliyorsunuz.'
      );

      return response()->json($result);
    }
    function delete(int $id){
        $find = Candidate::find($id);
        $find->delete();


        return redirect()->back()->with('alert', 'Başarıyla silindi.');
    }
    public function upload(Request $request): \Illuminate\Http\JsonResponse
    {

        $user_id = $this->request->user()->id;
        $data = $this->request->all();
        $file = $request->file();
        $file = $file[0];
        
        /*if(isset($file)){
          $destinationPath = 'uploads/contract';
          $filename = uniqid().".".$file->getClientOriginalExtension();
          $file->move($destinationPath,$filename);
        }*/

        if(isset($file)){
          $filename = uniqid().".".$file->getClientOriginalExtension();
         $filePath = '/uploads/users';
         Storage::disk('s3')->putFileAs($filePath, $file, $filename, ['visibility' => 'public']);
        }



      return response()->json(array('file' => $filename));
    }
}
