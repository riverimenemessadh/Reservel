<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AssetController extends Controller
{
    protected $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    public function index()
    {
        $this->bookingService->updateAssetStatuses();

        $assets = Asset::orderBy('type')->orderBy('name')->get();
        $rooms = $assets->where('type', 'room');
        $equipment = $assets->where('type', 'equipment');

        return view('assets.index', compact('rooms', 'equipment'));
    }

    public function create()
    {
        $this->authorize('create', Asset::class);
        return view('assets.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Asset::class);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:room,equipment',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['name', 'description', 'type']);

        if ($request->hasFile('image')) {
            $data['image'] = 'data:' . $request->file('image')->getMimeType() . ';base64,' . base64_encode(file_get_contents($request->file('image')->getRealPath()));
        }

        Asset::create($data);

        return redirect()->route('assets.index')->with('success', __('messages.asset_created'));
    }

    public function show(Asset $asset)
    {
        $this->bookingService->updateAssetStatuses();

        $assetId = $asset->id;
        $asset->load(['currentBooking.user', 'reports' => function ($query) use ($assetId) {
            $query->where('asset_id', $assetId)->where('status', 'pending')->latest();
        }]);

        return view('assets.show', compact('asset'));
    }

    public function edit(Asset $asset)
    {
        $this->authorize('update', $asset);
        return view('assets.edit', compact('asset'));
    }

    public function update(Request $request, Asset $asset)
    {
        $this->authorize('update', $asset);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['name', 'description']);

        if ($request->hasFile('image')) {
            $data['image'] = 'data:' . $request->file('image')->getMimeType() . ';base64,' . base64_encode(file_get_contents($request->file('image')->getRealPath()));
        }

        $asset->update($data);

        return redirect()->route('assets.index')->with('success', __('messages.asset_updated'));
    }

    public function destroy(Asset $asset)
    {
        $this->authorize('delete', $asset);

        $asset->delete();

        return redirect()->route('assets.index')->with('success', __('messages.asset_deleted'));
    }
}
