<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 6/15/15
 * Time: 11:08 PM
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhpDdd\Foundation\Domain\Repository;

use InvalidArgumentException;
use PhpDdd\Foundation\Domain\Repository\Collection\Collection;

final class Sort
{
    /**
     * @var Collection
     */
    private $properties;

    /**
     * @var Order
     */
    private $order;

    /**
     * Creates a new Sort instance using the given Order.
     *
     * @param array $properties
     * @param Order $order
     */
    public function __construct(array $properties = [], Order $order = null)
    {
        $this->setOrder($order);
        $this->setProperties($properties);
    }

    /**
     * @param Order|null $order
     *
     * @return Order
     */
    private function setOrder(Order $order = null)
    {
        if (null === $order) {
            $order = new Order(Order::ASCENDING);
        }
        $this->order = $order;
    }

    /**
     * Returns a new Sort consisting of the orders of the current Sort combined with the given ones.
     *
     * @param Sort $sort
     *
     * @return Sort
     */
    public function andSort(self $sort)
    {
        $this->properties = new Collection(
            array_merge(
                $this->getProperties()->toArray(),
                $sort->getProperties()->toArray()
            )
        );

        return $this;
    }

    /**
     * @return Collection
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @param Sort $sort
     *
     * @return bool
     */
    public function equals(self $sort)
    {
        return $sort->getProperties() == $this->getProperties();
    }

    /**
     * Returns the order registered for the given property.
     *
     * @param string $propertyName
     *
     * @return Order
     */
    public function getOrderFor($propertyName)
    {
        $this->hasProperty($propertyName);

        return $this->properties[$propertyName];
    }

    /**
     * @param       $propertyName
     * @param Order $order
     */
    public function setOrderFor($propertyName, Order $order)
    {
        $this->properties[$propertyName] = $order;
    }

    /**
     * @param string $propertyName
     *
     * @return Order
     * @throws \InvalidArgumentException
     */
    public function getProperty($propertyName)
    {
        $this->hasProperty($propertyName);

        return $this->properties[$propertyName];
    }

    /**
     * @param $propertyName
     *
     * @throws \InvalidArgumentException
     */
    private function hasProperty($propertyName)
    {
        if (true === empty($this->properties[$propertyName])) {
            throw new InvalidArgumentException('Provided property could not be found.');
        }
    }

    /**
     * @param array $properties
     */
    private function setProperties(array $properties)
    {
        if (false === empty($properties)) {
            $properties = array_fill_keys(array_values($properties), clone $this->order);
        }
        $this->properties = new Collection($properties);
    }
}