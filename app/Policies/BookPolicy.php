<?php

namespace App\Policies;

use App\Models\Book;
use App\Models\User;

class BookPolicy
{
    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Book $book)
    {
        return true;
    }

    public function create(User $user)
    {
        return true;
    }

    public function update(User $user, Book $book)
    {
        return true;
    }

    public function delete(User $user, Book $book)
    {
        return true;
    }
}
