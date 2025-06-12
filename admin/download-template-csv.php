<?php
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="product_template.csv"');

$output = fopen('php://output', 'w');

// Header
fputcsv($output, ['name', 'price', 'category_id', 'image', 'description']);

// Sample Row
fputcsv($output, ['Sample Product', 19.99, 1, 'sample.jpg', 'This is a sample product.']);

fclose($output);
exit;
