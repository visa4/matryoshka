<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model;

/**
 * ModelAwareInterface
 */
interface ModelAwareInterface
{
    /**
     * Set Model
     * @param ModelInterface $model
     * @return $this
     */
    public function setModel(ModelInterface $model);

    /**
     * Get Model
     * @return ModelInterface
     */
    public function getModel();
}
