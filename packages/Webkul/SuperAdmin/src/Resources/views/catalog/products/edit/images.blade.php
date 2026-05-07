{!! view_render_event('bagisto.admin.catalog.product.edit.form.images.before', ['product' => $product]) !!}

<div class="box-shadow relative rounded bg-white p-4 dark:bg-gray-900">
    <!-- Panel Header -->
    <div class="mb-4 flex justify-between gap-5">
        <div class="flex flex-col gap-2">
            <p class="text-base font-semibold text-gray-800 dark:text-white">
                @lang('superadmin::app.catalog.products.edit.images.title')
            </p>

            <p class="text-xs font-medium text-gray-500 dark:text-gray-300">
                @lang('superadmin::app.catalog.products.edit.images.info')
            </p>
        </div>
    </div>

    @php
        $uploadedImages = $product->images
            ->map(fn ($image) => [
                'id' => $image->id,
                'url' => $image->url,
            ])
            ->values()
            ->toArray();
    @endphp

    @if (! empty($uploadedImages))
        <div class="mb-3">
            <p class="mb-2 text-xs font-semibold text-gray-700 dark:text-gray-300">
                Existing Images (Server Rendered)
            </p>

            <div class="grid grid-cols-3 gap-2 max-sm:grid-cols-1">
                @foreach ($uploadedImages as $existingImage)
                    <div class="rounded border border-gray-200 p-2 dark:border-gray-800">
                        <input
                            type="hidden"
                            name="images[files][{{ $existingImage['id'] }}]"
                            value="1"
                            id="keep-image-{{ $existingImage['id'] }}"
                        >

                        <img
                            src="{{ $existingImage['url'] }}"
                            alt="Existing image"
                            class="mb-2 h-20 w-20 rounded border border-gray-200 object-cover dark:border-gray-800"
                        >

                        <div class="grid gap-1">
                            <label class="text-[11px] text-gray-600 dark:text-gray-300">
                                Replace
                                <input
                                    type="file"
                                    name="images[files][{{ $existingImage['id'] }}]"
                                    accept="image/*"
                                    class="mt-1 block w-full text-[11px]"
                                >
                            </label>

                            <label class="inline-flex items-center gap-1 text-[11px] text-red-600">
                                <input
                                    type="checkbox"
                                    onchange="document.getElementById('keep-image-{{ $existingImage['id'] }}').disabled = this.checked"
                                >
                                Remove
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="mb-3">
        <label class="text-xs font-semibold text-gray-700 dark:text-gray-300">
            Upload New Images
        </label>

        <input
            type="file"
            id="manual-new-images-input"
            name="images[files][]"
            accept="image/*"
            multiple
            class="hidden"
        >

        <label
            for="manual-new-images-input"
            id="manual-new-images-dropzone"
            class="mt-1 grid h-[120px] w-[120px] cursor-pointer items-center justify-items-center rounded border border-dashed border-gray-300 text-center text-[11px] font-semibold text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:text-gray-300"
        >
            <span class="icon-image text-4xl"></span>
        </label>

        <p id="manual-new-images-files" class="mt-1 text-[11px] text-gray-500 dark:text-gray-400"></p>
    </div>

    <x-superadmin::form.control-group.error control-name='images.files[0]' />
</div>

{!! view_render_event('bagisto.admin.catalog.product.edit.form.images.after', ['product' => $product]) !!}

@pushOnce('scripts')
    <script type="module">
        (() => {
            const input = document.getElementById('manual-new-images-input');
            const dropzone = document.getElementById('manual-new-images-dropzone');
            const fileInfo = document.getElementById('manual-new-images-files');

            if (! input || ! dropzone) {
                return;
            }

            const updateFileInfo = (files) => {
                if (! fileInfo) {
                    return;
                }

                if (! files || ! files.length) {
                    fileInfo.textContent = '';
                    return;
                }

                fileInfo.textContent = `${files.length} image(s) selected`;
            };

            ['dragenter', 'dragover'].forEach((eventName) => {
                dropzone.addEventListener(eventName, (event) => {
                    event.preventDefault();
                    dropzone.classList.add('border-blue-500');
                });
            });

            ['dragleave', 'drop'].forEach((eventName) => {
                dropzone.addEventListener(eventName, (event) => {
                    event.preventDefault();
                    dropzone.classList.remove('border-blue-500');
                });
            });

            dropzone.addEventListener('drop', (event) => {
                const dt = event.dataTransfer;

                if (! dt || ! dt.files || ! dt.files.length) {
                    return;
                }

                input.files = dt.files;
                updateFileInfo(dt.files);
            });

            input.addEventListener('change', () => updateFileInfo(input.files));
        })();
    </script>
@endPushOnce