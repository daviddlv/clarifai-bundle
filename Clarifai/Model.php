<?php

namespace ClarifaiBundle\Clarifai;

class Model extends Param
{
    /**
     * Name
     *
     * @var string
     */
    protected $name;

    /**
     * @var OutputInfo
     */
    protected $outputInfo;

    public function __construct($name, OutputInfo $outputInfo = null)
    {
        $this->name = $name;
        if ($outputInfo !== null) {
            $this->outputInfo = $outputInfo;
        }
    }

    public function toArray()
    {
        $array = array('name' => $this->name);

        if ($this->outputInfo) {
            $array['output_info'] = $this->outputInfo;
        }

        $this->setParams(array_merge($this->getParams(), $array));

        return parent::toArray();
    }
}