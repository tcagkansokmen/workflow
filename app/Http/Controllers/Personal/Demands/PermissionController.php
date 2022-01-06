<?php declare(strict_types = 1);

namespace App\Http\Controllers\Personal\Demands;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\PermissionType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Code16\CarbonBusiness\BusinessDays;
use App\Core\CalculateDays;
use Illuminate\Support\Facades\Validator;

class PermissionController extends Controller
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

        $kosulmatch = array(
            'icerir' => 'like',
            'ile_baslar' => 'like',
            'esittir' => '=',
            'kucuktur' => '<',
            'buyuktur' => '>'
        );

        $data = Permission::where('user_id', $user_id)->get();

        return View::make('portal.permission.index', [
            'data' => $data
        ]);
    }
    public function add(): \Illuminate\Contracts\View\View
    {
      $types = PermissionType::all();

        return View::make('portal.permission.add', [
          'types' => $types
        ]);
    }

    public function update(int $permission_id): \Illuminate\Contracts\View\View
    {
      $detail = Permission::find($permission_id);
      $types = PermissionType::all();

        return View::make('portal.permission.add', [
          'types' => $types,
          'detail' => $detail
        ]);
    }

    public function save(Request $request): \Illuminate\Http\JsonResponse
    {
      
        $user_id = $this->request->user()->id;
        $data = $this->request->all();
        
        $validator = Validator::make($data, [
            'type' => 'required',
            'start_at' => 'required|date',
            'end_at' => 'required|after_or_equal:start_at',
            'type' => 'required',
            'description' => 'required',
        ]);
  
        if ($validator->fails()) {
            return response()->json([
                'message' => error_formatter($validator),
                'errors' => $validator->errors(),
            ]);
        }

        $permission = new Permission;
        if(isset($data['id'])){
          $permission = Permission::find($data['id']);
        }

        $baslangic = date('Y-m-d', strtotime($data['start_at']));
        $baslangic_saat = $baslangic." ".date('H:i', strtotime($data['start_time']));

        $bitis = date('Y-m-d', strtotime($data['end_at']));
        $bitis_saat = $bitis." ".date('H:i', strtotime($data['end_time']));

        $calculate_days = new CalculateDays;
        $days = $calculate_days->calculate($baslangic, $bitis);
        
        if(date('H', strtotime($data['start_time']))>12&&date('H', strtotime($data['start_time']))<18){
            $days -= 0.5;
        }

        if(date('H', strtotime($data['start_time']))>=18){
            $days -= 1;
        }

        if(date('H', strtotime($data['end_time']))>12&&date('H', strtotime($data['end_time']))<18){
            $days -= 0.5;
        }

        if(date('H', strtotime($data['end_time']))<12){
            $days -= 1;
        }
    
        $permission->user_id = $user_id;
        $permission->start_at = $baslangic_saat;
        $permission->end_at = $bitis_saat;
        $permission->days = $days;
        $permission->type = $data['type'];
        $permission->description = $data['description'] ?? null;
        $permission->status = $data['status'] ?? 'Bekliyor';
        $permission->save();
        
        $result = array(
          'status' => 1,
          'redirect' => route('personel-izinler'),
          'message' => 'Başarıyla kaydedildi. Yönlendiriliyorsunuz.'
      );

      return response()->json($result);
    }

    function delete(int $id){
        $find = Permission::find($id);
        if($find->status!='Onaylandı'&&$find->status!='Reddedildi'){
            $find->delete();
            return redirect()->back()->with('alert', 'Başarıyla silindi.');
        }else{
            return redirect()->back()->with('alert', 'Silinemedi.');
        }
    }
}
