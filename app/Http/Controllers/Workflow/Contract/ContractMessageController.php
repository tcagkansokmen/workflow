<?php declare(strict_types = 1);

namespace App\Http\Controllers\Workflow\Contract;

use App\Http\Controllers\Controller;
use App\Models\User;
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

class ContractMessageController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function save(Request $request): \Illuminate\Http\JsonResponse
    {
      $data = $this->request->all();

      $validator = Validator::make($data, [
        'compose_to' => 'required',
        'offer_id' => 'required',
        'subject' => 'required',
        'comment' => 'required',
      ]);

      $niceNames = array(
          'compose_to' => 'Alıcı',
          'offer_id' => 'Proje/Teklif',
          'subject' => 'Başlık',
          'comment' => 'Mesaj',
      );
      $validator->setAttributeNames($niceNames); 

      if ($validator->fails()) {
          return response()->json([
              'message' => error_formatter($validator),
              'errors' => $validator->errors(),
          ]);
      }

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
      
      $offer = new OfferMessage();
      $offer->user_id = $user_id ?? null;
      $offer->from = $data['from'] ?? null;
      $offer->offer_id = $data['offer_id'];
      $offer->message_to = $compose_to ?? null;
      $offer->message_cc = $compose_cc ?? null;
      $offer->message_bcc = $compose_bcc ?? null;
      $offer->subject = $data['subject'];
      $offer->type = 'contract';
      $offer->comment = $data['comment'];
      $offer->save();
      
      if(isset($data['files'])){
        foreach ($data["files"] as $file) {
          $offer_file = new OfferMessageFile();
          $offer_file->offer_message_id = $offer->id;
          $offer_file->filename = $file;
          $offer_file->save();
        }
      }
      
      $getoffer = Offer::find($offer->offer_id);

      if($getoffer->contract_status == 'Yönetici Onayladı'){
        $getoffer->contract_status = 'Müşteri Onayında';
        $getoffer->save();
      }


      $title = "Sözleşme";

        $message = "<p>Merhaba,</p><p>Sözleşmenizi incelemek, cevaplamak veya mesaj göndermek için aşağıdaki bağlantıya tıklayabilirsiniz.</p>";
        $message .= '<p><a href="'.route('musteri-sozlesme', ['hash' => $getoffer->customer_id.'.'.$getoffer->project_id.'.'.$getoffer->id]).'">Sözleşmeyi görüntülemek için tıklayınız</a></p>';
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