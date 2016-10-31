<?php
namespace CLLibs\Queue;

/**
 * The queue task
 */
class Task implements \CLLibs\Serializable
{
    protected $id;

    /**
     * Task constructor.
     */
    public function __construct()
    {
        $this->id = uniqid("task_");
    }

    /**
     * Get task id.
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * String representation of object
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize() : string
    {
        return json_encode(['id' => $this->id]);
    }

    /**
     * Constructs the object
     *
     * @param string $serialized The serialized
     *
     * @return Task
     */
    public static function unserialize(string $serialized)
    {
        $task = new self();
        $task->id = json_decode($serialized, true)['id'];
    }
}
