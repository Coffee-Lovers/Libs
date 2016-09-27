<?php
namespace CLLibs\Queue;

/**
 * Simple queue interface.
 */
interface Queue
{
    /**
     * Push task to the queue
     * @param  Task $task The task to push to queue.
     * @param string $queueName
     *
     * @return bool
     */
    public function push(\CLLibs\Queue\Task $task, string $queueName): bool;

    /**
     * @param string $queueName
     * @param callable $callback
     * @return void
     */
    public function consume(string $queueName, Callable $callback);
}
