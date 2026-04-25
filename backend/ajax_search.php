<?php
require '../db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    exit('Unauthorized');
}

$type = $_GET['type'] ?? '';
$search = $_GET['search'] ?? '';
$sort = $_GET['sort'] ?? 'id';
$order = $_GET['order'] ?? 'DESC';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 5; 
$offset = ($page - 1) * $limit;

// Sécuriser le tri
$allowed_sorts = ['id', 'full_name', 'email', 'name', 'amount', 'donation_date', 'total_capacity'];
if (!in_array($sort, $allowed_sorts)) $sort = 'id';
if ($order !== 'ASC') $order = 'DESC';

switch ($type) {
    case 'volunteers':
        $query = "SELECT v.*, u.full_name, u.email, u.profile_photo 
                  FROM volunteers v 
                  JOIN users u ON v.user_id = u.id 
                  WHERE u.full_name LIKE ? OR u.email LIKE ? OR v.skills LIKE ?
                  ORDER BY $sort $order LIMIT $limit OFFSET $offset";
        $stmt = $pdo->prepare($query);
        $stmt->execute(["%$search%", "%$search%", "%$search%"]);
        $results = $stmt->fetchAll();
        
        foreach ($results as $v) {
            echo "<tr>
                    <td>" . ($v['profile_photo'] ? "<img src='../{$v['profile_photo']}' style='width: 40px; height: 40px; border-radius: 50%; object-fit: cover;'>" : "<div class='avatar'>".substr($v['full_name'], 0, 2)."</div>") . "</td>
                    <td>".htmlspecialchars($v['full_name'])."</td>
                    <td>".htmlspecialchars($v['email'])."</td>
                    <td>".htmlspecialchars($v['skills'])."</td>
                    <td><a href='delete_volunteer.php?id={$v['id']}' class='btn-action delete' onclick='return confirm(\"Supprimer ?\")'><i class='fas fa-trash'></i></a></td>
                  </tr>";
        }
        break;

    case 'donations':
        $query = "SELECT d.*, u.full_name, u.email 
                  FROM donations d 
                  LEFT JOIN users u ON d.user_id = u.id 
                  WHERE u.full_name LIKE ? OR u.email LIKE ? OR d.amount LIKE ?
                  ORDER BY $sort $order LIMIT $limit OFFSET $offset";
        $stmt = $pdo->prepare($query);
        $stmt->execute(["%$search%", "%$search%", "%$search%"]);
        $results = $stmt->fetchAll();

        foreach ($results as $d) {
            echo "<tr>
                    <td>#{$d['id']}</td>
                    <td>".htmlspecialchars($d['full_name'] ?? 'Anonyme')."</td>
                    <td>".htmlspecialchars($d['email'] ?? 'N/A')."</td>
                    <td style='font-weight: 700; color: var(--accent-blue);'>".number_format($d['amount'], 2, ',', ' ')." €</td>
                    <td>".($d['donation_date'] ?? 'N/A')."</td>
                    <td><span class='status status-completed'>Complété</span></td>
                  </tr>";
        }
        break;

    case 'shelters':
        $query = "SELECT * FROM shelters 
                  WHERE name LIKE ? OR address LIKE ?
                  ORDER BY $sort $order LIMIT $limit OFFSET $offset";
        $stmt = $pdo->prepare($query);
        $stmt->execute(["%$search%", "%$search%"]);
        $results = $stmt->fetchAll();

        foreach ($results as $s) {
            echo "<tr>
                    <td>{$s['id']}</td>
                    <td>".htmlspecialchars($s['name'])."</td>
                    <td>{$s['total_capacity']}</td>
                    <td>".htmlspecialchars($s['address'])."</td>
                    <td>
                        <a href='edit_shelter.php?id={$s['id']}' class='btn-action edit'><i class='fas fa-edit'></i></a>
                        <a href='delete_shelter.php?id={$s['id']}' class='btn-action delete' onclick='return confirm(\"Supprimer ?\")'><i class='fas fa-trash'></i></a>
                    </td>
                  </tr>";
        }
        break;
}
