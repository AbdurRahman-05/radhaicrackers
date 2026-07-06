<?php

require_once 'vendor/autoload.php';

use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class SampleStockExport implements FromArray, WithHeadings, WithStyles
{
    public function array(): array
    {
        return [
            [
                'Lakshmi Bomb 1000 Wala',
                'BOMBS',
                'High-intensity bomb with 1000 sound effects, perfect for Diwali celebrations',
                500,
                150.00,
                200.00,
                25,
                1,
                'https://www.youtube.com/watch?v=lakshmi_bomb_demo'
            ],
            [
                'Bijili Crackers Pack',
                'BIJILI CRACKERS',
                'Electric sparklers with colorful effects, safe for children',
                1000,
                75.00,
                100.00,
                25,
                1,
                'https://www.youtube.com/watch?v=bijili_sparklers'
            ],
            [
                'Rocket Launcher Set',
                'ROCKETS',
                'Multi-color rockets with whistling sound, launches up to 50 feet',
                200,
                120.00,
                150.00,
                20,
                1,
                'https://www.youtube.com/watch?v=rocket_launcher_show'
            ],
            [
                'Twinkling Star Pack',
                'TWINKLING STAR',
                'Beautiful star-shaped crackers with golden sparkles',
                800,
                60.00,
                80.00,
                25,
                1,
                'https://www.youtube.com/watch?v=twinkling_stars'
            ],
            [
                'Chit Put Deluxe',
                'CHIT PUT',
                'Traditional chit put with enhanced sound and light effects',
                300,
                90.00,
                120.00,
                25,
                1,
                'https://www.youtube.com/watch?v=chit_put_traditional'
            ],
            [
                'Gift Box Premium',
                'GIFT BOX',
                'Premium gift box containing variety of crackers for special occasions',
                100,
                500.00,
                750.00,
                33,
                1,
                'https://www.youtube.com/watch?v=gift_box_premium'
            ],
            [
                'Single Flash Pack',
                'SINGLE FLASH',
                'Single flash crackers with bright light effects',
                600,
                45.00,
                60.00,
                25,
                1,
                'https://www.youtube.com/watch?v=single_flash_demo'
            ],
            [
                'Sparklers Magic',
                'SPARKLERS',
                'Magic sparklers with rainbow colors and long burning time',
                1200,
                35.00,
                50.00,
                30,
                1,
                'https://www.youtube.com/watch?v=sparklers_magic'
            ],
            [
                'Bomb Supreme 2000',
                'BOMBS',
                'Supreme quality bomb with 2000 sound effects, premium category',
                150,
                300.00,
                400.00,
                25,
                1,
                'https://www.youtube.com/watch?v=bomb_supreme_2000'
            ],
            [
                'Bijili Special',
                'BIJILI CRACKERS',
                'Special bijili crackers with multiple color combinations',
                700,
                85.00,
                110.00,
                23,
                1,
                'https://www.youtube.com/watch?v=bijili_special_colors'
            ],
            [
                'Rocket Galaxy',
                'ROCKETS',
                'Galaxy rockets with star burst effects at high altitude',
                180,
                180.00,
                240.00,
                25,
                1,
                'https://www.youtube.com/watch?v=rocket_galaxy_burst'
            ],
            [
                'Star Burst Pack',
                'TWINKLING STAR',
                'Star burst crackers with multiple explosion patterns',
                400,
                95.00,
                130.00,
                27,
                1,
                'https://www.youtube.com/watch?v=star_burst_patterns'
            ],
            [
                'Chit Put Classic',
                'CHIT PUT',
                'Classic chit put with traditional sound and authentic feel',
                250,
                70.00,
                90.00,
                22,
                1,
                'https://www.youtube.com/watch?v=chit_put_classic'
            ],
            [
                'Gift Box Family',
                'GIFT BOX',
                'Family gift box with safe crackers for all age groups',
                80,
                350.00,
                500.00,
                30,
                1,
                'https://www.youtube.com/watch?v=gift_box_family'
            ],
            [
                'Flash Deluxe',
                'SINGLE FLASH',
                'Deluxe flash crackers with enhanced brightness',
                450,
                55.00,
                75.00,
                27,
                1,
                'https://www.youtube.com/watch?v=flash_deluxe_bright'
            ],
            [
                'Sparklers Gold',
                'SPARKLERS',
                'Golden sparklers with premium quality and long duration',
                900,
                40.00,
                55.00,
                27,
                1,
                'https://www.youtube.com/watch?v=sparklers_gold_premium'
            ],
            [
                'Bomb Thunder',
                'BOMBS',
                'Thunder bomb with earth-shaking sound effects',
                220,
                200.00,
                280.00,
                29,
                1,
                'https://www.youtube.com/watch?v=bomb_thunder_sound'
            ],
            [
                'Bijili Rainbow',
                'BIJILI CRACKERS',
                'Rainbow bijili with seven color effects',
                550,
                95.00,
                125.00,
                24,
                1,
                'https://www.youtube.com/watch?v=bijili_rainbow_colors'
            ],
            [
                'Rocket Comet',
                'ROCKETS',
                'Comet rockets with trailing light effects',
                120,
                220.00,
                300.00,
                27,
                1,
                'https://www.youtube.com/watch?v=rocket_comet_trail'
            ],
            [
                'Star Magic',
                'TWINKLING STAR',
                'Magic star crackers with unpredictable patterns',
                350,
                110.00,
                150.00,
                27,
                1,
                'https://www.youtube.com/watch?v=star_magic_patterns'
            ]
        ];
    }

    public function headings(): array
    {
        return [
            'item_name',
            'category', 
            'description',
            'quantity',
            'price',
            'original_price',
            'discount_percentage',
            'is_active',
            'youtube_url'
        ];
    }

    public function styles($sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E2E8F0']
                ]
            ]
        ];
    }
}

// Create the Excel file
Excel::store(new SampleStockExport(), 'sample_stock_data.xlsx');

echo "Excel file 'sample_stock_data.xlsx' created successfully!\n";
echo "File location: " . storage_path('app/sample_stock_data.xlsx') . "\n";
?> 