{{--
    Nested category list for the mobile drawer (replaces v-mobile-category).
    Expects: $categories — tree branch (collection)
--}}
<ul class="{{ ($depth ?? 0) ? 'mt-2 space-y-1 border-l border-zinc-100 pl-4' : 'space-y-1' }}">
    @foreach ($categories as $category)
        <li>
            <a
                href="{{ route('shop.captcha-gate.index', ['redirect' => $category->url]) }}"
                class="inline-flex items-center gap-2 py-2 text-base font-medium text-black"
            >
                @if ($category->logo_url)
                    <img
                        src="{{ $category->logo_url }}"
                        alt=""
                        class="h-6 w-6 rounded-full object-cover"
                    >
                @else
                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-zinc-200 text-[10px] font-semibold text-zinc-600">
                        {{ mb_strtoupper(mb_substr($category->name, 0, 1)) }}
                    </span>
                @endif

                <span>{{ $category->name }}</span>
            </a>

            @if ($category->children && $category->children->count())
                @include('shop::components.layouts.header.partials.mobile-categories-html', [
                    'categories' => $category->children,
                    'depth' => ($depth ?? 0) + 1,
                ])
            @endif
        </li>
    @endforeach
</ul>
