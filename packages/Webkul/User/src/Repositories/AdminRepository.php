<?php

namespace Webkul\User\Repositories;

use Webkul\Core\Eloquent\Repository;

class AdminRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return 'Webkul\User\Contracts\Admin';
    }

    /**
     * Count admins with all access.
     */
    public function countAdminsWithAllAccess(): int
    {
        return $this->getModel()
            ->leftJoin('roles', 'seller.role_id', '=', 'roles.id')
            ->where('roles.permission_type', 'all')
            ->get()
            ->count();
    }

    /**
     * Count admins with all access and active status.
     */
    public function countAdminsWithAllAccessAndActiveStatus(): int
    {
        return $this->getModel()
            ->leftJoin('roles', 'seller.role_id', '=', 'roles.id')
            ->where('seller.status', 1)
            ->where('roles.permission_type', 'all')
            ->get()
            ->count();
    }
}
