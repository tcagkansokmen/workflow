<?php

namespace App\Http\Controllers\Order\Printing;

use App\Http\Controllers\Controller;

use App\Models\County;
use App\Models\Customer;
use App\Models\Printing;
use App\Models\PrintingExtra;
use App\Models\PrintingMessage;
use App\Models\PrintingMessageFile;

use App\Models\Project;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Core\InventoryLog;
use DataTables;
use DB;

class PrintingMessageController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
    }


    public function save(Request $request)
    {
        $data = $this->request->all();
        $parameters = $this->request->query();
        $user_id = $this->request->user()->id;

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
  
        $printing = new PrintingMessage();
        $printing->user_id = $user_id;
        $printing->printing_id = $data['id'];
        $printing->message = $data['comment'];
        $printing->save();

        //broadcast(new MakeBriefComment($brief))->toOthers();

        if(isset($data['files'])){
          $ar = array();
          foreach ($data["files"] as $d) {
              $brief_file = new PrintingMessageFile();
              $brief_file->printing_message_id = $printing->id;
              $brief_file->filename = $d;
              $brief_file->save();
          }
        }

        $result = array(
            'status' => 1,
            'message' => 'Başarıyla kaydettiniz.',
            'redirect' => route('printings'),
        );
        return response()->json($result);
    }

}
