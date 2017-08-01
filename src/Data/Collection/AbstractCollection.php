<?php
/**
 * This class sets up basic collection functionality common to all maleficarum collections.
 */
declare (strict_types=1);

namespace Maleficarum\Data\Collection;

abstract class AbstractCollection implements \ArrayAccess, \Iterator, \Countable, \JsonSerializable {
    /* ------------------------------------ Class Property START --------------------------------------- */

    /**
     * Internal container for collection objects.
     *
     * @var array
     */
    protected $data = [];

    /* ------------------------------------ Class Property END ----------------------------------------- */

    /* ------------------------------------ Class Methods START ---------------------------------------- */

    /**
     * Attach provided input and flatten result into a 1:1 relation.
     *
     * @param array|\Maleficarum\Data\Collection\AbstractCollection $input
     * @param string $newParamName
     * @param array $mapping
     *
     * @returns \Maleficarum\Data\Collection\AbstractCollection
     * @throws \InvalidArgumentException
     */
    public function attachflat($input, string $newParamName, array $mapping): \Maleficarum\Data\Collection\AbstractCollection {
        // check if there is anything to do
        if (!count($this->data)) {
            return $this;
        }

        // attach input
        $this->attach($input, $newParamName, $mapping);

        // flatten
        foreach ($this->data as &$val) {
            count($val[$newParamName]) and $val[$newParamName] = array_shift($val[$newParamName]);
        }

        // conclude
        return $this;
    }

    /**
     * Attach data from another collection into this one base on a matching column.
     *
     * @param array|\Maleficarum\Data\Collection\AbstractCollection $input
     * @param string $newParamName
     * @param array $mapping
     *
     * @returns \Maleficarum\Data\Collection\AbstractCollection
     * @throws \InvalidArgumentException
     */
    public function attach($input, string $newParamName, array $mapping): \Maleficarum\Data\Collection\AbstractCollection {
        // check if there is anything to do
        if (!count($this->data)) {
            return $this;
        }

        //validate input
        is_array($input) || $input instanceof \Maleficarum\Data\Collection\AbstractCollection or $this->respondToInvalidArgument('Incorrect input provided - array or Collection expected. \%s::attach()');
        array_key_exists('local', $mapping) or $this->respondToInvalidArgument('Incorrect mapping provided - correct array expected. \%s::attach()');

        // remote is only used if defined - otherwise defaults to local
        array_key_exists('remote', $mapping) or $mapping['remote'] = $mapping['local'];

        // execute logic
        $temp = [];
        foreach ($this->data as &$val) {
            $val[$newParamName] = [];
            $temp[$val[$mapping['local']]] = [];
        }

        foreach ($input as $remoteVal) {
            $temp[$remoteVal[$mapping['remote']]][] = $remoteVal;
        }

        foreach ($this->data as &$val) {
            $val[$newParamName] = $temp[$val[$mapping['local']]];
        }

        // conclude
        return $this;
    }

    /**
     * Attach provided input but use one of it's columns as indexes of the attached list.
     *
     * @param array|\Maleficarum\Data\Collection\AbstractCollection $input
     * @param string $newParamName
     * @param array $mapping
     *
     * @return \Maleficarum\Data\Collection\AbstractCollection
     * @throws \InvalidArgumentException
     */
    public function attachindexed($input, string $newParamName, array $mapping): \Maleficarum\Data\Collection\AbstractCollection {
        // check if there is anything to do
        if (!count($this->data)) {
            return $this;
        }

        //validate input
        is_array($input) || $input instanceof \Maleficarum\Data\Collection\AbstractCollection or $this->respondToInvalidArgument('Incorrect input provided - array or Collection expected. \%s::attachindexed()');
        array_key_exists('local', $mapping) && array_key_exists('index', $mapping) or $this->respondToInvalidArgument('Incorrect mapping provided - correct array expected. \%s::attachindexed()');

        // remote is only used if defined - otherwise defaults to local
        array_key_exists('remote', $mapping) or $mapping['remote'] = $mapping['local'];

        // execute logic
        $temp = [];
        foreach ($this->data as &$val) {
            $val[$newParamName] = [];
            $temp[$val[$mapping['local']]] = [];
        }

        foreach ($input as $remoteVal) {
            isset($temp[$remoteVal[$mapping['remote']]][$remoteVal[$mapping['index']]]) or $temp[$remoteVal[$mapping['remote']]][$remoteVal[$mapping['index']]] = [];
            $temp[$remoteVal[$mapping['remote']]][$remoteVal[$mapping['index']]][] = $remoteVal;
        }

        foreach ($this->data as &$val) {
            $val[$newParamName] = $temp[$val[$mapping['local']]];
        }

        // conclude
        return $this;
    }

    /**
     * Attach provided input, but use one of it's columns as indexes of the attached list, and flatten result into a 1:1 relation.
     *
     * @param array|\Maleficarum\Data\Collection\AbstractCollection $input
     * @param string $newParamName
     * @param array $mapping
     *
     * @return \Maleficarum\Data\Collection\AbstractCollection
     * @throws \InvalidArgumentException
     */
    public function attachflatindexed($input, string $newParamName, array $mapping): \Maleficarum\Data\Collection\AbstractCollection {
        // check if there is anything to do
        if (!count($this->data)) {
            return $this;
        }

        //validate input
        is_array($input) || $input instanceof \Maleficarum\Data\Collection\AbstractCollection or $this->respondToInvalidArgument('Incorrect input provided - array or Collection expected. \%s::attachflatindexed()');
        array_key_exists('local', $mapping) && array_key_exists('index', $mapping) or $this->respondToInvalidArgument('Incorrect mapping provided - correct array expected. \%s::attachflatindexed()');

        // remote is only used if defined - otherwise defaults to local
        array_key_exists('remote', $mapping) or $mapping['remote'] = $mapping['local'];

        // execute logic
        $temp = [];
        foreach ($this->data as &$val) {
            $val[$newParamName] = [];
            $temp[$val[$mapping['local']]] = [];
        }

        foreach ($input as $remoteVal) {
            isset($temp[$remoteVal[$mapping['remote']]][$remoteVal[$mapping['index']]]) or $temp[$remoteVal[$mapping['remote']]][$remoteVal[$mapping['index']]] = [];
            $temp[$remoteVal[$mapping['remote']]][$remoteVal[$mapping['index']]] = $remoteVal;
        }

        foreach ($this->data as &$val) {
            $val[$newParamName] = $temp[$val[$mapping['local']]];
        }

        // conclude
        return $this;
    }

    /**
     * Attach input to this collection using map as the intermediary.
     *
     * CAUTION: This method has a highly complicated logic but thanks to that it remains at the O(n) complexity.
     *
     * @param \Maleficarum\Data\Collection\AbstractCollection $input
     * @param \Maleficarum\Data\Collection\AbstractCollection $map
     * @param string $newParamName
     * @param array $mapping
     *
     * @return \Maleficarum\Data\Collection\AbstractCollection
     * @throws \InvalidArgumentException
     */
    public function usemap(\Maleficarum\Data\Collection\AbstractCollection $input, \Maleficarum\Data\Collection\AbstractCollection $map, string $newParamName, array $mapping): \Maleficarum\Data\Collection\AbstractCollection {
        // check if there is anything to do
        if (!count($this->data)) {
            return $this;
        }
        if (!count($input)) {
            return $this;
        }
        if (!count($map)) {
            return $this;
        }

        //validate input
        array_key_exists('local', $mapping) or $this->respondToInvalidArgument('Incorrect mapping provided - correct array expected. \%s::usemap()');

        /** Init */
        // remote not present - use local are remote
        array_key_exists('remote', $mapping) or $mapping['remote'] = $mapping['local'];

        // establish map local/remote names
        $mapping['map_local'] = 'map' . ucfirst($mapping['local']);
        $mapping['map_remote'] = 'map' . ucfirst($mapping['remote']);

        /** Preparations */
        // reindex input by remote name
        $temp = $input->reindex($mapping['remote']);

        // create a copy of map for manipulation
        $tempMap = $map->toArray();

        // prepare local data set with a placeholder to receive data
        foreach ($this->data as &$val) {
            $val[$newParamName] = [];
        }

        /** Logic */
        foreach ($tempMap as $key => &$tmEntry) {
            // check if the current map entry has a corresponding input value
            if (isset($temp[$tmEntry[$mapping['map_remote']]])) {
                // attach corresponding input value to map
                $tmEntry[$mapping['map_remote']] = $temp[$tmEntry[$mapping['map_remote']]];
            } else {
                // unset map entry if it has no corresponding value
                unset($tempMap[$key]);
            }
        }

        $temp = [];
        foreach ($tempMap as $tmpEntry) {
            isset($temp[$tmpEntry[$mapping['map_local']]]) or $temp[$tmpEntry[$mapping['map_local']]] = [];
            $temp[$tmpEntry[$mapping['map_local']]][] = $tmpEntry[$mapping['map_remote']];
        }

        foreach ($this->data as &$val) {
            isset($temp[$val[$mapping['local']]]) and $val[$newParamName] = $temp[$val[$mapping['local']]];
        }

        // conclude
        return $this;
    }

    /**
     * Fetch collection data as an array indexed by the provided column.
     * CAUTION: WILL ONLY WORK FOR COLUMNS WITH UNIQUE VALUES
     *
     * @param string $column
     *
     * @return array
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function reindex(string $column): array {
        // check if there is anything to do
        if (!count($this->data)) {
            return [];
        }

        // execute logic
        $result = [];
        foreach ($this->data as $value) {
            is_object($value) and $value = (array)$value;

            array_key_exists($column, $value) or $this->respondToInvalidArgument('Missing column. \%s::reindex()');
            if (array_key_exists($value[$column], $result)) {
                throw new \RuntimeException(sprintf('Non-unique key value detected. \%s::reindex()', get_class($this)));
            }

            $result[$value[$column]] = $value;
        }

        // conclude
        return $result;
    }

    /**
     * Cast this collection to a primitive array type.
     *
     * @param array $skip
     *
     * @return array
     */
    public function toArray(array $skip = []): array {
        // take the easier route (no need to skip)
        if (!count($skip)) {
            $result = (array)$this->data;
            // take the harder route (something needs to be skipped)
        } else {
            $result = [];
            $skip = array_flip($skip);

            foreach ($this->data as $entry) {
                $result[] = array_diff_key((array)$entry, $skip);
            }
        }

        // conclude
        return $result;
    }

    /**
     * Remove a specified property from each collection element.
     *
     * @param string $property
     *
     * @return \Maleficarum\Data\Collection\AbstractCollection
     */
    public function purge(string $property): \Maleficarum\Data\Collection\AbstractCollection {
        // check if there is anything to do
        if (!count($this->data)) {
            return $this;
        }

        // execute logic
        foreach ($this->data as &$el) {
            if (is_array($el) && array_key_exists($property, $el)) {
                unset($el[$property]);
            }
            if (is_object($el) && property_exists($el, $property)) {
                unset($el->$property);
            }
        }

        // conclude
        return $this;
    }

    /**
     * Extract a single column from the entire collection.
     *
     * @param string $column
     *
     * @return array
     * @throws \InvalidArgumentException
     */
    public function extract(string $column): array {
        // check if there is anything to do
        if (!count($this->data)) {
            return [];
        }

        // since the next check relies on the current internal function we need to reset our data structure to ensure that a "current" element exists
        reset($this->data);

        // check structure of current element
        !array_key_exists($column, current($this->data)) and $this->respondToInvalidArgument('Incorrect column name provided - column does not exist. \%s::extract()');

        // exevute logic
        $result = [];
        foreach ($this->data as $key => $el) {
            is_array($el) && array_key_exists($column, $el) and $result[$key] = $el[$column];
            is_object($el) && property_exists($el, $column) and $result[$key] = $el->$column;
        }

        // conclude
        return $result;
    }

    /**
     * Extract a few columns from the entire collection.
     *
     * @param array $columns
     *
     * @return array
     * @throws \InvalidArgumentException
     */
    public function extractMany(array $columns = []): array {
        // check if there is anything to do
        if (!count($this->data)) {
            return [];
        }

        // since the next check relies on the current internal function we need to reset our data structure to ensure that a "current" element exists
        reset($this->data);

        // validate input
        count($columns) or $this->respondToInvalidArgument('Incorrect columns name provided - nonempty array expected. \%s::extractMany()');

        $columns = array_flip($columns);
        count(array_diff_key($columns, current($this->data))) === 0 or $this->respondToInvalidArgument('Incorrect columns name provided - column does not exist. \%s::extractMany()');

        // execute logic
        $result = [];
        foreach ($this->data as $key => $el) {
            $result[$key] = array_intersect_key($el, $columns);
        }

        // conclude
        return $result;
    }

    /**
     * Extract many columns from the entire collection.
     *
     * @param array $columns
     *
     * @return array
     * @throws \InvalidArgumentException
     */
    public function removeMany(array $columns): array {
        // check if there is anything to do
        if (!count($this->data)) {
            return [];
        }

        // validate input
        count($columns) or $this->respondToInvalidArgument('Incorrect column name provided - nonempty array expected. \%s::removeMany()');

        // since the next check relies on the current internal function we need to reset our data structure to ensure that a "current" element exists
        reset($this->data);

        // init
        $result = [];
        $columns = array_flip($columns);

        // execute removal
        foreach ($this->data as $key => $el) {
            is_array($el) or $el = (array)$el;
            $result[$key] = array_diff_key($el, $columns);
        }

        // conclude
        return $result;
    }

    /**
     * Extract two specified columns as a hash map.
     * CAUTION: THIS METHOD WILL FAIL IF $keyCol HAS NONUNIQUE VALUES - FOR NONUNIQUE VALUES USE THE extractGroupedBy() METHOD
     *
     * @param string $keyCol
     * @param string $valCol
     *
     * @return array
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function map(string $keyCol, string $valCol): array {
        // check if there is anything to do
        if (!count($this->data)) {
            return [];
        }

        // exevute logic
        $result = [];
        foreach ($this->data as $el) {
            is_object($el) and $el = (array)$el;

            array_key_exists($keyCol, $el) or $this->respondToInvalidArgument('Missing key column. \%s::map()');
            array_key_exists($valCol, $el) or $this->respondToInvalidArgument('Missing value column. \%s::map()');
            if (array_key_exists($el[$keyCol], $result)) {
                throw new \RuntimeException(sprintf('Non-unique key value detected. \%s::map()', get_class($this)));
            }

            $result[$el[$keyCol]] = $el[$valCol];
        }

        // conclude
        return $result;
    }

    /**
     * Fetch current data as an array with indexes matching values from the specified column and only the first match element in each key.
     *
     * @param string $column
     *
     * @return array
     */
    public function groupflat(string $column): array {
        // group
        $result = $this->group($column);

        // flatten to 1:1 relation
        foreach ($result as &$el) {
            $el = array_shift($el);
        }

        // conclude
        return $result;
    }

    /**
     * Fetch current data as an array with indexes matching values from the specified column.
     *
     * @param string $column
     *
     * @return array
     */
    public function group(string $column): array {
        // check if there is anything to do
        if (!count($this->data)) {
            return [];
        }

        // execute logic
        $result = [];
        foreach ($this->data as $value) {
            is_object($value) and $value = (array)$value;

            array_key_exists($value[$column], $result) or $result[$value[$column]] = [];
            $result[$value[$column]][] = $value;
        }

        // conclude
        return $result;
    }

    /**
     * Extract two specified columns as a map of grouped values. Useful when the $keyCol column has non-unique values.
     *
     * @param string $keyCol
     * @param string $valCol
     *
     * @return array
     */
    public function regroup(string $keyCol, string $valCol): array {
        // check if there is anything to do
        if (!count($this->data)) {
            return [];
        }

        // execute logic
        $result = [];
        foreach ($this->data as $el) {
            is_object($el) and $el = (array)$el;

            !array_key_exists($el[$keyCol], $result) and $result[$el[$keyCol]] = [];
            $result[$el[$keyCol]][] = $el[$valCol];
        }

        // conclude
        return $result;
    }

    /**
     * Extract a numerical subset of data.
     *
     * @param int $limit
     * @param int $offset
     *
     * @return array
     */
    public function subset(int $limit = 10, int $offset = 0): array {
        return array_slice($this->data, $offset, $limit);
    }

    /* ------------------------------------ Class Methods END ------------------------------------------ */

    /* ------------------------------------ Helper methods START --------------------------------------- */

    /**
     * This method is a code style helper - it will simply throw an \InvalidArgumentException
     *
     * @param string $msg
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    protected function respondToInvalidArgument(string $msg) {
        throw new \InvalidArgumentException(sprintf($msg, static::class));
    }

    /* ------------------------------------ Helper methods END ----------------------------------------- */

    /* ------------------------------------ Setters & Getters START ------------------------------------ */

    /**
     * Replace current data with the provided container.
     *
     * @param array $data
     *
     * @return \Maleficarum\Data\Collection\AbstractCollection
     */
    public function setData(array $data): \Maleficarum\Data\Collection\AbstractCollection {
        $this->data = $data;

        return $this;
    }

    /**
     * Clear this collection of all data.
     *
     * @return \Maleficarum\Data\Collection\AbstractCollection
     */
    public function clear(): \Maleficarum\Data\Collection\AbstractCollection {
        $this->data = [];

        return $this;
    }

    /* ------------------------------------ Setters & Getters END -------------------------------------- */

    /* ------------------------------------ ArrayAccess methods START ---------------------------------- */

    /**
     * @see \ArrayAccess::offsetExists()
     */
    public function offsetExists($offset): bool {
        return array_key_exists($offset, $this->data);
    }

    /**
     * @see \ArrayAccess::offsetGet()
     */
    public function offsetGet($offset) {
        return $this->data[$offset];
    }

    /**
     * @see \ArrayAccess::offsetSet()
     */
    public function offsetSet($offset, $value) {
        $this->data[$offset] = $value;

        return $this;
    }

    /**
     * @see \ArrayAccess::offsetUnset()
     */
    public function offsetUnset($offset) {
        unset($this->data[$offset]);
    }

    /* ------------------------------------ ArrayAccess methods END ------------------------------------ */

    /* ------------------------------------ Iterator methods START ------------------------------------- */

    /**
     * @see \Iterator::current()
     */
    public function current() {
        return current($this->data);
    }

    /**
     * @see \Iterator::next()
     */
    public function next() {
        next($this->data);
    }

    /**
     * @see \Iterator::key()
     */
    public function key() {
        return key($this->data);
    }

    /**
     * @see \Iterator::valid()
     */
    public function valid(): bool {
        return array_key_exists($this->key(), $this->data);
    }

    /**
     * @see \Iterator::rewind()
     */
    public function rewind() {
        reset($this->data);
    }

    /* ------------------------------------ Iterator methods END --------------------------------------- */

    /* ------------------------------------ Countable methods START ------------------------------------ */

    /**
     * @see \Countable::count()
     */
    public function count(): int {
        return count($this->data);
    }

    /* ------------------------------------ Countable methods END -------------------------------------- */

    /* ------------------------------------ JsonSerializable methods START ----------------------------- */

    /**
     * @see \JsonSerializable::jsonSerialize()
     */
    public function jsonSerialize() {
        return $this->toArray();
    }

    /* ------------------------------------ JsonSerializable methods END ------------------------------- */

    /* ------------------------------------ Abstract methods START ------------------------------------- */

    /**
     * Populate this collection with data.
     *
     * @param array $data
     *
     * @return \Maleficarum\Data\Collection\AbstractCollection
     */
    abstract public function populate(array $data = []): \Maleficarum\Data\Collection\AbstractCollection;

    /* ------------------------------------ Abstract methods END --------------------------------------- */
}
