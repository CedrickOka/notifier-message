<?php

namespace Oka\Notifier\Message;

use Oka\Notifier\Message\Enum\AddressType;

/**
 * @author Cedrick Oka Baidai <okacedrick@gmail.com>
 */
class Address
{
    public function __construct(
        protected string $value,
        protected ?string $name = null,
        protected ?AddressType $type = null,
    ) {
        if (null === $type) {
            $this->type = AddressType::Default;
        }
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getType(): AddressType
    {
        return $this->type;
    }

    public function toArray(): array
    {
        $address = [
            'type' => $this->type->value,
            'value' => $this->value,
        ];

        if (null !== $this->name) {
            $address['name'] = $this->name;
        }

        return $address;
    }

    public function __toString(): string
    {
        if (null === $this->name) {
            return $this->value;
        }

        return sprintf('%s <%s>', $this->name, $this->value);
    }

    /**
     * @param array|string $address
     *
     * @throws \InvalidArgumentException
     */
    public static function create($address): self
    {
        if (false === is_string($address) && false === is_array($address)) {
            throw new \InvalidArgumentException(sprintf('The "$address" arguments must be of type "string" or "array", "%s" given.', gettype($address)));
        }
        if (true === is_string($address)) {
            $address = ['value' => $address];
        }
        if ($diff = array_diff(array_keys($address), ['value', 'name', 'type'])) {
            throw new \InvalidArgumentException(sprintf('The following keys are not supported "%s".', implode(', ', $diff)));
        }

        return new self($address['value'], $address['name'] ?? null, isset($address['type']) ? AddressType::from($address['type']) : null);
    }
}
