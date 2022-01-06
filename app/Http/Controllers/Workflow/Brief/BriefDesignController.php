<?php declare(strict_types = 1);

namespace App\Http\Controllers\Workflow\Brief;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Brief;
use App\Models\BriefType;
use App\Models\BriefFile;
use App\Models\BriefDesignDetail;
use App\Models\BriefDesignComment;
use App\Models\BriefDesignCommentFile;
use App\Events\MakeDesignComment;

use App\Models\BriefDesign;

use App\Models\Fair;
use App\Models\Firm;
use App\Models\FirmOfficer;
use App\Models\FirmFair;
use App\Models\Log;
use App\Models\Sector;
use App\Models\StandType;
use App\Models\FairsSector;
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

class BriefDesignController extends Controller
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
      ]);

      if ($validator->fails()) {
          return response()->json([
              'message' => 'Lütfen tüm zorunlu alanları doldurun',
              'errors' => $validator->errors(),
          ]);
      }

      $user_id = $this->request->user()->id;

      $finddesigns = BriefDesign::where('brief_id', $data['id'])->count();

      $brief = new BriefDesign();
      $brief->designer_id = $user_id;
      $brief->brief_id = $data['id'];
      $brief->comment = $data['comment'] ?? null;
      $brief->is_active = 1;
      $brief->save();

      $yoket = BriefDesign::where('brief_id', $data['id'])->where('id', '!=', $brief->id)->update(array('is_active' => 0));

      if(isset($data['files'])){
        $ar = array();
        foreach ($data["files"] as $d) {
            $brief_file = new BriefDesignDetail();
            $brief_file->brief_id = $data['id'];
            $brief_file->design_id = $brief->id;
            $brief_file->file = $d;
            $brief_file->save();
        }
      }

      $br = Brief::find($data['id']);
      $br->status = $finddesigns ? 'Revize MT Onayında' : 'MT Onayında';
      $br->save();
      
      $result = array(
          'status' => 1,
          'message' => 'Başarıyla kaydedildi. Yönlendiriliyorsunuz.'
      );
      return response()->json($result);
    }

    public function saveComment(Request $request): \Illuminate\Http\JsonResponse
    {
      $data = $this->request->all();

      $user_id = $this->request->user()->id;
      
      $brief = new BriefDesignComment();
      $brief->user_id = $user_id;
      $brief->brief_id = $data['brief_id'];
      $brief->design_id = $data['id'];
      $brief->comment = $data['comment'];
      $brief->save();

      broadcast(new MakeDesignComment($brief))->toOthers();
      
      if(isset($data['files'])){
        $ar = array();
        foreach ($data["files"] as $d) {
            $brief_file = new BriefDesignCommentFile();
            $brief_file->brief_design_comment_id = $brief->id;
            $brief_file->file = $d;
            $brief_file->save();
        }
      }

      $detail = Brief::find($data['brief_id']);
      
      $result = array(
        'status' => 1,
        'message' => 'Başarıyla kaydedildi'
      );
    return response()->json($result);
    }

}