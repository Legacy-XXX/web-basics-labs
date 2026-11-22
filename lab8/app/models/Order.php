<?php
declare(strict_types=1);

// Сутність «Замовлення»
class Order
{
    public const CATEGORIES = [
        'mouse' => ['Миші', 'assets/img/mouse.svg'],
        'keyboard' => ['Клавіатури', 'assets/img/keyboard.svg'],
        'headphones' => ['Навушники', 'assets/img/headphones.svg'],
        'monitor' => ['Монітори', 'assets/img/monitor.svg'],
        'mousepad' => ['Килимки', 'assets/img/mousepad.svg'],
        'webcam' => ['Вебкамери', 'assets/img/webcam.svg'],
    ];

    public function __construct(
        private ?int $id,
        private string $customerName,
        private string $product,
        private string $category,
        private int $quantity,
        private float $price,
        private string $email,
        private ?string $phone,
        private ?string $comment,
        private ?string $createdAt = null,
    ) {
    }

    public function getId(): ?int { return $this->id; }
    public function getCustomerName(): string { return $this->customerName; }
    public function getProduct(): string { return $this->product; }
    public function getCategory(): string { return $this->category; }
    public function getQuantity(): int { return $this->quantity; }
    public function getPrice(): float { return $this->price; }
    public function getEmail(): string { return $this->email; }
    public function getPhone(): ?string { return $this->phone; }
    public function getComment(): ?string { return $this->comment; }
    public function getCreatedAt(): ?string { return $this->createdAt; }

    public function getTotal(): float
    {
        return $this->price * $this->quantity;
    }

    public function getCategoryLabel(): string
    {
        return self::CATEGORIES[$this->category][0] ?? $this->category;
    }

    public function getCategoryImage(): string
    {
        return self::CATEGORIES[$this->category][1] ?? 'assets/img/logo.svg';
    }

    // Дані для підстановки у поля форми редагування
    public function toFormData(): array
    {
        return [
            'customer_name' => $this->customerName,
            'product' => $this->product,
            'category' => $this->category,
            'quantity' => (string) $this->quantity,
            'price' => (string) $this->price,
            'email' => $this->email,
            'phone' => (string) $this->phone,
            'comment' => (string) $this->comment,
        ];
    }
}
