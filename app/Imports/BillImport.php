<?php

namespace App\Imports;

use App\Models\Bill;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Auth;

class BillImport implements ToCollection
{
    public function __construct($upload_id = null)
    {
        $this->upload_id = $upload_id;
    }
    public function collection(Collection $rows)
    {
        $user_id = Auth::user()->id;
        $i = 0;
        foreach ($rows as $row) 
        {
            $i++;
            if($i>1){
                $firm = $row[0];
                $short = $row[1];
                if($short){
                $exp = explode("/", $row[2]);
                $tarih = $exp[2]."-".$exp[1]."-".$exp[0];
    
                $bill_no = $row[3];
                $price = $row[4];
                $yetkili = $row[6];
                $isim = explode(' ', $yetkili);
                $isim = $isim[0];
                    $customer = Customer::where('code', $short)->first();
                    if(!$customer){
                        $customer = new Customer();
                        $customer->title = $firm;
                        $customer->code = $short;
                        $customer->save();
                    }

                    $userbul = User::where('name', $isim)->first();

                    $bill = new Bill();
                    $bill->status = 'Müşteriye Gönderildi';
                    $bill->user_id = $userbul ? $userbul->id : $user_id;
                    $bill->bill_no = $bill_no;
                    $bill->customer_id = $customer->id;
                    $bill->price = str_replace('.', '', $price);
                    $bill->bill_date = $tarih;
                    $bill->save();
                }
            } 
        }
    }
}