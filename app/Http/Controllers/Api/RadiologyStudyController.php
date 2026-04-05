<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RadiologyStudy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
class RadiologyStudyController extends Controller
{
    public function index(Request $request)
{
    $cacheKey = 'radiology_studies_' . md5(json_encode($request->all()));

    $studies = Cache::remember($cacheKey, 60, function () use ($request) {
        $query = RadiologyStudy::query();

        // SEARCH
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // FILTER modality
        if ($request->filled('modality')) {
            $query->where('modality', $request->modality);
        }

        // FILTER body_part
        if ($request->filled('body_part')) {
            $query->where('body_part', $request->body_part);
        }

        // SORT
        if ($request->filled('sort_by')) {
            $direction = $request->get('direction', 'asc');

            if (in_array($request->sort_by, ['title', 'duration_minutes'])) {
                $query->orderBy($request->sort_by, $direction);
            }
        }

        // LIMIT
        $limit = $request->get('limit', 10);

        return $query->limit($limit)->get();
    });

    return response()->json([
        'success' => true,
        'count' => $studies->count(),
        'data' => $studies,
    ]);
}
    public function show($id)
    {
        $study = RadiologyStudy::find($id);

        if (! $study) {
            return response()->json([
                'success' => false,
                'message' => 'Radioloogilist uuringut ei leitud.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $study,
        ]);
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'modality' => 'required|string|max:100',
        'body_part' => 'required|string|max:100',
        'duration_minutes' => 'required|integer|min:1',
        'contrast_needed' => 'nullable|boolean',
        'image' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    if ($request->hasFile('image')) {
        $path = $request->file('image')->store('radiology', 'public');
        $validated['image'] = $path;
    }

    $study = RadiologyStudy::create($validated);

    Cache::flush();

    return response()->json([
        'success' => true,
        'message' => 'Radioloogiline uuring lisati edukalt.',
        'data' => $study,
    ], 201);
    }
    public function update(Request $request, $id)
{
    $study = RadiologyStudy::find($id);

    if (! $study) {
        return response()->json([
            'success' => false,
            'message' => 'Radioloogilist uuringut ei leitud.',
        ], 404);
    }

    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'modality' => 'required|string|max:100',
        'body_part' => 'required|string|max:100',
        'duration_minutes' => 'required|integer|min:1',
        'contrast_needed' => 'nullable|boolean',
        'image' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    if ($request->hasFile('image')) {
        $path = $request->file('image')->store('radiology', 'public');
        $validated['image'] = $path;
    }

    $study->update($validated);

    Cache::flush();

    return response()->json([
        'success' => true,
        'message' => 'Radioloogiline uuring uuendati edukalt.',
        'data' => $study,
    ]);
}

public function destroy($id)
{
    $study = RadiologyStudy::find($id);

    if (! $study) {
        return response()->json([
            'success' => false,
            'message' => 'Radioloogilist uuringut ei leitud.',
        ], 404);
    }

    $study->delete();

    Cache::flush();

    return response()->json([
        'success' => true,
        'message' => 'Radioloogiline uuring kustutati edukalt.',
    ]);
}
}