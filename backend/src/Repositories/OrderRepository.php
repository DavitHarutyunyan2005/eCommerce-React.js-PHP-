<?php

namespace App\Repositories;

use Doctrine\DBAL\Connection;
use Psr\Log\LoggerInterface;
use Exception;
use DateTimeImmutable;
use DateTimeZone;

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

    public function insertOrder(array $orderItems): array
    {
        $orderRef = 'ORD-' . time() . '-' . strtoupper(bin2hex(random_bytes(4)));
        $tz = new DateTimeZone('Asia/Yerevan');
        $now = (new DateTimeImmutable('now', $tz))->format('Y-m-d H:i:s');
        $this->conn->beginTransaction();

        try {
            // Inserting the order
            $this->conn->insert('orders', [
                'order_ref' => $orderRef,
                'created_at' => $now,
            ]);

            // Getting the inserted order ID
            $orderId = (int) $this->conn->lastInsertId();

            // Inserting each order item
            foreach ($orderItems as $orderItem) {
                try {
                    $this->orderItemRepository->insertOrderItem($orderId, $orderItem);
                } catch (Exception $e) {
                    $this->logger->error('Failed to insert order item', ['item' => $orderItem, 'error' => $e->getMessage()]);
                    throw $e;
                }
            }

            $this->conn->commit();

            return [
                'success' => true,
                'orderRef' => $orderRef,
                'message' => 'Order inserted successfully.',
                'products' => $orderItems,
            ];
        } catch (Exception $e) {
            $this->conn->rollBack();
            $this->logger->error('Order insert failed: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
