<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use App\Models\UserGroup;
use App\Models\Workflow;
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

class WorkflowController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {
        $parameters = $this->request->query();

        $detail = User::orderBy('is_active', 'desc')->orderBy('id')->get();

        $page_title = 'Onay Süreçleri';
        $page_description = 'Onay süreçlerini düzenleyebilirsiniz.';
        $groups = UserGroup::select('id as value', 'name')->orderBy('id')->get();

        $offers = Workflow::where('key', 'offer')->wherenull('parent_sef')->get();

        return view('management.workflow.index', compact('page_title', 'page_description', 'detail', 'offers', 'groups'));
    }
    public function button()
    {
        $parameters = $this->request->query();

        $uniqid = $parameters['uniqid'];
        $groups = UserGroup::select('id as value', 'name')->orderBy('id')->get();
        $uniqid_2 = uniqid();

        return view('management.workflow.button', compact('groups', 'uniqid', 'uniqid_2'));
    }
    public function item()
    {
        $uniqid = uniqid();
        return view('management.workflow.kanban-item', compact('uniqid'));
    }
    public function save(Request $request): \Illuminate\Http\JsonResponse
    {
        $user_id = $this->request->user()->id;
        $parameters = $this->request->all();

        $data = $parameters['data'];

        foreach($data as $key => $value){
            $name = $value['name'];
            $color = $value['color'];
            $parent_sef = $value['sef'];
            $is_editable = $value['is_editable'] ?? null;
            $is_done = $value['is_done'] ?? null;

            if(isset($value['item_id'])){
                $new = Workflow::find($value['item_id']);
            }else{
                $new = new Workflow();
            }
            $new->key = $parameters['key'];
            $new->sef = $parent_sef;
            $new->title = $name;
            $new->color = $color;
            $new->is_editable = $is_editable;
            $new->is_done = $is_done;
            $new->save();

            if(isset($value['button'])){
                foreach($value['button'] as $k => $v){
                    $sef = $k;
                    $button_name = $v['name'];
                    $button_color = $v['color'];
                    $button_type = $v['type'];
                    $button_redirect = $v['redirect'];
                    
                    foreach($v['group'] as $g){
                        if(isset($value['button_id'])){
                            $new = Workflow::find($value['button_id']);
                        }else{
                            $new = new Workflow();
                        }
                        $new->user_group_id = $g;
                        $new->key = $parameters['key'];
                        $new->parent_sef = $parent_sef;
                        $new->sef = $sef;
                        $new->title = $button_name;
                        $new->next = $button_redirect;
                        $new->type = $button_type;
                        $new->color = $button_color;
                        $new->save();
                    }
                }
            }
        }

        $result = array(
            'status' => 0,
            'data' => $parameters,
            'message' => 'Başarıyla kaydedildi, yönlendiriliyorsunuz.'
          );

      return response()->json($result);
    }
}
