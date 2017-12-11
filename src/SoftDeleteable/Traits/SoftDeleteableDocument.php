<?php

namespace Gedmo\SoftDeleteable\Traits;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * SoftDeletable Trait.
 *
 * @author Wesley van Opdorp <wesley.van.opdorp@freshheads.com>
 * @author Constantin Bejenaru <constantin.bejenaru@evozon.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
trait SoftDeleteableDocument
{
    /**
     * Deleted flag.
     *
     * @var bool
     * @ODM\Field(type="bool")
     */
    protected $deleted;

    /**
     * Sets deleted flag.
     *
     * @param bool $deleted
     *
     * @return $this
     */
    public function setDeleted(bool $deleted = false)
    {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * Returns deleted flag.
     *
     * @return bool
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * Is deleted?
     *
     * @return bool
     */
    public function isDeleted()
    {
        return $this->deleted;
    }
}
