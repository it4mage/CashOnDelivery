<?php
/**
 * IDEALIAGroup srl
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@idealiagroup.com so we can send you a copy immediately.
 *
 * @copyright  Copyright (c) 2014 IDEALIAGroup srl (http://www.idealiagroup.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace MSP\CashOnDelivery\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Quote\Model\QuoteRepository;
use Magento\Sales\Api\Data\OrderInterface;

class ChangeOrderStatus implements ObserverInterface
{
    const CODE = 'msp_cashondelivery';
    const XML_PATH_ORDER_STATUS = 'payment/msp_cashondelivery/order_status';

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $_scopeConfig = $objectManager->get('Magento\Framework\App\Config\ScopeConfigInterface');
        /** @var OrderInterface $order */
        $order = $observer->getEvent()->getOrder();

        if ($order->getPayment()->getMethod() == static::CODE) {
            $currentStatus = $order->getStatus();
            $customStatus = $_scopeConfig->getValue(static::XML_PATH_ORDER_STATUS);
            if ($currentStatus != $customStatus) {
                $order->setStatus($customStatus);
                $order->save();
            }
        }
    }
}
