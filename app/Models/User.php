<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Marketplace;
use DB;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'surname',
        'email',
        'password',
        'group_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function group()
    {
        return $this->belongsTo('App\Models\UserGroup', 'group_id', 'id');
    }
    
    public function addresses()
    {
        $address = Marketplace::select('id as value', 'title as name')->get();

        return $address;
    }

    public function power($field, $action){
        $group_id = Auth::user()->group_id;
        $bul = UserPower::where('group_id', $group_id)
        ->where('field', $field)
        ->where('action', $action)
        ->select('type')->first();

        if($bul){
            return true;
        }else{
            return false;
        }
    }

    public function userAvatar($size = 35, $color = 'success', $class = null){
        $user = User::find($this->id);
        $options = array(
            'size' => $size,
            'color' => $color,
            'class' => $class
        );
        return view('user.avatar', compact('user', 'options'));
    }

    public function categories(){
        $categories = ProductCategory::select('id as value', 'title as name')->orderBy('id','asc')->get();

        return $categories;
    }

    public function cities(){
        $cities = City::select('id as value', 'city as name')->orderByRaw('IF(id = 40, 0,1)')->orderBy('id','asc')->get();

        return $cities;
    }
    
    public function customers(){
        $customers = Customer::select('id as value', 'code as name')->get();

        return $customers;
    }
    public function findColor($ordering){
        switch($ordering){
            default;
                $color = 'success';
            break;

            case 1;
                $color = 'info';
            break;

            case 2;
                $color = 'warning';
            break;

            case 3;
                $color = 'danger';
            break;
        }
        return $color;
    }
    public function isDesigner(){
        if($this->group_id == 7){
            return true;
        }
        return false;
    }
    public function isAdmin(){
        if($this->group_id == 1){
            return true;
        }
        return false;
    }
    public function isAccountant(){
        if($this->group_id==5){
            return true;
        }
        return false;
    }
    public function isSatinAlma(){
        if($this->group_id==3){
            return true;
        }
        return false;
    }
    public function projelerim(){
        $user_id = Auth::user()->id;
        $user_group = Auth::user()->group_id;
        if(in_array($user_group, [1,2,3,5,6,7])){
            $projeler = Project::pluck('id')->toArray();
        }else{
            $projeler = Project::where('user_id', $user_id)->orWhereNull('user_id')->pluck('id')->toArray();
        }
        return $projeler;
    }
    
    public function izinTalepleri(){
        $data = Permission::where('status', 'Bekliyor')->count();
        return $data;
    }
    public function avansTalepleri(){
        $data = Earnest::where('status', 'Bekliyor')->count();
        return $data;
    }
    public function bekleyenMasraflar(){
        $data = Cost::where('status', 'beklemede')->count();
        return $data;
    }
    public function bekleyenIhtiyaclar(){
        $data = Need::where('status', 'talep_edildi')->count();
        return $data;
    }
    public function bekleyenTalepler(){
        return $this->izinTalepleri()+$this->avansTalepleri()+$this->bekleyenMasraflar()+$this->bekleyenIhtiyaclar();
    }
    
    public function odenmeyenMasraflar(){
        $data = Cost::where('status', 'Onaylandı')->count();
        return $data;
    }
    public function odenmeyenAvanslar(){
        $data = Earnest::where('status', 'Onaylandı')->count();
        return $data;
    }

    public function bekleyenMaaslar(){
        $data = Wage::whereNull('is_paid')->orWhereNull('bordro')->count();
        return $data;
    }
    public function muhasebeTalepleri(){
        return $this->odenmeyenMasraflar()+$this->odenmeyenAvanslar()+$this->bekleyenMaaslar();
    }

    public function alinmayanIhtiyaclar(){
        $data = Need::where('status', 'Kabul Edildi')->count();
        return $data;
    }

    public function satinAlmaTalepleri(){
        return $this->alinmayanIhtiyaclar();
    }
    
    public function waitingCare(){
        return Vehicle::where('is_active', 1)->where('care_date', '<=', date('Y-m-d', strtotime('+1 month')))->count();
    }
    public function waitingKasko(){
        return Vehicle::where('is_active', 1)->where('kasko_end', '<=', date('Y-m-d', strtotime('+1 month')))->count();
    }
    public function waitingInsurance(){
        return Vehicle::where('is_active', 1)->where('insurance_end', '<=', date('Y-m-d', strtotime('+1 month')))->count();
    }
    public function waitingLoan(){
        return Vehicle::where('is_active', 1)->where('loan_end', '<=', date('Y-m-d', strtotime('+1 month')))->count();
    }
    public function aracTalepleri(){
        return $this->waitingCare()+
                $this->waitingKasko()+
                $this->waitingInsurance()+
                $this->waitingLoan();
    }

    public function projectApproval(){
        return $this->explorationApproval()+
                $this->productionApproval()+
                $this->assemblyApproval()+
                $this->printingApproval()+
                $this->briefApproval()+
                $this->offerApproval()+
                $this->contractApproval()+
                $this->billApproval()+
                $this->expenseApproval()+
                $this->purchaseApproval();
    }

    public function explorationApproval(){
        $user_group = Auth::user()->group_id;
        $user_id = Auth::user()->id;
        if($user_group==1){
            return $data = Exploration::where('status', 'Talep Açıldı')->where('user_id', $user_id)->count();
        }elseif($user_group==2){
            return $data = Exploration::where('status', 'Talep Açıldı')->where('user_id', $user_id)->count();
        }elseif($user_group==4){
            return $data = Exploration::where('status', 'Talep Açıldı')->where('user_id', $user_id)->count();
        }
            return 0;
    }
    public function productionApproval(){
        //1,2,3,4
        $user_group = Auth::user()->group_id;
        $user_id = Auth::user()->id;
        if($user_group==2){
            return $data = Production::where('status', 'Talep Açıldı')->count();
        }
            return 0;
    }
    public function assemblyApproval(){
        //1,2,3,4
        $user_group = Auth::user()->group_id;
        $user_id = Auth::user()->id;
        if($user_group==2){
            return $data = Assembly::where('status', 'Talep Açıldı')->count();
        }
            return 0;
        
    }
    public function printingApproval(){
        //1,2,3,4,6
        $user_group = Auth::user()->group_id;
        $user_id = Auth::user()->id;
        if($user_group==6){
            return $data = Printing::where('status', 'Talep Açıldı')->count();
        }
            return 0;
    }
    public function briefApproval(){
        //1,4,7
        $user_group = Auth::user()->group_id;
        $user_id = Auth::user()->id;
        $projelerim = Auth::user()->projelerim();

        if($user_group==4){
            return $data = Brief::
            whereIn('project_id', $projelerim)
            ->where(function($q){
                $q->where('status', 'MT Onayında');
                $q->orWhere('status', 'Revize MT Onayında');
            })
            ->where('user_id', $user_id)->count();
        }elseif($user_group==7){
            return $data = Brief::
            whereIn('project_id', $projelerim)
            ->where('designer_status', '!=', 'Kabul Edildi')
            ->where('designer_id', $user_id)
            ->where('designer_status', '!=', 'Reddedildi')
            ->count();
        }
            return 0;
    }
    public function offerApproval(){
        //1,4
        $user_group = Auth::user()->group_id;
        $user_id = Auth::user()->id;
        $projelerim = Auth::user()->projelerim();

        if($user_group==1){
            return $data = Offer::
            whereIn('project_id', $projelerim)
            ->where('status', 'Yönetici Onayında')
            ->count();
        }elseif($user_group==4){
            return $data = Offer::
            whereIn('project_id', $projelerim)
            ->where('status', 'Yönetici Onayladı')
            ->where('user_id', $user_id)
            ->count();
        }
            return 0;
    }
    public function contractApproval(){
        //1,4
        $user_group = Auth::user()->group_id;
        $user_id = Auth::user()->id;
        $projelerim = Auth::user()->projelerim();
        
        if($user_group==1){
            return $data = Offer::
            whereIn('project_id', $projelerim)
            ->where('contract_status', 'Yönetici Onayında')->count();
        }elseif($user_group==4){
            return $data = Offer::
            whereIn('project_id', $projelerim)
            ->where('contract_status', 'Yönetici Onayladı')
            ->where('user_id', $user_id)
            ->count();
        }
            return 0;
    }
    public function billApproval(){
        //1,4,5
        $user_group = Auth::user()->group_id;
        $user_id = Auth::user()->id;
        $projelerim = Auth::user()->projelerim();
        
        if($user_group==1){
            return $data = Bill::
            whereIn('project_id', $projelerim)
            ->where('status', 'Yönetici Onayında')->count();
        }elseif($user_group==4){
            return $data = Bill::
            whereIn('project_id', $projelerim)
            ->where('status', 'Fatura Kesildi')
            ->where('user_id', $user_id)
            ->count();
        }elseif($user_group==5){
            return $data = Bill::where('status', 'Yönetici Onayladı')->count();
        }
            return 0;
    }
    public function expenseApproval(){
        $user_group = Auth::user()->group_id;
        $user_id = Auth::user()->id;
        if($user_group==1){
            return $data = Expense::where('status', 'Yönetici Onayında')->count();
        }elseif($user_group==5){
            return $data = Expense::where('status', 'Onaylandı')->count();
        }
            return 0;
    }
    public function purchaseApproval(){
        $user_group = Auth::user()->group_id;
        $user_id = Auth::user()->id;
        if($user_group==1){
            return $data = PurchaseItem::whereIn('status', ['Yönetici Onayında', 'Revize Edildi'])->count();
        }elseif($user_group==3){
            return $data = PurchaseItem::where('status', 'Onaylandı')->count();
        }
            return 0;
    }
    
    public function notifications(){
        //1,4,5
        $user_id = Auth::user()->id;
        $notifications = Notification::where('user_id', $user_id)->where('is_read', 0)->count();

        return $notifications;
    }
    
    public function latestNotifications(){
        //1,4,5
        $user_id = Auth::user()->id;
        $notifications = Notification::where('user_id', $user_id)->where('is_read', 0)->limit(10)->get();

        return $notifications;
    }
    public function readNotification($path){
        //1,4,5
        $user_id = Auth::user()->id;
        $notifications = Notification::where('user_id', $user_id)->where('redirect', $path)->update(['is_read' => 1]);
    }
    
    public function permissionsLeft(){
        $user_id = $this->id;
        $start_at = $this->start_at;
        $wasted = $this->wasted_permission;
        $used = Permission::where('user_id', $user_id)->where('type', 1)->where('status', 'Onaylandı')->first();
        if(!$used){
            return 0;
        }
        $used = Permission::where('user_id', $user_id)->where('type', 1)->where('status', 'Onaylandı')->sum('days');

        $to = \Carbon\Carbon::createFromFormat('Y-m-d', date('Y-m-d'));
        $from = \Carbon\Carbon::createFromFormat('Y-m-d', $start_at);
        
        $diff_in_days = $to->diffInDays($from);

        $worked_days = $diff_in_days;
        $worked_years = floor($worked_days/365);

        $hak = 0;
        if($worked_years>=1&&$worked_years<5){
            $hak = $worked_years*14;
        }elseif($worked_years>=5&&$worked_years<15){
            $hak += 4*14;
            $hak += ($worked_years-4)*20;
        }elseif($worked_years>=15){
            $hak += 4*14;
            $hak += 10*20;
            $hak += ($worked_years-14)*26;
        }

        $remaining_permisisons = $hak-$wasted-$used;

        return $remaining_permisisons;
    }

    public function earnestLeft(){
        $user_id = $this->id;

        $get_earnest = Earnest::where('user_id', $user_id)
        ->where(function($q){
            $q->where('status', 'Onaylandı');
            $q->orWhere('status', 'ödendi');
        });

        $get_cost = Cost::where('user_id', $user_id)
        ->where(function($q){
            $q->where('status', 'Onaylandı');
            $q->orWhere('status', 'ödendi');
        });

        $total_earnest = $get_earnest->sum('price');
        $total_cost = $get_cost->sum('price');
      
        return $total_earnest-$total_cost;
    }
}
