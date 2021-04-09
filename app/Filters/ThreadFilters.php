<?php


namespace App\Filters;


use App\Models\Thread;
use App\Models\User;
use Illuminate\Http\Request;

class ThreadFilters extends Filters
{

    protected $filters = ['by', 'popular', 'unanswered'];

    /**
     * Filter the query by a given username
     *
     * @param string $username
     * @return mixed
     */
    protected function by($username)
    {
        $user = User::where('name', $username)->firstOrFail();
        return $this->builder->where('user_id', $user->id);
    }

    /**
     * Filter the query by popularity
     *
     * @return mixed
     */
    protected function popular()
    {
        $this->resetBuilder();
        return $this->builder->orderBy('replies_count', 'desc');
    }

    /**
     * Filter the query by unanswered threads
     *
     * @return mixed
     */
    protected function unanswered()
    {
        $this->resetBuilder();
        return $this->builder->whereDoesntHave('replies');
    }


    private function resetBuilder()
    {
        $this->builder->getQuery()->orders = [];
    }

}
