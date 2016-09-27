<?php
namespace CLLibs\Queue\Queue;

use CLLibs\ConnectionConfig;
use \CLLibs\Queue\Queue;
use PhpAmqpLib\Channel\AMQPChannel;
use \PhpAmqpLib\Connection\AMQPStreamConnection;
use \PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\LoggerInterface;

/**
 * RabbitMQ implementation of task queu.
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
     *
     * @return bool
     */
    public function push(\CLLibs\Queue\Task $task): bool
    {
        $this->logger->notice("Pushing the task to the queeu", ['task' => $task]);
        $this->connect();
        $msg = new AMQPMessage(serialize($task), array('delivery_mode' => 2));

        $this->channel->basic_publish($msg, '', 'task_queue');
        $this->tearDown();
        return true;
    }

    /**
     * Make connection to the rabbitMQ
     * @return boolean if connection succeeded
     */
    protected function connect()
    {
        $this->connection = new AMQPStreamConnection($this->host, $this->port, $this->username, $this->password);
        $this->channel = $this->connection->channel();
        $this->channel->queue_declare('task_queue', false, true, false, false);
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
