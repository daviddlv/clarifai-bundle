<?php

namespace ClarifaiBundle\Clarifai;

class Concept extends Param
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var bool|null
     */
    protected $value;

    public function __construct($id, $value = null)
    {
        $this->id = $id;
        if ($value !== null) {
            $this->value = $value;
        }
    }

    public function toArray()
    {
        $array = array('id' => $this->id);

        if ($this->value !== null) {
            $array['value'] = $this->value;
        }

        return $this->_convertArrayable($array);
    }
}