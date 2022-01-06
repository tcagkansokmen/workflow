<?php declare(strict_types = 1);

namespace App\Core;

use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class LogSet
{
    protected $request;
    
    public function statusUpdates($data)
    {
      $data['user_id'] = Auth::user()->id;

      $log = new Log();
      $log->user_id = $data['user_id'];
      $log->customer_id = $data['customer_id'] ?? null;
      $log->project_id = $data['project_id'] ?? null;
      $log->brief_id = $data['brief_id'] ?? null;
      $log->offer_id = $data['offer_id'] ?? null;
      $log->bill_id = $data['bill_id'] ?? null;
      $log->type = $data['type'] ?? null;
      $log->title = $data['title'] ?? null;
      $log->description = $data['description'] ?? null;
      $log->status = $data['status'] ?? null;
      $log->save();
      
      return $log;
    }
}
