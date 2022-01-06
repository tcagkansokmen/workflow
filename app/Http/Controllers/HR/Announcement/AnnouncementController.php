<?php declare(strict_types = 1);

namespace App\Http\Controllers\HR\Announcement;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\AnnouncementUser;
use App\Models\AnnouncementCategory;
use App\Models\PersonelDemand;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Department;
use App\Core\OfferLogs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use DataTables;
use \Carbon\Carbon;

class AnnouncementController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    private function valueMatch($kosul, $val){
        
        if($kosul == 'icerir'){
            return '%'.$val.'%';
        }elseif($kosul == 'ile_baslar'){
            return $val.'%';
        }elseif($kosul == 'esittir'){
            return $val;
        }elseif($kosul == 'kucuktur'){
            return $val;
        }elseif($kosul == 'buyuktur'){
            return $val;
        }

    }

    public function index(): \Illuminate\Contracts\View\View
    {
        $page_title = "Duyurular";
        $page_description = "Tüm duyuruları görüntüleyebilir ve yeni duyurular ekleyebilirsiniz";
        return View::make('hr.announce.index', compact('page_title', 'page_description'));
    }

    public function json()
    {
        $d = $this->request->all();
        $parameters = $this->request->query();
        $user_id = $this->request->user()->id;
        $user = $this->request->user();
        $send_confirmation_allowed = $user->power('announcement', 'send_confirmation') ? true : false;
        $confirmation_allowed = $user->power('announcement', 'confirmation') ? true : false;
        $edit_allowed = $user->power('announcement', 'edit') ? true : false;
        $delete_allowed = $user->power('announcement', 'delete') ? true : false;
        $detail_allowed = $user->power('announcement', 'detail') ? true : false;

        $data = Announcement::with(['user', 'category', 'department']);
        
        return Datatables::of($data)
        ->addColumn('start_at_formatted', function($data){
            return Carbon::parse($data->start_at)->formatLocalized('%d %B %Y');
        })
        ->addColumn('end_at_formatted', function($data){
            return $data->end_at ? Carbon::parse($data->end_at)->formatLocalized('%d %B %Y') : '';
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
        $categories = AnnouncementCategory::get();

        $page_title = "Yeni Duyuru";
        $page_description = "Yeni duyuru ekleyebilirsiniz.";

        return View::make('hr.announce.add',[
            'users' => $user,
            'departments' => $departments,
            'categories' => $categories,
            'page_title' => $page_title,
            'page_description' => $page_description
        ]);
    }

    public function detail(int $announcement_id): \Illuminate\Contracts\View\View
    {
      $detail = Announcement::find($announcement_id);
      
      return View::make('hr.announce.detail',[
        'detail' => $detail
      ]);
    }
    public function update(int $announcement_id): \Illuminate\Contracts\View\View
    {
      $detail = Announcement::find($announcement_id);
      $user = User::get();
      $departments = Department::get();
      $categories = AnnouncementCategory::get();

      $page_title = "Duyuru Düzenle";
      $page_description = "Mevcut duyurunuzu düzenleyebilirsiniz.";

      return View::make('hr.announce.add',[
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
            'department_id' => 'required',
            'category_id' => 'required',
            'title' => 'required',
            'description' => 'required',
            'start_at' => 'required',
            'end_at' => 'nullable'
        ]);
  
        $niceNames = array(
            'department_id' => 'Departman',
            'category_id' => 'Kategori',
            'title' => 'Duyuru Başlığı',
            'description' => 'Açıklama',
            'start_at' => 'Başlangıç',
            'end_at' => 'Yayından Kalkış'
        );

        $validator->setAttributeNames($niceNames); 

        if ($validator->fails()) {
            return response()->json([
                'message' => error_formatter($validator),
                'errors' => $validator->errors(),
            ]);
        }

        if(date_deformatter($data['start_at'])<date('Y-m-d')){
            return response()->json([
                'message' => 'Yayın tarihi günümüzden tarihinden küçük olamaz.',
                'errors' => $validator->errors(),
            ]);
        }

        if(isset($data['end_at'])){
            if(date_deformatter($data['end_at'])<date_deformatter($data['start_at'])||date_deformatter($data['end_at'])<date('Y-m-d')){
                return response()->json([
                    'message' => 'Yayından kaldırılma tarihi günümüzden veya başlangıç tarihinden küçük olamaz.',
                    'errors' => $validator->errors(),
                ]);
            }
        }

        $announcement = new Announcement;
        if(isset($data['id'])){
            $announcement = Announcement::find($data['id']);
        }

        $baslangic = isset($data['start_at']) ? date('Y-m-d H:i', strtotime($data['start_at'])) : date('Y-m-d H:i');
        $bitis = isset($data['end_at']) ? date('Y-m-d H:i', strtotime($data['end_at'])) : date('Y-m-d H:i', strtotime('+10 year'));

        $announcement->user_id = $user_id;
        $announcement->title = $data['title'] ?? '';
        $announcement->description = $data['description'] ?? '';
        $announcement->type = $data['type'] ?? '';
        $announcement->start_at = $baslangic ?? '';
        $announcement->end_at = $bitis ?? '';
        $announcement->status = 'Hazırlanıyor';
        $announcement->category_id = $data['category_id'] ?? 1;
        $announcement->department_id = $data['department_id'] == "Herkes" ? 0 : $data['department_id'];

        $file = $request->file('photo');
        if(isset($data['photo'])){
            $filename = uniqid().".".$file->getClientOriginalExtension();
            $filePath = 'uploads/announcement';
            Storage::disk('s3')->putFileAs($filePath, $file, $filename, ['visibility' => 'public']);
            $announcement->photo = $filename ?? $announcement->filename;
        }

        $file = $request->file('file');
        if(isset($data['file'])){
            $filename = uniqid().".".$file->getClientOriginalExtension();
            $filePath = 'uploads/announcement';
            Storage::disk('s3')->putFileAs($filePath, $file, $filename, ['visibility' => 'public']);
            $announcement->file = $filename ?? $announcement->filename;
        }

        $announcement->end_at = $bitis ?? '';
        $announcement->save();

        $result = array(
          'status' => 1,
          'redirect' => route('announcements'),
          'message' => 'Başarıyla kaydedildi. Yönlendiriliyorsunuz.'
      );

      return response()->json($result);
    }

    public function read(Request $request): \Illuminate\Http\JsonResponse
    {
        $user_id = $this->request->user()->id;
        $data = $this->request->all();
        
        $announcement = new AnnouncementUser;
        $announcement->user_id = $user_id;
        $announcement->announcement_id = $data['announcement_id'];
        $announcement->is_read = 1;
        $announcement->save();

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

        $announcement = Announcement::find($data['id']);
        $announcement->status = $data['value'];
        $announcement->description = $data['aciklama'] ?? NULL;
        $announcement->is_active = $data['value']=='Onaylandı' ? '1' : ($data['value']=='kaldırıldı' ? 0 : 0);
        $announcement->save();


        $result = array(
          'status' => 1,
          'message' => 'Başarıyla kaydedildi. Yönlendiriliyorsunuz.'
      );

      return response()->json($result);
    }
}
