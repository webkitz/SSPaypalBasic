<?php

class SSPaypalBasic extends DataExtension
{    
    protected static $sandBox = false;


    /**
     * @param $sandbox bool sets sandbox mode
     */
    public static function sandBoxMode()
    {
        self::$sandBox = true;
    }

}