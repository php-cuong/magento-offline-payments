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

namespace PHPCuong\OfflinePayments\Setup;

class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{
    /**
     * Install tables
     *
     * @param \Magento\Framework\Setup\SchemaSetupInterface $setup
     * @param \Magento\Framework\Setup\ModuleContextInterface $context
     * @return void
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(
        \Magento\Framework\Setup\SchemaSetupInterface $setup, 
        \Magento\Framework\Setup\ModuleContextInterface $context
    ) {
        $installer = $setup;
        $installer->startSetup();
        if ($installer->tableExists('quote_payment')) {
            $tableName = $setup->getTable('quote_payment');
            $connection = $setup->getConnection();
            if (!$connection->tableColumnExists($tableName, 'assistant_id')) {
                $connection->addColumn(
                    $tableName,
                    'assistant_id',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'nullable' => true,
                        'default' => null,
                        'comment' => 'Added by PHPCuong for the PDQ Payment',
                        'after' => 'po_number',
                        'length' => 255
                    ]
                );
            }
        }

        if ($installer->tableExists('sales_order_payment')) {
            $tableName = $setup->getTable('sales_order_payment');
            $connection = $setup->getConnection();
            if (!$connection->tableColumnExists($tableName, 'assistant_id')) {
                $connection->addColumn(
                    $tableName,
                    'assistant_id',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'nullable' => true,
                        'default' => null,
                        'comment' => 'Added by PHPCuong for the PDQ Payment',
                        'after' => 'po_number',
                        'length' => 255
                    ]
                );
            }
        }
        $installer->endSetup();
    }
}
