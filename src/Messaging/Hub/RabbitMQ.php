<?php
/**
 * Created by PhpStorm.
 * User: nikolatrbojevic
 * Date: 27/09/2016
 * Time: 08:01
 */

namespace CLLibs\Messaging\Hub;


use CLLibs\ConnectionConfig;
use CLLibs\Messaging\Hub;
use CLLibs\Messaging\Message;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\LoggerInterface;

/**
 * Class RabbitMQ, messaging hub implementation.
 * @package CLLibs\Messaging\Hub
 */
class RabbitMQ implements Hub
{
    /** @var  AMQPStreamConnection */
    private $connection;

    /** @var  AMQPChannel */
    private $channel;

    /**
     * @var ConnectionConfig
     */
    private $config;
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * RabbitMQ constructor.
     * @param ConnectionConfig $config
     * @param LoggerInterface $logger
     */
    public function __construct(ConnectionConfig $config, LoggerInterface $logger)
    {
        $this->config = $config;
        $this->logger = $logger;
    }

    /**
     * @param Message $message Publish the message to everybody
     * @return bool
     */
    public function publish(Message $message) : bool
    {
        $this->logger->notice("Publishing message.", ["message" => serialize($message)]);
        $this->connect();
        $msg = new AMQPMessage(serialize($message));

        $this->channel->basic_publish($msg, 'coffeepot_progress', $message->getTopic());
        $this->tearDown();
        return true;
    }

    /**
     * @todo move to some common place for queue and messaging hub
     * Make connection to the rabbitMQ
     * @return boolean if connection succeeded
     */
    protected function connect()
    {
        $this->connection = new AMQPStreamConnection(
            $this->config->getHost(), $this->config->getPort(), $this->config->getUser(), $this->config->getPassword()
        );
        $this->channel = $this->connection->channel();
        $this->channel->exchange_declare('coffeepot_progress', 'topic', false, true, false);
    }

    /**
     * @todo move to some common place for queue and messaging hub
     * Close open connectoins.
     * @return void
     */
    protected function tearDown()
    {
        $this->channel->close();
        $this->connection->close();
    }

    /**
     * @param string $topic
     * @param callable $callback
     * @return void
     */
    public function subscribe(string $topic, callable $callback)
    {
        $this->connect();
        list($queueName, ,) = $this->channel->queue_declare("", false, false, true, false);
        $this->channel->queue_bind($queueName, 'coffeepot_progress', $topic);
        $logger = $this->logger;

        $wrapper = function($message) use ($callback, $queueName, $logger) {
            $logger->debug("Consuming message.", ["message" => $message, "queue" => $queueName]);
            $callback($message->body);
        };

        $this->channel->basic_consume($queueName, '', false, true, false, false, $wrapper);
        while(count($this->channel->callbacks)) {
            $this->channel->wait();
        }

        $this->tearDown();
    }
}