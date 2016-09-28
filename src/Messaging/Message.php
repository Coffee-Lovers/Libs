<?php
/**
 * Created by PhpStorm.
 * User: nikolatrbojevic
 * Date: 27/09/2016
 * Time: 07:49
 */

namespace CLLibs\Messaging;


/**
 * Class Message that carries simple progress update.
 * @package CLLibs\Messaging
 */
class Message implements \Serializable
{

    /** @var string  */
    protected $topic;
    /**
     * @var array
     */
    protected $payload;

    /**
     * Message constructor.
     *
     * @param string $topic   Message topic.
     * @param array  $payload Message payload.
     */
    public function __construct(string $topic, array $payload)
    {
        $this->topic   = $topic;
        $this->payload = $payload;
    }

    /**
     * @return string
     */
    public function getTopic(): string
    {
        return $this->topic;
    }

    /**
     * @return array
     */
    public function getPayload(): array
    {
        return $this->payload;
    }

    /**
     * String representation of object
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize() : string
    {
        return serialize([
            'topic'   => $this->topic,
            'payload' => $this->payload
        ]);
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
        list($this->topic, $this->payload) = unserialize($serialized);
    }
}