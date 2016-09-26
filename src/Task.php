<?php
namespace CL\Queue;

/**
 * The queue task
 */
class Task implements \Serializable
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
        return serialize(['id' => $this->id]);
    }

    /**
     * Constructs the object
     * @link http://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function unserialize($serialized)
    {
        list($this->id) = unserialize($serialized);
    }
}
