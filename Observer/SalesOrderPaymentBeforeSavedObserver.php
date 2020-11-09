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

class SalesOrderPaymentBeforeSavedObserver implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \Magento\Webapi\Controller\Rest\InputParamsResolver
     */
    protected $inputParamsResolver;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $requestInterface;

    /**
     * @param \Magento\Webapi\Controller\Rest\InputParamsResolver $inputParamsResolver
     * @param \Magento\Framework\App\RequestInterface $requestInterface
     */
    public function __construct(
        \Magento\Webapi\Controller\Rest\InputParamsResolver $inputParamsResolver,
        \Magento\Framework\App\RequestInterface $requestInterface
    ) {
        $this->inputParamsResolver = $inputParamsResolver;
        $this->requestInterface = $requestInterface;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $payment = $observer->getEvent()->getPayment();
        if (empty($payment)) {
            return $this;
        }

        if ($payment->getMethod() != 'pdqpayment') {
            return $this;
        }

        if ($payment->getAssistantId()) {
            return $this;
        }

        // This is used while creating an order from the backend by an administrator.
        if ($this->requestInterface->getFullActionName() == 'sales_order_create_save') {
            $paymentFromPosting = $this->requestInterface->getParam('payment');
            if ($paymentFromPosting && isset($paymentFromPosting['assistant_id'])) {
                $payment->setAssistantId($paymentFromPosting['assistant_id']);
            }
            return $this;
        }

        // This is used while creating an order from the frontend by a customer.
        $inputParams = $this->inputParamsResolver->resolve();

        foreach ($inputParams as $inputParam) {
            if ($inputParam instanceof \Magento\Quote\Model\Quote\Payment) {
                $additionalData = $inputParam->getData('additional_data');
                if (isset($additionalData['assistant_id'])) {
                    $payment->setAssistantId($additionalData['assistant_id']);
                    break;
                }
            }
        }

        return $this;
    }
}
