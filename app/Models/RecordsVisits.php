<?php


namespace App\Models;


use Illuminate\Support\Facades\Redis;

trait RecordsVisits
{

    public function resetVisits()
    {
        Redis::del($this->visitsCacheKey());
        return $this;
    }

    public function visitsCacheKey()
    {
        return "threads.{$this->id}.visits";
    }

    public function visits()
    {
        return Redis::get($this->visitsCacheKey()) ?? 0;
    }

    public function recordVisits()
    {
        Redis::incr($this->visitsCacheKey());
        return $this;
    }
}
