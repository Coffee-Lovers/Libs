<?php
namespace CLLibs;
/**
 * Created by PhpStorm.
 * User: nikolatrbojevic
 * Date: 30/10/2016
 * Time: 22:52
 */

interface Serializable
{
    /**
     * serialize object to string
     *
     * @return string
     */
    public function serialize(): string;

    /**
     * Unserialize from string to object
     *
     * @param string $serialized The serialized object.
     *
     * @return mixed
     */
    public static function unserialize(string $serialized);
}