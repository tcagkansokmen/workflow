<?php declare(strict_types = 1);

namespace App\Http\Controllers\HR\Document;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\DocumentUser;
use App\Models\PersonelDemand;
use App\Models\User;
use App\Models\Department;
use App\Models\DocumentCategory;

use App\Core\OfferLogs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

use DataTables;
use \Carbon\Carbon;

class DocumentController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index(): \Illuminate\Contracts\View\View
    {
        $page_title = "Dokümanlar";
        $page_description = "Tüm dokümanları görüntüleyebilir ve yeni dokümanlar ekleyebilirsiniz";
        return View::make('hr.document.index', compact('page_title', 'page_description'));
    }

    public function json()
    {
        $d = $this->request->all();
        $parameters = $this->request->query();
        $user_id = $this->request->user()->id;
        $user = $this->request->user();
        $send_confirmation_allowed = $user->power('document', 'send_confirmation') ? true : false;
        $confirmation_allowed = $user->power('document', 'confirmation') ? true : false;
        $edit_allowed = $user->power('document', 'edit') ? true : false;
        $edit_allowed = $user->power('document', 'edit') ? true : false;
        $delete_allowed = $user->power('document', 'delete') ? true : false;
        $detail_allowed = $user->power('document', 'detail') ? true : false;

        $data = Document::whereNotNull('title')->with(['category', 'department', 'users.user']);

        return Datatables::of($data)
        ->addColumn('date_formatted', function($data){
            return Carbon::parse($data->created_at)->formatLocalized('%d %B %Y');
        })
        ->addColumn('send_confirmation_allowed', function() use ($send_confirmation_allowed){
            return $send_confirmation_allowed;
        })->addColumn('confirmation_allowed', function() use ($confirmation_allowed){
            return $confirmation_allowed;
        })->addColumn('edit_allowed', function() use ($edit_allowed){
            return $edit_allowed;
        })->addColumn('delete_allowed', function() use ($delete_allowed){
            return $delete_allowed;
        })->addColumn('detail_allowed', function() use ($detail_allowed){
            return $detail_allowed;
        })->make(true);
    }
    public function add(): \Illuminate\Contracts\View\View
    {
        $user = User::get();
        $departments = Department::get();
        $categories = DocumentCategory::get();

        $page_title = "Yeni Doküman Ekle";
        $page_description = "Dokümanın iletileceği kişileri/departmanları seçebilirsiniz.";

        return View::make('hr.document.add',[
            'users' => $user,
            'departments' => $departments,
            'categories' => $categories,
            'page_title' => $page_title,
            'page_description' => $page_description
        ]);
    }

    public function detail(int $document_id): \Illuminate\Contracts\View\View
    {
      $detail = Document::find($document_id);
      $users = DocumentUser::where('document_id', $document_id)->get();
      
      return View::make('hr.document.detail',[
        'detail' => $detail,
        'users' => $users
      ]);
    }
    public function update(int $document_id): \Illuminate\Contracts\View\View
    {
      $detail = Document::find($document_id);
      $user = User::get();
      $departments = Department::get();
      $categories = DocumentCategory::get();
      
      $page_title = "Doküman Düzenle";
      $page_description = "Dokümanın iletileceği kişileri/departmanları seçebilirsiniz.";

      return View::make('hr.document.add',[
        'detail' => $detail,
        'users' => $user,
        'departments' => $departments,
        'categories' => $categories,
        'page_title' => $page_title,
        'page_description' => $page_description
      ]);
    }

    public function save(Request $request): \Illuminate\Http\JsonResponse
    {
        $user_id = $this->request->user()->id;
        $data = $this->request->all();
        
        $validator = Validator::make($data, [
            'title' => 'required',
            'priority' => 'required',
            'notes' => 'required',
            'category_id' => 'required',
            'sign_type' => 'required'
        ]);

        $niceNames = array(
            'title' => 'Doküman başlığı',
            'priority' => 'Gizlilik derecesi',
            'notes' => 'Doküman açıklaması',
            'category_id' => 'Kategori',
            'sign_type' => 'Onay türü'
        );

        $validator->setAttributeNames($niceNames); 

        if(!isset($data['department_id'])&&!isset($data['user_id'])){
            return response()->json([
                'message' => 'Departman veya kişi seçmelisiniz.',
                'errors' => $validator->errors(),
            ]);
        }

        if(!isset($data['file'])){
            return response()->json([
                'message' => 'Dosya eklemeden devam edemezsiniz.',
                'errors' => $validator->errors(),
            ]);
        }

        if ($validator->fails()) {
            return response()->json([
                'message' => error_formatter($validator),
                'errors' => $validator->errors(),
            ]);
        }

        $document = new Document;
        if(isset($data['id'])){
          $document = Document::find($data['id']);
        }

        $file = $request->file('file');
        if(isset($data['id']) && isset($data['file'])){
          $new_contract = $document->replicate();
          $new_contract->older = $document->older ?? $document->id;
          $new_contract->title = $data['title'] ?? '';
          $new_contract->status = 'Hazırlanıyor';
          

          $new_contract->notes = $data['notes'] ?? '';
          $new_contract->priority = $data['priority'] ?? '';
          $new_contract->mobile_sign = $data['sign_type']=='mobile' ? 1 : NULL ;
          $new_contract->confirmation = $data['sign_type']=='confirmation' ? 1 : NULL ;
          if(isset($data['is_status'])){
            //$new_contract->user_id = $data['user_id'] ?? NULL;
          }else{
            $new_contract->department_id = $data['department_id'] ?? NULL;
          }

          $new_contract->category_id = $data['category_id'] ?? 1;
          $code .= isset($data['user_id']) ? 'K0' : '';
          $code .= (!$data['department_id'] && !isset($data['user_id'])) ? 'ALL' : '';
          
          $code .= '_'.date('y-m');
          $code .= '_M'.($data['sign_type'] ?? 0);
          $code .= 'I'.$data['priority'];
          $code .= '_'.time();

          if(isset($data['file'])){
            $filename = $code.".".$file->getClientOriginalExtension();
            $filePath = 'uploads/document';
            Storage::disk('s3')->putFileAs($filePath, $file, $filename, ['visibility' => 'public']);
            $new_contract->filename = $filename ?? $document->filename;
         }else{
            $new_contract->filename = $document->filename;
         }

          $new_contract->code = $code;
          $new_contract->notes = $data['notes'] ?? '';
          $new_contract->save();

          $document->is_active = 0;
          $document->save();


        if(isset($data['is_status'])){
            $ar = array();
            foreach($data['user_id'] as $usr){
                $document_user_bul = DocumentUser::where('user_id', $usr)->where('document_id', $new_contract->id)->first();
                if($document_user_bul){
                    $doc_user = DocumentUser::find($document_user_bul->id);
                }else{
                    $doc_user = new DocumentUser();
                }
                $doc_user->document_id = $new_contract->id;
                $doc_user->user_id = $usr;
                $doc_user->save();

                $ar[] = $doc_user->id;
            }
            $sil = DocumentUser::whereNotIn('user_id', $ar)->where('document_id', $new_contract->id)->delete();
        }else{
            $document->department_id = $data['department_id'] ?? NULL;
            
            $users = User::where('department_id', $data['department_id'])->get();

            foreach($users as $usr){
                $document_user_bul = DocumentUser::where('user_id', $usr['usr'])->where('document_id', $document->id)->first();
                if($document_user_bul){
                    $doc_user = DocumentUser::find($document_user_bul->id);
                }else{
                    $doc_user = new DocumentUser();
                }
                $doc_user->document_id = $document->id;
                $doc_user->user_id = $usr['id'];
                $doc_user->save();
            }
        }

        }else{

          $document->title = $data['title'] ?? '';
          $document->status = 'Hazırlanıyor';
          $code = $data['department_id'] ? 'D'.$data['department_id'] : '';
          $code .= isset($data['user_id']) ? 'K0' : '';
          $code .= (!$data['department_id'] && !isset($data['user_id'])) ? 'ALL' : '';
          
          $code .= '_'.date('y-m');
          $code .= '_M'.($data['mobile_sign'] ?? 0);
          $code .= 'I'.$data['priority'];
          $code .= '_'.time();
          
          if(isset($data['file'])){
            $filename = $code.".".$file->getClientOriginalExtension();
            $filePath = 'uploads/document';
            Storage::disk('s3')->putFileAs($filePath, $file, $filename, ['visibility' => 'public']);
            $document->filename = $filename ?? $document->filename;
         }else{
            $document->filename = $document->filename;
         }
          

          $document->code = $code;
          $document->notes = $data['notes'] ?? '';
          $document->priority = $data['priority'] ?? '';
          $document->mobile_sign = $data['sign_type']=='mobile' ? 1 : NULL;
          $document->confirmation = $data['sign_type']=='confirmation' ? 1 : NULL;
          if(isset($data['is_status'])){
            //$document->user_id = $data['user_id'] ?? NULL;
          }else{
            $document->department_id = $data['department_id'] ?? NULL;
          }
          $document->category_id = $data['category_id'] ?? 1;
          $document->is_active = 0;
          $document->save();

        if(isset($data['is_status'])){
            $ar = array();
            foreach($data['user_id'] as $usr){
                $document_user_bul = DocumentUser::where('user_id', $usr)->where('document_id', $document->id)->first();
                if($document_user_bul){
                    $doc_user = DocumentUser::find($document_user_bul->id);
                }else{
                    $doc_user = new DocumentUser();
                }
                $doc_user->document_id = $document->id;
                $doc_user->user_id = $usr;
                $doc_user->save();

                $ar[] = $doc_user->id;
            }
            $sil = DocumentUser::whereNotIn('id', $ar)->where('document_id', $document->id)->delete();
        }else{
            $document->department_id = $data['department_id'] ?? NULL;
            
            $users = User::where('department_id', $data['department_id'])->get();

            foreach($users as $usr){
                $document_user_bul = DocumentUser::where('user_id', $usr['id'])->where('document_id', $document->id)->first();
                if($document_user_bul){
                    $doc_user = DocumentUser::find($document_user_bul->id);
                }else{
                    $doc_user = new DocumentUser();
                }
                $doc_user->document_id = $document->id;
                $doc_user->user_id = $usr['id'];
                $doc_user->save();
            }
        }

        }
        

        $result = array(
          'status' => 1,
          'redirect' => route('documents'),
          'message' => 'Başarıyla kaydedildi. Yönlendiriliyorsunuz.'
      );

      return response()->json($result);
    }

    public function read(Request $request): \Illuminate\Http\JsonResponse
    {
        $user_id = $this->request->user()->id;
        $data = $this->request->all();
        
        $bul = DocumentUser::where('user_id', $user_id)->where('document_id', $data['document_id'])->first();
        $document = DocumentUser::find($bul->id);
        $document->is_read = 1;
        $document->save();

        $result = array(
          'status' => 1,
          'message' => 'Başarıyla kaydedildi.'
      );

      return response()->json($result);
    }
    public function sign(Request $request): \Illuminate\Http\JsonResponse
    {
        $user_id = $this->request->user()->id;
        $data = $this->request->all();
        
        $bul = DocumentUser::where('user_id', $user_id)->where('document_id', $data['document_id'])->first();
        $document = DocumentUser::find($bul->id);
        $document->is_signed = 1;
        $document->save();

        $result = array(
          'status' => 1,
          'message' => 'Başarıyla kaydedildi.'
      );

      return response()->json($result);
    }

    function delete(int $id){
        $find = Announcement::find($id);
        $find->delete();

        $result = array(
            'status' => 1,
            'message' => 'Başarıyla silindi. Yönlendiriliyorsunuz.'
        );
  
        return response()->json($result);
    }
    public function updateState(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->request->validate([
            'kt_user_add_user_avatar' => 'nullable'
        ]);


        $user_id = $this->request->user()->id;
        $data = $this->request->all();

        $document = Document::find($data['id']);
        $document->status = $data['value'];
        $document->description = $data['aciklama'] ?? NULL;
        $document->is_active = $data['value']=='Onaylandı' ? '1' : ($data['value']=='kaldırıldı' ? 0 : 0);
        $document->save();


        $result = array(
          'status' => 1,
          'message' => 'Başarıyla kaydedildi. Yönlendiriliyorsunuz.'
      );

      return response()->json($result);
    }
}
