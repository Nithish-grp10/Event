<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EventPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['super-admin', 'admin', 'staff']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Event $event): bool
    {
        if ($user->hasRole('super-admin')) return true;
        if ($user->hasRole('admin')) return $event->created_by === $user->id;
        if ($user->hasRole('staff')) return $event->staff()->where('user_id', $user->id)->exists();
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['super-admin', 'admin']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Event $event): bool
    {
        if ($user->hasRole('super-admin')) return true;
        return $user->hasRole('admin') && $event->created_by === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Event $event): bool
    {
        return $this->update($user, $event);
    }

    /**
     * Determine whether the user can publish the model.
     */
    public function publish(User $user, Event $event): bool
    {
        return $this->update($user, $event);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Event $event): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Event $event): bool
    {
        return false;
    }
}
