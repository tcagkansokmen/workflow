<?php declare(strict_types = 1);

namespace App\Http\Controllers\HR\Poll;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Customer;
use App\Models\Event;
use App\Models\Form;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ModuleController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index(): \Illuminate\Contracts\View\View
    {
        $data = $this->request->all();
        $all = $data['all'];
        return View::make('module.'.$data['type'],
        [
          'data' => $all,
          'all' => array(
            'is_required' => $data['is_required'] ?? false,
            'label' => '',
            'name' => uniqid(),
            'class' => 'class',
            'values' => ''
          )
        ]);
    }

    public function create(): \Illuminate\Contracts\View\View
    {
        $data = $this->request->all();
        $values = $data['values'] ?? '';

        if($values){
          $values = implode(', ', array_column(json_decode($values), 'value'));
        }

        return View::make('module.'.$data['type'],
        [
          'all' => array(
            'is_required' => $data['is_required'] ?? false,
            'label' => $data['label'],
            'name' => $data['name'] ?? uniqid(),
            'class' => $data['input_type'] ?? '',
            'values' => $values,
          )
        ]);
    }
}
