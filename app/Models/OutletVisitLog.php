<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\DB;

class OutletVisitLog extends Model
{
    protected $guarded = ['id'];

    public static function getDistributeDeviceData(?String $uuid_outlet) {
        $query = $uuid_outlet ? self::where('uuid_outlet', $uuid_outlet) : self::query();

        $data = $query->select('device', DB::raw('count(*) as total'))
                        ->groupBy('device')
                        ->pluck('total', 'device');

        return $data;
    }

    public static function detectDevice(String $uuid_outlet) {
        $agent = new Agent();

        if (!$agent->isRobot()) {
            $device = 'desktop';
            if ($agent->isTablet()) {
                $device = 'tablet';
            } elseif ($agent->isMobile()) {
                $device = 'mobile';
            }

            OutletVisitLog::create([
                'uuid_outlet' => $uuid_outlet,
                'device' => $device,
            ]);
        }
    }
}
