<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\DokumenPengeluaran;
use Illuminate\Auth\Access\HandlesAuthorization;

class DokumenPengeluaranPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:DokumenPengeluaran');
    }

    public function view(AuthUser $authUser, DokumenPengeluaran $dokumenPengeluaran): bool
    {
        return $authUser->can('View:DokumenPengeluaran');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:DokumenPengeluaran');
    }

    public function update(AuthUser $authUser, DokumenPengeluaran $dokumenPengeluaran): bool
    {
        return $authUser->can('Update:DokumenPengeluaran');
    }

    public function delete(AuthUser $authUser, DokumenPengeluaran $dokumenPengeluaran): bool
    {
        return $authUser->can('Delete:DokumenPengeluaran');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:DokumenPengeluaran');
    }

    public function restore(AuthUser $authUser, DokumenPengeluaran $dokumenPengeluaran): bool
    {
        return $authUser->can('Restore:DokumenPengeluaran');
    }

    public function forceDelete(AuthUser $authUser, DokumenPengeluaran $dokumenPengeluaran): bool
    {
        return $authUser->can('ForceDelete:DokumenPengeluaran');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:DokumenPengeluaran');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:DokumenPengeluaran');
    }

    public function replicate(AuthUser $authUser, DokumenPengeluaran $dokumenPengeluaran): bool
    {
        return $authUser->can('Replicate:DokumenPengeluaran');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:DokumenPengeluaran');
    }

}