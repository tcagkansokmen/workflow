<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\User;
use App\Models\UserGroup;
use App\Models\Belonging;
use App\Models\Permission;
use App\Models\Earnest;
use App\Models\Cost;
use App\Rules\UserId;
use App\Http\Resources\CostResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use DataTables;
use DB;

class VehicleController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
        $types = array(
            array(
                'value' => "Ticari",
                'name' => "Ticari"
            ),
            array(
                'value' => "Bireysel",
                'name' => "Bireysel"
            ),
        );
        $types = json_decode(json_encode($types));
        $this->types = $types;
    }

    public function index()
    {
      $parameters = $this->request->query();

      $detail = Vehicle::orderBy('id');

      $page_title = 'Araç Bilgileri';
      $page_description = 'Araçları görüntüleyip güncelleyebilirsiniz.';

      if(isset($parameters['care'])){
        $page_title = 'Bakımı Yaklaşanlar';
        $page_description = 'Bakımı yaklaşan araçlarınızı görebilirsiniz.';

        $detail->where('care_date', '<=', date('Y-m-d', strtotime('+1 month')));
      }

      if(isset($parameters['kasko'])){
        $page_title = 'Kasko Bitişi Yaklaşanlar';
        $page_description = 'Kasko bitiş tarihi yaklaşan araçlarınızı görebilirsiniz.';

        $detail->where('kasko_end', '<=', date('Y-m-d', strtotime('+1 month')));
      }

      if(isset($parameters['insurance'])){
        $page_title = 'Sigorta Bitişi Yaklaşanlar';
        $page_description = 'Sigorta bitiş tarihi yaklaşan araçlarınızı görebilirsiniz.';

        $detail->where('insurance_end', '<=', date('Y-m-d', strtotime('+1 month')));
      }

      if(isset($parameters['loan'])){
        $page_title = 'Kiralık Süresi Bitimi Yaklaşanlar';
        $page_description = 'Kiralık süresi bitimi yaklaşan araçlarınızı görebilirsiniz.';

        $detail->where('loan_end', '<=', date('Y-m-d', strtotime('+1 month')));
      }

      $detail = $detail->get();

      return view('management.vehicles.index', compact('page_title', 'page_description', 'detail'));
    }
    public function add()
    {
        $parameters = $this->request->query();

        $types = $this->types;

        $page_title = 'Araç Bilgileri';
        $page_description = 'Araç bilgilerinizi görüntüleyip güncelleyebilirsiniz.';

        return view('management.vehicles.add', compact('page_title', 'page_description', 'types'));
    }


    public function update(int $vehicle_id)
    {
        $parameters = $this->request->query();

        $detail = Vehicle::find($vehicle_id);

        $types = $this->types;

        $page_title = 'Kullanıcı Bilgileri';
        $page_description = 'Kullanıcı bilgilerinizi görüntüleyip güncelleyebilirsiniz.';

        return view('management.vehicles.add', compact('page_title', 'page_description', 'detail', 'types'));
    }

    public function save(Request $request)
    {
        $data = $this->request->all();

        $validator = Validator::make($data, [
            'brand' => 'required',
            'model' => 'required',
            'plate' => 'required',
            'loan_end' => 'required_if:is_loan,1',
            'kasko_start' => 'required_without:is_loan',
            'kasko_end' => 'required_without:is_loan',
            'insurance_start' => 'required_without:is_loan',
            'insurance_end' => 'required_without:is_loan',
            'care_date' => 'required_without:is_loan',
        ]);

        $niceNames = array(
          'brand' => 'Marka',
          'model' => 'Model',
          'plate' => 'Plaka',
          'loan_end' => 'Kiralama bitiş tarihi',
          'kasko_start' => 'Kasko başlangıç tarihi',
          'kasko_end' => 'Kasko bitiş tarihi',
          'insurance_start' => 'Sigorta başlangıç tarihi',
          'insurance_end' => 'Sigorta bitiş tarihi',
          'care_date' => 'Bakım tarihi',
      );

      $validator->setAttributeNames($niceNames); 

      if ($validator->fails()) {
          return response()->json([
              'message' => error_formatter($validator),
              'errors' => $validator->errors(),
          ]);
      }

      if(isset($data['id'])){
        $vehicle = Vehicle::find($data['id']);
      }else{
        $vehicle = new Vehicle();
        $vehicle->is_active = 1;
      }
      $vehicle->brand = $data['brand'];
      $vehicle->model = $data['model'];
      $vehicle->plate = $data['plate'];
      $vehicle->type = $data['type'] ?? 'Ticari';
      $vehicle->is_loan = $data['is_loan'] ?? 0;
      $vehicle->loan_end = isset($data['loan_end']) ? date_deformatter($data['loan_end']) : null;
      $vehicle->kasko_start = isset($data['kasko_start']) ? date_deformatter($data['kasko_start']) : null;
      $vehicle->kasko_end = isset($data['kasko_end']) ? date_deformatter($data['kasko_end']) : null;
      $vehicle->insurance_start = isset($data['insurance_start']) ? date_deformatter($data['insurance_start']) : null;
      $vehicle->insurance_end = isset($data['insurance_end']) ? date_deformatter($data['insurance_end']) : null;
      $vehicle->care_date = isset($data['care_date']) ? date_deformatter($data['care_date']) : null;
      $vehicle->save();

      $result = array(
          'status' => 1,
          'redirect' => route('vehicles'),
          'message' => 'Başarıyla kaydettiniz.'
      );
      return response()->json($result);
    }

    public function passive(int $user_id): \Illuminate\Http\JsonResponse
    {
        $user = Vehicle::find($user_id);
        $user->is_active = 0;
        $user->save();

        $result = array(
            'status' => 1,
            'message' => 'Kullanıcıyı pasife aldınız.'
        );
        return response()->json($result);
    }
    public function active(int $user_id): \Illuminate\Http\JsonResponse
    {
        $user = Vehicle::find($user_id);
        $user->is_active = $user;
        $user->save();

        $result = array(
            'status' => 1,
            'message' => 'Başarıyla aktife aldınız.'
        );
        return response()->json($result);
    }
}
