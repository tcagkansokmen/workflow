<?php

namespace App\Http\Controllers\Order\Exploration;

use App\Http\Controllers\Controller;

use App\Models\County;
use App\Models\Customer;
use App\Models\Exploration;
use App\Models\ExplorationExtra;
use App\Models\ExplorationMessage;
use App\Models\ExplorationMessageFile;

use App\Models\Project;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Core\InventoryLog;
use DataTables;
use DB;

class ExplorationMessageController extends Controller
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
  
        $exploration = new ExplorationMessage();
        $exploration->user_id = $user_id;
        $exploration->exploration_id = $data['id'];
        $exploration->message = $data['comment'];
        $exploration->save();

        //broadcast(new MakeBriefComment($brief))->toOthers();

        if(isset($data['files'])){
          $ar = array();
          foreach ($data["files"] as $d) {
              $brief_file = new ExplorationMessageFile();
              $brief_file->exploration_message_id = $exploration->id;
              $brief_file->filename = $d;
              $brief_file->save();
          }
        }

        $result = array(
            'status' => 1,
            'message' => 'Başarıyla kaydettiniz.',
            'redirect' => route('explorations'),
        );
        return response()->json($result);
    }

}
