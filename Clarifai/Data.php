<?php

namespace ClarifaiBundle\Clarifai;

class Data extends Param
{
    /**
     * @var array
     */
    protected $media;

    /**
     * @var array
     */
    protected $metadata;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var array
     */
    protected $concepts = array();

    public function __construct(array $media, array $metadata = array(), $concepts = [], $id = null)
    {
        $this->media = $media;
        $this->metadata = $metadata;
        $this->id = $id;

        if ($concepts instanceof Concept) {
            $this->addConcept($concepts);
        } elseif (is_array($concepts)) {
            foreach ($concepts as $concept) {
                $this->addConcept($concept);
            }
        }
    }

    public function addConcept(Concept $concept)
    {
        $this->concepts[] = $concept;

        return $this;
    }

    public function toArray()
    {
        $array = array('data' => array('image' => $this->media));

        if (!empty($this->metadata)) {
            $array['data']['metadata'] = $this->metadata;
        }

        if ($this->id) {
            $array['id'] = (string) $this->id;
        }

        if (!empty($this->concepts)) {
            $array['data']['concepts'] = $this->concepts;
        }

        return $this->_convertArrayable($array);
    }
}