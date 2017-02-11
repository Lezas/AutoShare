<?php

namespace MyAutoBundle\Document;

use ONGR\ElasticsearchBundle\Annotation as ES;

/**
 * @ES\Document(type="car")
 */
class CarProfile
{
    /**
     * @var string
     *
     * @ES\Id()
     */
    public $id;

    /**
     * @var string
     *
     * @ES\Property(name="name", type="string")
     */
    public $name;
}