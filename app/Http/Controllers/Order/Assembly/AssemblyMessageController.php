<?php

namespace App\Http\Controllers\Order\Assembly;

use App\Http\Controllers\Controller;

use App\Models\County;
use App\Models\Customer;
use App\Models\Assembly;
use App\Models\AssemblyExtra;
use App\Models\AssemblyMessage;
use App\Models\AssemblyMessageFile;

use App\Models\Project;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Core\InventoryLog;
use DataTables;
use DB;

class AssemblyMessageController extends Controller
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
  
        $assembly = new AssemblyMessage();
        $assembly->user_id = $user_id;
        $assembly->assembly_id = $data['id'];
        $assembly->message = $data['comment'];
        $assembly->save();

        //broadcast(new MakeBriefComment($brief))->toOthers();

        if(isset($data['files'])){
          $ar = array();
          foreach ($data["files"] as $d) {
              $brief_file = new AssemblyMessageFile();
              $brief_file->assembly_message_id = $assembly->id;
              $brief_file->filename = $d;
              $brief_file->save();
          }
        }

        $result = array(
            'status' => 1,
            'message' => 'Başarıyla kaydettiniz.',
            'redirect' => route('assemblys'),
        );
        return response()->json($result);
    }

}
