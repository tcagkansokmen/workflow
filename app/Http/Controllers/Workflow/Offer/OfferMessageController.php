<?php declare(strict_types = 1);

namespace App\Http\Controllers\Workflow\Offer;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Project;
use App\Models\Offer;
use App\Models\OfferMessage;
use App\Models\OfferMessageFile;
use App\Notifications\NewVendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use DB;
use DataTables;
use Mail;
use Khsing\World\World;
use Khsing\World\Models\Continent;
use Khsing\World\Models\Country;
use Illuminate\Support\Arr;

class OfferMessageController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function save(Request $request): \Illuminate\Http\JsonResponse
    {
      $data = $this->request->all();

      $user_id = $this->request->user()->id ?? null;

      $compose_to = json_decode($data['compose_to'], true);
      $compose_to = implode(",", Arr::pluck($compose_to, 'value'));

      if(isset($data['compose_cc'])){
        $compose_cc = json_decode($data['compose_cc'], true);
        $compose_cc = implode(",", Arr::pluck($compose_cc, 'value'));
      }else{
        $compose_cc = null;
      }

      if(isset($data['compose_bcc'])){
        $compose_bcc = json_decode($data['compose_bcc'], true);
        $compose_bcc = implode(",", Arr::pluck($compose_bcc, 'value'));
      }else{
        $compose_bcc = null;
      }

      $off = Offer::find($data['offer_id']);
      $project = Offer::find($data['offer_id']);
      
      $offer = new OfferMessage();
      $offer->user_id = $user_id ?? null;
      $offer->from = $data['from'] ?? null;
      $offer->offer_id = $data['offer_id'];
      $offer->message_to = $compose_to ?? null;
      $offer->message_cc = $compose_cc ?? null;
      $offer->message_bcc = $compose_bcc ?? null;
      $offer->subject = $data['subject'];
      $offer->type = 'offer';
      $offer->comment = $data['comment'];
      $offer->save();
      
      /*
      if(isset($data['files'])){
        foreach ($data["files"] as $file) {
          $offer_file = new OfferMessageFile();
          $offer_file->offer_message_id = $offer->id;
          $offer_file->filename = $file;
          $offer_file->save();
        }
      }
      */
      
      $getoffer = Offer::find($offer->offer_id);
      if($getoffer->status == 'Yönetici Onayladı'){
        $getoffer->status = 'Müşteri Onayında';
        $getoffer->save();
      }

      $title = $project->title." Teklifi";

        $message = '<p>Merhaba,</p><p>'.$project->title." için hazırlanmış fiyat teklifinizi incelemek, cevaplamak veya mesaj göndermek için aşağıdaki bağlantıya tıklayabilirsiniz.</p>";
        $message .= '<p><a href="'.route('musteri-teklif', ['hash' => $off->customer_id.'.'.$off->project_id.'.'.$off->id]).'">Teklifi görüntülemek için tıklayınız</a></p>';
        $subject = $data['subject'];
        
        $ddd = array('name'=>$data['subject'], 'body' => $message);

          Mail::send('emails.mail', $ddd, function($message) use ($compose_to, $compose_cc, $compose_bcc, $title, $subject) {
          $message->to(explode(',', $compose_to));
          isset($compose_cc) ? $message->cc(explode(',', $compose_cc)) : '';
          isset($compose_bcc) ? $message->bcc(explode(',', $compose_bcc)) : '';
          
          $message->subject($subject);
          
          $message->from('hello@b166er.co', env('APP_NAME'));
          });
        

      $result = array(
        'status' => 1,
        'message' => 'Başarıyla kaydedildi'
      );
    return response()->json($result);
    }
    
}