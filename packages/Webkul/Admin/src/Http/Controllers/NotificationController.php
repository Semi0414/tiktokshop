<?php

namespace Webkul\Admin\Http\Controllers;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;
use Webkul\Notification\Repositories\NotificationRepository;

class NotificationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected NotificationRepository $notificationRepository) {}

    /**
     * Append `open_url` for header / JSON consumers (marks read via this URL).
     */
    protected function withOpenUrls(?LengthAwarePaginator $paginator): ?LengthAwarePaginator
    {
        if (! $paginator) {
            return null;
        }

        $paginator->getCollection()->transform(function ($item) {
            $item->setAttribute('open_url', route('admin.notification.open', $item->id));

            return $item;
        });

        return $paginator;
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        $params = request()->except('page');

        if (isset($params['status']) && (string) $params['status'] === '') {
            unset($params['status']);
        }

        $searchResults = count($params)
            ? $this->notificationRepository->getParamsData($params)
            : $this->notificationRepository->getAll();

        $paginator = $searchResults['notifications'];
        $statusCounts = $searchResults['status_counts'] ?? collect();
        $nonOrderCount = (int) ($searchResults['non_order_count'] ?? 0);

        $countsByStatus = [];
        $totalAll = 0;

        foreach ($statusCounts as $row) {
            $statusKey = $row->status ?? null;

            if ($statusKey === null) {
                continue;
            }

            $c = (int) ($row->status_count ?? 0);
            $countsByStatus[$statusKey] = $c;
            $totalAll += $c;
        }

        $currentStatus = request()->query('status');

        if ($currentStatus === null || $currentStatus === '' || strcasecmp((string) $currentStatus, 'all') === 0) {
            $currentStatus = 'all';
        }

        $paginator->appends(array_filter(
            request()->except('page'),
            fn ($v) => $v !== null && $v !== ''
        ));

        return view('admin::notifications.index', [
            'paginator' => $paginator,
            'statusCountsByStatus' => $countsByStatus,
            'totalAll' => $totalAll,
            'nonOrderCount' => $nonOrderCount,
            'currentStatus' => $currentStatus,
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return array
     */
    public function getNotifications()
    {
        $params = request()->except('page');

        $searchResults = count($params)
            ? $this->notificationRepository->getParamsData($params)
            : $this->notificationRepository->getAll();

        $results = isset($searchResults['notifications']) ? $searchResults['notifications'] : $searchResults;

        if ($results instanceof LengthAwarePaginator) {
            $this->withOpenUrls($results);
        }

        $statusCount = isset($searchResults['status_counts']) ? $searchResults['status_counts'] : '';

        return [
            'search_results' => $results,
            'status_count' => $statusCount,
            'total_unread' => $this->notificationRepository->unreadCountForCurrentAdmin(),
        ];
    }

    /**
     * Mark notification read and redirect to order or configured seller route.
     */
    public function open(int $id): RedirectResponse
    {
        $notification = $this->notificationRepository->findForOpenScoped($id);

        if (! $notification) {
            abort(404);
        }

        $notification->read = true;
        $notification->save();

        if ($notification->order_id) {
            return redirect()->route('admin.sales.orders.view', $notification->order_id);
        }

        $routeName = $notification->action_route ?: 'admin.wallet.index';
        $params = is_array($notification->action_params) ? $notification->action_params : [];

        if ($routeName && Route::has($routeName)) {
            return redirect()->route($routeName, $params);
        }

        return redirect()->route('admin.notification.index');
    }

    /**
     * Update the notification is reade or not.
     *
     * @param  int  $orderId
     * @return View
     */
    public function viewedNotifications($orderId)
    {
        if ($notification = $this->notificationRepository->findNotificationForOrderScoped((int) $orderId)) {
            $notification->read = 1;

            $notification->save();

            return redirect()->route('admin.sales.orders.view', $orderId);
        }

        abort(404);
    }

    /**
     * Mark all notifications read (JSON for header; redirect for full notifications page form).
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function readAllNotifications()
    {
        $this->notificationRepository->markAllReadForCurrentAdmin();

        $searchResults = $this->notificationRepository->getParamsData([
            'limit' => 5,
            'read' => 0,
        ]);

        $results = isset($searchResults['notifications'])
            ? $searchResults['notifications']
            : $searchResults;

        if ($results instanceof LengthAwarePaginator) {
            $this->withOpenUrls($results);
        }

        $payload = [
            'search_results' => $results,
            'total_unread' => $this->notificationRepository->unreadCountForCurrentAdmin(),
            'success_message' => trans('admin::app.notifications.marked-success'),
        ];

        if (request()->expectsJson()) {
            return response()->json($payload);
        }

        return redirect()
            ->route('admin.notification.index')
            ->with('success', $payload['success_message']);
    }
}
