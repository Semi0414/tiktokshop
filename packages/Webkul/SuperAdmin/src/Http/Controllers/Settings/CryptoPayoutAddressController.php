<?php

namespace Webkul\SuperAdmin\Http\Controllers\Settings;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Webkul\SuperAdmin\Http\Controllers\Controller;
use Webkul\SuperAdmin\Models\CryptoPayoutAddress;

class CryptoPayoutAddressController extends Controller
{
    public function index(): View
    {
        $addresses = CryptoPayoutAddress::query()
            ->orderByDesc('id')
            ->get();

        $networkOptions = $this->networkOptions();

        return view('superadmin::settings.crypto-addresses.index', compact('addresses', 'networkOptions'));
    }

    public function store(): RedirectResponse
    {
        $data = request()->validate([
            'network_type' => 'required|string|max:64',
            'address' => 'required|string|max:2000',
            'label' => 'nullable|string|max:255',
        ]);

        CryptoPayoutAddress::query()->create($data);

        return redirect()
            ->route('superadmin.settings.crypto_addresses.index')
            ->with('success', trans('superadmin::app.settings.crypto-addresses.created'));
    }

    public function edit(int $id): View
    {
        $address = CryptoPayoutAddress::query()->findOrFail($id);

        $networkOptions = $this->networkOptions();

        return view('superadmin::settings.crypto-addresses.edit', compact('address', 'networkOptions'));
    }

    public function update(int $id): RedirectResponse
    {
        $data = request()->validate([
            'network_type' => 'required|string|max:64',
            'address' => 'required|string|max:2000',
            'label' => 'nullable|string|max:255',
        ]);

        CryptoPayoutAddress::query()->whereKey($id)->update($data);

        return redirect()
            ->route('superadmin.settings.crypto_addresses.index')
            ->with('success', trans('superadmin::app.settings.crypto-addresses.updated'));
    }

    public function destroy(int $id): RedirectResponse
    {
        CryptoPayoutAddress::query()->whereKey($id)->delete();

        return redirect()
            ->route('superadmin.settings.crypto_addresses.index')
            ->with('success', trans('superadmin::app.settings.crypto-addresses.deleted'));
    }

    /**
     * @return array<string, string>
     */
    public function networkOptions(): array
    {
        return [
            'trc20_usdt' => 'USDT (TRC20)',
            'erc20_usdt' => 'USDT (ERC20)',
            'bep20_usdt' => 'USDT (BEP20 / BSC)',
            'btc' => 'Bitcoin (BTC)',
            'eth' => 'Ethereum (ETH)',
            'bnb_bsc' => 'BNB (BSC)',
            'sol' => 'Solana (SOL)',
            'polygon_usdt' => 'USDT (Polygon)',
            'arbitrum_usdt' => 'USDT (Arbitrum)',
            'optimism_usdt' => 'USDT (Optimism)',
            'binance_uid' => 'Binance UID',
            'binance_pay_id' => 'Binance Pay ID',
            'other' => trans('superadmin::app.settings.crypto-addresses.other-network'),
        ];
    }
}
