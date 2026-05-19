<?php

namespace Webkul\Notification\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Webkul\Core\Eloquent\Repository;
use Webkul\User\Models\Admin;

class NotificationRepository extends Repository
{
    /**
     * Platform operators (explicit role IDs in seller-panel config) may see every seller's notifications.
     * Everyone else — including sellers whose Bagisto role uses permission_type "all" — is limited to their own rows.
     */
    protected function seesAllSellersNotifications(?Admin $user): bool
    {
        if (! $user instanceof Admin || ! $user->role) {
            return false;
        }

        $platformRoleIds = config('seller-panel.platform_admin_role_ids', []);

        return $user->role->permission_type === 'all'
            && $platformRoleIds !== []
            && in_array((int) $user->role_id, $platformRoleIds, true);
    }

    /**
     * Limit notifications to the current seller admin, unless platform admin.
     */
    protected function scopeForCurrentAdmin(Builder $query): Builder
    {
        $user = auth()->guard('admin')->user();

        if (! $user instanceof Admin || ! $user->role) {
            return $query;
        }

        if ($this->seesAllSellersNotifications($user)) {
            return $query;
        }

        $table = $this->model->getTable();

        return $query->where(function (Builder $q) use ($user, $table) {
            $orderScope = function (Builder $q2) use ($user, $table) {
                $q2->whereNotNull($table.'.order_id')
                    ->whereHas('order', function ($oq) use ($user) {
                        $oq->where('seller_id', $user->id);

                        if (Schema::hasColumn('orders', 'seller_approval_status')) {
                            $oq->where('seller_approval_status', 'approved');
                        }
                    });
            };

            if ($this->notificationsTableHasSellerId()) {
                $q->where($table.'.seller_id', $user->id)
                    ->orWhere($orderScope);
            } else {
                $orderScope($q);
            }
        });
    }

    protected function notificationsTableHasSellerId(): bool
    {
        static $hasColumn = null;

        if ($hasColumn === null) {
            $hasColumn = Schema::hasTable('notifications')
                && Schema::hasColumn('notifications', 'seller_id');
        }

        return $hasColumn;
    }

    /**
     * Only persist attributes that exist on the current notifications table (no migration required).
     */
    protected function filterToExistingColumns(array $attributes): array
    {
        static $columns = null;

        if ($columns === null) {
            $columns = array_flip(Schema::getColumnListing($this->model->getTable()));
        }

        return array_intersect_key($attributes, $columns);
    }

    public function create(array $attributes)
    {
        return parent::create($this->filterToExistingColumns($attributes));
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

        if ($this->seesAllSellersNotifications($user)) {
            return $query;
        }

        return $query->where('orders.seller_id', $user->id)
            ->where('orders.seller_approval_status', 'approved');
    }

    /**
     * Specify Model class name
     */
    public function model(): string
    {
        return 'Webkul\Notification\Contracts\Notification';
    }

    /**
     * Non-order notifications (wallet, etc.) visible to current admin.
     */
    public function nonOrderCountForCurrentAdmin(): int
    {
        return $this->scopeForCurrentAdmin($this->model->newQuery())
            ->whereNull('order_id')
            ->count();
    }

    /**
     * Create a notification row for a seller admin (order_id may be null).
     */
    public function createForSellerAdmin(array $attributes): mixed
    {
        return $this->create(array_merge([
            'read' => 0,
        ], $this->filterToExistingColumns($attributes)));
    }

    /**
     * Single notification by primary key, respecting seller scope.
     */
    public function findForOpenScoped(int $id)
    {
        return $this->scopeForCurrentAdmin($this->model->newQuery())
            ->whereKey($id)
            ->first();
    }

    /**
     * Return Filtered Notification resources.
     */
    public function getParamsData(array $params): array
    {
        $query = $this->scopeForCurrentAdmin(
            $this->model->with('order')
        );

        if (isset($params['status']) && strcasecmp((string) $params['status'], 'all') !== 0) {
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

        return [
            'notifications'   => $notifications,
            'status_counts'   => $statusCounts,
            'non_order_count' => $this->nonOrderCountForCurrentAdmin(),
        ];
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

        return [
            'notifications'   => $notifications,
            'status_counts'   => $statusCounts,
            'non_order_count' => $this->nonOrderCountForCurrentAdmin(),
        ];
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
