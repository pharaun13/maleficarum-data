<?php
/**
 * This class sets up basic model functionality common to all maleficarum persistable models.
 */
declare(strict_types=1);

namespace Maleficarum\Data\Model;

abstract class AbstractPersistableModel extends AbstractModel {
    /**
     * Persist data stored in this model as a new storage entry.
     *
     * @return \Maleficarum\Data\Model\AbstractPersistableModel|$this enables method chaining
     */
    abstract public function create(): \Maleficarum\Data\Model\AbstractPersistableModel;

    /**
     * Refresh this model with current data from the storage
     *
     * @return \Maleficarum\Data\Model\AbstractPersistableModel|$this enables method chaining
     */
    abstract public function read(): \Maleficarum\Data\Model\AbstractPersistableModel;

    /**
     * Update storage entry with data currently stored in this model.
     *
     * @return \Maleficarum\Data\Model\AbstractPersistableModel|$this enables method chaining
     */
    abstract public function update(): \Maleficarum\Data\Model\AbstractPersistableModel;

    /**
     * Delete an entry from the storage based on ID data stored in this model
     *
     * @return \Maleficarum\Data\Model\AbstractPersistableModel|$this enables method chaining
     */
    abstract public function delete(): \Maleficarum\Data\Model\AbstractPersistableModel;

    /**
     * Validate data stored in this model to check if it can be persisted in storage.
     *
     * @param bool $clear
     *
     * @return bool
     */
    abstract public function validate(bool $clear = true): bool;

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
    abstract protected function getModelPrefix(): string;
}
