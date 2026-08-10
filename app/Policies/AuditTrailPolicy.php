<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\AuditTrail;
use Illuminate\Auth\Access\HandlesAuthorization;

class AuditTrailPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:AuditTrail');
    }

    public function view(AuthUser $authUser, AuditTrail $auditTrail): bool
    {
        return $authUser->can('View:AuditTrail');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:AuditTrail');
    }

    public function update(AuthUser $authUser, AuditTrail $auditTrail): bool
    {
        return $authUser->can('Update:AuditTrail');
    }

    public function delete(AuthUser $authUser, AuditTrail $auditTrail): bool
    {
        return $authUser->can('Delete:AuditTrail');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:AuditTrail');
    }

    public function restore(AuthUser $authUser, AuditTrail $auditTrail): bool
    {
        return $authUser->can('Restore:AuditTrail');
    }

    public function forceDelete(AuthUser $authUser, AuditTrail $auditTrail): bool
    {
        return $authUser->can('ForceDelete:AuditTrail');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:AuditTrail');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:AuditTrail');
    }

    public function replicate(AuthUser $authUser, AuditTrail $auditTrail): bool
    {
        return $authUser->can('Replicate:AuditTrail');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:AuditTrail');
    }

}