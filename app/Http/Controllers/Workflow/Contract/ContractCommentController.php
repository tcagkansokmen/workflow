<?php declare(strict_types = 1);

namespace App\Http\Controllers\Workflow\Contract;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Offer;
use App\Models\OfferComment;
use App\Notifications\NewVendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use DB;
use DataTables;
use Khsing\World\World;
use Khsing\World\Models\Continent;
use Khsing\World\Models\Country;

class ContractCommentController extends Controller
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
        'id' => 'required',
        'comment' => 'required',
      ]);

      $niceNames = array(
          'id' => 'ID',
          'comment' => 'Yorum',
      );
      $validator->setAttributeNames($niceNames); 

      if ($validator->fails()) {
          return response()->json([
              'message' => error_formatter($validator),
              'errors' => $validator->errors(),
          ]);
      }
      
      $user_id = $this->request->user()->id;
      
      $offer = new OfferComment();
      $offer->user_id = $user_id;
      $offer->offer_id = $data['id'];
      $offer->comment = $data['comment'];
      $offer->type = $data['type'];
      $offer->save();
      
      $detail = Offer::find($data['id']);
      
      $result = array(
        'status' => 1,
        'message' => 'Başarıyla kaydedildi'
      );
    return response()->json($result);
    }
    
}