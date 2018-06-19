<?php
/**
 * This class sets up basic model functionality common to all maleficarum persistable models.
 */
declare(strict_types=1);

namespace Maleficarum\Data\Model\Persistable;

abstract class AbstractModel extends \Maleficarum\Data\Model\AbstractModel {
    /**
     * Persist data stored in this model as a new storage entry.
     *
     * @return \Maleficarum\Data\Model\Persistable\AbstractModel|$this enables method chaining
     */
    abstract public function create(): \Maleficarum\Data\Model\Persistable\AbstractModel;

    /**
     * Refresh this model with current data from the storage
     *
     * @return \Maleficarum\Data\Model\Persistable\AbstractModel|$this enables method chaining
     */
    abstract public function read(): \Maleficarum\Data\Model\Persistable\AbstractModel;

    /**
     * Update storage entry with data currently stored in this model.
     *
     * @return \Maleficarum\Data\Model\Persistable\AbstractModel|$this enables method chaining
     */
    abstract public function update(): \Maleficarum\Data\Model\Persistable\AbstractModel;

    /**
     * Delete an entry from the storage based on ID data stored in this model
     *
     * @return \Maleficarum\Data\Model\Persistable\AbstractModel|$this enables method chaining
     */
    abstract public function delete(): \Maleficarum\Data\Model\Persistable\AbstractModel;

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
