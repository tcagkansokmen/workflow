<?php declare(strict_types = 1);

namespace App\Http\Controllers\HR\Personel\Candidate;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\PersonelDemand;
use App\Models\Title;
use App\Models\Interview;
use App\Models\CandidateOffer;
use App\Models\CandidateLog;
use App\Models\Iller;
use App\Models\Ilceler;
use App\Models\User;
use App\Models\UserTitle;

use App\Models\Department;
use App\Core\CandidateLogs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CandidateController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function add(int $demand_id): \Illuminate\Contracts\View\View
    {
        $demand = PersonelDemand::find($demand_id);
        $iller = Iller::select('isim as value', 'isim as name')->get();
        $ilceler = Ilceler::select('isim as value', 'isim as name')->get();

        $page_title = "Aday Ekle";
        $page_description = $demand->title." isimli ilana yeni aday ekleyebilirsiniz.";
        
        return View::make('hr.notice.candidate.add',[
          'demand' => $demand,
          'iller' => $iller,
          'ilceler' => $ilceler,
          'page_title' => $page_title,
          'page_description' => $page_description
        ]);
    }

    public function makeUser(int $candidate_id): \Illuminate\Contracts\View\View
    {
        $candidate = Candidate::find($candidate_id);
        $demand = PersonelDemand::find($candidate->demand_id);

        $departments = Department::get();
        $titles = Title::get();
      

        return View::make('hr.notice.candidate.personel-tanimla',[
            'candidate' => $candidate,
            'demand' => $demand,
            'departments' => $departments,
            'titles' => $titles,
        ]);
    }

    public function makeUserSave(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->request->validate([
            'kt_user_add_user_avatar' => 'nullable'
        ]);

        $user_id = $this->request->user()->id;
        $data = $this->request->all();
        $candidate_id = $data["candidate_id"];
        $position_id = $data["position_id"];
        $department_id = $data["department_id"];

        $candidate = Candidate::find($candidate_id);
        $candidate->status = "kabul_edildi";
        $candidate->save();

        $log = new CandidateLogs();
        $log->statusUpdates($candidate->id, $candidate->status);

        $demand = PersonelDemand::find($candidate->demand_id);
        $title = Title::find($position_id);

        $check_in_date = strtotime($data['check_in_date']);
        
        $user = new User;
        $user->name = $candidate->name;
        $user->surname = $candidate->surname;
        $user->phone = $candidate->phone ?? '';
        $user->city = $candidate->city ?? '';
        $user->state = $candidate->state ?? '';
        $user->title = $title->title;
        $user->contract_type = $demand->type;
        $user->group_id = 3;
        $user->department_id = $department_id;
        $user->check_in_date = date("Y-m-d", $check_in_date);
        $user->trial_ends = date('Y-m-d', strtotime('+2 months', $check_in_date));
        $user->second_period_ends = date('Y-m-d', strtotime('+6 months', $check_in_date));
        $user->candidate_id = $candidate_id;
        $user->save();

        $title_bul = UserTitle::where('user_id', $user->id)
        ->where('title_id', $title['id'])->first();
        $user_title = new UserTitle();
        if($title_bul){
            $user_title = UserTitle::find($title_bul->id);
        }
        $user_title->user_id = $user->id;
        $user_title->title_id = $title['id'];
        $user_title->save();

        $result = array(
          'status' => 1,
          'message' => 'Başarıyla kaydedildi. Yönlendiriliyorsunuz.'
      );

      return response()->json($result);
    }

    public function detail(int $candidate_id): \Illuminate\Contracts\View\View
    {
        $detail = Candidate::with('user')->find($candidate_id);
        $demand = PersonelDemand::with(['user'])->find($detail->demand_id);

        $page_title = $detail->name." ".$detail->surname;
        $page_description = "Adaya ait süreci görüntüleyin";

        $interviews = Interview::selectRaw('"Mülakat Süreci" as type, status as status, notes as description, created_at')->where('candidate_id', $candidate_id)->with('documents');
        $offers = CandidateOffer::selectRaw('"Teklif Süreci" as type, status as status, notes as description, created_at')->where('candidate_id', $candidate_id);
        $logs = CandidateLog::
        where('candidate_id', $candidate_id)
        ->orderBy('created_at', 'desc')
        ->get();
        
        return View::make('hr.notice.candidate.index',[
          'detail' => $detail,
          'demand' => $demand,
          'logs' => $logs,
          'page_title' => $page_title,
          'page_description' => $page_description
        ]);
    }
    public function update(int $candidate_id): \Illuminate\Contracts\View\View
    {
      $detail = Candidate::find($candidate_id);
      $demand = PersonelDemand::with(['user'])->find($detail->demand_id);
      $iller = Iller::select('isim as value', 'isim as name')->get();
      $ilceler = Ilceler::select('isim as value', 'isim as name')->get();

      $page_title = $detail->name." ".$detail->surname;
      $page_description = "Adayın bilgilerini düzenleyebilirsiniz.";
      
      return View::make('hr.notice.candidate.add',[
        'detail' => $detail,
        'demand' => $demand,
        'iller' => $iller,
        'ilceler' => $ilceler,
        'page_title' => $page_title,
        'page_description' => $page_description
      ]);
    }

    public function checkinDate(int $candidate_id): \Illuminate\Contracts\View\View
    {
      $detail = Candidate::find($candidate_id);
      $demand = PersonelDemand::with(['user'])->find($detail->demand_id);
      
      return View::make('hr.notice.candidate.checkin',[
        'detail' => $detail,
        'demand' => $demand
      ]);
    }


    public function save(Request $request): \Illuminate\Http\JsonResponse
    {
        $user_id = $this->request->user()->id;
        $data = $this->request->all();
        
        $validator = Validator::make($data, [
            'name' => 'required',
            'surname' => 'required',
            'birthdate' => 'required',
            'email' => 'required',
            'phone' => 'required',
        ]);
  
        if ($validator->fails()) {
            return response()->json([
                'message' => error_formatter($validator),
                'errors' => $validator->errors(),
            ]);
        }

        $candidate = new Candidate;
        $status = 'beklemede';
        if(isset($data['id'])){
          $candidate = Candidate::find($data['id']);
        }

        $photo = $request->file('photo');
        if(isset($data['photo'])){
            $photoname = uniqid().".".$photo->getClientOriginalExtension();
            $filePath = 'uploads/candidate';
            Storage::disk('s3')->putFileAs($filePath, $photo, $photoname, ['visibility' => 'public']);
            $candidate->photo = $photoname ?? NULL;
        }

        $file = $request->file('cv');
        if(isset($data['cv'])){
          $filename = uniqid().".".$file->getClientOriginalExtension();
          $filePath = 'uploads/candidate';
          Storage::disk('s3')->putFileAs($filePath, $file, $filename, ['visibility' => 'public']);
          $candidate->cv = $filename ?? NULL;
        }

        $candidate->demand_id = $data['demand_id'];
        $candidate->name = $data['name'] ?? '';
        $candidate->surname = $data['surname'] ?? '';
        $candidate->birthdate = isset($data['birthdate']) ? date('Y-m-d', strtotime($data['birthdate'])) : NULL;
        $candidate->email = $data['email'] ?? NULL;
        $candidate->phone = $data['phone'] ?? NULL;
        $candidate->city = $data['city'] ?? NULL;
        $candidate->state = $data['state'] ?? NULL;
        $candidate->reference = $data['reference'] ?? NULL;
        $candidate->reference_id = $data['reference_id'] ?? NULL;
        $candidate->status = $data['status'] ?? $status;
        $candidate->save();

        $log = new CandidateLogs();
        $log->statusUpdates($candidate->id, $candidate->status);

        $result = array(
          'status' => 1,
          'redirect' => route('notice-detail', ['id' => $data['demand_id']]),
          'message' => 'Başarıyla kaydedildi. Yönlendiriliyorsunuz.'
      );

      return response()->json($result);
    }

    public function updateState(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->request->validate([
            'kt_user_add_user_avatar' => 'nullable'
        ]);


        $user_id = $this->request->user()->id;
        $data = $this->request->all();

        $candidate = Candidate::find($data['id']);
        $candidate->status = $data['value'];
        $candidate->save();

        $log = new CandidateLogs();
        $log->statusUpdates($candidate->id, $candidate->status);
        
        $result = array(
          'status' => 1,
          'message' => 'Başarıyla kaydedildi. Yönlendiriliyorsunuz.'
      );

      return response()->json($result);
    }
    function delete(int $id){
        $find = Candidate::find($id);
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
         $filePath = 'uploads/candidate';
         Storage::disk('s3')->putFileAs($filePath, $file, $filename, ['visibility' => 'public']);
        }



      return response()->json(array('file' => $filename));
    }
}
