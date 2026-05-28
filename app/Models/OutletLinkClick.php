<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OutletLinkClick extends Model
{
    protected $guarded = ['id'];

    public static function getTopClickData(?String $uuid_outlet) {
        $query = OutletLinkClick::join('outlet_links', 'outlet_link_clicks.uuid_outlet_link', '=', 'outlet_links.uuid');

        if ($uuid_outlet) {
            $query->where('outlet_links.uuid_outlet', $uuid_outlet);
        }

        $raw_data = $query->select('outlet_links.title', DB::raw('count(*) as total'))
            ->groupBy('outlet_links.title')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->pluck('total', 'outlet_links.title')
            ->toArray();

        $links = OutletLink::when($uuid_outlet, fn($q) => $q->where('uuid_outlet', $uuid_outlet))
            ->pluck('title')
            ->toArray();

        $raw_array_data = [];

        foreach ($links as $title) {
            $raw_array_data[$title] = $raw_data[$title] ?? 0;
        }

        arsort($raw_array_data);

        $data = \array_slice($raw_array_data, 0, 5);

        return $data;
    }

    public static function getDailyClickData(?string $uuid_outlet)
    {
        $query = OutletLinkClick::join('outlet_links', 'outlet_link_clicks.uuid_outlet_link', '=', 'outlet_links.uuid');

        if ($uuid_outlet) {
            $query->where('outlet_links.uuid_outlet', $uuid_outlet);
        }

        $raw_data = $query->where('outlet_link_clicks.created_at', '>=', Carbon::now()->subDays(6)->startOfDay())
            ->select(
                DB::raw('DATE(outlet_link_clicks.created_at) as date'),
                DB::raw('count(*) as total')
            )
            ->groupBy('date')
            ->pluck('total', 'date')
            ->toArray();

        $data = [];

        for ($i = 6; $i >= 0; $i--) {
            $carbon_date = Carbon::now()->subDays($i);
            $tanggal_key = $carbon_date->format('Y-m-d');

            $day_name = $carbon_date->locale('id')->isoFormat('dddd');

            $data[$day_name] = $raw_data[$tanggal_key] ?? 0;
        }

        return $data;
    }

    public function outletLink()
    {
        return $this->belongsTo(OutletLink::class, 'uuid_outlet_link', 'uuid');
    }
}
