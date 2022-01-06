<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use App\Models\UserGroup;
use App\Models\Belonging;
use App\Models\Permission;
use App\Models\Earnest;
use App\Models\Cost;
use App\Rules\UserId;
use App\Http\Resources\CostResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use DataTables;
use DB;

class UsersController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {
        $parameters = $this->request->query();

        $detail = User::orderBy('is_active', 'desc')->orderBy('id')->get();

        $page_title = 'Kullanıcı Bilgileri';
        $page_description = 'Kullanıcıları görüntüleyip güncelleyebilirsiniz.';

        return view('management.users.index', compact('page_title', 'page_description', 'detail'));
    }
    public function add()
    {
        $parameters = $this->request->query();

        $groups = UserGroup::select('id as value', 'name')->get();

        $page_title = 'Kullanıcı Bilgileri';
        $page_description = 'Kullanıcı bilgilerinizi görüntüleyip güncelleyebilirsiniz.';

        return view('management.users.add', compact('page_title', 'page_description', 'groups'));
    }


    public function update(int $service_id)
    {
        $parameters = $this->request->query();

        $detail = User::find($service_id);

        $groups = UserGroup::select('id as value', 'name')->get();

        $page_title = 'Kullanıcı Bilgileri';
        $page_description = 'Kullanıcı bilgilerinizi görüntüleyip güncelleyebilirsiniz.';

        return view('management.users.add', compact('page_title', 'page_description', 'detail', 'groups'));
    }

    public function profile($user_id): \Illuminate\Contracts\View\View
    {
        $page_title = "Profil düzenle";
        $page_description = '';
        $redirect = '/';
        $detail = User::find($user_id);

        return view('management.users.components.profile', compact('page_title', 'page_description', 'redirect', 'detail'));
    }
    public function earnest($user_id): \Illuminate\Contracts\View\View
    {
      $page_title = "Profil düzenle";
      $page_description = '';
      $redirect = '/';

      $get_earnest = Earnest::where('user_id', $user_id)
      ->where(function($q){
          $q->where('status', 'Onaylandı');
          $q->orWhere('status', 'ödendi');
      });

      $get_cost = Cost::where('user_id', $user_id)
      ->where(function($q){
          $q->where('status', 'Onaylandı');
          $q->orWhere('status', 'ödendi');
      });

      $total_earnest = $get_earnest->sum('price');
      $total_cost = $get_cost->sum('price');

      $earnest = $get_earnest->get();
      $cost = $get_cost->get();
      $detail = User::find($user_id);

      return view('management.users.components.earnest', compact('page_title', 'page_description', 'redirect', 'total_earnest', 'total_cost', 'earnest', 'cost', 'detail'));
    }

    public function permission($user_id): \Illuminate\Contracts\View\View
    {
        $page_title = "Profil düzenle";
        $page_description = '';
        $redirect = '/';

        $get_permission = Permission::where('user_id', $user_id)->orderBy('created_at', 'desc');
  
        $days = $get_permission->sum('days');
  
        $data = $get_permission->get();
        $detail = User::find($user_id);

        return view('management.users.components.permission', compact('page_title', 'page_description', 'redirect', 'data', 'days', 'detail'));
    }

    public function belonging($user_id): \Illuminate\Contracts\View\View
    {
        $page_title = "Profil düzenle";
        $page_description = '';
        $redirect = '/';

        $belongings = Belonging::where('user_id', $user_id)->get();
        $detail = User::find($user_id);

        return view('management.users.components.belonging', compact('page_title', 'page_description', 'redirect', 'belongings', 'detail'));
    }

    public function save(Request $request)
    {
        $data = $this->request->all();

        $validator = Validator::make($data, [
            'name' => 'required',
            'surname' => 'required',
            'group_id' => 'required',
            'title' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'profile_avatar' => 'nullable'
        ]);

        $niceNames = array(
            'name' => 'İsim',
            'surname' => 'Soyisim',
            'group_id' => 'Yetki',
            'title' => 'Unvan',
            'phone' => 'Telefon',
            'address' => 'Adres',
          );
    
          $validator->setAttributeNames($niceNames); 
    
          if ($validator->fails()) {
              return response()->json([
                  'message' => error_formatter($validator),
                  'errors' => $validator->errors(),
              ]);
          }

        if(!isset($data['id'])){
            $validator = Validator::make($data, [
                'email' => 'required|email|unique:users',
            ]);
        }

        if ($validator->fails()) {
            return response()->json([
                'message' => error_formatter($validator),
                'errors' => $validator->errors(),
            ]);
        }
        if(isset($data['id'])){
          $user = User::find($data['id']);
        }else{
          $validator = Validator::make($data, [
              'password' => 'required'
          ]);

          if ($validator->fails()) {
              return response()->json([
                  'message' => 'Lütfen tüm zorunlu alanları doldurun',
                  'errors' => $validator->errors(),
              ]);
          }
          $user = new User();
          $user->is_active = 1;
        }
        $user->name = $data['name'];
        $user->surname = $data['surname'];
        $user->group_id = $data['group_id'];
        $user->title = $data['title'];
        $user->email = $data['email'];
        $user->phone = $data['phone'];
        $user->address = $data['address'];
        $user->emergency = $data['emergency'] ?? null;
        $user->blood = $data['blood'] ?? null;
        if(isset($data['password'])){
          $user->password = Hash::make($data['password']);
        }
        $user->save();

        $result = array(
            'status' => 1,
            'redirect' => route('users'),
            'message' => 'Başarıyla kaydettiniz.'
        );
        return response()->json($result);
    }

    public function passive(int $user_id): \Illuminate\Http\JsonResponse
    {
        $user = User::find($user_id);
        $user->is_active = 0;
        $user->save();

        $result = array(
            'status' => 1,
            'message' => 'Kullanıcıyı pasife aldınız.'
        );
        return response()->json($result);
    }
    public function active(int $user_id): \Illuminate\Http\JsonResponse
    {
        $user = User::find($user_id);
        $user->is_active = 1;
        $user->save();

        $result = array(
            'status' => 1,
            'message' => 'Başarıyla aktife aldınız.'
        );
        return response()->json($result);
    }
}
