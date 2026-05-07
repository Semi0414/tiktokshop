<?php

namespace Webkul\SuperAdmin\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Webkul\Core\Models\HomePageMedia;

class HomePageMediaController extends Controller
{
    public function index(): View
    {
        $items = HomePageMedia::query()
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->get();

        return view('superadmin::home-page-media.index', compact('items'));
    }

    public function store(): RedirectResponse
    {
        $data = $this->validatedUpload();

        $file = request()->file('media');
        $path = $file->store('home-page-media', 'public');

        HomePageMedia::query()->create([
            'media_type' => $this->detectType($file),
            'path' => $path,
            'original_filename' => $file->getClientOriginalName(),
            'is_active' => $data['is_active'],
            'sort_order' => $data['sort_order'],
        ]);

        return redirect()
            ->route('superadmin.home_page_media.index')
            ->with('success', trans('superadmin::app.home-page-media.created'));
    }

    public function edit(int $id): View
    {
        $item = HomePageMedia::query()->findOrFail($id);

        return view('superadmin::home-page-media.edit', compact('item'));
    }

    public function update(int $id): RedirectResponse
    {
        $item = HomePageMedia::query()->findOrFail($id);

        $data = request()->validate([
            'is_active' => 'required|in:0,1',
            'sort_order' => 'nullable|integer|min:0|max:999999',
            'media' => 'nullable|file|max:51200|mimes:jpg,jpeg,png,gif,webp,mp4,webm,mov',
        ]);

        $updates = [
            'is_active' => (bool) (int) $data['is_active'],
            'sort_order' => array_key_exists('sort_order', $data) && $data['sort_order'] !== null
                ? (int) $data['sort_order']
                : $item->sort_order,
        ];

        if (request()->hasFile('media')) {
            $file = request()->file('media');
            Storage::disk('public')->delete($item->path);
            $updates['path'] = $file->store('home-page-media', 'public');
            $updates['media_type'] = $this->detectType($file);
            $updates['original_filename'] = $file->getClientOriginalName();
        }

        $item->update($updates);

        return redirect()
            ->route('superadmin.home_page_media.index')
            ->with('success', trans('superadmin::app.home-page-media.updated'));
    }

    public function destroy(int $id): RedirectResponse
    {
        $item = HomePageMedia::query()->findOrFail($id);

        Storage::disk('public')->delete($item->path);
        $item->delete();

        return redirect()
            ->route('superadmin.home_page_media.index')
            ->with('success', trans('superadmin::app.home-page-media.deleted'));
    }

    /**
     * @return array{is_active: bool, sort_order: int}
     */
    protected function validatedUpload(): array
    {
        $data = request()->validate([
            'media' => 'required|file|max:51200|mimes:jpg,jpeg,png,gif,webp,mp4,webm,mov',
            'is_active' => 'nullable|in:0,1',
            'sort_order' => 'nullable|integer|min:0|max:999999',
        ]);

        return [
            'is_active' => (bool) (int) ($data['is_active'] ?? 1),
            'sort_order' => isset($data['sort_order']) ? (int) $data['sort_order'] : 0,
        ];
    }

    protected function detectType(UploadedFile $file): string
    {
        $mime = (string) $file->getMimeType();

        return str_starts_with($mime, 'video/') ? 'video' : 'image';
    }
}
