<?php
/**
 * This is a generic implementation of the abstract model class defined in maleficarum-data.
 */
declare (strict_types=1);

namespace Maleficarum\Data\Model\Generic;

class Model extends \Maleficarum\Data\Model\AbstractModel {

    /* ------------------------------------ Class Property START --------------------------------------- */

    private $modelId = null;
    private $modelData = null;

    /* ------------------------------------ Class Property END ----------------------------------------- */

    /* ------------------------------------ AbstractModel START ---------------------------------------- */

    /**
     * @see \Maleficarum\Data\Model\AbstractModel.setId()
     */
    public function setId($id): \Maleficarum\Data\Model\AbstractModel {
        $this->modelId = $id;

        return $this;
    }

    /**
     * @see \Maleficarum\Data\Model\AbstractModel::getId()
     */
    public function getId() {
        return $this;
    }

    /* ------------------------------------ AbstractModel END ------------------------------------------ */

    /* ------------------------------------ Setters & Getters START ------------------------------------ */

    public function getModelId() {
        return $this->modelId;
    }

    public function setModelId($modelId) {
        $this->modelId = $modelId;
    }

    public function getModelData() {
        return $this->modelData;
    }

    public function setModelData($modelData) {
        $this->modelData = $modelData;
    }

    /* ------------------------------------ Setters & Getters END -------------------------------------- */
}
