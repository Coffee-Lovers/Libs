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
     *
     * @return boolean (if the push was successfull or not)
     */
    public function push(\CLLibs\Queue\Task $task): bool;
}
