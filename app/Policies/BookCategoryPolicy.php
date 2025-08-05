<?php

namespace App\Policies;

use App\Models\User;
use App\Models\BookCategory;

class BookCategoryPolicy
{
    public function viewAny(User $user)
    {
        return true;
    }
    public function view(User $user, BookCategory $category)
    {
        return true;
    }
    public function create(User $user)
    {
        return $user->can('book_category.create');
    }
    public function update(User $user, BookCategory $category)
    {
        return $user->can('book_category.update');
    }
    public function delete(User $user, BookCategory $category)
    {
        return $user->can('book_category.delete');
    }
}
