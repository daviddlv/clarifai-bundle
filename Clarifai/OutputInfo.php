<?php

namespace ClarifaiBundle\Clarifai;


class OutputInfo extends Param
{
    /**
     * @var array
     */
    protected $config;

    /**
     * Concepts.
     *
     * @var array Concepts
     */
    protected $concepts = array();

    public function __construct($concepts, array $config)
    {
        if ($concepts instanceof Concept) {
            $this->addConcept($concepts);
        } elseif (is_array($concepts)) {
            foreach ($concepts as $concept) {
                $this->addConcept($concept);
            }
        }

        $this->config = $config;
    }

    public function addConcept(Concept $concept)
    {
        $this->concepts[] = $concept;

        return $this;
    }

    public function toArray()
    {
        $array = array(
            'data' => array('concepts' => $this->concepts),
            'output_config' => $this->config,
        );

        return $this->_convertArrayable($array);
    }
}