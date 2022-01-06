<?php declare(strict_types = 1);

namespace App\Http\Controllers\Personal\Demands;

use App\Http\Controllers\Controller;
use App\Models\Belonging;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class BelongingsController extends Controller
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

        $data = Belonging::where('user_id', $user_id)->get();

        return View::make('portal.belonging.index', [
            'data' => $data
        ]);
    }
}
