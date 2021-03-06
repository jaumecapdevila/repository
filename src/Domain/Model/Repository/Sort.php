<?php

/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 6/15/15
 * Time: 11:08 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NilPortugues\Foundation\Domain\Model\Repository;

use InvalidArgumentException;
use NilPortugues\Foundation\Domain\Model\Repository\Contracts\Order as OrderInterface;
use NilPortugues\Foundation\Domain\Model\Repository\Contracts\Sort as SortInterface;
use NilPortugues\Foundation\Domain\Model\Repository\Traits\Nullable;

class Sort implements SortInterface
{
    use Nullable;

    /**
     * @var array
     */
    protected $properties;

    /**
     * @var Order
     */
    protected $order;

    /**
     * Creates a new Sort instance using the given Order.
     *
     * @param array          $properties
     * @param OrderInterface $order
     */
    public function __construct(array $properties = [], OrderInterface $order = null)
    {
        $this->setOrder($order);
        $this->setProperties($properties);
    }

    /**
     * @param OrderInterface|null $order
     */
    protected function setOrder(OrderInterface $order = null)
    {
        if (null === $order) {
            $order = new Order(OrderInterface::ASCENDING);
        }
        $this->order = $order;
    }

    /**
     * Returns a new Sort consisting of the orders of the current Sort combined with the given ones.
     *
     * @param SortInterface $sort
     *
     * @return SortInterface
     */
    public function andSort(SortInterface $sort): SortInterface
    {
        $this->properties = array_merge($this->orders(), $sort->orders());

        return $this;
    }

    /**
     * @return array
     */
    public function orders(): array
    {
        return (array) $this->properties;
    }

    /**
     * @param array $properties
     */
    protected function setProperties(array $properties)
    {
        if (false === empty($properties)) {
            $properties = array_fill_keys(array_values($properties), clone $this->order);
        }
        $this->properties = $properties;
    }

    /**
     * @param SortInterface $sort
     *
     * @return bool
     */
    public function equals(SortInterface $sort): bool
    {
        return $sort->orders() == $this->orders();
    }

    /**
     * Returns the order registered for the given property.
     *
     * @param string $propertyName
     *
     * @return OrderInterface
     */
    public function orderFor(string $propertyName): OrderInterface
    {
        $this->hasProperty($propertyName);

        return $this->properties[$propertyName];
    }

    /**
     * @param string $propertyName
     *
     * @throws \InvalidArgumentException
     */
    protected function hasProperty(string $propertyName)
    {
        if (true === empty($this->properties[$propertyName])) {
            throw new InvalidArgumentException('Provided property could not be found.');
        }
    }

    /**
     * @param string         $propertyName
     * @param OrderInterface $order
     */
    public function setOrderFor(string $propertyName, OrderInterface $order)
    {
        $this->properties[$propertyName] = $order;
    }

    /**
     * @param string $propertyName
     *
     * @return OrderInterface
     *
     * @throws \InvalidArgumentException
     */
    public function property(string $propertyName): OrderInterface
    {
        $this->hasProperty($propertyName);

        return $this->properties[$propertyName];
    }
}
