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
     * @return void
     */
    public function publish(Message $message)
    {
        $this->logger->notice("Publishing message.", ["message" => serialize($message)]);
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
        $this->channel->queue_declare('task_queue', false, true, false, false);
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
}