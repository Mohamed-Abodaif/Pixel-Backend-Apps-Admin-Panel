<?php

namespace App\Observers;

use App\Models\WorkSector\UsersModule\User;



class userObserver
{

    /**
     * Handle the Product "created" event.
     *
     * @param  \App\Models\WorkSector\UsersModule\User  $user
     * @return void
     */
    public function retrieved(User $user)
    {
        //         $user->role = isset($user->roles) ? $user->roles->only(['id', 'name'])->first() : null; //$user->getRoleNames()->first() ?? 'unsigned';
        //         unset($user->roles);
    }
    /**
     * Handle the Product "created" event.
     *
     * @param  \App\Models\WorkSector\UsersModule\User  $user
     * @return void
     */
    public function creating(User $user)
    {
        $user->name = "{$user->first_name} {$user->last_name}";
    }

    /**
     * Handle the Product "created" event.
     *
     * @param  \App\Models\WorkSector\UsersModule\User $user
     * @return void
     */
    public function updating(User $user)
    {
        $user->name = "{$user->first_name} {$user->last_name}";
    }

    /**
     * Handle the User "created" event.
     *
     * @param  \App\Models\WorkSector\UsersModule\User  $user
     * @return void
     */
    public function created(User $user)
    {
        //
    }

    /**
     * Handle the User "updated" event.
     *
     * @param  \App\Models\WorkSector\UsersModule\User  $user
     * @return void
     */
    public function updated(User $user)
    {
    }

    /**
     * Handle the User "deleted" event.
     *
     * @param  \App\Models\WorkSector\UsersModule\User  $user
     * @return void
     */
    public function deleted(User $user)
    {
        //
    }

    /**
     * Handle the User "restored" event.
     *
     * @param  \App\Models\WorkSector\UsersModule\User  $user
     * @return void
     */
    public function restored(User $user)
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     *
     * @param  \App\Models\WorkSector\UsersModule\User  $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        //
    }
}
