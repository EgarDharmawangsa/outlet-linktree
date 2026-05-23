<?php

namespace App\Http\Controllers;

use App\Models\OutletLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\Services\OutletApiService;
use Illuminate\Support\Str;

class OutletLinkController extends Controller
{
    /**
     * Display a listing of the resource.
     */

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
        // if (!Gate::allows('super-admin')) {
        //     abort(404);
        // }

        try {
            $outlets = $request->routeIs('tautan-outlet.sync') ? $outlet_api_service->syncOutlets() : $outlet_api_service->getOutlets();

            return response()->json([
                'data' => $outlets,
                'status' => 'success',
                'message' => 'Data outlet berhasil disinkronkan.'
            ]);
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
        return response()->json([
            'data' => OutletLink::where('uuid_outlet', $uuid_outlet)->latest()->get()
        ]);
    }

    public function store(Request $request)
    {
        // if (!Gate::allows('super-admin') && Auth::user()->uuid_outlet !== $uuid_outlet) {
        //     abort(404);
        // }

        $validated_outlet = $request->validate([
            'uuid_outlet' => 'required|uuid',
            'title' => 'required|string|min:3|max:100',
            'link'  => 'required|string|min:3|max:255'
        ]);

        OutletLink::create($validated_outlet);

        return response()->json([
            'status' => 'success',
            'message' => 'Tautan outlet berhasil ditambahkan.'
        ]);
    }

    public function update(Request $request, OutletLink $outlet_link)
    {
        // if (!Gate::allows('super-admin') && Auth::user()->uuid_outlet !== $outlet_link->uuid_outlet) {
        //     abort(404);
        // }

        $validated_outlet_link = $request->validate([
            'title' => 'required|string|min:3|max:100',
            'link'  => 'required|string|min:3|max:255'
        ]);

        $outlet_link->update($validated_outlet_link);

        return response()->json([
            'status' => 'success',
            'message' => 'Tautan outlet berhasil diperbarui.'
        ]);
    }

    public function destroy(OutletLink $outlet_link)
    {
        // if (!Gate::allows('super-admin') && Auth::user()->uuid_outlet !== $outlet_link->uuid_outlet) {
        //     abort(404);
        // }

        $outlet_link->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Tautan outlet berhasil dihapus.'
        ]);
    }

    // Show public 
    public function showPublic(String $outlet_slug, OutletApiService $outlet_api_service)
    {
        $outlets = $outlet_api_service->getOutlets();

        $outlet = $outlets->first(fn($outlet) => Str::slug($outlet['nama']) === $outlet_slug);

        if (empty($outlet)) {
            return redirect()->route('masuk');
        }

        $outlet_links = OutletLink::where('uuid_outlet', $outlet['id'])->latest()->get();

        return view('pages.show_public', [
            'outlet_name' => $outlet['nama'],
            'outlet_links' => $outlet_links
        ]);
    }
}
