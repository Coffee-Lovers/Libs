<?php
/**
 * Created by PhpStorm.
 * User: nikolatrbojevic
 * Date: 28/09/2016
 * Time: 08:08
 */

namespace CLLibs\Messaging;

/**
 * Class CoffeePotProgressMessage
 * @package CLLibs\Messaging
 */
class CoffeePotProgressMessage extends Message
{
    const TOPIC       = 'coffeepot.progress';
    const __VERSION__ = 'v1';

    const STAGE_PENDING          = 1;
    const STAGE_BOILING_WATTER   = 2;
    const STAGE_BREWING_COFFEE   = 3;
    const STAGE_ADDING_ADDITIONS = 4;
    const STAGE_FINISHED         = 5;

    /**
     * CoffeePotProgressMessage constructor.
     * @param string $relatedTaskID
     * @param string $stage
     */
    public function __construct(string $relatedTaskID, string $stage)
    {
        parent::__construct(
            self::__VERSION__,
            self::TOPIC,
            [
                'taskID' => $relatedTaskID,
                'stage'  => $stage,
            ]
        );
    }

    /**
     * @return string
     */
    public function getStage() : string
    {
        return parent::getPayload()['stage'];
    }

    /**
     * @return string
     */
    public function getRelatedTaskId() : string
    {
        return parent::getPayload()['taskID'];
    }
}