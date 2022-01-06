<?php declare(strict_types = 1);

namespace App\Http\Controllers\Order\Exploration;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Exploration;
use App\Models\ExplorationFile;
use App\Models\ExplorationDesignDetail;
use App\Models\ExplorationDesignComment;
use App\Models\ExplorationDesignCommentFile;
use App\Events\MakeDesignComment;

use App\Models\ExplorationDesign;

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

class ExplorationDesignController extends Controller
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

      $exploration = new ExplorationDesign();
      $exploration->designer_id = $user_id;
      $exploration->exploration_id = $data['id'];
      $exploration->comment = $data['comment'] ?? null;
      $exploration->is_active = 1;
      $exploration->save();

      $yoket = ExplorationDesign::where('exploration_id', $data['id'])->where('id', '!=', $exploration->id)->update(array('is_active' => 0));

      if(isset($data['files'])){
        $ar = array();
        foreach ($data["files"] as $d) {
            $exploration_file = new ExplorationDesignDetail();
            $exploration_file->exploration_id = $data['id'];
            $exploration_file->design_id = $exploration->id;
            $exploration_file->file = $d;
            $exploration_file->save();
        }
      }

      $exp = Exploration::find($data['id']);
      $exp->status = 'Keşif Tamamlandı';
      $exp->save();
      
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
      
      $exploration = new ExplorationDesignComment();
      $exploration->user_id = $user_id;
      $exploration->exploration_id = $data['exploration_id'];
      $exploration->design_id = $data['id'];
      $exploration->comment = $data['comment'];
      $exploration->save();
      
      if(isset($data['files'])){
        $ar = array();
        foreach ($data["files"] as $d) {
            $exploration_file = new ExplorationDesignCommentFile();
            $exploration_file->exp_design_comment_id = $exploration->id;
            $exploration_file->file = $d;
            $exploration_file->save();
        }
      }

      $detail = Exploration::find($data['exploration_id']);
      
      $result = array(
        'status' => 1,
        'message' => 'Başarıyla kaydedildi'
      );
    return response()->json($result);
    }

}