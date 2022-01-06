<?php declare(strict_types = 1);

namespace App\Http\Controllers\Personal\Demands;

use App\Http\Controllers\Controller;
use App\Models\Overtime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class OvertimeController extends Controller
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

        $data = Overtime::where('user_id', $user_id)->get();

        return View::make('portal.overtime.index', [
            'data' => $data
        ]);
    }
    public function add(): \Illuminate\Contracts\View\View
    {
        return View::make('portal.overtime.add');
    }

    public function update(int $visa_id): \Illuminate\Contracts\View\View
    {
      $detail = Overtime::find($visa_id);

        return View::make('portal.overtime.add', [
          'detail' => $detail
        ]);
    }

    public function save(Request $request): \Illuminate\Http\JsonResponse
    {
      
        $user_id = $this->request->user()->id;
        
        $overtime = new Overtime;
        if(isset($data['id'])){
          $overtime = Overtime::find($data['id']);
        }
        $data = $this->request->all();

        $overtime->user_id = $user_id;
        $overtime->start_at = date('Y-m-d', strtotime($data['start_at']));
        $overtime->end_at = date('Y-m-d', strtotime($data['end_at']));
        $overtime->country = $data['country'];
        $overtime->description = $data['description'] ?? null;
        $overtime->type = $data['type'] ?? 'seyahat';
        $overtime->status = $data['status'] ?? 'Bekliyor';
        $overtime->save();
        
        $result = array(
          'status' => 1,
          'redirect' => route('ilanlar'),
          'message' => 'Başarıyla kaydedildi. Yönlendiriliyorsunuz.'
      );

      return response()->json($result);
    }

    function delete(int $id){
        $find = Overtime::find($id);
        $find->delete();


        return redirect()->back()->with('alert', 'Başarıyla silindi.');
    }
}
