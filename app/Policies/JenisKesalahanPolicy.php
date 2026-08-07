<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\JenisKesalahan;
use Illuminate\Auth\Access\HandlesAuthorization;

class JenisKesalahanPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:JenisKesalahan');
    }

    public function view(AuthUser $authUser, JenisKesalahan $jenisKesalahan): bool
    {
        return $authUser->can('View:JenisKesalahan');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:JenisKesalahan');
    }

    public function update(AuthUser $authUser, JenisKesalahan $jenisKesalahan): bool
    {
        return $authUser->can('Update:JenisKesalahan');
    }

    public function delete(AuthUser $authUser, JenisKesalahan $jenisKesalahan): bool
    {
        return $authUser->can('Delete:JenisKesalahan');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:JenisKesalahan');
    }

    public function restore(AuthUser $authUser, JenisKesalahan $jenisKesalahan): bool
    {
        return $authUser->can('Restore:JenisKesalahan');
    }

    public function forceDelete(AuthUser $authUser, JenisKesalahan $jenisKesalahan): bool
    {
        return $authUser->can('ForceDelete:JenisKesalahan');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:JenisKesalahan');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:JenisKesalahan');
    }

    public function replicate(AuthUser $authUser, JenisKesalahan $jenisKesalahan): bool
    {
        return $authUser->can('Replicate:JenisKesalahan');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:JenisKesalahan');
    }

}