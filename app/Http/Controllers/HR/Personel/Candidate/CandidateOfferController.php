<?php declare(strict_types = 1);

namespace App\Http\Controllers\HR\Personel\Candidate;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\CandidateOffer;
use App\Models\PersonelDemand;
use App\Models\User;
use App\Core\CandidateLogs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CandidateOfferController extends Controller
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
        $offers = CandidateOffer::where('candidate_id', $candidate_id)
        ->orderBy('created_at', 'desc')
        ->get();

        $page_title = "Teklifler";
        $page_description = "Görüşmesi başarılı tamamlanan adaya yapılan teklifler";
        
        return View::make('hr.notice.candidate.offer.index',[
          'detail' => $detail,
          'offers' => $offers,
          'candidate_id' => $candidate_id,
          'page_title' => $page_title,
          'page_description' => $page_description
        ]);
    }
    public function add(int $candidate_id): \Illuminate\Contracts\View\View
    {
        $detail = Candidate::find($candidate_id);

        $page_title = $detail->name." ".$detail->surname." adaya ait teklif";
        $page_description = "Ekleyebilirsiniz";

        return View::make('hr.notice.candidate.offer.add',[
          'detail' => $detail,
          'candidate_id' => $candidate_id,
          'page_title' => $page_title,
          'page_description' => $page_description
        ]);
    }

    public function update(int $offer_id): \Illuminate\Contracts\View\View
    {
        $offer = CandidateOffer::find($offer_id);
        $detail = Candidate::find($offer->candidate_id);
        
        $page_title = $detail->name." ".$detail->surname." adaya ait teklif";
        $page_description = "Düzenleyebilirsiniz";

        return View::make('hr.notice.candidate.offer.add',[
          'detail' => $detail,
          'candidate_id' => $offer->candidate_id,
          'offer' => $offer,
          'page_title' => $page_title,
          'page_description' => $page_description
        ]);
    }


    public function save(Request $request): \Illuminate\Http\JsonResponse
    {
      
        $user_id = $this->request->user()->id;
        $data = $this->request->all();
        
        
        $offer = new CandidateOffer;
        if(isset($data['id'])){
          $offer = CandidateOffer::find($data['id']);
        }else{
          $offer->status = $data['status'] ?? 'teklif_verildi';
        }
        $offer->candidate_id = $data['candidate_id'];
        $offer->price = str_replace(",", ".", str_replace(".", "", $data['price']));
        $offer->offer_date = isset($data['offer_date']) ? date('Y-m-d', strtotime($data['offer_date'])) : NULL;
        $offer->notes = $data['notes'] ?? NULL;
        $offer->save();

        $candidate = Candidate::find($data['candidate_id']);
        $candidate->status = "teklif_verildi";
        $candidate->save();

        $log = new CandidateLogs();
        $log->statusUpdates($candidate->id, $candidate->status, null, null, $offer->id);

        $result = array(
          'status' => 1,
          'message' => 'Başarıyla kaydedildi. Yönlendiriliyorsunuz.',
          'redirect' => route('candidate-offer', ['candidate_id' => $data['candidate_id']]),
      );

      return response()->json($result);
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

    public function updateState(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->request->validate([
            'kt_user_add_user_avatar' => 'nullable'
        ]);

        $user_id = $this->request->user()->id;
        $data = $this->request->all();

        $candidate_offer = CandidateOffer::find($data['id']);
        $candidate_offer->status = $data['value'];
        $candidate_offer->save();

        $candidate = Candidate::find($candidate_offer->candidate_id);
        $candidate->status = $data['value'];
        $candidate->save();

        $log = new CandidateLogs();
        $log->statusUpdates($candidate->id, $candidate->status, null, null, $candidate_offer->id);

        $result = array(
          'status' => 1,
          'message' => 'Başarıyla kaydedildi. Yönlendiriliyorsunuz.'
      );

      return response()->json($result);
    }
}
