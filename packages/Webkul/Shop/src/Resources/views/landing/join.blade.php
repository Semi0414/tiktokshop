<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@if ($__sfLegacy)Seller Join — TikTok Shop Partnership @else Seller application — {{ $__sfName }} @endif</title>
    <link rel="icon" type="image/webp" href="{{ asset('storage/theme/1/favicon.webp') }}" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body { background: #1a1a1a; }
        .join-hero {
            position: relative;
            min-height: 320px;
            background: #2d2d2d url('{{ asset('images/seller-join/banner.jpg') }}') center/cover no-repeat;
        }
        .join-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, rgba(0,0,0,.88) 0%, rgba(0,0,0,.55) 45%, rgba(0,0,0,.15) 100%);
        }
        .join-hero-inner { position: relative; z-index: 1; padding: 2.5rem 1rem 6rem; max-width: 980px; margin: 0 auto; }
        .join-hero h1 {
            color: #ff6b00;
            font-weight: 800;
            font-size: clamp(1.75rem, 4vw, 2.25rem);
            margin-bottom: 1rem;
        }
        .join-hero p.lead {
            color: #fff;
            max-width: 36rem;
            font-size: 0.95rem;
            line-height: 1.55;
            margin: 0;
        }
        .join-wrap { max-width: 980px; margin: -4rem auto 3rem; padding: 0 12px; position: relative; z-index: 2; }
        .join-card { border: 1px solid rgba(0,0,0,.06); border-radius: .5rem; }
        .req::after { content: " *"; color: #dc3545; font-weight: 600; }
        .col-form-label { font-weight: 500; color: #343a40; }
        .id-upload-slot {
            border: 2px dashed #ced4da;
            border-radius: .375rem;
            min-height: 140px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fafafa;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }
        .id-upload-slot:hover { border-color: #fd7e14; background: #fff8f0; }
        .id-upload-slot input[type=file] {
            position: absolute;
            inset: 0;
            opacity: 0;
            cursor: pointer;
            z-index: 3;
        }
        .id-upload-slot .id-upload-placeholder {
            position: relative;
            z-index: 1;
            pointer-events: none;
            font-size: 2rem;
            color: #adb5bd;
        }
        .id-upload-slot.has-preview .id-upload-placeholder { display: none; }
        .id-upload-slot img.id-upload-preview {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: 2;
            pointer-events: none;
            border-radius: .25rem;
        }
        .id-upload-slot.has-preview img.id-upload-preview { display: block !important; }
        .id-upload-slot.shop-logo-upload-slot {
            min-height: 0;
            width: min(160px, 100%);
            aspect-ratio: 1;
            height: auto;
        }
        .id-examples { margin-top: .75rem; }
        .id-examples figure { margin: 0; }
        .id-examples img {
            width: 100%;
            max-width: 160px;
            height: auto;
            border-radius: 6px;
            border: 1px solid #e9ecef;
            box-shadow: 0 1px 3px rgba(0,0,0,.08);
        }
        .id-examples figcaption {
            font-size: 0.7rem;
            color: #6c757d;
            margin-top: .35rem;
            line-height: 1.3;
        }
        #occupancyAgreementModal .modal-body {
            line-height: 1.6;
        }
        #occupancyAgreementModal .modal-body h3,
        #occupancyAgreementModal .modal-body .agreement-section-title {
            font-size: 1rem;
            font-weight: 700;
            margin-top: 1.25rem;
            margin-bottom: 0.5rem;
        }
        #occupancyAgreementModal .modal-body h3:first-child {
            margin-top: 0;
        }
    </style>
</head>
<body>
    <section class="join-hero">
        <div class="join-hero-inner">
            <h1>@if ($__sfLegacy)TikTok Shop Partnership @else Seller partnership — {{ $__sfName }} @endif</h1>
            <p class="lead">
                @if ($__sfLegacy)
                Our partner program provides you with a full range of marketing support and services, and our customer service team will provide professional support and advice to help you optimize your product display and sales strategies. Join us now! Let us create greater business opportunities and grow together!
                @else
                Apply to sell on {{ $__sfName }}. Our team reviews applications and may contact you about your shop details, verification documents, and compliance. Complete the form accurately — you are creating credentials for your seller account on this site only.
                @endif
            </p>
        </div>
    </section>

    <div class="join-wrap">
        <div class="card join-card shadow-lg bg-white">
            <div class="card-body p-4 p-md-5">
                <h2 class="h3 mb-2">Business information</h2>
                <p class="text-muted mb-4">
                    @if ($__sfLegacy)
                    If you are already a seller?
                    <a href="{{ route('admin.session.create') }}" class="link-warning text-decoration-none fw-semibold">Click to log in</a>
                    @else
                    Already have a seller account?
                    <a href="{{ route('admin.session.create') }}" class="link-warning text-decoration-none fw-semibold">Seller Dashboard</a>
                    @endif
                </p>

                @if (session('success'))
                    <div class="alert alert-success border-0 shadow-sm" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="post" action="{{ route('shop.landing.join.submit') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row mb-3 align-items-start">
                        <label class="col-md-3 col-form-label req" for="shop_logo">Shop logo</label>
                        <div class="col-md-9">
                            <div class="id-upload-slot shop-logo-upload-slot @error('shop_logo') border-danger @enderror">
                                <img src="" alt="" class="id-upload-preview d-none" id="shop_logo_preview">
                                <span class="id-upload-placeholder" aria-hidden="true">📷</span>
                                <input
                                    type="file"
                                    name="shop_logo"
                                    id="shop_logo"
                                    accept="image/*"
                                    required
                                >
                            </div>
                            @error('shop_logo')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3 align-items-start">
                        <label class="col-md-3 col-form-label req" for="shop_name">Shop name</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control @error('shop_name') is-invalid @enderror" name="shop_name" id="shop_name" value="{{ old('shop_name') }}" placeholder="Please enter the store name" required>
                            @error('shop_name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3 align-items-start">
                        <label class="col-md-3 col-form-label req" for="shop_address">Shop Address</label>
                        <div class="col-md-9">
                            <textarea class="form-control @error('shop_address') is-invalid @enderror" name="shop_address" id="shop_address" rows="3" placeholder="Please enter the store address" required>{{ old('shop_address') }}</textarea>
                            @error('shop_address')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3 align-items-start">
                        <label class="col-md-3 col-form-label req" for="country">Country</label>
                        <div class="col-md-9">
                            <select
                                class="form-select @error('country') is-invalid @enderror"
                                name="country"
                                id="country"
                                required
                            >
                                <option value="" disabled @selected(! old('country'))>Select country</option>
                                @foreach (collect(core()->countries())->sortBy('name') as $country)
                                    <option value="{{ $country->code }}" @selected(old('country') === $country->code)>
                                        {{ $country->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('country')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3 align-items-start">
                        <label class="col-md-3 col-form-label req" for="id_passport_number">ID/passport number</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control @error('id_passport_number') is-invalid @enderror" name="id_passport_number" id="id_passport_number" value="{{ old('id_passport_number') }}" required>
                            @error('id_passport_number')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3 align-items-start">
                        <label class="col-md-3 col-form-label req" for="legal_name">Legal name</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control @error('legal_name') is-invalid @enderror" name="legal_name" id="legal_name" value="{{ old('legal_name') }}" placeholder="Please enter your full name" required>
                            @error('legal_name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3 align-items-start">
                        <label class="col-md-3 col-form-label req pt-0">ID/passport upload</label>
                        <div class="col-md-9">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="id-upload-slot @error('document_front') border-danger @enderror">
                                        <img src="" alt="" class="id-upload-preview d-none" id="preview_document_front">
                                        <span class="id-upload-placeholder" aria-hidden="true">📷</span>
                                        <input type="file" name="document_front" id="input_document_front" accept="image/*" required>
                                    </div>
                                    <div class="small text-muted text-center mt-1">Front of the document</div>
                                    @error('document_front')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <div class="id-upload-slot @error('document_back') border-danger @enderror">
                                        <img src="" alt="" class="id-upload-preview d-none" id="preview_document_back">
                                        <span class="id-upload-placeholder" aria-hidden="true">📷</span>
                                        <input type="file" name="document_back" id="input_document_back" accept="image/*" required>
                                    </div>
                                    <div class="small text-muted text-center mt-1">Reverse side of document</div>
                                    @error('document_back')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <div class="id-upload-slot @error('document_selfie') border-danger @enderror">
                                        <img src="" alt="" class="id-upload-preview d-none" id="preview_document_selfie">
                                        <span class="id-upload-placeholder" aria-hidden="true">📷</span>
                                        <input type="file" name="document_selfie" id="input_document_selfie" accept="image/*" required>
                                    </div>
                                    <div class="small text-muted text-center mt-1">Hold up the document next to your face</div>
                                    @error('document_selfie')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <p class="small fw-semibold mt-3 mb-2">Image example (what to upload)</p>
                            <div class="id-examples row g-3 justify-content-center">
                                <div class="col-6 col-md-4 text-center">
                                    <figure>
                                        <img src="{{ asset('images/seller-join/examples/id-front.svg') }}" width="160" height="112" alt="Example: ID front with photo">
                                        <figcaption>Front: your photo and name visible, whole card in frame.</figcaption>
                                    </figure>
                                </div>
                                <div class="col-6 col-md-4 text-center">
                                    <figure>
                                        <img src="{{ asset('images/seller-join/examples/id-back.svg') }}" width="160" height="112" alt="Example: ID back">
                                        <figcaption>Back: barcode / text side, not blurry.</figcaption>
                                    </figure>
                                </div>
                                <div class="col-6 col-md-4 text-center">
                                    <figure>
                                        <img src="{{ asset('images/seller-join/examples/id-selfie.svg') }}" width="160" height="112" alt="Example: selfie holding ID">
                                        <figcaption>Selfie: your face + ID next to cheek, both readable.</figcaption>
                                    </figure>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3 align-items-start">
                        <label class="col-md-3 col-form-label req">Verify your Email or Mobile number</label>
                        <div class="col-md-9">
                            <div class="btn-group" role="group" aria-label="Verification method">
                                <button type="button" class="btn btn-warning" id="tab-email">E-mail</button>
                                <button type="button" class="btn btn-outline-secondary" id="tab-mobile">Mobile</button>
                            </div>
                            <input type="hidden" name="verify_type" id="verify_type" value="{{ old('verify_type', 'email') }}">
                        </div>
                    </div>

                    <div class="row mb-3 align-items-start" id="email-row">
                        <label class="col-md-3 col-form-label req" for="email">E-mail</label>
                        <div class="col-md-9">
                            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="email" value="{{ old('email') }}" {{ old('verify_type', 'email') === 'email' ? 'required' : '' }}>
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3 align-items-start d-none" id="mobile-row">
                        <label class="col-md-3 col-form-label req" for="mobile">Mobile</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control @error('mobile') is-invalid @enderror" name="mobile" id="mobile" value="{{ old('mobile') }}" {{ old('verify_type') === 'mobile' ? 'required' : '' }}>
                            @error('mobile')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3 align-items-start">
                        <label class="col-md-3 col-form-label req" for="password">@if ($__sfLegacy)Login password @else Create your {{ $__sfName }} seller password @endif</label>
                        <div class="col-md-9">
                            <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="password" required autocomplete="new-password">
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3 align-items-start">
                        <label class="col-md-3 col-form-label req" for="password_confirmation">@if ($__sfLegacy)Confirm login password @else Confirm seller password @endif</label>
                        <div class="col-md-9">
                            <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" required autocomplete="new-password">
                        </div>
                    </div>

                    <div class="row mb-3 align-items-start">
                        <label class="col-md-3 col-form-label req" for="invite_code">Invite code</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control @error('invite_code') is-invalid @enderror" name="invite_code" id="invite_code" value="{{ old('invite_code') }}" placeholder="Enter a valid referral code" required autocomplete="off">
                            @error('invite_code')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-9 offset-md-3">
                            <div class="form-check justify-content-center">
                                <input class="form-check-input @error('agreement') is-invalid @enderror" type="checkbox" name="agreement" id="agreement" value="1" {{ old('agreement') ? 'checked' : '' }} required>
                                <label class="form-check-label" for="agreement">
                                    I have read and agree to the
                                    <a
                                        href="#occupancyAgreementModal"
                                        class="link-warning text-decoration-none fw-semibold"
                                        data-bs-toggle="modal"
                                        data-bs-target="#occupancyAgreementModal"
                                    >Occupancy Agreement</a>
                                </label>
                                @error('agreement')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="text-center pt-2">
                        <button type="submit" class="btn btn-warning btn-lg px-5 fw-semibold text-dark">Submit application</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div
        class="modal fade"
        id="occupancyAgreementModal"
        tabindex="-1"
        aria-labelledby="occupancyAgreementModalLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="occupancyAgreementModalLabel">Merchant entry agreement</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-secondary small">
                    <p class="text-dark mb-3">
                        Welcome to become a merchant partner of the TikTokMall platform. In order to maintain the normal operation of the platform and protect the rights and interests of you and consumers, we have formulated the following merchant entry agreement:
                    </p>

                    <h3>1. Admission requirements</h3>
                    <ol>
                        <li>Merchants must provide true and valid personal information, contact information and other relevant certification materials, and ensure that the information provided is true, accurate, complete, legal and valid;</li>
                        <li>Merchants should sell products produced or supplied by suppliers provided by the platform, and bear legal responsibilities and economic losses arising from the sale of products not provided by platform suppliers;</li>
                        <li>Merchants must have sufficient capital, technology, service and other capabilities to meet the needs of consumers and bear corresponding commercial risks.</li>
                    </ol>

                    <h3>2. Cooperation method</h3>
                    <ol>
                        <li>Merchants can submit an application through the online application system provided by the TikTokMall platform, and can officially become a cooperative merchant of the platform after being approved by the platform;</li>
                        <li>The goods sold by the merchant are provided by the platform supplier, and the merchant should ensure that the goods provided are consistent with the actual goods;</li>
                        <li>Merchants can conduct commodity management, order processing, transaction settlement and other operations through the sales management system provided by the platform;</li>
                        <li>Merchants must abide by the platform's "Seller Policy" and "Privacy Policy" to ensure their own legal and compliant operations, and bear legal responsibility and economic losses arising from violations.</li>
                    </ol>

                    <h3>3. Commodity information and service quality</h3>
                    <ol>
                        <li>Merchants need to provide true, accurate and complete product information, including but not limited to product name, price, specification, description, picture and other information, and ensure that the product information is consistent with the actual product;</li>
                        <li>Merchants should provide goods and services that comply with national laws and regulations and platform requirements to ensure the quality and safety of goods and services;</li>
                        <li>Merchants should respond to consumers' inquiries, complaints and other information in a timely manner, and actively solve related problems to ensure that consumers' rights and interests are protected.</li>
                    </ol>

                    <h3>4. Commission and Settlement</h3>
                    <ol>
                        <li>The merchant pays the commission according to the commission standard stipulated by the platform, and ensures the legitimacy and accuracy of the commission;</li>
                        <li>The platform will settle the commission according to the agreed settlement cycle based on the transaction volume and sales data of the merchant.</li>
                    </ol>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        (function () {
            const tabEmail = document.getElementById('tab-email');
            const tabMobile = document.getElementById('tab-mobile');
            const verifyType = document.getElementById('verify_type');
            const emailRow = document.getElementById('email-row');
            const mobileRow = document.getElementById('mobile-row');
            const emailInput = document.getElementById('email');
            const mobileInput = document.getElementById('mobile');

            function setType(type) {
                verifyType.value = type;

                if (type === 'mobile') {
                    tabMobile.classList.remove('btn-outline-secondary');
                    tabMobile.classList.add('btn-warning');
                    tabEmail.classList.remove('btn-warning');
                    tabEmail.classList.add('btn-outline-secondary');
                    emailRow.classList.add('d-none');
                    mobileRow.classList.remove('d-none');
                    if (emailInput) { emailInput.removeAttribute('required'); emailInput.value = ''; }
                    if (mobileInput) mobileInput.setAttribute('required', 'required');
                } else {
                    tabEmail.classList.remove('btn-outline-secondary');
                    tabEmail.classList.add('btn-warning');
                    tabMobile.classList.remove('btn-warning');
                    tabMobile.classList.add('btn-outline-secondary');
                    emailRow.classList.remove('d-none');
                    mobileRow.classList.add('d-none');
                    if (mobileInput) { mobileInput.removeAttribute('required'); mobileInput.value = ''; }
                    if (emailInput) emailInput.setAttribute('required', 'required');
                }
            }

            tabEmail.addEventListener('click', function () { setType('email'); });
            tabMobile.addEventListener('click', function () { setType('mobile'); });
            setType(verifyType.value || 'email');
        })();

        (function () {
            function revokeIfAny(img) {
                if (img && img.dataset.blobUrl) {
                    URL.revokeObjectURL(img.dataset.blobUrl);
                    delete img.dataset.blobUrl;
                }
            }

            function bindSlotPreview(inputId, imgId) {
                const input = document.getElementById(inputId);
                const img = document.getElementById(imgId);
                if (!input || !img) {
                    return;
                }

                input.addEventListener('change', function () {
                    const file = this.files && this.files[0];
                    revokeIfAny(img);

                    if (!file || !file.type.startsWith('image/')) {
                        img.src = '';
                        img.classList.add('d-none');
                        var slotClear = this.closest('.id-upload-slot');
                        if (slotClear) {
                            slotClear.classList.remove('has-preview');
                        }

                        return;
                    }

                    const url = URL.createObjectURL(file);
                    img.dataset.blobUrl = url;
                    img.src = url;
                    img.classList.remove('d-none');
                    var slotAdd = this.closest('.id-upload-slot');
                    if (slotAdd) {
                        slotAdd.classList.add('has-preview');
                    }
                });
            }

            bindSlotPreview('shop_logo', 'shop_logo_preview');
            bindSlotPreview('input_document_front', 'preview_document_front');
            bindSlotPreview('input_document_back', 'preview_document_back');
            bindSlotPreview('input_document_selfie', 'preview_document_selfie');
        })();

        (function () {
            const agreement = document.getElementById('agreement');
            const modalEl = document.getElementById('occupancyAgreementModal');
            if (! agreement || ! modalEl || typeof bootstrap === 'undefined' || ! bootstrap.Modal) {
                return;
            }

            agreement.addEventListener('change', function () {
                if (this.checked) {
                    bootstrap.Modal.getOrCreateInstance(modalEl).show();
                }
            });
        })();
    </script>
@include('shop::components.layouts.storefront-chat-widgets')
</body>
</html>
