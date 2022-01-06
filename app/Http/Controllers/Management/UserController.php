<?php declare(strict_types = 1);

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Belonging;
use App\Models\Permission;
use App\Models\Earnest;
use App\Models\Cost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function profile(): \Illuminate\Contracts\View\View
    {
        $page_title = "Profil düzenle";
        $page_description = '';
        $redirect = '/';
        return View::make('profile.components.profile', compact('page_title', 'page_description', 'redirect'));
    }

    public function earnest(): \Illuminate\Contracts\View\View
    {
      $user_id = $this->request->user()->id;
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

      return View::make('profile.components.earnest', compact('page_title', 'page_description', 'redirect', 'total_earnest', 'total_cost', 'earnest', 'cost'));
    }

    public function permission(): \Illuminate\Contracts\View\View
    {
        $page_title = "Profil düzenle";
        $page_description = '';
        $redirect = '/';
        $user_id = $this->request->user()->id;

        $get_permission = Permission::where('user_id', $user_id)->orderBy('created_at', 'desc');
  
        $days = $get_permission->sum('days');
  
        $data = $get_permission->get();

        return View::make('profile.components.permission', compact('page_title', 'page_description', 'redirect', 'data', 'days'));
    }

    public function belonging(): \Illuminate\Contracts\View\View
    {
        $page_title = "Profil düzenle";
        $page_description = '';
        $redirect = '/';
        $user_id = $this->request->user()->id;

        $belongings = Belonging::where('user_id', $user_id)->get();

        return View::make('profile.components.belonging', compact('page_title', 'page_description', 'redirect', 'belongings'));
    }

    public function password(): \Illuminate\Contracts\View\View
    {
        return View::make('user.password');
    }
    public function notes()
    {
      return View::make('user/notes');
    }
    public function save(Request $request): \Illuminate\Http\JsonResponse
    {
      $data = $this->request->all();
      $validator = Validator::make($data, [
        'name' => 'required',
        'surname' => 'required',
        'phone' => 'required',
        'address' => 'required',
        'profile_avatar' => 'nullable'
      ]);

      $niceNames = array(
        'name' => 'İsim',
        'surname' => 'Soyisim',
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

        $user_id = $this->request->user()->id;
        $data = $this->request->all();

        $file = $request->file('profile_avatar');
        
        if(isset($data['profile_avatar'])){
          $filename = time() . "." . $file->getClientOriginalExtension();
          $filePath = 'uploads/users';
          Storage::disk('s3')->putFileAs($filePath, $file, $filename, ['visibility' => 'public']);
        }


        $user = User::find($user_id);
        if(isset($data['profile_avatar'])){
          $user->avatar = $filename;
        }
        $user->name = $data['name'];
        $user->surname = $data['surname'];
        $user->phone = $data['phone'];
        $user->address = $data['address'];
        $user->emergency = $data['emergency'] ?? null;
        $user->blood = $data['blood'] ?? null;
        if($data['password']){
          if($data['password'] == $data['password_repeat']){
            $user->password = Hash::make($data["password"]);
          }
        }
        $user->save();

        $result = array(
          'status' => 1,
          'redirect' => '/user/profil',
          'data' => $user,
          'message' => 'Başarıyla kaydedildi, yönlendiriliyorsunuz.'
      );

      return response()->json($result);
    }
    public function updatePassword(Request $request): \Illuminate\Http\JsonResponse
    {
        $user_id = $this->request->user()->id;
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
}
