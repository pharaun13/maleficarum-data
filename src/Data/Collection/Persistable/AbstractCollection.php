<?php
declare(strict_types=1);

namespace Maleficarum\Data\Collection\Persistable;

/**
 * This class sets up basic model functionality common to all maleficarum persistable collections.
 */
abstract class AbstractCollection extends \Maleficarum\Data\Collection\AbstractCollection {
    /* ------------------------------------ Class Methods START ---------------------------------------- */

    /**
     * @see \Maleficarum\Data\Collection\AbstractCollection::populate()
     */
    public function populate(array $data = []): \Maleficarum\Data\Collection\AbstractCollection {
        $this->data = $data;

        return $this;
    }
    
    /* ------------------------------------ Class Methods END ------------------------------------------ */
    
    /* ------------------------------------ Abstract methods START ------------------------------------- */

    /**
     * Validate data stored in this collection to check if it can be persisted in storage.
     *
     * @param bool $clear
     * @return bool
     */
    abstract public function validate(bool $clear = true): bool;

    /**
     * Fetch the name of the domain group for the entity stored in this model.
     *
     * @return string
     */
    abstract public function getDomainGroup(): string;

    /**
     * Fetch the prefix used as a prefix for database column property names.
     *
     * @return string
     */
    abstract public function getModelPrefix(): string;

    /**
     * Fetch the name of the grouping used inside the storage engine. IE:
     *  - for RDBMS it's the table name
     *  - for Redis it's the prefix that will be added to the key to group entities together
     *
     * @return string
     */
    abstract public function getStorageGroup(): string;

    /* ------------------------------------ Abstract methods END --------------------------------------- */
}
