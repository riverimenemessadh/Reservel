<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AssetController extends Controller
{
    protected $bookingService;

    /**
     * Create a new controller instance.
     */
    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    /**
     * Display a listing of all assets grouped by type.
     */
    public function index()
    {
        $assets = Asset::orderBy('type')->orderBy('name')->get();
        $rooms = $assets->where('type', 'room');
        $equipment = $assets->where('type', 'equipment');

        return view('assets.index', compact('rooms', 'equipment'));
    }

    /**
     * Show the form for creating a new asset.
     */
    public function create()
    {
        $this->authorize('create', Asset::class);
        return view('assets.create');
    }

    /**
     * Store a newly created asset in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Asset::class);

        // Note: `status` is intentionally not validated here — it is system-managed, not user-set.
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:room,equipment',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['name', 'description', 'type']);

        if ($request->hasFile('image')) {
            // Images are stored as base64 in a longText column intentionally
            // to avoid filesystem dependencies and simplify deployment.
            $data['image'] = 'data:' . $request->file('image')->getMimeType() . ';base64,' . base64_encode(file_get_contents($request->file('image')->getRealPath()));
        }

        Asset::create($data);

        return redirect()->route('assets.index')->with('success', __('messages.asset_created'));
    }

    /**
     * Display the specified asset with its current booking and pending reports.
     */
    public function show(Asset $asset)
    {
        $asset->load(['currentBooking.user', 'reports' => function ($query) use ($asset) {
            $query->where('asset_id', $asset->id)->where('status', 'pending')->latest();
        }]);

        return view('assets.show', compact('asset'));
    }

    /**
     * Show the form for editing the specified asset.
     */
    public function edit(Asset $asset)
    {
        $this->authorize('update', $asset);
        return view('assets.edit', compact('asset'));
    }

    /**
     * Update the specified asset in storage.
     */
    public function update(Request $request, Asset $asset)
    {
        $this->authorize('update', $asset);

        // Note: `status` is intentionally not validated here — it is system-managed, not user-set.
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|string',
        ]);

        $data = $request->only(['name', 'description']);

        if ($request->filled('image')) {
            $data['image'] = $request->input('image');
        }

        $asset->update($data);

        return redirect()->route('assets.index')->with('success', __('messages.asset_updated'));
    }

    /**
     * Remove the specified asset from storage.
     */
    public function destroy(Asset $asset)
    {
        $this->authorize('delete', $asset);

        $asset->delete();

        return redirect()->route('assets.index')->with('success', __('messages.asset_deleted'));
    }
}
