<?php
/**
 * GiaPhuGroup Co., Ltd.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the GiaPhuGroup.com license that is
 * available through the world-wide-web at this URL:
 * https://www.giaphugroup.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    PHPCuong
 * @package     PHPCuong_OfflinePayments
 * @copyright   Copyright (c) 2020-2021 GiaPhuGroup Co., Ltd. All rights reserved. (http://www.giaphugroup.com/)
 * @license     https://www.giaphugroup.com/LICENSE.txt
 */

namespace PHPCuong\OfflinePayments\Observer;

use Magento\Quote\Api\Data\PaymentInterface;

class SalesQuotePaymentBeforeSavedObserver implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if (empty($observer->getEvent()->getPayment())) {
            return $this;
        }

        $payment = $observer->getEvent()->getPayment();
        $additionalData = $payment->getData(PaymentInterface::KEY_ADDITIONAL_DATA);

        if (isset($additionalData['assistant_id'])) {
            $payment->setAssistantId($additionalData['assistant_id']);
        }

        return $this;
    }
}
