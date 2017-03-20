<?php

/**
 * This is a generic implementation of the abstract collection class defined in maleficarum-data.
 */
declare (strict_types=1);

namespace Maleficarum\Data\Collection\Generic;

class Collection extends \Maleficarum\Data\Collection\AbstractCollection {
	/* ------------------------------------ AbstractCollection methods START --------------------------- */
		
	/**
	 * @see Maleficarum\Data\Collection\AbstractCollection::populate()
	 */
	public function populate(array $data = []) : \Maleficarum\Data\Collection\AbstractCollection {
		$this->data = $data;

		return $this;
	}

	/* ------------------------------------ AbstractCollection methods END ----------------------------- */
}
