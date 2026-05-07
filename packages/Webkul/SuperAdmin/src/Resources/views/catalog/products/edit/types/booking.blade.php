@php
    $bookingProduct = app('\Webkul\BookingProduct\Repositories\BookingProductRepository')->findOneByField('product_id', $product->id)
@endphp

{!! view_render_event('bagisto.admin.catalog.product.edit.form.types.booking.before', ['product' => $product]) !!}

<!-- Vue Component -->
<v-booking-information></v-booking-information>

{!! view_render_event('bagisto.admin.catalog.product.edit.form.types.booking.after', ['product' => $product]) !!}

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-booking-information-template"
    >
        <div class="box-shadow relative rounded bg-white p-4 dark:bg-gray-900">
            <!-- Booking Type -->
            <x-superadmin::form.control-group class="w-full">
                <x-superadmin::form.control-group.label class="required">
                    @lang('superadmin::app.catalog.products.edit.types.booking.title')
                </x-superadmin::form.control-group.label>

                <x-superadmin::form.control-group.control
                    class="hidden"
                    name="booking[type]"
                    ::value="booking.type"
                />

                <x-superadmin::form.control-group.control
                    type="select"
                    name="booking[type]"
                    rules="required"
                    ::value="booking.type"
                    v-model="booking.type"
                    :label="trans('superadmin::app.catalog.products.edit.types.booking.title')"
                    ::disabled="! is_new"
                >
                    @foreach (['default', 'appointment', 'event', 'rental', 'table'] as $item)
                        <option value={{ $item }}>
                            @lang('superadmin::app.catalog.products.edit.types.booking.type.' . $item)
                        </option>
                    @endforeach
                </x-superadmin::form.control-group.control>

                <x-superadmin::form.control-group.error  control-name="booking[type]" />
            </x-superadmin::form.control-group>

            <!-- Location -->
            <x-superadmin::form.control-group class="w-full">
                <x-superadmin::form.control-group.label class="required">
                    @lang('superadmin::app.catalog.products.edit.types.booking.location')
                </x-superadmin::form.control-group.label>

                <x-superadmin::form.control-group.control
                    type="text"
                    rules="required"
                    name="booking[location]"
                    v-model="booking.location"
                    :label="trans('superadmin::app.catalog.products.edit.types.booking.location')"
                />

                <x-superadmin::form.control-group.error  control-name="booking[location]" />
            </x-superadmin::form.control-group>

            <!-- QTY -->
            <x-superadmin::form.control-group
                class="w-full"
                v-if="booking.type == 'default'
                    || booking.type == 'appointment'
                    || booking.type == 'rental'"
            >
                <x-superadmin::form.control-group.label class="required">
                    @lang('superadmin::app.catalog.products.edit.types.booking.qty')
                </x-superadmin::form.control-group.label>

                <x-superadmin::form.control-group.control
                    type="text"
                    name="booking[qty]"
                    rules="required|numeric|min:0"
                    v-model="booking.qty"
                    :label="trans('superadmin::app.catalog.products.edit.types.booking.qty')"
                />

                <x-superadmin::form.control-group.error  control-name="booking[qty]" />
            </x-superadmin::form.control-group>

            <!-- Available Every Week -->
            <x-superadmin::form.control-group
                class="w-full"
                v-if="booking.type != 'event' && booking.type != 'default'"
            >
                <x-superadmin::form.control-group.label class="required">
                    @lang('superadmin::app.catalog.products.edit.types.booking.available-every-week.title')
                </x-superadmin::form.control-group.label>

                <x-superadmin::form.control-group.control
                    type="select"
                    name="booking[available_every_week]"
                    rules="required"
                    v-model="booking.available_every_week"
                    :label="trans('superadmin::app.catalog.products.edit.types.booking.available-every-week.title')"
                    @change="booking.availableEveryWeekSwatch= ! booking.availableEveryWeekSwatch"
                >
                    <option value="1">
                        @lang('superadmin::app.catalog.products.edit.types.booking.available-every-week.yes')
                    </option>

                    <option value="0">
                        @lang('superadmin::app.catalog.products.edit.types.booking.available-every-week.no')
                    </option>
                </x-superadmin::form.control-group.control>

                <x-superadmin::form.control-group.error  control-name="booking[available_every_week]" />
            </x-superadmin::form.control-group>

            <div
                class="flex gap-2.5"
                v-if="! parseInt(booking.available_every_week)"
            >
                <!-- Available From  -->
                <x-superadmin::form.control-group class="w-full">
                    <x-superadmin::form.control-group.label class="required">
                        @lang('superadmin::app.catalog.products.edit.types.booking.available-from')
                    </x-superadmin::form.control-group.label>

                    @php
                        $dateMin = \Carbon\Carbon::yesterday()->format('Y-m-d 23:59:59');
                    @endphp

                    <x-superadmin::form.control-group.control
                        type="datetime"
                        name="booking[available_from]"
                        :rules="'required|after:' . $dateMin"
                        v-model="booking.available_from"
                        :label="trans('superadmin::app.catalog.products.edit.types.booking.available-from')"
                        :placeholder="trans('superadmin::app.catalog.products.edit.types.booking.available-from')"
                    />

                    <x-superadmin::form.control-group.error  control-name="booking[available_from]" />
                </x-superadmin::form.control-group>

                <!-- Available To -->
                <x-superadmin::form.control-group class="w-full">
                    <x-superadmin::form.control-group.label class="required">
                        @lang('superadmin::app.catalog.products.edit.types.booking.available-to')
                    </x-superadmin::form.control-group.label>

                    <x-superadmin::form.control-group.control
                        type="datetime"
                        name="booking[available_to]"
                        ::rules="'required|after:' + booking.available_from"
                        v-model="booking.available_to"
                        :label="trans('superadmin::app.catalog.products.edit.types.booking.available-to')"
                        :placeholder="trans('superadmin::app.catalog.products.edit.types.booking.available-to')"
                    />

                    <x-superadmin::form.control-group.error  control-name="booking[available_to]" />
                </x-superadmin::form.control-group>
            </div>

            @php
                $bookingTypes = [
                    'default',
                    'appointment',
                    'event',
                    'rental',
                    'table'
                ];
            @endphp

            @foreach ($bookingTypes as $type)
                <template v-if="booking.type === '{{ $type }}'">
                    @include('superadmin::catalog.products.edit.types.booking.' . $type, ['bookingProduct' => $bookingProduct])
                </template>
            @endforeach
        </div>
    </script>

    <script type="module">
        defineRule('required_if', (value, { condition = true } = {}) => {
            if (condition) {
                if (
                    value === null
                    || value === undefined
                    || value === ''
                ) {
                    return false;
                }
            }

            return true;
        });

        defineRule('after', (value, [target]) => {
            if (! value || ! target) {
                return false;
            }

            return new Date(value) > new Date(target);
        });

        window.app.component('v-booking-information', {
            template: '#v-booking-information-template',

            data() {
                return {
                    is_new: @json($bookingProduct) ? false : true,

                    booking: @json($bookingProduct) ? @json($bookingProduct) : {

                        type: 'default',

                        location: '',

                        qty: 0,

                        available_every_week: 0,

                        availableEveryWeekSwatch: true,

                        available_from: '',

                        available_to: ''
                    }
                }
            },

            created() {
                this.booking.available_from = "{{ $bookingProduct && $bookingProduct->available_from ? $bookingProduct->available_from->format('Y-m-d H:i:s') : '' }}";

                this.booking.available_to = "{{ $bookingProduct && $bookingProduct->available_to ? $bookingProduct->available_to->format('Y-m-d H:i:s') : '' }}";
            }
        });
    </script>

    <!-- Slots component Included -->
    @include('superadmin::catalog.products.edit.types.booking.slots')

    <!-- Empty Info Page Included -->
    @include('superadmin::catalog.products.edit.types.booking.empty-info')
@endpushOnce
