<?php declare(strict_types = 1);

namespace App\Http\Controllers\Personal\Demands;

use App\Http\Controllers\Controller;
use App\Models\Earnest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class EarnestController extends Controller
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

        $data = Earnest::where('user_id', $user_id)->get();

        return View::make('portal.earnest.index', [
            'data' => $data
        ]);
    }
    public function add(): \Illuminate\Contracts\View\View
    {
        return View::make('portal.earnest.add');
    }

    public function update(int $earnest_id): \Illuminate\Contracts\View\View
    {
      $detail = Earnest::find($earnest_id);

        return View::make('portal.earnest.add', [
          'detail' => $detail
        ]);
    }

    public function save(Request $request): \Illuminate\Http\JsonResponse
    {
      
        $user_id = $this->request->user()->id;
        $data = $this->request->all();
        
        $earnest = new Earnest;
        if(isset($data['id'])){
          $earnest = Earnest::find($data['id']);
        }

        $earnest->user_id = $user_id;
        $earnest->price = str_replace(",", ".", str_replace(".", "", $data['price']));
        $earnest->reason = $data['reason'] ?? null;
        $earnest->category = $data['category'] ?? null;
        $earnest->installments = $data['installments'] ?? 1;
        $earnest->status = $data['status'] ?? 'Bekliyor';
        $earnest->save();
        
        $result = array(
          'status' => 1,
          'redirect' => route('personel-avanslar'),
          'message' => 'Başarıyla kaydedildi. Yönlendiriliyorsunuz.'
      );

      return response()->json($result);
    }

    function delete(int $id){
        $find = Earnest::find($id);
        $find->delete();


        return redirect()->back()->with('alert', 'Başarıyla silindi.');
    }
}
