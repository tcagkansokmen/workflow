<?php declare(strict_types = 1);

namespace App\Http\Controllers\Workflow\Brief;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Brief;
use App\Models\BriefComment;
use App\Models\BriefCommentFile;
use App\Notifications\NewVendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Events\MakeBriefComment;
use DB;
use DataTables;
use Khsing\World\World;
use Khsing\World\Models\Continent;
use Khsing\World\Models\Country;

class BriefCommentController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function save(Request $request): \Illuminate\Http\JsonResponse
    {
      $user = Auth::user();
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
      
      $brief = new BriefComment();
      $brief->user_id = $user_id;
      $brief->brief_id = $data['id'];
      $brief->comment = $data['comment'];
      $brief->save();

      broadcast(new MakeBriefComment($brief))->toOthers();

      if(isset($data['files'])){
        $ar = array();
        foreach ($data["files"] as $d) {
            $brief_file = new BriefCommentFile();
            $brief_file->brief_comment_id = $brief->id;
            $brief_file->file = $d;
            $brief_file->save();
        }
      }


      $detail = Brief::find($data['id']);
      
      $result = array(
        'status' => 1,
        'message' => 'Başarıyla kaydedildi'
      );
    return response()->json($result);
    }
}