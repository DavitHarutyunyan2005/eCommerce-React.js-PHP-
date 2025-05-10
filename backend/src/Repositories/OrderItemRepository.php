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

    public function insertOrderItem(int $orderId, array $orderItem): array
    {
        try {
            $query = $this->conn->createQueryBuilder()
                ->insert('order_items')
                ->values([
                    'product_id' => ':product_id',
                    'order_id' => ':order_id',
                    'price_id' => ':price_id',
                    'quantity' => ':quantity'
                ])
                ->setParameter('product_id', $orderItem['productId'])
                ->setParameter('order_id', $orderId)
                ->setParameter('price_id', $orderItem['priceId'])
                ->setParameter('quantity', $orderItem['quantity']);

            $query->executeStatement();

            $orderItemId = (int) $this->conn->lastInsertId();

            if (isset($orderItem['attributeItemIds']) && is_array($orderItem['attributeItemIds']) && count($orderItem['attributeItemIds']) > 0) {
                foreach ($orderItem['attributeItemIds'] as $attributeItemId) {
                    $this->conn->insert('order_attributes', [
                        'order_item_id' => $orderItemId,
                        'attribute_item_id' => $attributeItemId,
                    ]);
                }
            }
            
            return [
                'productId' => $orderItem['productId'],
                'priceId' => $orderItem['priceId'],
                'attributeItemIds' => $orderItem['attributeItemIds'] ?? [],
                'quantity' => $orderItem['quantity'],
            ];
        } catch (Exception $e) {
            $this->logger->error('Error inserting order item: ' . $e->getMessage());
            throw $e;
        }
    }
}
