<?php declare(strict_types = 1);

namespace App\Core;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PublicHoliday;
use App\Models\PermissionType;
use Carbon\Carbon;
use Code16\CarbonBusiness\BusinessDays;


class CalculateDays
{
    protected $request;
    
    public function calculate($start, $end)
    {
      $date = new BusinessDays();
      $date->setWeekendDays([Carbon::SUNDAY, Carbon::SATURDAY]);
      $start_time = strtotime($start);
      $end_time = strtotime($end);
      
      // Set a closed period (whole 2nd week)
      $holidays = PublicHoliday::get();
      foreach($holidays as $h){
        $holiday_start = strtotime($h->start_at);
        $holiday_end = strtotime($h->end_at);
        $date->addClosedPeriod(
          Carbon::createFromDate(date('Y', $holiday_start), date('m', $holiday_start), date('d', $holiday_start)),
          Carbon::createFromDate(date('Y', $holiday_end), date('m', $holiday_end), date('d', $holiday_end))
        );
      }
      $days = $date->daysBetween(
        Carbon::createFromDate(date('Y', $start_time), date('m', $start_time), date('d', $start_time)),
        Carbon::createFromDate(date('Y', $end_time), date('m', $end_time), date('d', $end_time))
      );
      // $days is finally 5

      return $days;
    }
}
