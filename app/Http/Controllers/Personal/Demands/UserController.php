<?php declare(strict_types = 1);

namespace App\Http\Controllers\Personal\Demands;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Balance;

use App\Models\Candidate;
use App\Models\Interview;
use App\Models\UserGroup;
use App\Models\Title;
use App\Models\Wage;
use App\Models\Cost;
use App\Models\UserTitle;
use App\Models\PersonalFamily;
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

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\Hash;

use DB;

class UserController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function profile(): \Illuminate\Contracts\View\View
    {
        return View::make('user/profile');
    }
    public function password(): \Illuminate\Contracts\View\View
    {
        return View::make('user/password');
    }
    public function notes()
    {
      return View::make('user/notes');
    }
    public function detail(): \Illuminate\Contracts\View\View
    {
      $detail = User::find($user_id);
      $personel_family = PersonalFamily::where('user_id', $user_id)->get();
      $user_documents = UserDocument::where('user_id', $user_id)->get();
      
      return View::make('user.components.personel',[
        'detail' => $detail,
        'personel_family' => $personel_family,
        'user_documents' => $user_documents
      ]);
    }

    public function personal(): \Illuminate\Contracts\View\View
    {
      $user_id = $this->request->user()->id;
      $detail = User::find($user_id);
      $personel_family = PersonalFamily::where('user_id', $user_id)->get();
      $user_documents = UserDocument::where('user_id', $user_id)->get();
      
      return View::make('user.components.personel',[
        'detail' => $detail,
        'personel_family' => $personel_family,
        'user_documents' => $user_documents
      ]);
    }


    public function account(): \Illuminate\Contracts\View\View
    {
      $user_id = $this->request->user()->id;
      $detail = User::find($user_id);
      $departments = Department::get();
      $titles = Title::get();
      $groups = UserGroup::get();
      
      return View::make('user.components.account',[
        'detail' => $detail,
        'departments' => $departments,
        'titles' => $titles,
        'user_groups' => $groups
      ]);
    }

    public function education(): \Illuminate\Contracts\View\View
    {
      $user_id = $this->request->user()->id;
      $detail = User::find($user_id);
      $education = EducationUser::where('user_id', $user_id)->get();
      $certificates = UserCertificate::where('user_id', $user_id)->with('certificate')->get();
      
      return View::make('user.components.education',[
        'detail' => $detail,
        'education' => $education,
        'certificates' => $certificates
      ]);
    }

    public function finance(): \Illuminate\Contracts\View\View
    {
      $user_id = $this->request->user()->id;
      $detail = User::find($user_id);

      $wages = Wage::where('user_id', $user_id)->where('year', date('Y'))->get()->keyBy('month');
      $costs = Cost::where('user_id', $user_id)->where('doc_date', '>', date('Y'))
      ->orderBy('doc_date', 'desc')
      ->limit(100)->get();
      
      return View::make('user.components.finance',[
        'detail' => $detail,
        'wages' => $wages,
        'costs' => $costs
      ]);
    }

    public function rating(): \Illuminate\Contracts\View\View
    {
      $user_id = $this->request->user()->id;
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

      return View::make('user.components.rating',[
        'detail' => $detail,
        'ratings' => $ratings,
        'avg' => $avg,
        'quarter' => $quarter.". Çeyrek"
      ]);
    }

    public function demand(): \Illuminate\Contracts\View\View
    {
      $user_id = $this->request->user()->id;
      $detail = User::find($user_id);
      $permissions = Permission::where('user_id', $user_id)
      ->orderBy('start_at')->get();
      $earnests = Earnest::where('user_id', $user_id)->get();
      $visas = VisaDemand::where('user_id', $user_id)->get();

      $total_count = Permission::where('user_id', $user_id)
      ->where('status', 'Onaylandı')
      ->where('start_at', '>', date('Y'))
      ->where('start_at', '<', date('Y', strtotime('+1 year')))
      ->where('type', 1)
      ->sum('days');

      $types = PermissionType::where('id', '!=', 1)->withCount([
        'used as paid_sum' => function ($query) use ($user_id) {
                    $query->select(DB::raw("SUM(days) as paidsum"))->where('user_id', $user_id);
                }
            ])
      ->get();
      
      return View::make('user.components.demands',[
        'detail' => $detail,
        'permissions' => $permissions,
        'earnests' => $earnests,
        'visas' => $visas,
        'total_count' => $total_count,
        'types' => $types
      ]);
    }
    public function belonging(): \Illuminate\Contracts\View\View
    {
      $user_id = $this->request->user()->id;
      $detail = User::find($user_id);
      $belongings = Belonging::where('user_id', $user_id)->get();
      
      return View::make('user.components.belonging',[
        'detail' => $detail,
        'belongings' => $belongings
      ]);
    }
    public function save(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->request->validate([
            'name' => 'required',
            'phone' => 'required',
            'kt_user_add_user_avatar' => 'nullable'
        ]);


        $user_id = $this->request->user()->id;
        $data = $this->request->all();
        $file = $request->file('kt_user_add_user_avatar');
        /*if(isset($data['kt_user_add_user_avatar'])){
          $destinationPath = '/uploads/users';
          $filename = uniqid().".".$file->getClientOriginalExtension();
          $file->move($destinationPath,$filename);
        }*/

        if(isset($data['kt_user_add_user_avatar'])){
          $filename = uniqid().".".$file->getClientOriginalExtension();
          $filePath = '/uploads/users';
           Storage::disk('s3')->putFileAs($filePath, $file, $filename, ['visibility' => 'public']);
      }

        $user = User::find($user_id);
        if(isset($data['kt_user_add_user_avatar'])){
          $user->avatar = $filename;
        }
        $user->name = $data['name'];
        $user->phone = $data['phone'];
        $user->save();

        $result = array(
          'status' => 1,
          'redirect' => '/user/profil',
          'data' => $user,
          'message' => 'Başarıyla kaydedildi. Yönlendiriliyorsunuz.'
      );

      return response()->json($result);
    }
    public function updatePassword(Request $request): \Illuminate\Http\JsonResponse
    {
        $user_id = $this->request->user()->id;
        $firm_id = 4;
        $data = $this->request->all();

        $user = User::find($user_id);
        $bul = $user->first();
        if(!Hash::check($data["current_password"], $bul->password)){
          $result = array(
            'status' => 0,
            'message' => 'Mevcut şifrenizi hatalı girdiniz'
          );
          return response()->json($result);
        }
        if($data["password"] != $data["retype-password"]){
          $result = array(
            'status' => 0,
            'message' => 'Şifreleriniz uyuşmuyor'
          );
          return response()->json($result);
        }

        $user->password = Hash::make($data["password"]);
        $user->save();

        $result = array(
          'status' => 1,
          'redirect' => '/user/sifre-degistir',
          'data' => $user,
          'message' => 'Şifreniz başarıyla güncellendi.'
        );

      return response()->json($result);
    }

    
    public function pickFirm(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->request->validate([
            'id' => 'required'
        ]);

        $user_id = $this->request->user()->id;
        $data = $this->request->all();
        $id = $data["id"];

        $user = User::find($user_id);
        $user->firm_id = $data['id'];
        $user->balance_id = NULL;
        $user->save();

        $balance = Balance::where('firm_id', $id)->get();

        $result = array(
          'status' => 1,
          'data' => $balance,
          'message' => 'Firma başarıyla kaydedildi.'
        );

      return response()->json($result);
    }
    public function bordroSign(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->request->validate([
            'id' => 'required'
        ]);

        $user_id = $this->request->user()->id;
        $data = $this->request->all();
        $id = $data["id"];

        $wage = Wage::where('user_id', $user_id)->where('id', $data['id']);
        $wage->update(
          array(
            'mobile_sign' => 1
          )
        );

        $result = array(
          'status' => 1
        );

      return response()->json($result);
    }
}
