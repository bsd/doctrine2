<?php

declare(strict_types=1);

namespace Doctrine\ORM\Sequencing;

use Doctrine\ORM\EntityManager;
use Serializable;

/**
 * Represents an ID generator that uses a database sequence.
 *
 * @since 2.0
 * @author Roman Borschel <roman@code-factory.org>
 */
class SequenceGenerator implements Generator, Serializable
{
    /**
     * The allocation size of the sequence.
     *
     * @var int
     */
    private $allocationSize;

    /**
     * The name of the sequence.
     *
     * @var string
     */
    private $sequenceName;

    /**
     * @var int
     */
    private $nextValue = 0;

    /**
     * @var int|null
     */
    private $maxValue = null;

    /**
     * Initializes a new sequence generator.
     *
     * @param string  $sequenceName   The name of the sequence.
     * @param integer $allocationSize The allocation size of the sequence.
     */
    public function __construct($sequenceName, $allocationSize)
    {
        $this->sequenceName = $sequenceName;
        $this->allocationSize = $allocationSize;
    }

    /**
     * {@inheritdoc}
     */
    public function generate(EntityManager $em, $entity)
    {
        if ($this->maxValue === null || $this->nextValue == $this->maxValue) {
            // Allocate new values
            $conn = $em->getConnection();
            $sql  = $conn->getDatabasePlatform()->getSequenceNextValSQL($this->sequenceName);

            // Using `query` to force usage of the master server in MasterSlaveConnection
            $this->nextValue = (int) $conn->query($sql)->fetchColumn();
            $this->maxValue  = $this->nextValue + $this->allocationSize;
        }

        return $this->nextValue++;
    }

    /**
     * Gets the maximum value of the currently allocated bag of values.
     *
     * @return integer|null
     */
    public function getCurrentMaxValue()
    {
        return $this->maxValue;
    }

    /**
     * Gets the next value that will be returned by generate().
     *
     * @return integer
     */
    public function getNextValue()
    {
        return $this->nextValue;
    }

    /**
     * @return string
     */
    public function serialize()
    {
        return serialize(
            [
            'allocationSize' => $this->allocationSize,
            'sequenceName'   => $this->sequenceName
            ]
        );
    }

    /**
     * @param string $serialized
     *
     * @return void
     */
    public function unserialize($serialized)
    {
        $array = unserialize($serialized);

        $this->sequenceName = $array['sequenceName'];
        $this->allocationSize = $array['allocationSize'];
    }

    /**
     * {@inheritdoc}
     */
    public function isPostInsertGenerator()
    {
        return false;
    }
}
