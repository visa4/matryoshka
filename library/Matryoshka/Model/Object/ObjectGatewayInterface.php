<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/ripaclub/matryoshka
 * @copyright   Copyright (c) 2014, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace Matryoshka\Model\Object;

/**
 * Interface ObjectGatewayInterface
 */
interface ObjectGatewayInterface
{

    /**
     * Save
     */
    public function save();

    /**
     * Delete
     */
    public function delete();
}