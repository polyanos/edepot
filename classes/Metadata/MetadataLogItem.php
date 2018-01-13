<?php
/**
 * Created by PhpStorm.
 * User: Gregor
 * Date: 12-12-2017
 * Time: 11:02
 */

class MetadataLogItem
{
    private $type;
    private $name;
    private $message;

    const error = "error";
    const info = "info";

    /**
     * MetadataError constructor.
     * @param $type
     * @param $name
     * @param $message
     */
    public function __construct($type, $name, $message)
    {
        $this->type = $type;
        $this->name = $name;
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }
}