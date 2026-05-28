<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\OutletLink;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\Services\OutletApiService;

class DashboardController extends Controller
{
    public function index(OutletApiService $outletApiService)
    {
        // $user = collect(Auth::user()->is_super_admin ? Auth::user() : User::firstWhere('uuid_outlet', Auth::user()->uuid_outlet));

        if (!Gate::allows('super-admin')) {
            return view('pages.dashboard', [
                'title' => 'Beranda',
                'counted_links' => OutletLink::where('uuid_outlet', Auth::user()->uuid_outlet)->count(),
                'outlet' => $outletApiService->getOutletById(Auth::user()->uuid_outlet)
            ]);
        }

        return view('pages.dashboard', [
            'title' => 'Beranda',
            'counted_outlets' => $outletApiService->getOutlets()->count(),
            'counted_users' => User::count()
        ]);
    }
}
