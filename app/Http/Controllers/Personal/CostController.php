<?php declare(strict_types = 1);

namespace App\Http\Controllers\Personal;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Customer;
use App\Models\Cost;
use App\Models\Project;
use App\Models\ExpenseType;
use App\Models\ExpenseDocType;
use App\Models\SystemParameter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use Carbon\Carbon;
use DataTables;

class CostController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index(): \Illuminate\Contracts\View\View
    {
        $user_id = $this->request->user()->id;
        $datas = $this->request->all();

        if(isset($datas['year'])){
            $month = $datas['year']."-".$datas['month'];
        }else{
            $month = date('Y-m');
        }
        
        $page_title = "Masraf Fişleri";
        $page_description = "Masraf fişlerini görüntüleyebilir, ekleyebilir ve düzenleyebilirsiniz.";

        $start = Carbon::parse($month)->startOfMonth();
        $end = Carbon::parse($month)->endOfMonth();

        $data = Cost::with('project', 'customer')->where('user_id', $user_id)
        ->where('doc_date', '>=', $start->copy()->format('Y-m-d'))
        ->where('doc_date', '<=', $end->copy()->addDay()->format('Y-m-d'))
        ->orderBy('id', 'desc')->get();

        $expense = ExpenseType::all();
        $expense_doc_type = ExpenseDocType::all();

        $projelerim = $this->request->user()->projelerim();
        $firmalar = Project::whereIn('id', $projelerim)->pluck('customer_id')->toArray();

        $firms = Customer::whereIn('id', $firmalar)->orderBy('id', 'desc')->get();
        return View::make('personel.cost.index', [
            'data' => $data,
            'firms' => $firms,
            'expense' => $expense,
            'expense_doc_type' => $expense_doc_type,
            'page_title' => $page_title,
            'page_description' => $page_description
        ]);
    }

    public function json(): \Illuminate\Http\JsonResponse
    {
        $d = $this->request->all();
        $parameters = $this->request->query();
        $user_id = $this->request->user()->id;
        $user = $this->request->user();
        $edit_allowed = $user->power('cost', 'edit') ? true : false;
        $delete_allowed = $user->power('cost', 'delete') ? true : false;
        $detail_allowed = $user->power('cost', 'detail') ? true : false;


        $data = Cost::with(['project', 'customer','expense', 'expense_doc_type'])
        ->where('user_id', $user_id);

        if(isset($parameters['year'])){
            $month = $parameters['year']."-".$parameters['month'];
            $start = Carbon::parse($month)->startOfMonth();
            $end = Carbon::parse($month)->endOfMonth();
            $data->where('doc_date', '>=', $start->copy()->format('Y-m-d'))
            ->where('doc_date', '<=', $end->copy()->addDay()->format('Y-m-d'));
            
        }
        

        return Datatables::of($data)
        ->addColumn('date_formatted', function($data){
            return Carbon::parse($data->doc_date)->formatLocalized('%d %B %Y');
        })
        ->addColumn('edit_allowed', function($d) use ($edit_allowed){
            if($d->status!='Onaylandı'&&$d->status!='Reddedildi'){
                return $edit_allowed;
            }
            return false;
        })->addColumn('delete_allowed', function($d) use ($delete_allowed){
            if($d->status!='Onaylandı'&&$d->status!='Reddedildi'){
                return $delete_allowed;
            }
            return false;
        })->addColumn('detail_allowed', function() use ($detail_allowed){
            return $detail_allowed;
        })->make(true);
    }
    public function add(): \Illuminate\Contracts\View\View
    {
        $user_id = $this->request->user()->id;
        $datas = $this->request->all();

        $expense = ExpenseType::select('id as value', 'name')->get();
        $expense_doc_type = ExpenseDocType::select('id as value', 'name')->get();

        $projelerim = $this->request->user()->projelerim();
        $firmalar = Project::whereIn('id', $projelerim)->pluck('customer_id')->toArray();

        $firms = Customer::select('id as value', 'title as name')->whereIn('id', $firmalar)->orderBy('id', 'desc')->get();
        return View::make('personel.cost.add', [
            'firms' => $firms,
            'expense' => $expense,
            'expense_doc_type' => $expense_doc_type
        ]);
    }
    public function onayBekleyen(): \Illuminate\Contracts\View\View
    {
        $user_id = $this->request->user()->id;
        $request = $this->request->all();

        $projeler = $this->request->user()->projelerim();
        $data = Cost::with(['project', 'customer', 'user', 'expense']);

        if(isset($request['status'])){
            if($request['status']=='Bekliyor'){
                $data = $data->where('status', 'beklemede');
            }elseif($request['status']=='onaylanan'){
                $data = $data->where('status', 'Onaylandı');
            }elseif($request['status']=='reddedilen'){
                $data = $data->where('status', 'Reddedildi');
            }
        }

        $data = $data->orderBy('costs.id', 'desc')->get();

        $expense = ExpenseType::all();
        $expense_doc_type = ExpenseDocType::all();
        $firms = Customer::orderBy('id', 'desc')->get();
        return View::make('portal.cost.onay', [
            'data' => $data,
            'firms' => $firms
        ]);
    }

    public function odemeBekleyen(): \Illuminate\Contracts\View\View
    {
        $user_id = $this->request->user()->id;
        $request = $this->request->all();
        $data = Cost::with(['project', 'customer', 'user', 'expense'])
        ->where(function($q){
            $q->where('status', 'Onaylandı');
            $q->orWhere('status', 'ödendi');
        });

        if(isset($request['status'])){
            if($request['status']=='Bekliyor'){
                $data = $data->where('status', 'Onaylandı');
            }elseif($request['status']=='odenen'){
                $data = $data->where('status', 'ödendi');
            }
        }

        $data = $data->orderBy('costs.id', 'desc')->get();
        $expense = ExpenseType::all();
        $expense_doc_type = ExpenseDocType::all();
        $firms = Customer::orderBy('id', 'desc')->get();
        return View::make('portal.cost.onay', [
            'data' => $data,
            'firms' => $firms
        ]);
    }

    public function update(int $cost_id): \Illuminate\Contracts\View\View
    {   
        $user_id = $this->request->user()->id;
        $data = Cost::with('project', 'customer')->where('user_id', $user_id)->orderBy('id', 'desc')->get();
        $expense = ExpenseType::select('id as value', 'name')->get();
        $expense_doc_type = ExpenseDocType::select('id as value', 'name')->get();
        $firms = Customer::select('id as value', 'title as name')->orderBy('id', 'desc')->get();
        
        $detail = Cost::find($cost_id);
        $projects = Project::select('id as value', 'title as name')->where('customer_id', $detail->project->customer_id)->get();
        $project = Project::find($detail->project_id);

        return View::make('personel.cost.add',[
          'firms' => $firms,
          'detail' => $detail,
          'data' => $data,
          'firms' => $firms,
          'expense' => $expense,
          'expense_doc_type' => $expense_doc_type,
          'project' => $project,
          'projects' => $projects
        ]);
    }

    public function save(Request $request)
    {
        $user_id = $this->request->user()->id;
        $data = $this->request->all();

        $validator = Validator::make($data, [
            'doc_date' => 'required',
            'customer_id' => 'required',
            'price' => 'required',
            'doc_no' => 'required',
            'type' => 'required'
        ]);
  
        $niceNames = array(
            'doc_date' => 'Belge tarihi',
            'customer_id' => 'Firma',
            'price' => 'Tutar',
            'doc_no' => 'Belge No',
            'type' => 'Harcama türü'
        );

        $validator->setAttributeNames($niceNames); 

        if ($validator->fails()) {
            return response()->json([
                'message' => error_formatter($validator),
                'errors' => $validator->errors(),
            ]);
        }

        if(isset($data['doc_date'])){
            $month_year = date('m-Y', strtotime($data['doc_date']));
            if(!isset($data['price'])||money_deformatter($data['price']<=0)){
                $result = array(
                    'status' => 0,
                    'message' => 'Tutar alanı boş bırakılamaz ve eksi değer girilemez.'
                );
                return response()->json($result);
            }

            $main_date = $data['doc_date'];
            $main_description = $data['description'];
            if(date('Y-m-d', strtotime($data['doc_date'])) > date('Y-m-d')){
                $result = array(
                  'status' => 0,
                  'message' => 'İleri tarihli masraf girişi yapamazsınız.'
                );
                return response()->json($result);
            }
        }


        if(!$data['doc_no']){
            $result = array(
              'status' => 0,
              'message' => 'Belge ibrazı bulunan harcamalarda, belge numarası yazmak zorunludur.'
            );
            return response()->json($result);
        }

        if(isset($data['id'])){
            $cost = Cost::find($data['id']);
        }else{
            $cost = new Cost();
        }

        $cost->user_id = $user_id;
        $cost->project_id = $data['project_id'] ?? null;
        $cost->customer_id = $data['customer_id'];
        $cost->firm = $data['firm'] ?? null;
        $cost->type = $data['type'] ?? NULL;
        $cost->expense_doc_types = $data['expense_doc_types'] ?? NULL;
        $cost->file = $data['file_input'] ?? NULL;
        $cost->status = $data['status'] ?? "beklemede";
        $cost->price = money_deformatter($data['price']);
        $cost->doc_date = date_deformatter($main_date);
        $cost->doc_no = $data['doc_no'];
        $cost->description = $main_description;
        $cost->save();

        $data = Cost::where('user_id', $user_id)->get();

        $result = array(
            'status' => 1,
            'message' => 'Başarıyla kaydedildi. Yönlendiriliyorsunuz.'
        );
  
        return response()->json($result);
    }

    public function statusUpdate(Request $request): \Illuminate\Http\JsonResponse
    {
        $user_id = $this->request->user()->id;
        $data = $this->request->all();


        if(isset($data['id'])){
            $cost = Cost::find($data['id']);
            $cost->status = $data['status'];
            $cost->save();
        }else{
            Cost::whereIn('id', explode(',', $data['ids']))  // find your user by their email
            ->update(array('status' => $data['status']));  // update th
        }

        $result = array('status' => 1);
        return response()->json($result);
    }
    function delete(int $id){
        $find = Cost::find($id);
        $find->delete();


        return redirect()->back()->with('alert', 'Başarıyla silindi.');
    }
    public function upload(Request $request): \Illuminate\Http\JsonResponse
    {

        $user_id = $this->request->user()->id;
        $data = $this->request->all();
        $file = $request->file();
        $file = $file[0];
        
        /*if(isset($file)){
          $destinationPath = 'uploads/contract';
          $filename = uniqid().".".$file->getClientOriginalExtension();
          $file->move($destinationPath,$filename);
        }*/

        if(isset($file)){
            $filename = uniqid().".".$file->getClientOriginalExtension();
            $filePath = 'uploads/cost';
            $ee = Storage::disk('s3')->putFileAs($filePath, $file, $filename, ['visibility' => 'public']);
        }



      return response()->json(array('file' => $filename));
    }
}
