<?php

namespace App\Service;

class CounterService
{
    const COUNTER_KEY = 'visit_counter';

    /**
     * @var \Redis
     */
    private $redis;

    public function __construct(\Redis $redis)
    {
        $this->redis = $redis;
    }

    public function getCounter()
    {
        return $this->redis->get(self::COUNTER_KEY);
    }

    public function incrementCounter()
    {
        return $this->redis->incr(self::COUNTER_KEY);
    }
}
