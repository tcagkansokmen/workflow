<?php

namespace App\Http\Controllers\Order\Printing;

use App\Http\Controllers\Controller;

use App\Models\County;
use App\Models\Customer;
use App\Models\Printing;
use App\Models\PrintingExtra;
use App\Models\PrintingMessage;
use App\Models\PrintingMessageFile;
use App\Models\PrintingMeta;

use App\Models\Project;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Core\InventoryLog;
use DataTables;
use DB;

class PrintingMetaController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {
        $page_title = "Baskı Özellikleri Listesi";
        $page_description = "";

        
        $metas = PrintingMeta::where('type', 'index')->get();

        return view('order.printing.meta.index', compact('page_title', 'page_description', 'metas'));
    }
    public function add()
    {
        $d = $this->request->all();
        $parameters = $this->request->query();
        

        $page_title = "Yeni Baskı Özelliği";
        $page_description = "";

        $input = array(
            array(
                'value' => 'input',
                'name' => "Serbest Yazım"
            ),
            array(
                'value' => 'select',
                'name' => "Çoktan Seçmeli"
            ),
        );
        $input = json_decode(json_encode($input));

        return view('order.printing.meta.add', compact('page_title', 'page_description', 'input'));
    }
    public function update($meta_id)
    {
        $d = $this->request->all();
        $parameters = $this->request->query();
        

        $page_title = "Yeni Baskı Özelliği";
        $page_description = "";

        $input = array(
            array(
                'value' => 'input',
                'name' => "Serbest Yazım"
            ),
            array(
                'value' => 'select',
                'name' => "Çoktan Seçmeli"
            ),
        );
        $input = json_decode(json_encode($input));

        $detail = PrintingMeta::find($meta_id);
        

        return view('order.printing.meta.add', compact('page_title', 'page_description', 'input', 'detail'));
    }

    public function save(Request $request)
    {
        $data = $this->request->all();
        $parameters = $this->request->query();

        $validator = Validator::make($data, [
            'title' => 'required',
            'input' => 'required',
        ]);
        

        if(isset($data['product_category_id'])){
            $validator = Validator::make($data, [
                'product_category_id' => [new ProductCategoryId],
            ]);
        }


        if($data['input']=='select'){
            $validator = Validator::make($data, [
                'values' => ['required'],
            ]);
        }

        if ($validator->fails()) {
            return response()->json([
                'message' => error_formatter($validator),
                'errors' => $validator->errors(),
            ]);
        }

        $slug = Str::slug($data['title'], '_');
        $type = true;

        if(isset($data['id'])){
            $ekle = PrintingMeta::find($data['id']);
        }else{
            $ekle = new PrintingMeta();
        }
        $ekle->key = $slug;
        $ekle->value = $data['title'] ?? null;
        $ekle->type = 'index';
        $ekle->input = $data['input'];
        $ekle->save();

        if($data['input']=='select'){
            $vals = json_decode($data['values'], true);
            $destroy = PrintingMeta::where('key', $slug)->where('type', 'options')->delete();
            foreach($vals as $e){
                $ekle = new PrintingMeta();
                $ekle->type = 'options';
                $ekle->key = $slug;
                $ekle->value = $e['value'] ?? null;
                $ekle->save();
            }
        }

        $result = array(
            'status' => 1,
            'redirect' => route('printing-meta-index'),
            'message' => 'Başarıyla kaydettiniz.',
        );
        return response()->json($result);
    }

}
