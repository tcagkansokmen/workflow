<?php declare(strict_types = 1);

namespace App\Http\Controllers\HR\Poll;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Customer;
use App\Models\Event;
use App\Models\QrCode;
use App\Models\Form;
use App\Models\FormUser;
use App\Models\ListModel;
use App\Models\Company;
use App\Models\FormContact;
use App\Models\FormAnswer;
use App\Models\FormField;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Notifications\CustomEmail;
use DB;
use DataTables;
use Config;

class FormController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index(): \Illuminate\Contracts\View\View
    {
        $company_id = $this->request->user()->company_id;
        $user_id = $this->request->user()->id;
        $yetki = $this->request->user()->group_id;

        $page_title = "Anketler";
        $page_description = "Tüm anketler ve cevapları";

        $forms = Form::withCount(['answers' => function($q){
            $q->select(DB::raw('count(distinct(user_id))'));
         }
         ])->orderBy('forms.id', 'desc')->paginate(50);

        return View::make('hr.form.index', [
          'forms' => $forms,
          'page_title' => $page_title,
          'page_description' => $page_description
        ]);
    }
    public function add(): \Illuminate\Contracts\View\View
    {
        $page_title = "Yeni Anket";
        $page_description = "Yeni anket ekleyebilirsiniz";
        
        return View::make('hr.form.add', compact('page_title', 'page_description'));
    }

    public function addElements(int $id = null): \Illuminate\Contracts\View\View
    {
        $id = $id ?? '';
        $form_single = Form::find($id);
        $searchelement = FormField::where('form_id', $id)->orderBy('priority')->get();

        $page_title = "Anket Elementleri";
        $page_description = "Yeni anket ekleyebilirsiniz";

        return View::make('hr.form.add-elements',
        [
            'fields' => $searchelement,
            'form_single' => $form_single,
            'page_title' => $page_title,
            'page_description' => $page_description
        ]);
    }

    public function formAnswers(int $id = null): \Illuminate\Contracts\View\View
    {
        $form_single = Form::find($id);

        $data = $this->request->all();
        $label = $data["label"] ?? '';
        $answer = $data["answer"] ?? '';

        $page_title = "Anket Cevapları";
        $page_description = "Ankete ait cevaplar";

        $findanswer = FormAnswer::where('field_id', $label)->where('answer', $answer)->pluck('user_id')->toArray();


        $cevaplar = FormField::where('form_id', $id)->where('type', '!=', 'text');
        $cevaplar = $cevaplar->with(['sets', 'answers' => function($q) use ($id, $findanswer, $label){
            if($label){
                $q->whereIn('user_id', $findanswer);
            }
         }])->orderBy('priority')->get();

         $ccc = FormField::where('form_id', $id)->with('answers')->orderBy('priority')->get();

 
        return View::make('hr.form.answers',
        [
            'form_single' => $form_single,
            'answers' => $cevaplar,
            'ccc' => $ccc,
            'page_title' => $page_title,
            'page_description' => $page_description
        ]);
    }
    public function formTable(int $id = null): \Illuminate\Contracts\View\View
    {
        $form_single = Form::find($id);

        $fields = FormField::where('form_id', $id)->where('type', '!=', 'text')->orderBy('priority')->get();
        
        $page_title = "Tablo Görünümü";
        $page_description = "Yeni anket ekleyebilirsiniz";

        $cvps = FormAnswer::where('form_id', $id)->groupBy('user_id')->get();
        $arrs = array();
        
        foreach($cvps as $c){
            $cid = $c['user_id'];
            $cep = FormField::where('form_id', $id)->with(['answers' => function($q) use ($id, $cid){
                $q->where('form_answers.user_id', $cid);
            }])->orderBy('priority')->get();

            $c['answers'] = $cep;
            $arrs[] = $c;
        }


        return View::make('hr.form.form-table',
        [
            'form_single' => $form_single,
            'answers' => $arrs,
            'fields' => $fields,
            'page_title' => $page_title,
            'page_description' => $page_description

        ]);
    }

    public function detail(int $id): \Illuminate\Contracts\View\View
    {
        $form = Form::where('id', $id)
        ->with(['event', 'answers' => function($q){
            $q->selectRaw('*, form_answers.created_at as answer_date');
            $q->join('users', 'users.id', 'form_answers.user_id');
            $q->orderBy('form_answers.id', 'desc');
            $q->groupBy('form_answers.user_id');
        }])->withCount(['answers' => function($q){
            $q->select(DB::raw('count(distinct(user_id))'));
         }
         ])->first();

        $cevaplar = FormField::where('form_id', $id)->where('type', '!=', 'text');
        $cevaplar = $cevaplar->with(['sets', 'answers'])->orderBy('priority')->get();

        $page_title = "Anket Detay";
        $page_description = "Yeni anket ekleyebilirsiniz";

        return View::make('hr.form.detail', [
            'c' => $form,
            'qr' => $qr,
            'answers' => $cevaplar,
            'lists' => $list,
            'sorumlular' => $sorumlular,
            'page_title' => $page_title,
            'page_description' => $page_description
          ]);
    }
    public function edit(int $id): \Illuminate\Contracts\View\View
    {
        $id = $id ?? '';
        $form_single = $id ? Form::find($id) : '';
        
        $page_title = "Anket Düzenle";
        $page_description = "Yeni anket ekleyebilirsiniz";

        return View::make('hr.form.add',
        [
            'form' => $form_single,
            'page_title' => $page_title,
            'page_description' => $page_description
        ]);
    }
    
    public function elementSave(int $id): \Illuminate\Http\JsonResponse
    {
      $user_id = $this->request->user()->id;

      $findform = Form::find($id);

      $data = $this->request->all();
      $searchelement = FormField::where('name', $data['name'])->where('form_id', $id)->first();
      if($searchelement){
        $form = FormField::find($searchelement->id);
        $form->form_id = $id;
        $form->label = $data['label'] ?? '';
        $form->values = $data['values'] ?? '';
        $form->is_required = $data['is_required'] ? '1' : '';
        $form->type = $data['type'] ?? '';
        $form->priority = $data['priority'] ?? '';
        $form->form_class = $data['class'] ?? '';
        $form->save();
      }else{
        $form = new FormField();
        $form->form_id = $id;
        $form->name = $data['name'] ?? '';
        $form->label = $data['label'] ?? '';
        $form->values = $data['values'] ?? '';
        $form->is_required = $data['is_required'] ? '1' : '';
        $form->type = $data['type'] ?? '';
        $form->priority = $data['priority'] ?? '';
        $form->form_class = $data['class'] ?? '';
        $form->save();
      }
      
      $result = array(
          'status' => 1,
          'message' => 'Başarıyla kaydedildi. Yönlendiriliyorsunuz.',
          'redirect' => route('form')
      );
      return response()->json($result);
    }
    public function save(Request $request): \Illuminate\Http\JsonResponse
    {
      $user_id = $this->request->user()->id;
      $data = $this->request->all();

      if(isset($data['id'])){
          $form = Form::find($data['id']);
      }else{
          $form = new Form();
      }

      $form->user_id = $user_id;
      $form->title = $data['title'];
      $form->description = $data['description'];
      
      if(isset($data['start_at'])){
        $form->start_at = date('Y-m-d', strtotime($data['start_at']));
      }

      if(isset($data['end_at'])){
        $form->end_at = date('Y-m-d', strtotime($data['end_at']));
      }

      $form->save();

      $result = array(
          'status' => 1,
          'message' => 'Başarıyla kaydedildi. Yönlendiriliyorsunuz.',
          'redirect' => route('form-elements', ['form_id' => $form->id])
      );
      return response()->json($result);
    }


    public function contactForm($id): \Illuminate\Contracts\View\View
    {
        $user_id = $this->request->user()->id;
        $form = Form::where('id', $id)->first();

        $fields = FormField::where('form_id', $id)->orderBy('priority')->get();
        $data = $this->request->all();

        $contact_id = $data['contact_id'] ?? '';

            $answered_fields = FormField::where('form_id', $id)->with(['answers' => function($q) use ($user_id){
                $q->where('user_id', $user_id);
                $q->pluck('answer');
            }])->orderBy('priority')->get()->keyBy('name');


        return View::make('hr.form.form',[
            'form' => $form,
            'fields' => $fields,
            'answered' => $answered_fields
        ]);

    }
    public function thanksForm($id): \Illuminate\Contracts\View\View
    {
        $form = Form::where('id', $id)->first();

        $fields = FormField::where('form_id', $id)->orderBy('priority')->get();
        
        return View::make('hr.form.thanks',[
            'form' => $form,
            'fields' => $fields
        ]);
    }

    public function getAnswers($id): \Illuminate\Http\JsonResponse
    {
        $data = $this->request->all();
        $result = FormAnswer::where('field_id', $id)->groupBy('answer')->get();
        
        return response()->json($result);

    }
    public function contactFormSend(): \Illuminate\Http\JsonResponse
    {
      $user_id = $this->request->user()->id;

      $data = $this->request->all();
        
      $form_id = $data['form_id'];
      $getform = Form::find($form_id);
      
      $answersil = FormAnswer::where('user_id', $user_id)->delete();

      foreach($data as $key => $value){
        $findField = FormField::where('name', $key)->first();
        if($findField){
            if(is_array($value)){
                foreach($value as $v){
                    $form = new FormAnswer();
                    $form->form_id = $form_id;
                    $form->user_id = $user_id;
                    $form->field_id = $findField->id;
                    $form->answer = $v ?? '';
                    $form->save();
                }
            }else{
                $form = new FormAnswer();
                $form->form_id = $form_id;
                $form->user_id = $user_id;
                $form->field_id = $findField->id;
                $form->answer = $value ?? '';
                $form->save();
            }
        }
      }


      $result = array(
          'status' => 1,
          'message' => 'Form başarıyla gönderildi. Teşekkürler!',
          'redirect' => route('tesekkurler', ['form_id' => $form_id])
      );
      return response()->json($result);
    }

    public function delete(string $form_id): \Illuminate\Http\RedirectResponse
    {   
        $company_id = $this->request->company_id;

        $form = Form::find($form_id);

        $formfields = FormField::where('form_id', $form_id)->delete();
        $form->delete();
        
        return back()->with('success', 'Kayıt başarıyla güncellendi');
    }
    public function deleteField(): \Illuminate\Http\JsonResponse
    {   
        $data = $this->request->all();
        $form_id = $data['id'];
        $name = $data['name'];
        $company_id = $this->request->company_id;

        $form = Form::find($form_id);
        
        $element = FormField::where('form_id', $form_id)->where('name', $name)->first();
        $elementsil = FormField::find($element->id)->delete();
        
      $result = array(
        'status' => 1,
        'message' => 'Form başarıyla gönderildi. Teşekkürler!'
    );
    return response()->json($result);
    }
    
}
