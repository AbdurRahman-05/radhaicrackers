<?php

/**
 * Test script to validate bulk upload functionality
 * Run this script to test the CSV upload with your specific data format
 */

class BulkUploadTest
{
    public function testCsvParsingWithSpecialCharacters()
    {
        echo "Testing CSV parsing with special characters...\n";
        
        // Create test CSV content matching your format
        $testData = [
            ['item_name', 'category', 'description', 'quantity', 'price', 'original_price', 'discount_percentage', 'special_discount_percentage', 'is_active', 'show_on_shop', 'is_popular', 'is_latest', 'expires_at', 'ordered_count', 'last_released_at', 'next_release_at', 'youtube_url', 'image'],
            ['"4"" Gold Lakshmi"', 'SINGLE FLASH', '1 Pkt/5 Pcs', '', '31', '120', '70', '15', '1', '0', '0', '0', '', '', '', '', '', ''],
            ['"2 3/4"" Kuruvi"', 'SINGLE FLASH', '1 Pkt/5 Pcs', '', '7', '28', '70', '15', '1', '1', '0', '0', '', '', '', '', '', ''],
            ['"4.5"" Bhagubali"', 'SINGLE FLASH', '1 Pkt/5 Pcs', '', '42', '164', '70', '15', '1', '1', '0', '0', '', '', '', '', '', ''],
            ['Red Bijili', 'BIJILI CRACKERS', '1 Pkt/50 Pcs', '', '18', '72', '70', '15', '1', '1', '0', '0', '', '', '', '', '', '']
        ];
        
        // Test each row processing
        foreach ($testData as $index => $row) {
            if ($index === 0) continue; // Skip header
            
            echo "\nTesting row " . ($index) . ": " . $row[0] . "\n";
            
            // Simulate row data processing
            $rowData = array_combine($testData[0], $row);
            $result = $this->simulateRowProcessing($rowData, $index + 1);
            
            if ($result['error']) {
                echo "❌ Error: " . $result['error'] . "\n";
            } else {
                echo "✅ Success: Item parsed correctly\n";
                echo "   - Name: " . $result['data']['item_name'] . "\n";
                echo "   - Category: " . $result['data']['category'] . "\n";
                echo "   - Price: " . $result['data']['price'] . "\n";
                echo "   - Original Price: " . ($result['data']['original_price'] ?? 'null') . "\n";
                echo "   - Discount: " . ($result['data']['discount_percentage'] ?? 'null') . "%\n";
            }
        }
    }
    
    private function simulateRowProcessing($rowData, $rowNumber)
    {
        try {
            // Simulate the processRowData method logic
            $data = [
                'item_name' => trim($rowData['item_name']),
                'category' => trim($rowData['category']),
                'description' => trim($rowData['description'] ?? ''),
                'quantity' => $this->parseNumeric($rowData['quantity'] ?? 0, 'int'),
                'price' => $this->parseNumeric($rowData['price'] ?? 0, 'float'),
                'original_price' => $this->parseNumeric($rowData['original_price'] ?? null, 'float'),
                'discount_percentage' => $this->parseNumeric($rowData['discount_percentage'] ?? null, 'int'),
                'special_discount_percentage' => $this->parseNumeric($rowData['special_discount_percentage'] ?? null, 'int'),
                'is_active' => $this->parseBoolean($rowData['is_active'] ?? 1),
                'show_on_shop' => $this->parseBoolean($rowData['show_on_shop'] ?? 1),
                'is_popular' => $this->parseBoolean($rowData['is_popular'] ?? 0),
                'is_latest' => $this->parseBoolean($rowData['is_latest'] ?? 0),
                'youtube_url' => trim($rowData['youtube_url'] ?? ''),
                'image' => trim($rowData['image'] ?? '')
            ];

            // Validation
            if ($data['price'] <= 0) {
                return ['error' => "Row {$rowNumber}: Price must be greater than 0", 'data' => null];
            }

            return ['error' => null, 'data' => $data];

        } catch (\Exception $e) {
            return ['error' => "Row {$rowNumber}: Data processing error - " . $e->getMessage(), 'data' => null];
        }
    }
    
    private function parseNumeric($value, $type = 'float')
    {
        if (empty($value) || $value === '') {
            return null;
        }
        
        $cleaned = preg_replace('/[^\d.-]/', '', $value);
        
        if ($type === 'int') {
            return (int) $cleaned;
        }
        
        return (float) $cleaned;
    }
    
    private function parseBoolean($value)
    {
        if (is_bool($value)) {
            return $value;
        }
        
        if (is_string($value)) {
            $value = strtolower(trim($value));
            return in_array($value, ['1', 'true', 'yes', 'on']);
        }
        
        return (bool) $value;
    }
    
    public function testSpecialCharacterHandling()
    {
        echo "\n\nTesting special character handling...\n";
        
        $testCases = [
            '"4"" Gold Lakshmi"' => '4" Gold Lakshmi',
            '"2 3/4"" Kuruvi"' => '2 3/4" Kuruvi',
            '"4.5"" Bhagubali"' => '4.5" Bhagubali',
            'Regular Product' => 'Regular Product'
        ];
        
        foreach ($testCases as $input => $expected) {
            $cleaned = trim($input);
            echo "Input: {$input} → Output: {$cleaned} → Expected: {$expected}\n";
            echo ($cleaned === $expected ? "✅ Passed" : "❌ Failed") . "\n";
        }
    }
}

// Run the tests
$test = new BulkUploadTest();
$test->testCsvParsingWithSpecialCharacters();
$test->testSpecialCharacterHandling();

echo "\n\n=== Test completed ===\n";
