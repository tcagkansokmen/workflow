<?php declare(strict_types = 1);

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\PermissionType;
use App\Models\Earnest;
use App\Models\Need;
use App\Models\VisaDemand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class DemandsController extends Controller
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

    public function permission(): \Illuminate\Contracts\View\View
    {
        $datas = $this->request->all();
        $user_id = $this->request->user()->id;

        $data = Permission::orderBy('created_at', 'desc')->get();

        return View::make('hr.demand.izin', [
            'data' => $data
        ]);
    }

    public function earnest(): \Illuminate\Contracts\View\View
    {
        $request = $this->request->all();
        $user_id = $this->request->user()->id;

        $data = Earnest::orderBy('created_at', 'desc');

        if(isset($request['status'])){
            if($request['status']=='Bekliyor'){
                $data = $data->where('status', 'beklemede');
            }elseif($request['status']=='onaylanan'){
                $data = $data->where('status', 'Onaylandı');
            }elseif($request['status']=='reddedilen'){
                $data = $data->where('status', 'Reddedildi');
            }
        }
        $data = $data->get();

        return View::make('hr.demand.avans', [
            'data' => $data
        ]);
    }

    public function earnestAccountant(): \Illuminate\Contracts\View\View
    {
        $request = $this->request->all();
        $user_id = $this->request->user()->id;

        $data = Earnest::where('status', 'Onaylandı');
        
        if(isset($request['status'])){
            if($request['status']=='Bekliyor'){
                $data = $data->where('status', 'Onaylandı');
            }elseif($request['status']=='odenen'){
                $data = $data->where('status', 'ödendi');
            }
        }


        $data = $data->orderBy('created_at', 'desc')->get();

        return View::make('hr.demand.avans', [
            'data' => $data
        ]);
    }

    public function visa(): \Illuminate\Contracts\View\View
    {
        $datas = $this->request->all();
        $user_id = $this->request->user()->id;

        $data = VisaDemand::orderBy('created_at', 'desc')->get();

        return View::make('hr.demand.vize', [
            'data' => $data
        ]);
    }

    public function need(): \Illuminate\Contracts\View\View
    {
        $datas = $this->request->all();
        $user_id = $this->request->user()->id;
        $user = $this->request->user();

        $data = [];
        if($user->group_id==1){
            $data = Need::where('status', 'talep_edildi')->get();
        }elseif($user->group_id==1){
            $data = Need::where('status', 'Kabul Edildi')->get();
        }

        return View::make('hr.demand.ihtiyac', [
            'data' => $data
        ]);
    }

}
