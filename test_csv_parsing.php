<?php

// Test CSV parsing for the bulk upload issue
$csvFile = 'correct data.csv';

if (file_exists($csvFile)) {
    echo "Testing CSV parsing for: $csvFile\n\n";
    
    $handle = fopen($csvFile, 'r');
    $headers = fgetcsv($handle, 0, ',', '"', '\\');
    
    echo "Headers found:\n";
    foreach ($headers as $index => $header) {
        echo "Column $index: '$header'\n";
    }
    
    echo "\nFirst 3 data rows:\n";
    $rowCount = 0;
    while (($row = fgetcsv($handle, 0, ',', '"', '\\')) !== false && $rowCount < 3) {
        $rowCount++;
        echo "\nRow $rowCount:\n";
        
        $rowData = array_combine($headers, $row);
        foreach ($rowData as $field => $value) {
            echo "  $field: '$value'\n";
        }
        
        // Test the specific fields causing issues
        echo "\nProcessed values:\n";
        echo "  item_name: '" . trim($rowData['item_name']) . "'\n";
        echo "  category: '" . trim($rowData['category']) . "'\n";
        echo "  quantity: '" . trim($rowData['quantity']) . "' (empty: " . (empty($rowData['quantity']) ? 'yes' : 'no') . ")\n";
        echo "  price: '" . trim($rowData['price']) . "' (empty: " . (empty($rowData['price']) ? 'yes' : 'no') . ")\n";
        
        // Test validation
        $isValid = !empty(trim($rowData['item_name'])) && !empty(trim($rowData['category']));
        echo "  Valid row: " . ($isValid ? 'YES' : 'NO') . "\n";
        echo "  Quantity after parsing: " . (empty($rowData['quantity']) ? '0 (default)' : $rowData['quantity']) . "\n";
    }
    
    fclose($handle);
} else {
    echo "CSV file not found: $csvFile\n";
}
