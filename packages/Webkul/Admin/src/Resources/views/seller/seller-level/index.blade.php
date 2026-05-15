<x-admin::layouts>
    <x-slot:title>
        Seller Level
    </x-slot>

    <x-admin::seller.panel :showWorkspaceTabs="false" :breadcrumb="[__('admin::app.components.layouts.sidebar.seller-level')]">

    <div class="rounded-md bg-white p-4 shadow-sm dark:bg-gray-900 sm:p-5">
        <div class="flex flex-col gap-4 lg:flex-row">

            <div class="flex-1">
                <p class="text-[28px] font-bold leading-tight text-gray-800 dark:text-white">
                    Upgrade<span class="text-blue-700">Sales Support</span> Easy<span class="text-blue-700">Monthly income over 10,000</span>
                </p>

                <div class="mt-3 space-y-3 text-sm leading-6 text-gray-700 dark:text-gray-300">
                    <div>
                        <p class="font-semibold text-blue-800 dark:text-blue-300">1.Seller Level Introduction</p>
                        <p>
                            To encourage entrepreneurs and provide them with greater business opportunities, let you grow with us, help you achieve greater success in sales, prepare rich upgrade rewards and increased sales profit ratios for you during the entrepreneurial process. Whether you are a novice or an experienced salesperson, we encourage you to participate in the upgrade plan and sincerely invite you to join us to realize your sales dreams!
                        </p>
                    </div>

                    <div>
                        <p class="font-semibold text-blue-800 dark:text-blue-300">2.Member Upgrade Instructions</p>
                        <p><span class="font-semibold">Membership Upgrade:</span> Membership upgrade is determined by the number of directly recommended branches. The higher the number of branches or if the operating funds meet the requirements, the system will automatically upgrade.</p>
                        <p><span class="font-semibold">Number of Branches:</span> Among the direct subordinates, the accumulated recharge amount exceeds $0.00 will be regarded as the number of valid people</p>
                        <p><span class="font-semibold">Team Size:</span> Among all subordinates, the accumulated recharge amount exceeds $100.00 is regarded as the number of valid team members</p>
                        <p><span class="font-semibold">Sales Profit Ratio:</span> The higher the level, the higher the sales profit</p>
                        <p><span class="font-semibold">Platform Traffic Support:</span> The system will prioritize providing you with a certain amount of product traffic exposure to create more sales opportunities</p>
                        <p><span class="font-semibold">Upgrade Bonus:</span> The system will automatically issue an upgrade bonus for each successful upgrade</p>
                    </div>

                    <div>
                        <p class="font-semibold text-blue-800 dark:text-blue-300">3.Growth Rules</p>
                        <p>Membership level is calculated from the moment of upgrade, and the level status remains valid for life;</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-5 rounded-md bg-white p-4 shadow-sm dark:bg-gray-900 sm:p-5">
        <x-admin::seller.responsive-table>
            <x-slot:table>
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[1120px] border-collapse">
                <thead>
                    <tr class="text-left text-sm text-gray-600 dark:text-gray-300">
                        <th class="border-b border-gray-200 px-3 py-2 font-medium">Seller Level</th>
                        <th class="border-b border-gray-200 px-3 py-2 font-medium">Operating Funds</th>
                        <th class="border-b border-gray-200 px-3 py-2 font-medium">Number of Branches</th>
                        <th class="border-b border-gray-200 px-3 py-2 font-medium">Sales Profit Ratio</th>
                        <th class="border-b border-gray-200 px-3 py-2 font-medium">Platform Traffic Support (Daily)</th>
                        <th class="border-b border-gray-200 px-3 py-2 font-medium">Purchase Discount</th>
                        <th class="border-b border-gray-200 px-3 py-2 font-medium">Upgrade Bonus</th>
                        <th class="border-b border-gray-200 px-3 py-2 text-center font-medium">Exclusive Service</th>
                        <th class="border-b border-gray-200 px-3 py-2 text-center font-medium">Home Page Recommendation</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-800 dark:text-gray-200">
                    <tr>
                        <td class="border-b border-gray-100 px-3 py-2 font-medium">C Level</td>
                        <td class="border-b border-gray-100 px-3 py-2">5000</td>
                        <td class="border-b border-gray-100 px-3 py-2">1</td>
                        <td class="border-b border-gray-100 px-3 py-2">15.00%~18.00%</td>
                        <td class="border-b border-gray-100 px-3 py-2">100~150</td>
                        <td class="border-b border-gray-100 px-3 py-2">1.00%</td>
                        <td class="border-b border-gray-100 px-3 py-2">$50.00</td>
                        <td class="border-b border-gray-100 px-3 py-2 text-center">
                            <span class="icon-checked text-base text-emerald-600"></span>
                        </td>
                        <td class="border-b border-gray-100 px-3 py-2 text-center">
                            <span class="icon-cancel text-base text-red-500"></span>
                        </td>
                    </tr>
                    <tr>
                        <td class="border-b border-gray-100 px-3 py-2 font-medium">B Level</td>
                        <td class="border-b border-gray-100 px-3 py-2">10000</td>
                        <td class="border-b border-gray-100 px-3 py-2">2</td>
                        <td class="border-b border-gray-100 px-3 py-2">18.00%~21.00%</td>
                        <td class="border-b border-gray-100 px-3 py-2">300~1000</td>
                        <td class="border-b border-gray-100 px-3 py-2">2.00%</td>
                        <td class="border-b border-gray-100 px-3 py-2">$100.00</td>
                        <td class="border-b border-gray-100 px-3 py-2 text-center">
                            <span class="icon-checked text-base text-emerald-600"></span>
                        </td>
                        <td class="border-b border-gray-100 px-3 py-2 text-center">
                            <span class="icon-cancel text-base text-red-500"></span>
                        </td>
                    </tr>
                    <tr>
                        <td class="border-b border-gray-100 px-3 py-2 font-medium">A Level</td>
                        <td class="border-b border-gray-100 px-3 py-2">30000</td>
                        <td class="border-b border-gray-100 px-3 py-2">3</td>
                        <td class="border-b border-gray-100 px-3 py-2">21.00%~25.00%</td>
                        <td class="border-b border-gray-100 px-3 py-2">500~2500</td>
                        <td class="border-b border-gray-100 px-3 py-2">3.00%</td>
                        <td class="border-b border-gray-100 px-3 py-2">$300.00</td>
                        <td class="border-b border-gray-100 px-3 py-2 text-center">
                            <span class="icon-checked text-base text-emerald-600"></span>
                        </td>
                        <td class="border-b border-gray-100 px-3 py-2 text-center">
                            <span class="icon-cancel text-base text-red-500"></span>
                        </td>
                    </tr>
                    <tr>
                        <td class="border-b border-gray-100 px-3 py-2 font-medium">S Level</td>
                        <td class="border-b border-gray-100 px-3 py-2">50000</td>
                        <td class="border-b border-gray-100 px-3 py-2">4</td>
                        <td class="border-b border-gray-100 px-3 py-2">25.00%~30.00%</td>
                        <td class="border-b border-gray-100 px-3 py-2">1000~3000</td>
                        <td class="border-b border-gray-100 px-3 py-2">4.00%</td>
                        <td class="border-b border-gray-100 px-3 py-2">$500.00</td>
                        <td class="border-b border-gray-100 px-3 py-2 text-center">
                            <span class="icon-checked text-base text-emerald-600"></span>
                        </td>
                        <td class="border-b border-gray-100 px-3 py-2 text-center">
                            <span class="icon-checked text-base text-emerald-600"></span>
                        </td>
                    </tr>
                    <tr>
                        <td class="border-b border-gray-100 px-3 py-2 font-medium">SS Level</td>
                        <td class="border-b border-gray-100 px-3 py-2">80000</td>
                        <td class="border-b border-gray-100 px-3 py-2">5</td>
                        <td class="border-b border-gray-100 px-3 py-2">30.00%~35.00%</td>
                        <td class="border-b border-gray-100 px-3 py-2">1500~5000</td>
                        <td class="border-b border-gray-100 px-3 py-2">5.00%</td>
                        <td class="border-b border-gray-100 px-3 py-2">$800.00</td>
                        <td class="border-b border-gray-100 px-3 py-2 text-center">
                            <span class="icon-checked text-base text-emerald-600"></span>
                        </td>
                        <td class="border-b border-gray-100 px-3 py-2 text-center">
                            <span class="icon-checked text-base text-emerald-600"></span>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-3 py-2 font-medium">SSS Level</td>
                        <td class="px-3 py-2">100000</td>
                        <td class="px-3 py-2">6</td>
                        <td class="px-3 py-2">35.00%~45.00%</td>
                        <td class="px-3 py-2">3000~10000</td>
                        <td class="px-3 py-2">6.00%</td>
                        <td class="px-3 py-2">$1,000.00</td>
                        <td class="px-3 py-2 text-center">
                            <span class="icon-checked text-base text-emerald-600"></span>
                        </td>
                        <td class="px-3 py-2 text-center">
                            <span class="icon-checked text-base text-emerald-600"></span>
                        </td>
                    </tr>
                </tbody>
                    </table>
                </div>
            </x-slot:table>

            <x-slot:cards>
                @php
                    $sellerLevelRows = [
                        ['level' => 'C Level', 'funds' => '5000', 'branches' => '1', 'profit' => '15.00%~18.00%', 'traffic' => '100~150', 'discount' => '1.00%', 'bonus' => '$50.00', 'service' => true, 'home' => false],
                        ['level' => 'B Level', 'funds' => '10000', 'branches' => '2', 'profit' => '18.00%~21.00%', 'traffic' => '300~1000', 'discount' => '2.00%', 'bonus' => '$100.00', 'service' => true, 'home' => false],
                        ['level' => 'A Level', 'funds' => '30000', 'branches' => '3', 'profit' => '21.00%~25.00%', 'traffic' => '500~2500', 'discount' => '3.00%', 'bonus' => '$300.00', 'service' => true, 'home' => false],
                        ['level' => 'S Level', 'funds' => '50000', 'branches' => '4', 'profit' => '25.00%~30.00%', 'traffic' => '1000~3000', 'discount' => '4.00%', 'bonus' => '$500.00', 'service' => true, 'home' => true],
                        ['level' => 'SS Level', 'funds' => '80000', 'branches' => '5', 'profit' => '30.00%~35.00%', 'traffic' => '1500~5000', 'discount' => '5.00%', 'bonus' => '$800.00', 'service' => true, 'home' => true],
                        ['level' => 'SSS Level', 'funds' => '100000', 'branches' => '6', 'profit' => '35.00%~45.00%', 'traffic' => '3000~10000', 'discount' => '6.00%', 'bonus' => '$1,000.00', 'service' => true, 'home' => true],
                    ];
                @endphp
                @foreach ($sellerLevelRows as $levelRow)
                    <article class="seller-mobile-card">
                        <div class="seller-mobile-card__header">
                            <p class="seller-mobile-card__title">{{ $levelRow['level'] }}</p>
                        </div>
                        <div class="seller-mobile-card__rows">
                            <x-admin::seller.mobile-card-field label="Operating Funds">{{ $levelRow['funds'] }}</x-admin::seller.mobile-card-field>
                            <x-admin::seller.mobile-card-field label="Number of Branches">{{ $levelRow['branches'] }}</x-admin::seller.mobile-card-field>
                            <x-admin::seller.mobile-card-field label="Sales Profit Ratio">{{ $levelRow['profit'] }}</x-admin::seller.mobile-card-field>
                            <x-admin::seller.mobile-card-field label="Platform Traffic Support">{{ $levelRow['traffic'] }}</x-admin::seller.mobile-card-field>
                            <x-admin::seller.mobile-card-field label="Purchase Discount">{{ $levelRow['discount'] }}</x-admin::seller.mobile-card-field>
                            <x-admin::seller.mobile-card-field label="Upgrade Bonus">{{ $levelRow['bonus'] }}</x-admin::seller.mobile-card-field>
                            <x-admin::seller.mobile-card-field label="Exclusive Service">
                                <span class="icon-checked text-base text-emerald-600"></span>
                            </x-admin::seller.mobile-card-field>
                            <x-admin::seller.mobile-card-field label="Home Page Recommendation">
                                @if ($levelRow['home'])
                                    <span class="icon-checked text-base text-emerald-600"></span>
                                @else
                                    <span class="icon-cancel text-base text-red-500"></span>
                                @endif
                            </x-admin::seller.mobile-card-field>
                        </div>
                    </article>
                @endforeach
            </x-slot:cards>
        </x-admin::seller.responsive-table>
    </div>
    </x-admin::seller.panel>
</x-admin::layouts>

