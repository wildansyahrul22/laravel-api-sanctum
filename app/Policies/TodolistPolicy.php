<?php

namespace App\Policies;

use App\Models\Todolist;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TodolistPolicy
{

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Todolist $todolist): bool
    {
        return $user->id === $todolist->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Todolist $todolist): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Todolist $todolist): bool
    {
        return false;
    }
}
