<?php

namespace Webkul\SuperAdmin\Http\Controllers\Marketing\Promotions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Webkul\CartRule\Repositories\CartRuleRepository;
use Webkul\SuperAdmin\DataGrids\Marketing\Promotions\CartRuleCouponDataGrid;
use Webkul\SuperAdmin\DataGrids\Marketing\Promotions\CartRuleDataGrid;
use Webkul\SuperAdmin\Http\Controllers\Controller;
use Webkul\SuperAdmin\Http\Requests\CartRuleRequest;

class CartRuleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected CartRuleRepository $cartRuleRepository) {}

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {

        return $this->superadminListing('superadmin::marketing.promotions.cart-rules.index', [], CartRuleDataGrid::class);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create()
    {
        return view('superadmin::marketing.promotions.cart-rules.create');
    }

    /**
     * Copy a given Cart Rule id. Always make the copy is inactive so the
     * user is able to configure it before setting it live.
     *
     * @return View
     */
    public function copy(int $cartRuleId)
    {
        $cartRule = $this->cartRuleRepository->with(['channels', 'customer_groups'])->findOrFail($cartRuleId);

        $copiedCartRule = $cartRule->replicate()->fill([
            'status' => 0,
            'name' => trans('superadmin::app.marketing.promotions.cart-rules.index.datagrid.copy-of', ['value' => $cartRule->name]),
        ]);

        $copiedCartRule->save();

        foreach ($copiedCartRule->channels as $channel) {
            $copiedCartRule->channels()->save($channel);
        }

        foreach ($copiedCartRule->customer_groups as $group) {
            $copiedCartRule->customer_groups()->save($group);
        }

        request()->merge(['id' => $copiedCartRule->id]);

        if (request()->boolean('export')) {
            return datagrid(CartRuleCouponDataGrid::class)->process();
        }

        return view(
            'superadmin::marketing.promotions.cart-rules.edit',
            $this->withDatagridPayload(['cartRule' => $copiedCartRule], CartRuleCouponDataGrid::class)
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(CartRuleRequest $cartRuleRequest)
    {
        try {
            Event::dispatch('promotions.cart_rule.create.before');

            $cartRule = $this->cartRuleRepository->create($cartRuleRequest->all());

            Event::dispatch('promotions.cart_rule.create.after', $cartRule);

            session()->flash('success', trans('superadmin::app.marketing.promotions.cart-rules.create.create-success'));

            return redirect()->route('superadmin.marketing.promotions.cart_rules.index');
        } catch (ValidationException $e) {
            if ($firstError = collect($e->errors())->first()) {
                session()->flash('error', $firstError[0]);
            }
        }

        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return View
     */
    public function edit(int $id)
    {
        $cartRule = $this->cartRuleRepository->findOrFail($id);

        request()->merge(['id' => $id]);

        if (request()->boolean('export')) {
            return datagrid(CartRuleCouponDataGrid::class)->process();
        }

        return view(
            'superadmin::marketing.promotions.cart-rules.edit',
            $this->withDatagridPayload(compact('cartRule'), CartRuleCouponDataGrid::class)
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @return Response
     */
    public function update(CartRuleRequest $cartRuleRequest, int $id)
    {
        try {
            $cartRule = $this->cartRuleRepository->findOrFail($id);

            if ($cartRule->coupon_type) {
                if ($cartRule->cart_rule_coupon) {
                    $this->validate(request(), [
                        'coupon_code' => 'required_if:use_auto_generation,==,0|unique:cart_rule_coupons,code,'.$cartRule->cart_rule_coupon->id,
                    ]);
                } else {
                    $this->validate(request(), [
                        'coupon_code' => 'required_if:use_auto_generation,==,0|unique:cart_rule_coupons,code',
                    ]);
                }
            }

            Event::dispatch('promotions.cart_rule.update.before', $id);

            $cartRule = $this->cartRuleRepository->update($cartRuleRequest->all(), $id);

            Event::dispatch('promotions.cart_rule.update.after', $cartRule);

            session()->flash('success', trans('superadmin::app.marketing.promotions.cart-rules.edit.update-success'));

            return redirect()->route('superadmin.marketing.promotions.cart_rules.index');
        } catch (ValidationException $e) {
            if ($firstError = collect($e->errors())->first()) {
                session()->flash('error', $firstError[0]);
            }
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $this->cartRuleRepository->findOrFail($id);

        try {
            Event::dispatch('promotions.cart_rule.delete.before', $id);

            $this->cartRuleRepository->delete($id);

            Event::dispatch('promotions.cart_rule.delete.after', $id);

            return new JsonResponse([
                'message' => trans('superadmin::app.marketing.promotions.cart-rules.delete-success'
                )]);
        } catch (Exception $e) {
        }

        return new JsonResponse([
            'message' => trans('superadmin::app.marketing.promotions.cart-rules.delete-failed'
            )], 400);
    }
}
