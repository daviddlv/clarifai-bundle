<?php

namespace ClarifaiBundle\Clarifai;

class Input extends Param
{
    /**
     * @var array
     */
    protected $data = array();

    public function __construct($data, $concepts = [])
    {
        if ($data instanceof Data) {
            $this->addData($data);
        } elseif (is_array($data)) {
            foreach ($data as $item) {
                $this->addData($item);
            }
        }
    }

    public function addData(Data $data)
    {
        $this->data[] = $data;

        return $this;
    }

    public function toArray()
    {
        $this->setParams(array_merge($this->getParams(), $this->data));

        return parent::toArray();
    }
}