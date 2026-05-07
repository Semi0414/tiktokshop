<!-- Shipment Vue Components -->
<v-create-shipment>
    <div
        class="transparent-button px-1 py-1.5 hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
    >
        <span class="icon-ship text-2xl"></span>

        @lang('superadmin::app.sales.orders.view.ship')
    </div>
</v-create-shipment>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-create-shipment-template"
    >
        <div>
            <div
                class="transparent-button px-1 py-1.5 hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
                @click="$refs.shipment.open()"
            >
                <span
                    class="icon-ship text-2xl"
                    role="button"
                    tabindex="0"
                >
                </span>

                @lang('superadmin::app.sales.orders.view.ship')
            </div>

            <!-- Shipment Create Drawer -->
            <x-superadmin::form
                method="POST"
                :action="route('superadmin.sales.shipments.store', $order->id)"
            >
                <x-superadmin::drawer ref="shipment">
                    <!-- Drawer Header -->
                    <x-slot:header>
                        <div class="grid gap-3 sm:h-8">
                            <div class="flex items-center justify-between">
                                <p class="text-xl font-medium dark:text-white">
                                    @lang('superadmin::app.sales.shipments.create.title')
                                </p>

                                @if (bouncer()->hasPermission('sales.shipments.create'))
                                    <button
                                        type="submit"
                                        class="primary-button ltr:mr-11 rtl:ml-11"
                                    >
                                        @lang('superadmin::app.sales.shipments.create.create-btn')
                                    </button>
                                @endif
                            </div>
                        </div>
                    </x-slot>

                    <!-- Drawer Content -->
                    <x-slot:content class="!p-0">
                        <div class="grid p-4 pt-2">
                            <div class="grid grid-cols-2 gap-x-5">
                                <!-- Carrier Name -->
                                <x-superadmin::form.control-group>
                                    <x-superadmin::form.control-group.label>
                                        @lang('superadmin::app.sales.shipments.create.carrier-name')
                                    </x-superadmin::form.control-group.label>

                                    <x-superadmin::form.control-group.control
                                        type="text"
                                        id="shipment[carrier_title]"
                                        name="shipment[carrier_title]"
                                        :label="trans('superadmin::app.sales.shipments.create.carrier-name')"
                                        :placeholder="trans('superadmin::app.sales.shipments.create.carrier-name')"
                                    />

                                    <x-superadmin::form.control-group.error control-name="carrier_name" />
                                </x-superadmin::form.control-group>

                                <!-- Tracking Number -->
                                <x-superadmin::form.control-group>
                                    <x-superadmin::form.control-group.label>
                                        @lang('superadmin::app.sales.shipments.create.tracking-number')
                                    </x-superadmin::form.control-group.label>

                                    <x-superadmin::form.control-group.control
                                        type="text"
                                        id="shipment[track_number]"
                                        name="shipment[track_number]"
                                        :label="trans('superadmin::app.sales.shipments.create.tracking-number')"
                                        :placeholder="trans('superadmin::app.sales.shipments.create.tracking-number')"
                                    />

                                    <x-superadmin::form.control-group.error control-name="shipment[track_number]" />
                                </x-superadmin::form.control-group>
                            </div>

                            <!-- Resource -->
                            <x-superadmin::form.control-group>
                                <x-superadmin::form.control-group.label class="required">
                                    @lang('superadmin::app.sales.shipments.create.source')
                                </x-superadmin::form.control-group.label>

                                <x-superadmin::form.control-group.control
                                    type="select"
                                    id="shipment[source]"
                                    name="shipment[source]"
                                    rules="required"
                                    v-model="source"
                                    :label="trans('superadmin::app.sales.shipments.create.source')"
                                    :placeholder="trans('superadmin::app.sales.shipments.create.source')"
                                    @change="onSourceChange"
                                >
                                    @foreach ($order->channel->inventory_sources as $inventorySource)
                                        <option 
                                            value="{{ $inventorySource->id }}"
                                            v-pre
                                        >
                                            {{ $inventorySource->name }}
                                        </option>
                                    @endforeach
                                </x-superadmin::form.control-group.control>

                                <x-superadmin::form.control-group.error control-name="shipment[source]" />
                            </x-superadmin::form.control-group>

                            <div class="grid">
                                <!-- Item Listing -->
                                @foreach ($order->items as $item)
                                    @if (
                                        $item->qty_to_ship > 0
                                        && $item->product
                                    )
                                        <div class="flex justify-between gap-2.5 py-4">
                                            <div class="flex gap-2.5">
                                                @if ($item->product?->base_image_url)
                                                    <img
                                                        class="relative h-[60px] max-h-[60px] w-full max-w-[60px] rounded"
                                                        src="{{ $item->product?->base_image_url }}"
                                                    >
                                                @else
                                                    <div class="relative h-[60px] max-h-[60px] w-full max-w-[60px] rounded border border-dashed border-gray-300 dark:border-gray-800 dark:mix-blend-exclusion dark:invert">
                                                        <img src="{{ bagisto_asset('images/product-placeholders/front.svg') }}">

                                                        <p class="absolute bottom-1.5 w-full text-center text-[6px] font-semibold text-gray-400">
                                                            @lang('superadmin::app.sales.invoices.view.product-image')
                                                        </p>
                                                    </div>
                                                @endif

                                                <div class="grid place-content-start gap-1.5">
                                                    <!-- Item Name -->
                                                    <p 
                                                        class="text-base font-semibold text-gray-800 dark:text-white"
                                                        v-pre
                                                    >
                                                        {{ $item->name }}
                                                    </p>

                                                    <div class="flex flex-col place-items-start gap-1.5">
                                                        <p class="text-gray-600 dark:text-gray-300">
                                                            @lang('superadmin::app.sales.shipments.create.amount-per-unit', [
                                                                'amount' => core()->formatBasePrice($item->base_price),
                                                                'qty'    => $item->qty_ordered,
                                                            ])
                                                        </p>

                                                        <!--Additional Attributes -->
                                                        @if (isset($item->additional['attributes']))
                                                            @foreach ($item->additional['attributes'] as $attribute)
                                                                <p 
                                                                    class="text-gray-600 dark:text-gray-300"
                                                                    v-pre
                                                                >
                                                                    @if (
                                                                        ! isset($attribute['attribute_type'])
                                                                        || $attribute['attribute_type'] !== 'file'
                                                                    )
                                                                        {{ $attribute['attribute_name'] }} : {{ $attribute['option_label'] }}
                                                                    @else
                                                                        {{ $attribute['attribute_name'] }} :

                                                                        <a
                                                                            href="{{ Storage::url($attribute['option_label']) }}"
                                                                            class="text-blue-600 hover:underline"
                                                                            download="{{ File::basename($attribute['option_label']) }}"
                                                                        >
                                                                            {{ File::basename($attribute['option_label']) }}
                                                                        </a>
                                                                    @endif
                                                                </p>
                                                            @endforeach
                                                        @endif

                                                        <!-- Item SKU -->
                                                        <p class="text-gray-600 dark:text-gray-300">
                                                            @lang('superadmin::app.sales.shipments.create.sku', ['sku' => $item->sku])
                                                        </p>

                                                        <!--Item Status -->
                                                        <p class="text-gray-600 dark:text-gray-300">
                                                            {{ $item->qty_ordered ? trans('superadmin::app.sales.shipments.create.item-ordered', ['qty_ordered' => $item->qty_ordered]) : '' }}

                                                            {{ $item->qty_invoiced ? trans('superadmin::app.sales.shipments.create.item-invoice', ['qty_invoiced' => $item->qty_invoiced]) : '' }}

                                                            {{ $item->qty_shipped ? trans('superadmin::app.sales.shipments.create.item-shipped', ['qty_shipped' => $item->qty_shipped]) : '' }}

                                                            {{ $item->qty_refunded ? trans('superadmin::app.sales.shipments.create.item-refunded', ['qty_refunded' => $item->qty_refunded]) : '' }}

                                                            {{ $item->qty_canceled ? trans('superadmin::app.sales.shipments.create.item-canceled', ['qty_canceled' => $item->qty_canceled]) : '' }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Information -->
                                        @foreach ($order->channel->inventory_sources as $inventorySource)
                                            <div class="grid grid-cols-2 gap-2.5 border-b border-slate-300 py-2.5 dark:border-gray-800">
                                                <div class="grid gap-1">
                                                    <!--Inventory Source -->
                                                    <p
                                                        class="text-base font-semibold text-gray-800 dark:text-white"
                                                        v-pre
                                                    >
                                                        {{ $inventorySource->name }}
                                                    </p>

                                                    <!-- Available Quantity -->
                                                    <p class="text-gray-600 dark:text-gray-300">
                                                        @lang('superadmin::app.sales.shipments.create.qty-available') :

                                                        @php
                                                            $product = $item->getTypeInstance()->getOrderedItem($item)->product;

                                                            $sourceQty = $product?->type == 'bundle' ? $item->qty_ordered : $product?->inventory_source_qty($inventorySource->id);
                                                        @endphp

                                                        {{ $sourceQty }}
                                                    </p>
                                                </div>

                                                <div class="grid ltr:text-right rtl:text-left">
                                                    @php
                                                        $inputName = "shipment[items][$item->id][$inventorySource->id]";
                                                    @endphp

                                                    <!-- Quantity To Ship -->
                                                    <x-superadmin::form.control-group class="!mb-0">
                                                        <x-superadmin::form.control-group.label class="required !block">
                                                            @lang('superadmin::app.sales.shipments.create.qty-to-ship')
                                                        </x-superadmin::form.control-group.label>

                                                        <x-superadmin::form.control-group.control
                                                            type="text"
                                                            class="!w-[100px]"
                                                            :id="$inputName"
                                                            :name="$inputName"
                                                            :rules="'required|numeric|min_value:0|max_value:' . $item->qty_ordered"
                                                            :value="$item->qty_to_ship"
                                                            :label="trans('superadmin::app.sales.shipments.create.qty-to-ship')"
                                                            data-original-quantity="{{ $item->qty_to_ship }}"
                                                            ::disabled="'{{ empty($sourceQty) }}' || source != '{{ $inventorySource->id }}'"
                                                            :ref="$inputName"
                                                        />

                                                        <x-superadmin::form.control-group.error :control-name="$inputName" />
                                                    </x-superadmin::form.control-group>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </x-slot>
                </x-superadmin::drawer>
            </x-superadmin::form>
        </div>
    </script>

    <script type="module">
    window.app.component('v-create-shipment', {
        template: '#v-create-shipment-template',

        data() {
            return {
                source: "",
            };
        },

        methods: {
            onSourceChange() {
                this.setOriginalQuantityToAllShipmentInputElements();
            },

            getAllShipmentInputElements() {
                let allRefs = this.$refs;

                let allInputElements = [];

                Object.keys(allRefs).forEach((key) => {
                    if (key.startsWith('shipment')) {
                        allInputElements.push(allRefs[key]);
                    }
                });

                return allInputElements;
            },

            setOriginalQuantityToAllShipmentInputElements() {
                this.getAllShipmentInputElements().forEach((element) => {
                    let data = Object.assign({}, element.dataset);

                    element.value = data.originalQuantity;
                });
            }
        },
    });
    </script>
@endPushOnce
