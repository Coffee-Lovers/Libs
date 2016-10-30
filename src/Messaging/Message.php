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
class Message implements \CLLibs\Serializable
{

    /** @var string  */
    protected $topic;
    /**
     * @var array
     */
    protected $payload;
    /**
     * @var string
     */
    private $version;

    /**
     * Message constructor.
     *
     * @param string $version The message version.
     * @param string $topic Message topic.
     * @param array  $payload Message payload.
     */
    public function __construct(string $version, string $topic, array $payload)
    {
        $this->topic   = $topic;
        $this->payload = $payload;
        $this->version = $version;
    }

    /**
     * @return string
     */
    public function getTopic(): string
    {
        return $this->topic;
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
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
     *
     * @return string
     */
    public function serialize() : string
    {
        return json_encode([
            'topic'    => $this->topic,
            '_version' => $this->version,
            'payload'  => $this->payload
        ]);
    }

    /**
     * Constructs the object
     * @link http://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return self
     * @since 5.1.0
     */
    public static function unserialize(string $serialized)
    {
        $unSerialized  = json_decode($serialized, true);
        return new self(
            $unSerialized['_version'], $unSerialized['topic'], $unSerialized['payload']
        );
    }
}