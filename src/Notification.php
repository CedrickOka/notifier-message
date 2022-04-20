<?php
namespace Oka\Notifier\Message;

/**
 * @author Cedrick Oka Baidai <okacedrick@gmail.com>
 */
class Notification
{
    protected $channels;
    protected $sender;
    protected $receiver;
    protected $message;
    protected $title;
    protected $attributes;
    
    public function __construct(array $channels, Address $sender, Address $receiver, string $message, string $title = null, array $attributes = [])
    {
        $this->channels = $channels;
        $this->sender = $sender;
        $this->receiver = $receiver;
        $this->message = $message;
        $this->title = $title;
        $this->attributes = $attributes;
    }
    
    public function getChannels(): array
    {
        return $this->channels;
    }
    
    public function hasChannel(string $channel): bool
    {
        return in_array($channel, $this->channels, true);
    }
    
    public function addChannel(string $channel): self
    {
        if (false === in_array($channel, $this->channels, true)) {
            $this->channels[] = $channel;
        }
        return $this;
    }
    
    public function setChannels(array $channels): self
    {
        $this->channels = [];
        foreach ($this->channels as $channel) {
            $this->addChannel($channel);
        }
        return $this;
    }
    
    public function removeChannel(string $channel): self
    {
        if (false !== ($key = array_search($channel, $this->channels, true))) {
            unset($this->channels[$key]);
            $this->channels = array_values($this->channels);
        }
        return $this;
    }
    
    public function getSender(): Address
    {
        return $this->sender;
    }
    
    public function setSender(Address $sender): self
    {
        $this->sender = $sender;
        return $this;
    }
    
    public function getReceiver(): Address
    {
        return $this->receiver;
    }
    
    public function setReceiver(Address $receiver): self
    {
        $this->receiver = $receiver;
        return $this;
    }
    
    public function getMessage(): string
    {
        return $this->message;
    }
    
    public function setMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }
    
    public function getTitle():? string
    {
        return $this->title;
    }
    
    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }
    
    public function getAttributes(): array
    {
        return $this->attributes;
    }
    
    public function setAttributes(array $attributes): self
    {
        $this->attributes = $attributes;
        return $this;
    }
    
    public function addAttribute(string $name, $value): self
    {
        $this->attributes[$name] = $value;
        return $this;
    }
    
    public function toArray(): array
    {
        $notification = [
            'channels' => $this->channels,
            'sender' => $this->sender->toArray(),
            'receiver' => $this->receiver->toArray(),
            'message' => $this->message
        ];
        
        if (null !== $this->title) {
            $notification['title'] = $this->title;
        }
        
        if (false === empty($this->attributes)) {
            $notification['attributes'] = $this->attributes;
        }
        
        return $notification;
    }
    
    public function fromArray(array $notification): self
    {
        $self = new self(
            $notification['channels'],
            Address::create($notification['sender']),
            Address::create($notification['receiver']),
            $notification['message'],
            $notification['title'] ?? null,
            $notification['attributes'] ?? [],
        );
        
        return $self;
    }
}
