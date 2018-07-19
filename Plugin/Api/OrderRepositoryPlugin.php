<?php


namespace MSP\CashOnDelivery\Plugin\Api;


use Magento\Sales\Api\Data\OrderExtensionFactory;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderSearchResultInterface;
use Magento\Sales\Api\OrderRepositoryInterface;

class OrderRepositoryPlugin
{
    const MSP_COD_AMOUNT_FIELD_NAME = 'msp_cod_amount';
    const BASE_MSP_COD_AMOUNT_FIELD_NAME = 'base_msp_cod_amount';
    const MSP_COD_TAX_AMOUNT_FIELD_NAME = 'msp_cod_tax_amount';
    const BASE_MSP_COD_TAX_AMOUNT_FIELD_NAME = 'base_msp_cod_tax_amount';
    /**
     * @var OrderExtensionFactory
     */
    private $extensionFactory;

    public function __construct(OrderExtensionFactory $extensionFactory)
    {
        $this->extensionFactory = $extensionFactory;
    }

    /**
     * @param OrderRepositoryInterface $subject
     * @param OrderInterface $order
     * @return OrderInterface
     */
    public function afterGet(OrderRepositoryInterface $subject, OrderInterface $order)
    {

        $this->setMspExtensionAttributesOnOrder($order);

        return $order;
    }

    /**
     * @param OrderRepositoryInterface $subject
     * @param OrderSearchResultInterface $searchResult
     * @return OrderSearchResultInterface
     */
    public function afterGetList(OrderRepositoryInterface $subject, OrderSearchResultInterface $searchResult)
    {
        $orders = $searchResult->getItems();

        foreach ($orders as &$order) {
            $this->setMspExtensionAttributesOnOrder($order);
        }

        return $searchResult;
    }

    /**
     * @param OrderInterface $order
     * @param $mspCodAmount
     */
    protected function setMspExtensionAttributesOnOrder(OrderInterface $order)
    {
        $mspCodAmount = $order->getData(self::MSP_COD_AMOUNT_FIELD_NAME);
        $baseMspCodAmount = $order->getData(self::BASE_MSP_COD_AMOUNT_FIELD_NAME);
        $mspCodTaxAmount = $order->getData(self::MSP_COD_TAX_AMOUNT_FIELD_NAME);
        $baseMspCodTaxAmount = $order->getData(self::BASE_MSP_COD_TAX_AMOUNT_FIELD_NAME);
        $extensionAttributes = $order->getExtensionAttributes();
        $extensionAttributes = $extensionAttributes ? $extensionAttributes : $this->extensionFactory->create();
        $extensionAttributes->setData(self::MSP_COD_AMOUNT_FIELD_NAME, $mspCodAmount);
        $extensionAttributes->setData(self::BASE_MSP_COD_AMOUNT_FIELD_NAME, $baseMspCodAmount);
        $extensionAttributes->setData(self::MSP_COD_TAX_AMOUNT_FIELD_NAME, $mspCodTaxAmount);
        $extensionAttributes->setData(self::BASE_MSP_COD_TAX_AMOUNT_FIELD_NAME, $baseMspCodTaxAmount);
        $order->setExtensionAttributes($extensionAttributes);
    }
}