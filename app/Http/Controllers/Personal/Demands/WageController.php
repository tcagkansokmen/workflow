<?php declare(strict_types = 1);

namespace App\Http\Controllers\Personal\Demands;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Service;
use App\Models\Firm;
use App\Models\Wage;
use App\Models\Project;
use App\Models\Contract;
use App\Models\ContractPayment;
use App\Models\ExpenseType;
use App\Models\ExpenseDocType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use Str;

class WageController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function odeme(): \Illuminate\Contracts\View\View
    {
        $user_id = $this->request->user()->id;
        $request = $this->request->all();

        $data = Wage::with(['user'])
        ->where('year', date('Y'))
        ->where('month', '<=', date('m'));

        if(isset($request['status'])){
            if($request['status']=='Bekliyor'){
                $data = $data->whereNull('is_paid');
            }elseif($request['status']=='odenen'){
                $data = $data->where('is_paid', 1);
            }elseif($request['status']=='bordro'){
                $data = $data->whereNull('bordro');
            }
        }

        $data = $data->orderBy('month', 'desc')->get();

        return View::make('portal.wage.odeme', [
            'data' => $data
        ]);
    }

    public function isPaid(Request $request): \Illuminate\Http\JsonResponse
    {
        $user_id = $this->request->user()->id;
        $data = $this->request->all();

        $cost = Wage::find($data['id']);
        $cost->is_paid = $data['status'];
        $cost->save();

        return response()->json($cost);
    }
    public function upload(Request $request, int $wage_id): \Illuminate\Http\JsonResponse
    {
        $user_id = $this->request->user()->id;
        $data = $this->request->all();
        $file = $request->file();
        $file = $file[0];

        $wage = Wage::find($wage_id);

        /*if(isset($file)){
          $destinationPath = 'uploads/contract';
          $filename = uniqid().".".$file->getClientOriginalExtension();
          $file->move($destinationPath,$filename);
        }*/

        if(isset($file)){
            $filename = Str::slug($wage->user->name."_".$wage->user->surname)."-".$wage->month."-".$wage->year.".".$file->getClientOriginalExtension();
            $filePath = 'uploads/wage';
            $ee = Storage::disk('s3')->putFileAs($filePath, $file, $filename, ['visibility' => 'public']);
        }

        $wage->bordro = $filename;
        $wage->save();

      return response()->json(array('file' => $filename));
    }
}
