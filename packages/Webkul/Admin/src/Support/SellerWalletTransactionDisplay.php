<?php

namespace Webkul\Admin\Support;

use Webkul\User\Models\SellerWalletTransaction;

final class SellerWalletTransactionDisplay
{
    /**
     * @return array{type_label: string, amount_sign: string, amount_class: string}
     */
    public static function meta(object $txn, bool $fundRecordMode = false): array
    {
        return [
            'type_label'   => self::typeLabel($txn, $fundRecordMode),
            'amount_sign'  => self::amountIsCredit($txn) ? '+' : '-',
            'amount_class' => self::amountIsCredit($txn)
                ? 'font-semibold text-emerald-600'
                : 'font-semibold text-red-600',
        ];
    }

    public static function typeLabel(object $txn, bool $fundRecordMode = false): string
    {
        $kind = $txn->kind ?? SellerWalletTransaction::KIND_LEGACY;

        return match ($kind) {
            SellerWalletTransaction::KIND_DEPOSIT_REQUEST => __('admin::app.seller.wallet.type-deposit'),
            SellerWalletTransaction::KIND_WITHDRAW_REQUEST => __('admin::app.seller.wallet.type-withdraw'),
            SellerWalletTransaction::KIND_WITHDRAW_REJECTION_REFUND => __('admin::app.seller.wallet.type-withdraw-refund'),
            SellerWalletTransaction::KIND_ORDER_REJECTION_REFUND => __('admin::app.seller.fund-record.type-rejection-refund'),
            SellerWalletTransaction::KIND_SELLER_PURCHASE => __('admin::app.seller.fund-record.type-product-purchase'),
            SellerWalletTransaction::KIND_ORDER_COMMISSION => __('admin::app.seller.fund-record.type-order-revenue'),
            SellerWalletTransaction::KIND_ORDER_REVENUE => __('admin::app.seller.fund-record.type-order-revenue'),
            SellerWalletTransaction::KIND_ORDER_REVENUE_APPROVAL => __('admin::app.seller.fund-record.type-order-revenue-approval'),
            default => $fundRecordMode
                ? ($txn->type === 'credit'
                    ? __('admin::app.seller.fund-record.type-order-revenue')
                    : __('admin::app.seller.fund-record.type-procurement'))
                : ($txn->type === 'credit'
                    ? __('admin::app.seller.wallet.deposit')
                    : __('admin::app.seller.wallet.withdraw')),
        };
    }

    public static function amountIsCredit(object $txn): bool
    {
        $kind = $txn->kind ?? SellerWalletTransaction::KIND_LEGACY;

        return in_array($kind, [
            SellerWalletTransaction::KIND_DEPOSIT_REQUEST,
            SellerWalletTransaction::KIND_ORDER_COMMISSION,
            SellerWalletTransaction::KIND_ORDER_REVENUE,
            SellerWalletTransaction::KIND_ORDER_REVENUE_APPROVAL,
            SellerWalletTransaction::KIND_WITHDRAW_REJECTION_REFUND,
            SellerWalletTransaction::KIND_ORDER_REJECTION_REFUND,
        ], true)
            || ($kind === SellerWalletTransaction::KIND_LEGACY && $txn->type === 'credit');
    }
}
