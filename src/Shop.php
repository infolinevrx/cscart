<?php

declare(strict_types=1);

namespace Shop;

final class Shop
{
    private const MIN_QUALITY = 0; // Минимальное значение ценности товара
    private const MAX_QUALITY = 50; // Максимальное значение ценности товара
    const MJOLNIR = 'Mjolnir';
    const BLUE_CHEESE = 'Blue cheese';
    const CONCRETE_TICKETS = 'Concert tickets';
    const MAGIC_CAKE = 'Magic cake';

    /**
     * @var Item[]
     */
    private $items;


    public function __construct(array $items)
    {
        $this->items = $items;
    }


    public function updateQuality(): void
    {
        foreach ($this->items as $item) {

            // Для Молота
            if ($item->name == self::MJOLNIR) continue;

            // Уменьшаем срок хранения
            $this->downSellIn($item);

            // Получаем инкремент для quality
            $inc = $this->getIncQuality($item);

            // Изменяем quality
            $this->setQuality($item, $inc);
        }
    }


    /**
     * Получаем инкримент для quality
     * @param Item $item
     * @return int
     */
    private function getIncQuality(Item $item) : int
    {
        switch ($item->name) {

            # Для Blue cheese
            case self::BLUE_CHEESE:
                return ($item->sell_in < 0 ? 2 : 1);

            # Для Concert tickets
            case self::CONCRETE_TICKETS:
                if ($item->sell_in >= 0) {
                    $rate = 1;
                    if ($item->sell_in < 10) $rate = 2;
                    if ($item->sell_in < 5) $rate = 3;
                }
                else
                    $rate = (-1) * $item->quality;

                return $rate;

            # для MAGIC_CAKE
            case self::MAGIC_CAKE:
                return (-1) * ($item->sell_in < 0 ? 2 : 1) * 2;

            # Остальные
            default:
                return (-1) * ($item->sell_in < 0 ? 2 : 1);
        }
    }


    /**
     * Общий метод для изменения quality
     * @param Item $item
     * @param int $inc
     */
    private function setQuality(Item &$item, int $inc) : void
    {
        if ($inc > 0)
            $this->upQuality($item, abs($inc));
        else
            $this->downQuality($item, abs($inc));
    }


    /**
     * Увеличиваем ценнонсть на inc
     * @param Item $item
     * @param int $inc
     */
    private function upQuality(Item &$item, int $inc = 1) : void
    {
        $item->quality =
            ($item->quality + $inc) < self::MAX_QUALITY
                ? $item->quality + $inc
                : self::MAX_QUALITY;
    }


    /**
     * Уменьшаем ценнонсть на inc
     * @param Item $item
     * @param int $inc
     */
    private function downQuality(Item &$item, int $inc = 1) : void
    {
        $item->quality =
            ($item->quality - $inc) > self::MIN_QUALITY
                ? $item->quality - $inc
                : self::MIN_QUALITY;
    }


    /**
     * Уменьшаем срок хранения
     * @param Item $item
     */
    private function downSellIn(Item &$item) : void
    {
        $item->sell_in--;
    }
}
