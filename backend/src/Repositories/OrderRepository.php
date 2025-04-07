<?php

namespace App\Repositories;

use Doctrine\DBAL\Connection;
use Psr\Log\LoggerInterface;
use Exception;

class OrderRepository
{
    private Connection $conn;
    private LoggerInterface $logger;
    private OrderItemRepository $orderItemRepository;

    public function __construct(
        Connection $conn,
        LoggerInterface $logger,
        OrderItemRepository $orderItemRepository
    ) {
        $this->conn = $conn;
        $this->logger = $logger;
        $this->orderItemRepository = $orderItemRepository;
    }

    public function insertOrder(array $orderItems)
    {
        $orderRef = 'ORD-' . time() . '-' . strtoupper(bin2hex(random_bytes(4)));

        $this->conn->beginTransaction();

        try {
            // Insert the order
            $query = $this->conn->createQueryBuilder()
                ->insert('orders')
                ->values([
                    'order_ref' => ':order_ref',
                    'created_at' => ':created_at',
                ])
                ->setParameter('order_ref', $orderRef)
                ->setParameter('created_at', (new \DateTimeImmutable())->format('Y-m-d H:i:s'));

            $query->executeStatement();

            // Insert each order item
            foreach ($orderItems as $orderItem) {
                $this->orderItemRepository->insertOrderItem($orderItem);
            }

            $this->conn->commit();

            return [
                'success' => true,
                'order_ref' => $orderRef,
                'products' => $orderItems,
                'message' => 'Order inserted successfully.'
            ];

        } catch (Exception $e) {
            $this->conn->rollBack();

            return $this->logger->error('Order insert failed: ' . $e->getMessage());
        }
    }
}
