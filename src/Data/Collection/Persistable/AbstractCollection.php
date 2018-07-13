<?php
declare(strict_types=1);

namespace Maleficarum\Data\Collection\Persistable;

/**
 * This class sets up basic model functionality common to all maleficarum persistable collections.
 */
abstract class AbstractCollection extends \Maleficarum\Data\Collection\AbstractCollection {
    /**
     * Insert all entries in this collection to it's storage.
     *
     * @return \Maleficarum\Data\Collection\Persistable\AbstractCollection|$this enables method chaining
     */
    abstract public function insertAll(): \Maleficarum\Data\Collection\Persistable\AbstractCollection;

    /**
     * Delete all entries in this collection from it's storage.
     *
     * @return \Maleficarum\Data\Collection\Persistable\AbstractCollection|$this enables method chaining
     */
    abstract public function deleteAll(): \Maleficarum\Data\Collection\Persistable\AbstractCollection;

    /**
     * Fetch the name of current shard.
     *
     * @return string
     */
    abstract public function getShardRoute(): string;

    /**
     * Fetch the name of main ID column - should return null on collections with no or multi-column primary keys.
     *
     * @return null|string
     */
    abstract protected function getIdColumn(): ?string;

    /**
     * Iterate over all current data entries and perform any data formatting necessary. This method should be
     * overloaded in any inheriting collection object that requires any specific data decoding such as JSON
     * de-serialization or date formatting.
     *
     * @return \Maleficarum\Data\Collection\Persistable\AbstractCollection|$this
     */
    protected function format(): \Maleficarum\Data\Collection\Persistable\AbstractCollection {
        return $this;
    }

    /**
     * Fetch current data set as a prepared set used by modification methods (insertAll(), deleteAll()).
     * This method should be overridden in collections that need to behave differently than using a 1:1 mapping of the
     * main data container.
     *
     * @param string $mode
     *
     * @return array
     * @throws \InvalidArgumentException
     */
    protected function prepareElements(string $mode): array {
        if (!\in_array($mode, ['INSERT', 'DELETE'], true)) {
            $this->respondToInvalidArgument('Incorrect preparation mode. \%s::prepareElements()');
        }

        return $this->data;
    }
}
