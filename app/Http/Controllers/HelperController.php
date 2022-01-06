<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\County;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Core\InventoryLog;
use DataTables;
use DB;

class HelperController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function counties()
    {
      $parameters = $this->request->query();
      
      $counties = County::select('id as value', 'county as name')->where('city_id', $parameters['city'])->get();

      return response()->json($counties);
    }
}
