<?php
namespace CL\Queue;

/**
 * Simple queue interface.
 */
interface QueueInterface
{
    /**
     * Push task to the queue
     * @param  Task $task The task to push to queue.
     *
     * @return boolean (if the push was successfull or not)
     */
    public function push(\CL\Queue\Task $task): bool;
}
