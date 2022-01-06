<?php declare(strict_types = 1);

namespace App\Http\Controllers\Personal\Demands;

use App\Http\Controllers\Controller;
use App\Models\VisaDemand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class VisaController extends Controller
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

        $data = VisaDemand::where('user_id', $user_id)->get();

        return View::make('portal.visa.index', [
            'data' => $data
        ]);
    }
    public function add(): \Illuminate\Contracts\View\View
    {
        return View::make('portal.visa.add');
    }

    public function update(int $visa_id): \Illuminate\Contracts\View\View
    {
      $detail = VisaDemand::find($visa_id);

        return View::make('portal.visa.add', [
          'detail' => $detail
        ]);
    }

    public function save(Request $request): \Illuminate\Http\JsonResponse
    {
      
        $user_id = $this->request->user()->id;
        $data = $this->request->all();
        
        $vize = new VisaDemand;
        if(isset($data['id'])){
          $vize = VisaDemand::find($data['id']);
        }

        $baslangic = date('Y-m-d', strtotime($data['start_at']));
        $baslangic_saat = $baslangic." ".date('H:i', strtotime($data['start_time']));

        $bitis = date('Y-m-d', strtotime($data['end_at']));
        $bitis_saat = $bitis." ".date('H:i', strtotime($data['end_time']));

        $vize->user_id = $user_id;
        $vize->start_at = $baslangic_saat;
        $vize->end_at = $bitis_saat;
        $vize->type = $data['type'];
        $vize->place = $data['place'];
        $vize->demand = isset($data['demand']) ? $data['demand'] : null;
        $vize->project_code = $data['project_code'] ?? null;
        $vize->firm_code = $data['firm_code'] ?? null;
        $vize->country = $data['country'] ?? null;
        $vize->description = $data['description'] ?? null;
        $vize->status = $data['status'] ?? 'Bekliyor';
        $vize->save();
        
        $result = array(
          'status' => 1,
          'redirect' => route('personel-vizeler'),
          'message' => 'Başarıyla kaydedildi. Yönlendiriliyorsunuz.'
      );

      return response()->json($result);

    }

    function delete(int $id){
        $find = VisaDemand::find($id);
        $find->delete();


        return redirect()->back()->with('alert', 'Başarıyla silindi.');
    }
}
