<?php

namespace App\Http\Controllers;

use App\Models\OutletLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\Services\OutletApiService;
use Illuminate\Support\Str;
use App\Models\OutletVisitLog;
use App\Models\OutletLinkClick;

class OutletLinkController extends Controller
{
    public function index(OutletApiService $outlet_api_service)
    {
        if (!Gate::allows('super-admin')) {
            abort(404);
        }

        $outlets = $outlet_api_service->getOutlets();

        return view('pages.outlet_links.index', [
            'title' => 'Tautan Outlet',
            'data' => $outlets
        ]);
    }

    public function getOutlets(Request $request, OutletApiService $outlet_api_service)
    {
        if (!Gate::allows('super-admin')) {
            abort(404);
        }

        try {
            $outlets = $request->routeIs('tautan-outlet.sync') ? $outlet_api_service->syncOutlets() : $outlet_api_service->getOutlets();

            return response()->json([
                'data' => $outlets,
                'status' => 'success',
                'message' => 'Data outlet berhasil disinkronkan.'
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data outlet gagal disinkronkan.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(String $uuid_outlet, OutletApiService $outlet_api_service)
    {
        if (!Gate::allows('super-admin') && Auth::user()->uuid_outlet !== $uuid_outlet) {
            abort(404);
        }

        $outlet = $outlet_api_service->getOutletById($uuid_outlet);

        return view('pages.outlet_links.manage_index', [
            'title' => 'Tautan Outlet',
            'outlet' => $outlet
        ]);
    }

    public function getOutletLinks(String $uuid_outlet)
    {
        if (!Gate::allows('super-admin') && Auth::user()->uuid_outlet !== $uuid_outlet) {
            abort(404);
        }

        try {
            return response()->json([
                'status' => 'success',
                'message' => 'Data tautan outlet berhasil diambil.',
                'data' => OutletLink::where('uuid_outlet', $uuid_outlet)->latest()->get()
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tautan outlet gagal diambil.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        if (!Gate::allows('super-admin') && Auth::user()->uuid_outlet !== $request->uuid_outlet) {
            abort(404);
        }

        try {
            $validated_outlet_link = $request->validate([
                'uuid_outlet' => 'required|uuid',
                'title' => 'required|string|min:3|max:100',
                'link'  => 'required|string|min:3|max:255'
            ]);

            $validated_outlet_link['uuid'] = Str::uuid();

            OutletLink::create($validated_outlet_link);

            return response()->json([
                'status' => 'success',
                'message' => 'Tautan outlet berhasil ditambahkan.'
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, OutletLink $outlet_link)
    {
        if (!Gate::allows('super-admin') && Auth::user()->uuid_outlet !== $outlet_link->uuid_outlet) {
            abort(404);
        }

        try {
            $validated_outlet_link = $request->validate([
                'title' => 'required|string|min:3|max:100',
                'link'  => 'required|string|min:3|max:255'
            ]);

            $outlet_link->update($validated_outlet_link);

            return response()->json([
                'status' => 'success',
                'message' => 'Tautan outlet berhasil diperbarui.'
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(OutletLink $outlet_link)
    {
        if (!Gate::allows('super-admin') && Auth::user()->uuid_outlet !== $outlet_link->uuid_outlet) {
            abort(404);
        }

        try {
            $outlet_link->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Tautan outlet berhasil dihapus.'
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tautan outlet gagal dihapus.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Show public 
    public function showPublic(String $outlet_slug, OutletApiService $outlet_api_service)
    {
        // Ini untuk fallbacknya
        OutletLink::findBySlug($outlet_slug) ? redirect()->route('masuk') : null;

        if (!empty($outlet_slug_result)) {
            return redirect()->route('masuk');
        }    

        $outlets = $outlet_api_service->getOutlets();

        $outlet = $outlets->first(fn($outlet) => Str::slug($outlet['nama']) === $outlet_slug);

        if (empty($outlet)) {
            return redirect()->route('masuk');
        }

        // Ini untuk pengecekan device
        OutletVisitLog::detectDevice($outlet['id']);

        $outlet_links = OutletLink::where('uuid_outlet', $outlet['id'])->latest()->get();

        return view('pages.show_public', [
            'outlet_name' => $outlet['nama'],
            'outlet_links' => $outlet_links
        ]);
    }

    // Diagram
    public function getDistributeDevice(String $uuid_outlet)
    {
        if (!Gate::allows('super-admin') && Auth::user()->uuid_outlet !== $uuid_outlet) {
            abort(404);
        }

        try {
            $data = OutletVisitLog::getDistributeDeviceData($uuid_outlet);

            return response()->json([
                'data' => $data,
                'status' => 'success',
                'message' => 'Data distribusi perangkat berhasil diambil.'
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data distribusi perangkat.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getTopClick(String $uuid_outlet)
    {
        if (!Gate::allows('super-admin') && Auth::user()->uuid_outlet !== $uuid_outlet) {
            abort(404);
        }

        try {
            $data = OutletLinkClick::getTopClickData($uuid_outlet);

            return response()->json([
                'data' => $data,
                'status' => 'success',
                'message' => 'Data top klik tautan berhasil diambil.'
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal top mengambil data klik tautan.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getDailyClick(String $uuid_outlet)
    {
        if (!Gate::allows('super-admin') && Auth::user()->uuid_outlet !== $uuid_outlet) {
            abort(404);
        }

        try {
            $data = OutletLinkClick::getDailyClickData($uuid_outlet);

            return response()->json([
                'data' => $data,
                'status' => 'success',
                'message' => 'Data klik tautan berhasil diambil.'
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data klik tautan.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function storeClick(Request $request)
    {
        try {
            $validated_outlet_link_click = $request->validate([
                'uuid_outlet_link' => 'required|uuid'
            ]);

            OutletLinkClick::create([
                'uuid_outlet_link' => $validated_outlet_link_click['uuid_outlet_link']
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Klik tautan berhasil disimpan.'
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan klik tautan.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
