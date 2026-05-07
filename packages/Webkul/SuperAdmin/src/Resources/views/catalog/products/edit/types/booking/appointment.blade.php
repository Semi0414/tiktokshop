{!! view_render_event('bagisto.admin.catalog.product.edit.booking.appointment.before', ['product' => $product]) !!}

<!-- Vue Component -->
<v-appointment-booking :bookingProduct="$bookingProduct ?? []"></v-appointment-booking>

{!! view_render_event('bagisto.admin.catalog.product.edit.booking.appointment.after', ['product' => $product]) !!}

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-appointment-booking-template"
    >
        <!-- Slot Duration -->
        <x-superadmin::form.control-group class="w-full">
            <x-superadmin::form.control-group.label class="required">
                @lang('superadmin::app.catalog.products.edit.types.booking.appointment.slot-duration')
            </x-superadmin::form.control-group.label>

            <x-superadmin::form.control-group.control
                type="text"
                name="booking[duration]"
                rules="required|min_value:1"
                v-model="appointment_booking.duration"
                :label="trans('superadmin::app.catalog.products.edit.types.booking.appointment.slot-duration')"
                :placeholder="trans('superadmin::app.catalog.products.edit.types.booking.appointment.slot-duration')"
            />

            <x-superadmin::form.control-group.error control-name="booking[duration]" />
        </x-superadmin::form.control-group>

        <!-- Break Time -->
        <x-superadmin::form.control-group class="w-full">
            <x-superadmin::form.control-group.label class="required">
                @lang('superadmin::app.catalog.products.edit.types.booking.appointment.break-duration')
            </x-superadmin::form.control-group.label>

            <x-superadmin::form.control-group.control
                type="text"
                name="booking[break_time]"
                rules="required|min_value:1"
                v-model="appointment_booking.break_time"
                :label="trans('superadmin::app.catalog.products.edit.types.booking.appointment.break-duration')"
                :placeholder="trans('superadmin::app.catalog.products.edit.types.booking.appointment.break-duration')"
            />

            <x-superadmin::form.control-group.error control-name="booking[break_time]" />
        </x-superadmin::form.control-group>

        <!-- Same slot for all days -->
        <x-superadmin::form.control-group class="w-full">
            <x-superadmin::form.control-group.label class="required">
                @lang('superadmin::app.catalog.products.edit.types.booking.appointment.same-slot-for-all-days.title')
            </x-superadmin::form.control-group.label>

            <x-superadmin::form.control-group.control
                type="select"
                name="booking[same_slot_all_days]"
                rules="required"
                v-model="appointment_booking.same_slot_all_days"
                :label="trans('superadmin::app.catalog.products.edit.types.booking.appointment.same-slot-for-all-days.title')"
                :placeholder="trans('superadmin::app.catalog.products.edit.types.booking.appointment.same-slot-for-all-days.title')"
            >
                <option value="1">
                    @lang('superadmin::app.catalog.products.edit.types.booking.appointment.same-slot-for-all-days.yes')
                </option>

                <option value="0">
                    @lang('superadmin::app.catalog.products.edit.types.booking.appointment.same-slot-for-all-days.no')
                </option>
            </x-superadmin::form.control-group.control>

            <x-superadmin::form.control-group.error control-name="booking[same_slot_all_days]" />
        </x-superadmin::form.control-group>

        <!-- Slots Vue Component -->
        <v-slots
            :booking-product="appointment_booking"
            :booking-type="'appointment_slot'"
            :same-slot-all-days="appointment_booking.same_slot_all_days"
        >
        </v-slots>
    </script>

    <script type="module">
        window.app.component('v-appointment-booking', {
            template: '#v-appointment-booking-template',

            props: ['bookingProduct'],

            data() {
                return {
                    appointment_booking: {!! json_encode($bookingProduct && $bookingProduct->appointment_slot
                        ? $bookingProduct->appointment_slot 
                        : [
                            'duration' => 45,

                            'break_time' => 15,

                            'same_slot_all_days' => 1,

                            'slots' => [],
                        ]
                    ) !!}
                }
            },
        });
    </script>
@endpushOnce