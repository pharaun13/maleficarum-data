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
     * Fetch the name of current shard.
     *
     * @return string
     */
    abstract public function getShardRoute(): string;

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
