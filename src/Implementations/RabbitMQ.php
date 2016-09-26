<?php
namespace CL\Queue\Implementations;

use \CL\Queue\QueueInterface;
use PhpAmqpLib\Channel\AMQPChannel;
use \PhpAmqpLib\Connection\AMQPStreamConnection;
use \PhpAmqpLib\Message\AMQPMessage;

/**
 * RabbitMQ implementation of task queu.
 */
class RabbitMQ implements QueueInterface
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
     * The constructor.
     * @param string $host     [description]
     * @param string $port     [description]
     * @param string $username [description]
     * @param string $password [description]
     */
    public function __construct(
        string $host,
        string $port,
        string $username,
        string $password
    ) {
        $this->host     = $host;
        $this->port     = $port;
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Push task to the queue
     * @param \CL\Queue\Task $task The task to push to queue.
     *
     * @return bool
     */
    public function push(\CL\Queue\Task $task): bool
    {
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
