<?php

declare(strict_types=1);

namespace MSP\CashOnDelivery\Model\Config\Source\Fee;

final class Tax implements \Magento\Framework\Option\ArrayInterface
{
    const USE_SHIPPING_PRICE = 0;
    const INCLUDING_TAX = 1;
    const EXCLUDING_TAX = 2;

    public function toOptionArray()
    {
        return [
            ['value' => self::USE_SHIPPING_PRICE, 'label' => __('Use shipping price setting')],
            ['value' => self::INCLUDING_TAX, 'label' => __('Including Tax')],
            ['value' => self::EXCLUDING_TAX, 'label' => __('Excluding Tax')],
        ];
    }
}
