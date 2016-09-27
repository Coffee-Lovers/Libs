<?php
/**
 * Created by PhpStorm.
 * User: nikolatrbojevic
 * Date: 27/09/2016
 * Time: 07:52
 */

namespace CLLibs\Messaging;


interface Hub
{
    /**
     * @param Message $message Publish the message to everybody
     * @return void
     */
    public function publish(Message $message);
}