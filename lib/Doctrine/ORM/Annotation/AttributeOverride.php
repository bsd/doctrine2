<?php

declare(strict_types=1);

namespace Doctrine\ORM\Annotation;

/**
 * This annotation is used to override the mapping of a entity property.
 *
 * @author  Fabio B. Silva <fabio.bat.silva@gmail.com>
 * @since   2.3
 *
 * @Annotation
 * @Target("ANNOTATION")
 */
final class AttributeOverride implements Annotation
{
    /**
     * The name of the property whose mapping is being overridden.
     * 
     * @var string 
     */
    public $name;

    /**
     * The column definition.
     *
     * @var \Doctrine\ORM\Annotation\Column
     */
    public $column;
}
