<?php
// Fetch all products (used as default fallback)
function getProducts() {
    global $conn;
    $res = $conn->query("SELECT * FROM products");
    return $res->fetch_all(MYSQLI_ASSOC);
}

// Fetch all categories (for sidebar filter)
function getAllCategories() {
    global $conn;
    $res = $conn->query("SELECT * FROM categories ORDER BY name");
    return $res->fetch_all(MYSQLI_ASSOC);
}

// Fetch products with search and filter
function getFilteredProducts($search = '', $categoryId = '') {
    global $conn;
    $query = "SELECT * FROM products WHERE 1";
    $types = "";
    $params = [];

    if (!empty($search)) {
        $query .= " AND name LIKE ?";
        $types .= "s";
        $params[] = "%" . $search . "%";
    }

    if (!empty($categoryId)) {
        $query .= " AND category_id = ?";
        $types .= "i";
        $params[] = $categoryId;
    }

    $stmt = $conn->prepare($query);
    
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

// ✅ Moved outside the previous function
function getProductById($id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}
?>
