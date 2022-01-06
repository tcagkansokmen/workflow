<?php

namespace App\Http\Controllers\Order\Production;

use App\Http\Controllers\Controller;

use App\Models\County;
use App\Models\Customer;
use App\Models\Production;
use App\Models\ProductionExtra;
use App\Models\ProductionMessage;
use App\Models\ProductionMessageFile;

use App\Models\Project;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Core\InventoryLog;
use DataTables;
use DB;

class ProductionMessageController extends Controller
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
  
        $production = new ProductionMessage();
        $production->user_id = $user_id;
        $production->production_id = $data['id'];
        $production->message = $data['comment'];
        $production->save();

        //broadcast(new MakeBriefComment($brief))->toOthers();

        if(isset($data['files'])){
          $ar = array();
          foreach ($data["files"] as $d) {
              $brief_file = new ProductionMessageFile();
              $brief_file->production_message_id = $production->id;
              $brief_file->filename = $d;
              $brief_file->save();
          }
        }

        $result = array(
            'status' => 1,
            'message' => 'Başarıyla kaydettiniz.',
            'redirect' => route('productions'),
        );
        return response()->json($result);
    }

}
