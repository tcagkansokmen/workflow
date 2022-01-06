<?php declare(strict_types = 1);

namespace App\Http\Controllers\HR\Personel\Candidate;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\Interview;
use App\Models\InterviewDocument;
use App\Models\PersonelDemand;
use App\Models\User;
use App\Models\Perfection;
use App\Models\PerfectionInterview;
use App\Models\InterviewPerfection;
use App\Core\CandidateLogs;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class InterviewController extends Controller
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

    public function index(int $candidate_id): \Illuminate\Contracts\View\View
    {
        $detail = Candidate::find($candidate_id);
        $interview = Interview::where('candidate_id', $candidate_id)->with('perfections')
        ->orderBy('created_at', 'desc')
        ->get();
        
        return View::make('hr.notice.candidate.interview.index',[
          'detail' => $detail,
          'interview' => $interview,
        ]);
    }
    public function add(int $candidate_id): \Illuminate\Contracts\View\View
    {
        $users = User::where('department_id', 2)->orWhere('group_id', 1)->orWhere('group_id', 2)->get();
        $detail = Candidate::find($candidate_id);
        $demand = PersonelDemand::find($detail->demand_id);
        $perfections = Perfection::get();

        $page_title = "Mülakat Ekle";
        $page_description = "Adaya ait yeni bir mülakat süreci başlatabilirsiniz";

        return View::make('hr.notice.candidate.interview.add', 
        [
            'detail' => $detail,
            'candidate_id' => $candidate_id,
            'candidate_files' => array(),
            'users' => $users,
            'demand' => $demand,
            'perfections' => $perfections,
            'page_title' => $page_title,
            'page_description' => $page_description
            ]
        );
    }

    public function detail(int $interview_id): \Illuminate\Contracts\View\View
    {
        $interview = Interview::find($interview_id);
        $detail = Candidate::find($interview->candidate_id);
        $demand = PersonelDemand::find($detail->demand_id);
        $candidate_files = InterviewDocument::where('candidate_id', $interview->candidate_id)->get();
        $users = User::where('department_id', 2)->get();
        $perfection_interviews = PerfectionInterview::where('interview_id', $interview_id)->with('perfection')->get()->pluck('perfection_id');

        $perfections = PerfectionInterview::where('perfection_interviews.interview_id', $interview_id)
        ->with('perfection')
        ->leftJoin('interview_perfections', function($join) use ($interview_id){
            $join->on('interview_perfections.perfection_id', 'perfection_interviews.perfection_id');
            $join->on('interview_perfections.interview_id', $interview_id);
        })
        ->get();
        
        return View::make('hr.candidate.components.mulakat-detay', 
        [
          'detail' => $detail,
          'interview' => $interview,
          'candidate_id' => $detail->id,
          'candidate_files' => $candidate_files,
          'users' => $users,
          'demand' => $demand,
          'perfections' => $perfections,
          'perfection_interviews' => $perfection_interviews
        ]);
    }
    public function update(int $interview_id): \Illuminate\Contracts\View\View
    {
      $interview = Interview::find($interview_id);
      $detail = Candidate::find($interview->candidate_id);
      $demand = PersonelDemand::find($detail->demand_id);
      $candidate_files = InterviewDocument::where('candidate_id', $interview->candidate_id)->get();
      $users = User::where('department_id', 2)->orWhere('group_id', 1)->orWhere('group_id', 2)->get();
      $perfections = Perfection::get();
      $perfection_interviews = PerfectionInterview::where('interview_id', $interview_id)->get()->pluck('perfection_id');
      
      $page_title = "Mülakat Düzenle";
      $page_description = "Adaya ait mülakat sürecini güncelleyebilirsiniz";

      return View::make('hr.notice.candidate.interview.add', 
      [
        'detail' => $detail,
        'interview' => $interview,
        'candidate_id' => $detail->id,
        'candidate_files' => $candidate_files,
        'users' => $users,
        'demand' => $demand,
        'perfections' => $perfections,
        'perfection_interviews' => $perfection_interviews,
        'page_title' => $page_title,
        'page_description' => $page_description
      ]);
    }
    public function rate(int $interview_id): \Illuminate\Contracts\View\View
    {
      $interview = Interview::find($interview_id);
      $detail = Candidate::find($interview->candidate_id);
      $demand = PersonelDemand::find($detail->demand_id);
      $candidate_files = InterviewDocument::where('interview_id', $interview->id)->get();

      $users = User::where('department_id', 2)->get();
      $perfection_interviews = PerfectionInterview::where('interview_id', $interview_id)->with('perfection')->get()->pluck('perfection_id');
      $perfections = PerfectionInterview::selectRaw('perfection_interviews.id, interview_perfections.rating, interview_perfections.notes, perfection_interviews.perfection_id')
      ->where('perfection_interviews.interview_id', $interview_id)
      ->with('perfection')
      ->leftJoin('interview_perfections', function($join) use ($interview_id){
          $join->on('interview_perfections.perfection_id', 'perfection_interviews.perfection_id');
          $join->on('interview_perfections.interview_id', DB::Raw($interview_id));
      })
      ->get();
      
      return View::make('hr.notice.candidate.interview.rating', 
      [
        'detail' => $detail,
        'interview' => $interview,
        'candidate_id' => $detail->id,
        'candidate_files' => $candidate_files,
        'users' => $users,
        'demand' => $demand,
        'perfections' => $perfections,
        'perfection_interviews' => $perfection_interviews
      ]);
    }

    public function save(Request $request): \Illuminate\Http\JsonResponse
    {
      
        $user_id = $this->request->user()->id;
        $data = $this->request->all();
        
        $validator = Validator::make($data, [
            'hr_id' => 'required',
            'start_at' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time'
        ]);
  
        if ($validator->fails()) {
            return response()->json([
                'message' => error_formatter($validator),
                'errors' => $validator->errors(),
            ]);
        }
        
        $interview = new Interview;
        if(isset($data['id'])){
          $interview = Interview::find($data['id']);
        }
        $baslangic = date('Y-m-d', strtotime($data['start_at']));
        $baslangic_saat = $baslangic." ".date('H:i', strtotime($data['start_time']));
        $bitis_saat = $baslangic." ".date('H:i', strtotime($data['end_time']));

        $interview->candidate_id = $data['candidate_id'];
        $interview->hr_id = $data['hr_id'];
        $interview->start_at = $baslangic_saat;
        $interview->end_at = $bitis_saat;
        $interview->status = $data['status'] ?? 'mulakat_tarihi';
        $interview->save();

        $candidate = Candidate::find($interview->candidate_id);
        $candidate->status = $data['status'] ?? 'mulakat_tarihi';
        $candidate->save();

        $log = new CandidateLogs();
        $log->statusUpdates($candidate->id, $candidate->status, $data['notes'] ?? NULL, $interview->id, null);

        foreach($_POST['perfections'] as $p){
            $bul = PerfectionInterview::where('interview_id', $interview->id)->where('perfection_id', $p)->first();
            if(!$bul){
                $add_perfection = new PerfectionInterview();
                $add_perfection->interview_id = $interview->id;
                $add_perfection->perfection_id = $p;
                $add_perfection->save();
            }
        }
        $sil = PerfectionInterview::where('interview_id', $interview->id)->whereNotIn('perfection_id', $_POST['perfections'])->delete();

        $result = array(
          'status' => 1,
          'redirect' => route('interview', ['candidate_id' => $data['candidate_id']]),
          'message' => 'Başarıyla kaydedildi. Yönlendiriliyorsunuz.'
      );

      return response()->json($result);
    }

    public function saveRating(Request $request): \Illuminate\Http\JsonResponse
    {
      
        $user_id = $this->request->user()->id;
        $data = $this->request->all();
        
        $validator = Validator::make($data, [
            'notes' => 'required',
        ]);
  
        if ($validator->fails()) {
            return response()->json([
                'message' => error_formatter($validator),
                'errors' => $validator->errors(),
            ]);
        }
        
        $interview = Interview::find($data['id']);

        $interview->status = $data['status'] ?? 'mülakat tarihi verildi';
        $interview->notes = $data['notes'] ?? NULL;
        $interview->overview = $data['overview'] ?? NULL;
        $interview->save();

        $candidate = Candidate::find($interview->candidate_id);
        $candidate->status = $data['status'] ?? 'mülakat tarihi verildi';
        $candidate->save();

        $log = new CandidateLogs();
        $log->statusUpdates($candidate->id, $candidate->status, $data['notes'] ?? NULL, $interview->id, null);

        $arr = array();
        if(isset($data['file'])){
        foreach($data['file'] as $p){
            if($p['title']){
                if(isset($p['id'])){
                    $candidate_file = InterviewDocument::find($p['id']);
                }else{
                    $candidate_file = new InterviewDocument();
                }
                $candidate_file->interview_id = $data['id'];
                $candidate_file->candidate_id = $data['candidate_id'];
                $candidate_file->file = isset($p['file_input']) ? $p['file_input'] : $candidate_file->file;
                $candidate_file->title = $p['title'];
                $candidate_file->save();

                $arr[] = $candidate_file->id;
            }
        }
        $sil = InterviewDocument::whereNotIn('id', $arr)->where('candidate_id', $data['candidate_id'])->delete();
        }

        if(isset($data['rating'])){
        foreach($data['rating'] as $p){
            if($p['rating']){
                
                $bul = InterviewPerfection::where('perfection_id', $p['perfection_id'])->where('interview_id', $data['id'])->first();
                if($bul){
                    $perfection = InterviewPerfection::find($bul['id']);
                }else{
                    $perfection = new InterviewPerfection();
                }
                    
                $perfection->perfection_id = $p['perfection_id'];
                $perfection->interview_id = $data['id'];
                $perfection->user_id = $user_id;
                $perfection->rating = $p['rating'] ?? 0;
                $perfection->notes = $p['notes'] ?? null;
                $perfection->save();

                $arr[] = $p['perfection_id'];
            }
        }
        }
        
        $result = array(
          'status' => 1,
          'redirect' => route('interview', ['candidate_id' => $data['candidate_id']]),
          'message' => 'Başarıyla kaydedildi. Yönlendiriliyorsunuz.'
      );

      return response()->json($result);
    }

}
