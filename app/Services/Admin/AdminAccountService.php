<?php

namespace App\Services\Admin;

use App\Models\Admin;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AdminAccountService
{
    public function primaryAdmin(): ?Admin
    {
        return Admin::query()->orderBy('id')->first();
    }

    public function isPrimary(Admin $admin): bool
    {
        $primary = $this->primaryAdmin();

        return $primary !== null && (int) $primary->id === (int) $admin->id;
    }

    /**
     * @param  array{
     *     username: string,
     *     email: string,
     *     password: string,
     *     is_super_admin?: bool,
     *     is_active?: bool,
     *     permissions?: array<string, bool>
     * }  $data
     */
    public function create(array $data, Admin $actor): Admin
    {
        $this->assertActorMayManageAdmins($actor);

        $isSuper = (bool) ($data['is_super_admin'] ?? false);
        $isActive = array_key_exists('is_active', $data) ? (bool) $data['is_active'] : true;

        $admin = new Admin;
        $admin->username = $data['username'];
        $admin->email = $data['email'];
        $admin->password_hash = Hash::make($data['password']);
        $admin->is_super_admin = $isSuper;
        $admin->is_active = $isActive;
        $this->applyPermissionColumns($admin, $isSuper ? [] : ($data['permissions'] ?? []), $isSuper);
        $admin->save();

        return $admin->fresh();
    }

    /**
     * @param  array{
     *     username?: string,
     *     email?: string,
     *     password?: string|null,
     *     is_super_admin?: bool,
     *     is_active?: bool,
     *     permissions?: array<string, bool>
     * }  $data
     */
    public function update(Admin $target, array $data, Admin $actor): Admin
    {
        return DB::transaction(function () use ($target, $data, $actor) {
            $locked = $this->lockAllAdmins();
            $actor = $this->lockedAdminOrFail($locked, (int) $actor->id, 'Administrator session is no longer valid.');
            $target = $this->lockedAdminOrFail($locked, (int) $target->id, 'Administrator not found.');

            $this->assertActorMayManageAdmins($actor, alreadyLocked: true);

            if (array_key_exists('username', $data)) {
                $target->username = $data['username'];
            }
            if (array_key_exists('email', $data)) {
                $target->email = $data['email'];
            }
            if (! empty($data['password'])) {
                $target->password_hash = Hash::make($data['password']);
            }

            $wantsSuper = array_key_exists('is_super_admin', $data)
                ? (bool) $data['is_super_admin']
                : $target->isSuperAdmin();
            $wantsActive = array_key_exists('is_active', $data)
                ? (bool) $data['is_active']
                : $target->isActive();

            $this->assertRoleAndActiveChangesAllowed($locked, $target, $actor, $wantsSuper, $wantsActive);

            $target->is_super_admin = $wantsSuper;
            $target->is_active = $wantsActive;

            if (array_key_exists('permissions', $data)) {
                $this->applyPermissionColumns($target, $data['permissions'] ?? [], $wantsSuper);
            } elseif ($wantsSuper) {
                $this->applyPermissionColumns($target, [], true);
            }

            $target->save();

            return $target->fresh();
        });
    }

    public function delete(Admin $target, Admin $actor): void
    {
        DB::transaction(function () use ($target, $actor) {
            $locked = $this->lockAllAdmins();
            $actor = $this->lockedAdminOrFail($locked, (int) $actor->id, 'Administrator session is no longer valid.');
            $target = $this->lockedAdminOrFail($locked, (int) $target->id, 'Administrator not found.');

            $this->assertActorMayManageAdmins($actor, alreadyLocked: true);

            if ((int) $target->id === (int) $actor->id) {
                throw ValidationException::withMessages([
                    'admin' => 'You cannot delete your own account.',
                ]);
            }

            if ($this->isPrimaryAmong($locked, $target)) {
                throw ValidationException::withMessages([
                    'admin' => 'The primary Super Admin cannot be deleted.',
                ]);
            }

            if ($target->isSuperAdmin() && ! $this->wouldLeaveActiveSuperAdmin($locked, $target)) {
                throw ValidationException::withMessages([
                    'admin' => 'Cannot delete the last active Super Admin.',
                ]);
            }

            $target->delete();
        });
    }

    private function assertActorMayManageAdmins(Admin $actor, bool $alreadyLocked = false): void
    {
        if (! $actor->exists) {
            throw ValidationException::withMessages([
                'admin' => 'Super Admin access required.',
            ]);
        }

        if (! $alreadyLocked) {
            try {
                $actor->refresh();
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
                throw ValidationException::withMessages([
                    'admin' => 'Super Admin access required.',
                ]);
            }
        }

        if (! $actor->isActive() || ! $actor->isSuperAdmin()) {
            throw ValidationException::withMessages([
                'admin' => 'Super Admin access required.',
            ]);
        }
    }

    /** @return Collection<int, Admin> */
    private function lockAllAdmins(): Collection
    {
        return Admin::query()->orderBy('id')->lockForUpdate()->get();
    }

    /**
     * @param  Collection<int, Admin>  $locked
     */
    private function lockedAdminOrFail(Collection $locked, int $id, string $message): Admin
    {
        $admin = $locked->firstWhere('id', $id);
        if (! $admin instanceof Admin) {
            throw ValidationException::withMessages([
                'admin' => $message,
            ]);
        }

        return $admin;
    }

    /**
     * @param  array<string, bool>  $permissions
     */
    private function applyPermissionColumns(Admin $admin, array $permissions, bool $isSuper): void
    {
        foreach (Admin::PERMISSION_MAP as $key => $column) {
            // Super Admin bypasses can_*; keep columns false so UI shows Full access cleanly.
            if ($isSuper) {
                $admin->setAttribute($column, false);

                continue;
            }

            $admin->setAttribute($column, (bool) ($permissions[$key] ?? false));
        }
    }

    /**
     * @param  Collection<int, Admin>  $locked
     */
    private function assertRoleAndActiveChangesAllowed(
        Collection $locked,
        Admin $target,
        Admin $actor,
        bool $wantsSuper,
        bool $wantsActive,
    ): void {
        $isSelf = (int) $target->id === (int) $actor->id;
        $isPrimary = $this->isPrimaryAmong($locked, $target);
        $currentlySuper = $target->isSuperAdmin();
        $currentlyActive = $target->isActive();

        if ($isPrimary) {
            if (! $wantsSuper) {
                throw ValidationException::withMessages([
                    'admin' => 'The primary Super Admin cannot be demoted.',
                ]);
            }
            if (! $wantsActive) {
                throw ValidationException::withMessages([
                    'admin' => 'The primary Super Admin cannot be deactivated.',
                ]);
            }
        }

        if ($isSelf) {
            if (! $wantsSuper && $currentlySuper) {
                throw ValidationException::withMessages([
                    'admin' => 'You cannot demote your own account.',
                ]);
            }
            if (! $wantsActive && $currentlyActive) {
                throw ValidationException::withMessages([
                    'admin' => 'You cannot deactivate your own account.',
                ]);
            }
        }

        $demoting = $currentlySuper && ! $wantsSuper;
        $deactivatingSuper = $currentlySuper && $currentlyActive && ! $wantsActive;

        if (($demoting || $deactivatingSuper) && ! $this->wouldLeaveActiveSuperAdmin($locked, $target)) {
            throw ValidationException::withMessages([
                'admin' => 'At least one active Super Admin must remain.',
            ]);
        }
    }

    /**
     * @param  Collection<int, Admin>  $locked
     */
    private function isPrimaryAmong(Collection $locked, Admin $admin): bool
    {
        $primary = $locked->sortBy('id')->first();

        return $primary instanceof Admin && (int) $primary->id === (int) $admin->id;
    }

    /**
     * True if another active Super remains after excluding $target.
     *
     * @param  Collection<int, Admin>  $locked
     */
    private function wouldLeaveActiveSuperAdmin(Collection $locked, Admin $target): bool
    {
        return $locked->contains(function (Admin $admin) use ($target): bool {
            return (int) $admin->id !== (int) $target->id
                && $admin->isSuperAdmin()
                && $admin->isActive();
        });
    }
}
