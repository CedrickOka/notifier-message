<?php
namespace Oka\Notifier\Message;

/**
 * @author Cedrick Oka Baidai <okacedrick@gmail.com>
 */
class Address
{
    protected $name;
    protected $value;
    
    public function __construct(string $value, string $name = null)
    {
        $this->value = $value;
        $this->name = $name;
    }
    
    public function getValue(): string
    {
        return $this->value;
    }
    
    public function getName():? string
    {
        return $this->name;
    }
    
    public function toArray(): array
    {
        $address = ['value' => $this->value];
        
        if (null !== $this->name) {
            $address['name'] = $this->name;
        }
        
        return $address;
    }
    
    public function __toString()
    {
        if (null === $this->name) {
            return $this->value;
        }
        
        return sprintf('%s <%s>', $this->name, $this->value);
    }
    
    /**
     * @param array|string $address
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
        
        if ($diff = array_diff(array_keys($address), ['value', 'name'])) {
            throw new \InvalidArgumentException(sprintf('The following keys are not supported "%s".', implode(', ', $diff)));
        }
        
        return new self($address['value'], $address['name'] ?? null);
    }
}
