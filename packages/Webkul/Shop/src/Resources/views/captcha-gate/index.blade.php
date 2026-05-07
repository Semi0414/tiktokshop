<x-shop::layouts
    :has-header="false"
    :has-feature="false"
    :has-footer="false"
>
    <div class="container mt-20 max-1180:px-5 max-md:mt-12">
        <div class="auth-card m-auto w-full max-w-[720px] rounded-xl border border-zinc-200 p-10 px-[70px] max-md:px-8 max-md:py-8 max-sm:border-none max-sm:p-0">
            <h1 class="font-dmserif text-3xl max-md:text-2xl max-sm:text-xl">
                Privacy policy & captcha verification
            </h1>

            <p class="mt-3 text-zinc-600">
                Access is protected. Please accept the privacy policy and complete the captcha.
            </p>

            <div class="mt-8">
                <x-shop::form
                    :action="route('shop.captcha-gate.verify')"
                    method="POST"
                >
                    @csrf

                    <input
                        type="hidden"
                        name="privacy_accepted"
                        id="privacy_accepted"
                        value="0"
                    >

                    <input
                        type="hidden"
                        name="redirect"
                        value="{{ $redirectUrl }}"
                    >

                    <div id="privacy_step" class="rounded-xl border border-zinc-200 p-4">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-zinc-700">
                                Privacy policy
                            </p>
                        </div>

                        <div class="mt-3 text-sm text-zinc-600">
                            Please accept our privacy policy to proceed.
                        </div>

                        <x-shop::form.control-group.error control-name="privacy_accepted" />

                        <div class="mt-4 flex flex-wrap items-center gap-4">
                            <button
                                type="button"
                                class="primary-button rounded-xl px-6 py-3"
                                onclick="acceptPrivacy()"
                            >
                                Accept
                            </button>

                            <button
                                type="button"
                                class="secondary-button rounded-xl px-6 py-3"
                                onclick="rejectPrivacy()"
                            >
                                Reject
                            </button>
                        </div>
                    </div>

                    <div id="captcha_step" class="mt-5 hidden">
                        {!! \Webkul\Customer\Facades\Captcha::render() !!}

                        <div class="mt-2">
                            <x-shop::form.control-group.error control-name="g-recaptcha-response" />
                        </div>

                        <button
                            type="submit"
                            class="primary-button mt-6 rounded-xl px-8 py-3"
                        >
                            Verify & Continue
                        </button>
                    </div>
                </x-shop::form>
            </div>
        </div>
    </div>

    @push('scripts')
        {!! \Webkul\Customer\Facades\Captcha::renderJS() !!}

        <script>
            function acceptPrivacy() {
                document.getElementById('privacy_accepted').value = '1';

                const privacyStep = document.getElementById('privacy_step');
                const captchaStep = document.getElementById('captcha_step');

                if (privacyStep) {
                    privacyStep.classList.add('hidden');
                }

                if (captchaStep) {
                    captchaStep.classList.remove('hidden');
                }
            }

            function rejectPrivacy() {
                window.location.href = "{{ route(\Webkul\Shop\Support\ShopRoutes::publicEntryRouteName()) }}";
            }
        </script>
    @endpush
</x-shop::layouts>

