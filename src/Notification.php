<?php

namespace Oka\Notifier\Message;

use Oka\Notifier\Message\Enum\NotificationPriority;

/**
 * @author Cedrick Oka Baidai <okacedrick@gmail.com>
 */
class Notification
{
    public function __construct(
        protected array|string $channels,
        protected Address $sender,
        protected Address $receiver,
        protected string $message,
        protected ?string $title = null,
        protected array $attributes = [],
        protected NotificationPriority $priority = NotificationPriority::Normal,
    ) {
        $this->channels = is_array($channels) ? $channels : [$channels];
    }

    public function hasChannel(string $channel): bool
    {
        return in_array($channel, $this->channels, true);
    }

    public function getChannels(): array
    {
        return $this->channels;
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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function hasAttribute(string $name): bool
    {
        return isset($this->attributes[$name]);
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

    public function addAttribute(string $name, mixed $value): self
    {
        $this->attributes[$name] = $value;

        return $this;
    }

    public function getPriority(): NotificationPriority
    {
        return $this->priority;
    }

    public function setPriority(NotificationPriority $priority): self
    {
        $this->priority = $priority;

        return $this;
    }

    public function toArray(): array
    {
        $notification = [
            'channels' => $this->channels,
            'sender' => $this->sender->toArray(),
            'receiver' => $this->receiver->toArray(),
            'message' => $this->message,
            'priority' => $this->priority->value,
        ];

        if (null !== $this->title) {
            $notification['title'] = $this->title;
        }
        if (false === empty($this->attributes)) {
            $notification['attributes'] = $this->attributes;
        }

        return $notification;
    }

    public static function create(array $notification): static
    {
        $self = new static(
            $notification['channels'],
            Address::create($notification['sender']),
            Address::create($notification['receiver']),
            $notification['message'],
            $notification['title'] ?? null,
            $notification['attributes'] ?? [],
            isset($notification['priority']) ? NotificationPriority::from($notification['type']) : null,
        );

        return $self;
    }

    public function __serialize(): array
    {
        return $this->toArray();
    }

    public function __unserialize(array $data): void
    {
        $this->channels = $data['channels'];
        $this->sender = Address::create($data['sender']);
        $this->receiver = Address::create($data['receiver']);
        $this->message = $data['message'];

        if (true === isset($data['title'])) {
            $this->title = $data['title'];
        }
        if (true === isset($data['attributes'])) {
            $this->attributes = $data['attributes'];
        }
    }
}
