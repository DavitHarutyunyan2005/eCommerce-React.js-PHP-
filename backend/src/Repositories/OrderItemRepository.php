<?php

namespace App\Repositories;

use Doctrine\DBAL\Connection;
use Psr\Log\LoggerInterface;
use Exception;

class OrderItemRepository
{
    private Connection $conn;
    private LoggerInterface $logger;

    public function __construct(Connection $conn, LoggerInterface $logger)
    {
        $this->conn = $conn;
        $this->logger = $logger;
    }

    public function insertOrderItem(array $orderItem): bool
    {
        try {
            $query = $this->conn->createQueryBuilder()
                ->insert('order_items')
                ->values([
                    'product_id' => ':product_id',
                    'order_id' => ':order_id',
                    'price_id' => ':price_id',
                    'attribute_item_id' => ':attribute_item_id',
                    'quantity' => ':quantity'
                ])
                ->setParameter('product_id', $orderItem['product_id'])
                ->setParameter('order_id', $orderItem['order_id'])
                ->setParameter('price_id', $orderItem['price_id'])
                ->setParameter('attribute_item_id', $orderItem['attribute_item_id'])
                ->setParameter('quantity', $orderItem['quantity']);

            return (bool) $query->executeStatement();
        } catch (Exception $e) {
            $this->logger->error('Error inserting order item: ' . $e->getMessage());
            return false;
        }
    }
}
