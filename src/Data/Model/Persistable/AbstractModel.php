<?php
/**
 * This class sets up basic model functionality common to all maleficarum persistable models.
 */
declare(strict_types=1);

namespace Maleficarum\Data\Model\Persistable;

abstract class AbstractModel extends \Maleficarum\Data\Model\AbstractModel {
    /* ------------------------------------ Data\AbstractModel methods START --------------------------- */

    /**
     * @see \Maleficarum\Data\Model\AbstractModel::getId()
     */
    public function getId() {
        $method = 'get' . ucfirst($this->getModelPrefix()) . 'Id';
        if (!method_exists($this, $method)) {
            throw new \RuntimeException(sprintf('Invalid model structure - getId() method could not be forwarded properly. %s::getId()', static::class));
        }
        
        return $this->$method();
    }

    /**
     * @see \Maleficarum\Data\Model\AbstractModel::setId()
     */
    public function setId($id): \Maleficarum\Data\Model\AbstractModel {
        $method = 'set' . ucfirst($this->getModelPrefix()) . 'Id';
        if (!method_exists($this, $method)) {
            throw new \RuntimeException(sprintf('Invalid model structure - setId() method could not be forwarded properly. %s::getId()', static::class));
        }
        
        $this->$method($id);

        return $this;
    }

    /* ------------------------------------ Data\AbstractModel methods END ----------------------------- */
    
    /* ------------------------------------ Abstract methods START ------------------------------------- */
    
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
    
    /**
     * Fetch the name of the grouping used inside the storage enginge. IE:
     *  - for RDBMS it's the table name
     *  - for Redis it's the prefix that will be added to the key to group entities together
     * 
     * @return string
     */
    abstract protected function getStorageGroup(): string;
    
    /* ------------------------------------ Abstract methods END --------------------------------------- */
}
