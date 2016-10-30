<?php
namespace CLLibs\Queue\Queue;

use CLLibs\ConnectionConfig;
use \CLLibs\Queue\Queue;
use PhpAmqpLib\Channel\AMQPChannel;
use \PhpAmqpLib\Connection\AMQPStreamConnection;
use \PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\LoggerInterface;

/**
 * RabbitMQ implementation of task queue.
 */
class RabbitMQ implements Queue
{
    /** @var string  */
    protected $host;
    /** @var string  */
    protected $port;
    /** @var string  */
    protected $username;
    /** @var string  */
    protected $password;
    /** @var  AMQPChannel */
    protected $channel;
    /** @var  AMQPStreamConnection */
    protected $connection;
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * RabbitMQ constructor.
     * @param ConnectionConfig $config The config of the connection to make.
     * @param LoggerInterface $logger The logger.
     */
    public function __construct(ConnectionConfig $config, LoggerInterface $logger) {
        $this->host     = $config->getHost();
        $this->port     = $config->getPort();
        $this->username = $config->getUser();
        $this->password = $config->getPassword();
        $this->logger   = $logger;
    }

    /**
     * Push task to the queue
     * @param \CLLibs\Queue\Task $task The task to push to queue.
     * @param string $queueName Which queue to use
     *
     * @return bool
     */
    public function push(\CLLibs\Queue\Task $task, string $queueName): bool
    {
        $this->logger->notice("Pushing the task to the queue", ['task' => $task]);
        $this->connect($queueName);
        $msg = new AMQPMessage($task->serialize(), array('delivery_mode' => 2));

        $this->channel->basic_publish($msg, '', 'task_queue');
        $this->tearDown();
        return true;
    }

    /**
     * @param string $queueName
     * @param callable $callback
     * @return void
     */
    public function consume(string $queueName, Callable $callback)
    {
        $this->connect($queueName);
        $this->channel->basic_qos(null, 1, null);

        $logger = $this->logger;
        $wrapper = function($message) use ($callback, $queueName, $logger) {
            $logger->debug("Consuming message.", ["message" => $message, "queue" => $queueName]);
            $callback($message->body);
            $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);
        };

        $this->channel->basic_consume('task_queue', '', false, false, false, false, $wrapper);

        while(count($this->channel->callbacks)) {
            $this->channel->wait();
        }

        $this->tearDown();
    }

    /**
     * Make connection to the rabbitMQ
     * @param string $queueName Which queue name to use.
     * @return bool if connection succeeded
     */
    protected function connect(string $queueName)
    {
        $this->connection = new AMQPStreamConnection($this->host, $this->port, $this->username, $this->password);
        $this->channel = $this->connection->channel();
        $this->channel->queue_declare($queueName, false, true, false, false);
    }

    /**
     * Close open connectoins.
     * @return void
     */
    protected function tearDown()
    {
        $this->channel->close();
        $this->connection->close();
    }
}
