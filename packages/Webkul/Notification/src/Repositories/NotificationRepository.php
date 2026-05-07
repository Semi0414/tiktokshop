<?php

namespace Webkul\Notification\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Webkul\Core\Eloquent\Repository;
use Webkul\User\Models\Admin;

class NotificationRepository extends Repository
{
    /**
     * Limit notifications to orders belonging to the current seller (admin guard), unless the role has full access.
     */
    protected function scopeForCurrentAdmin(Builder $query): Builder
    {
        $user = auth()->guard('admin')->user();

        if (! $user instanceof Admin || ! $user->role) {
            return $query;
        }

        if ($user->role->permission_type === 'all') {
            return $query;
        }

        return $query->whereHas('order', function ($q) use ($user) {
            $q->where('seller_id', $user->id);
        });
    }

    /**
     * @param  \Illuminate\Database\Query\Builder|Builder  $query
     * @return \Illuminate\Database\Query\Builder|Builder
     */
    protected function scopeJoinOrdersForCurrentAdmin($query)
    {
        $user = auth()->guard('admin')->user();

        if (! $user instanceof Admin || ! $user->role) {
            return $query;
        }

        if ($user->role->permission_type === 'all') {
            return $query;
        }

        return $query->where('orders.seller_id', $user->id);
    }

    /**
     * Specify Model class name
     */
    public function model(): string
    {
        return 'Webkul\Notification\Contracts\Notification';
    }

    /**
     * Return Filtered Notification resources.
     */
    public function getParamsData(array $params): array
    {
        $query = $this->scopeForCurrentAdmin(
            $this->model->with('order')
        );

        if (isset($params['status']) && $params['status'] != 'All') {
            $query->whereHas('order', function ($q) use ($params) {
                $q->where(['status' => $params['status']]);
            });
        }

        if (isset($params['read']) && isset($params['limit'])) {
            $query->where('read', $params['read'])->limit($params['limit']);
        } elseif (isset($params['limit'])) {
            $query->limit($params['limit']);
        }

        $notifications = $query->latest()->paginate($params['limit'] ?? 10);

        $statusQuery = $this->model->query()
            ->join('orders', 'notifications.order_id', '=', 'orders.id');
        $statusQuery = $this->scopeJoinOrdersForCurrentAdmin($statusQuery);

        $statusCounts = $statusQuery
            ->select('orders.status', DB::raw('COUNT(*) as status_count'))
            ->groupBy('orders.status')
            ->get();

        return ['notifications' => $notifications, 'status_counts' => $statusCounts];
    }

    /**
     * Return Notification resources.
     *
     * @return array
     */
    public function getAll(array $params = [])
    {
        $query = $this->scopeForCurrentAdmin(
            $this->model->with('order')
        );

        $notifications = $query->latest()->paginate($params['limit'] ?? 10);

        $statusQuery = $this->model->query()
            ->join('orders', 'notifications.order_id', '=', 'orders.id');
        $statusQuery = $this->scopeJoinOrdersForCurrentAdmin($statusQuery);

        $statusCounts = $statusQuery
            ->select('orders.status', DB::raw('COUNT(*) as status_count'))
            ->groupBy('orders.status')
            ->get();

        return ['notifications' => $notifications, 'status_counts' => $statusCounts];
    }

    /**
     * Unread count for the header bell (scoped for seller accounts).
     */
    public function unreadCountForCurrentAdmin(): int
    {
        return $this->scopeForCurrentAdmin($this->model->newQuery())
            ->where('read', 0)
            ->count();
    }

    /**
     * Mark all visible notifications as read (scoped for seller accounts).
     */
    public function markAllReadForCurrentAdmin(): int
    {
        return $this->scopeForCurrentAdmin($this->model->newQuery())
            ->where('read', 0)
            ->update(['read' => 1]);
    }

    /**
     * Single notification for an order, respecting seller scope.
     */
    public function findNotificationForOrderScoped(int $orderId)
    {
        return $this->scopeForCurrentAdmin($this->model->newQuery())
            ->where('order_id', $orderId)
            ->first();
    }
}
