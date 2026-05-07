<?php

namespace Webkul\SuperAdmin\Http\Controllers\Sellers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Webkul\SuperAdmin\Http\Controllers\Controller;
use Webkul\User\Services\SellerJoinSellerService;

class SellerApplicationController extends Controller
{
    public function index()
    {
        $applications = DB::table('seller_applications')
            ->orderByDesc('id')
            ->paginate(20);

        return view('superadmin::sellers.applications.index', compact('applications'));
    }

    public function view($id)
    {
        $application = DB::table('seller_applications')->find($id);

        abort_if(! $application, 404);

        return view('superadmin::sellers.applications.view', compact('application'));
    }

    public function updateStatus(Request $request, $id)
    {
        $data = $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $application = DB::table('seller_applications')->find($id);

        abort_if(! $application, 404);

        DB::transaction(function () use ($data, $application, $id) {
            DB::table('seller_applications')
                ->where('id', $id)
                ->update([
                    'status' => $data['status'],
                    'updated_at' => now(),
                ]);

            if (! $application->seller_id) {
                $newSellerId = app(SellerJoinSellerService::class)->ensureSellerForApplication($application);

                DB::table('seller_applications')
                    ->where('id', $id)
                    ->update([
                        'seller_id' => $newSellerId,
                        'updated_at' => now(),
                    ]);

                $application->seller_id = $newSellerId;
            }

            $sellerUpdates = match ($data['status']) {
                'approved' => [
                    'seller_approval_status' => 'approved',
                    'status' => 1,
                    'updated_at' => now(),
                ],
                'rejected' => [
                    'seller_approval_status' => 'rejected',
                    'status' => 0,
                    'updated_at' => now(),
                ],
                default => [
                    'seller_approval_status' => 'pending',
                    'status' => 0,
                    'updated_at' => now(),
                ],
            };

            DB::table('seller')->where('id', $application->seller_id)->update($sellerUpdates);
        });

        return redirect()
            ->route('superadmin.sellers.applications.view', $id)
            ->with('success', 'Application status updated.');
    }
}
