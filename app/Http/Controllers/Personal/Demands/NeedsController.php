<?php declare(strict_types = 1);

namespace App\Http\Controllers\Personal\Demands;

use App\Http\Controllers\Controller;
use App\Models\Need;
use App\Models\NeedItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class NeedsController extends Controller
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

        $data = Need::where('user_id', $user_id)->get();

        return View::make('portal.demand.index', [
            'data' => $data
        ]);
    }
    public function add(): \Illuminate\Contracts\View\View
    {
        return View::make('portal.demand.add');
    }

    public function update(int $visa_id): \Illuminate\Contracts\View\View
    {
      $detail = Need::find($visa_id);

        return View::make('portal.demand.add', [
          'detail' => $detail
        ]);
    }

    public function save(Request $request): \Illuminate\Http\JsonResponse
    {
      
        $user_id = $this->request->user()->id;
        $data = $this->request->all();
        
        $visa_demand = new Need;
        if(isset($data['id'])){
          $visa_demand = Need::find($data['id']);
        }

        $visa_demand->user_id = $user_id;
        $visa_demand->description = $data['description'] ?? null;
        $visa_demand->category = $data['category'] ?? null;
        $visa_demand->deadline = isset($data['deadline']) ? date('Y-m-d', strtotime($data['deadline'])) : null;
        $visa_demand->priority = $data['priority'] ?? null;
        $visa_demand->price = isset($data['price']) ? money_deformatter($data['price']) : 0;
        $visa_demand->status = $data['status'] ?? 'talep_edildi';
        $visa_demand->save();
        
        $result = array(
          'status' => 1,
          'redirect' => route('personel-ihtiyaclar'),
          'message' => 'Başarıyla kaydedildi. Yönlendiriliyorsunuz.'
      );

      return response()->json($result);
    }

    function delete(int $id){
        $find = Need::find($id);
        $find->delete();


        return redirect()->back()->with('alert', 'Başarıyla silindi.');
    }
}
