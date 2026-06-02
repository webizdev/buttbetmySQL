<?php
require_once 'config.php';

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'get_orders':
        try {
            $stmt = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC");
            $orders = $stmt->fetchAll();
            
            // Format arrays for frontend (sizesAtasan, sizesStelan, mockupImages)
            $formattedOrders = array_map(function($order) {
                return [
                    'id' => $order['id'],
                    'customerName' => $order['customer_name'],
                    'phone' => $order['phone'],
                    'designName' => $order['design_name'],
                    'collarType' => $order['collar_type'],
                    'fabricType' => $order['fabric_type'],
                    'jerseyCategory' => $order['jersey_category'],
                    'designNotes' => $order['design_notes'],
                    'deadline' => $order['deadline'],
                    'priority' => $order['priority'],
                    'status' => $order['status'],
                    'sizesAtasan' => [
                        'xs' => (int)$order['size_atasan_xs'],
                        's' => (int)$order['size_atasan_s'],
                        'm' => (int)$order['size_atasan_m'],
                        'l' => (int)$order['size_atasan_l'],
                        'xl' => (int)$order['size_atasan_xl'],
                        'xxl' => (int)$order['size_atasan_xxl']
                    ],
                    'sizesStelan' => [
                        'xs' => (int)$order['size_stelan_xs'],
                        's' => (int)$order['size_stelan_s'],
                        'm' => (int)$order['size_stelan_m'],
                        'l' => (int)$order['size_stelan_l'],
                        'xl' => (int)$order['size_stelan_xl'],
                        'xxl' => (int)$order['size_stelan_xxl']
                    ],
                    'totalQty' => (int)$order['total_qty'],
                    'mockupImages' => json_decode($order['mockup_images'], true) ?: [],
                    'createdAt' => $order['created_at']
                ];
            }, $orders);

            echo json_encode(['success' => true, 'data' => $formattedOrders]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;

    case 'save_order':
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) {
            echo json_encode(['success' => false, 'message' => 'Invalid JSON']);
            exit;
        }

        try {
            $stmt = $pdo->prepare("REPLACE INTO orders (
                id, customer_name, phone, design_name, collar_type, fabric_type, jersey_category, design_notes,
                deadline, priority, status, 
                size_atasan_xs, size_atasan_s, size_atasan_m, size_atasan_l, size_atasan_xl, size_atasan_xxl,
                size_stelan_xs, size_stelan_s, size_stelan_m, size_stelan_l, size_stelan_xl, size_stelan_xxl,
                total_qty, mockup_images, created_at
            ) VALUES (
                ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
                ?, ?, ?, ?, ?, ?,
                ?, ?, ?, ?, ?, ?,
                ?, ?, ?
            )");

            $sizesAtasan = $data['sizesAtasan'] ?? [];
            $sizesStelan = $data['sizesStelan'] ?? [];
            $mockups = json_encode($data['mockupImages'] ?? []);

            $stmt->execute([
                $data['id'],
                $data['customerName'],
                $data['phone'],
                $data['designName'],
                $data['collarType'],
                $data['fabricType'],
                $data['jerseyCategory'],
                $data['designNotes'],
                $data['deadline'],
                $data['priority'],
                $data['status'],
                $sizesAtasan['xs'] ?? 0, $sizesAtasan['s'] ?? 0, $sizesAtasan['m'] ?? 0, $sizesAtasan['l'] ?? 0, $sizesAtasan['xl'] ?? 0, $sizesAtasan['xxl'] ?? 0,
                $sizesStelan['xs'] ?? 0, $sizesStelan['s'] ?? 0, $sizesStelan['m'] ?? 0, $sizesStelan['l'] ?? 0, $sizesStelan['xl'] ?? 0, $sizesStelan['xxl'] ?? 0,
                $data['totalQty'],
                $mockups,
                $data['createdAt'] ?? date('Y-m-d')
            ]);
            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;

    case 'update_status':
        $data = json_decode(file_get_contents('php://input'), true);
        if (!isset($data['id']) || !isset($data['status'])) {
            echo json_encode(['success' => false, 'message' => 'Missing id or status']);
            exit;
        }

        try {
            $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
            $stmt->execute([$data['status'], $data['id']]);
            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;

    case 'delete_order':
        $data = json_decode(file_get_contents('php://input'), true);
        if (!isset($data['id'])) {
            echo json_encode(['success' => false, 'message' => 'Missing id']);
            exit;
        }

        try {
            $stmt = $pdo->prepare("DELETE FROM orders WHERE id = ?");
            $stmt->execute([$data['id']]);
            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Unknown action']);
        break;
}
?>
