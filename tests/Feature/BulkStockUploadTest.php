<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\Stock;
use App\Models\User;

class BulkStockUploadTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create an admin user for testing
        $this->admin = User::factory()->create([
            'email' => 'admin@test.com',
            'is_admin' => true
        ]);
    }

    /** @test */
    public function it_can_upload_csv_with_special_characters()
    {
        Storage::fake('local');

        // Create a CSV file with the exact format from your uploaded file
        $csvContent = 'item_name,category,description,quantity,price,original_price,discount_percentage,special_discount_percentage,is_active,show_on_shop,is_popular,is_latest,expires_at,ordered_count,last_released_at,next_release_at,youtube_url,image' . "\n";
        $csvContent .= '"4"" Gold Lakshmi","SINGLE FLASH","1 Pkt/5 Pcs",,31,120,70,15,1,0,0,0,,,,,' . "\n";
        $csvContent .= '"2 3/4"" Kuruvi","SINGLE FLASH","1 Pkt/5 Pcs",,7,28,70,15,1,1,0,0,,,,,' . "\n";
        $csvContent .= '"4"" Lakshmi","SINGLE FLASH","1 Pkt/5 Pcs",,15,60,70,15,1,1,0,0,,,,,' . "\n";
        $csvContent .= '"4.5"" Bhagubali","SINGLE FLASH","1 Pkt/5 Pcs",,42,164,70,15,1,1,0,0,,,,,' . "\n";

        $file = UploadedFile::fake()->createWithContent(
            'test_stock.csv',
            $csvContent
        );

        $response = $this->actingAs($this->admin)
            ->post(route('admin.stocks.import-csv'), [
                'csv_file' => $file
            ]);

        $response->assertRedirect(route('admin.stocks'));
        $response->assertSessionHas('success');

        // Verify the stocks were created correctly
        $this->assertEquals(4, Stock::count());
        
        // Test specific item with quotes
        $goldLakshmi = Stock::where('item_name', '4" Gold Lakshmi')->first();
        $this->assertNotNull($goldLakshmi);
        $this->assertEquals('SINGLE FLASH', $goldLakshmi->category);
        $this->assertEquals(31, $goldLakshmi->price);
        $this->assertEquals(120, $goldLakshmi->original_price);
        $this->assertEquals(70, $goldLakshmi->discount_percentage);
        
        // Test item with fractional inches
        $kuruvi = Stock::where('item_name', '2 3/4" Kuruvi')->first();
        $this->assertNotNull($kuruvi);
        $this->assertEquals(7, $kuruvi->price);
        
        // Test item with decimal size
        $bhagubali = Stock::where('item_name', '4.5" Bhagubali')->first();
        $this->assertNotNull($bhagubali);
        $this->assertEquals(42, $bhagubali->price);
    }

    /** @test */
    public function it_can_handle_empty_fields_gracefully()
    {
        Storage::fake('local');

        $csvContent = 'item_name,category,description,quantity,price,original_price,discount_percentage,special_discount_percentage,is_active,show_on_shop,is_popular,is_latest,expires_at,ordered_count,last_released_at,next_release_at,youtube_url,image' . "\n";
        $csvContent .= 'Test Product,SPARKLERS,Test Description,,25.50,,,,1,1,0,0,,,,,' . "\n";

        $file = UploadedFile::fake()->createWithContent(
            'test_empty_fields.csv',
            $csvContent
        );

        $response = $this->actingAs($this->admin)
            ->post(route('admin.stocks.import-csv'), [
                'csv_file' => $file
            ]);

        $response->assertRedirect(route('admin.stocks'));
        
        $stock = Stock::first();
        $this->assertEquals('Test Product', $stock->item_name);
        $this->assertEquals(25.50, $stock->price);
        $this->assertNull($stock->quantity);
        $this->assertNull($stock->original_price);
        $this->assertTrue($stock->is_active);
    }

    /** @test */
    public function it_validates_required_fields()
    {
        Storage::fake('local');

        // CSV with missing required fields
        $csvContent = 'item_name,category,description,quantity,price,original_price' . "\n";
        $csvContent .= ',"SPARKLERS","Test Description",,25.50,' . "\n"; // Missing item_name

        $file = UploadedFile::fake()->createWithContent(
            'test_invalid.csv',
            $csvContent
        );

        $response = $this->actingAs($this->admin)
            ->post(route('admin.stocks.import-csv'), [
                'csv_file' => $file
            ]);

        $response->assertRedirect(route('admin.stocks'));
        $response->assertSessionHas('error');
        
        // Should not create any stocks
        $this->assertEquals(0, Stock::count());
    }

    /** @test */
    public function it_can_preview_import_data()
    {
        Storage::fake('local');

        $csvContent = 'item_name,category,description,quantity,price,original_price' . "\n";
        $csvContent .= '"4"" Gold Lakshmi","SINGLE FLASH","1 Pkt/5 Pcs",,31,120' . "\n";
        $csvContent .= 'Test Product,SPARKLERS,Test Description,100,25.50,30.00' . "\n";

        $file = UploadedFile::fake()->createWithContent(
            'test_preview.csv',
            $csvContent
        );

        $response = $this->actingAs($this->admin)
            ->post(route('admin.stocks.preview-import'), [
                'csv_file' => $file
            ]);

        $response->assertStatus(200);
        $responseData = $response->json();
        
        $this->assertTrue($responseData['success']);
        $this->assertArrayHasKey('headers', $responseData);
        $this->assertArrayHasKey('preview_data', $responseData);
        $this->assertEquals(2, $responseData['total_rows']);
    }

    /** @test */
    public function it_can_download_template()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.stocks.download-template', ['format' => 'csv']));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv');
        $response->assertHeader('Content-Disposition', 'attachment; filename=stock_upload_template.csv');
    }

    /** @test */
    public function it_can_download_excel_template()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.stocks.download-template', ['format' => 'xlsx']));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    /** @test */
    public function it_handles_file_upload_errors()
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.stocks.import-csv'), [
                'csv_file' => 'not-a-file'
            ]);

        $response->assertSessionHasErrors('csv_file');
    }

    /** @test */
    public function it_handles_invalid_file_types()
    {
        $file = UploadedFile::fake()->create('test.txt', 100, 'text/plain');

        $response = $this->actingAs($this->admin)
            ->post(route('admin.stocks.import-csv'), [
                'csv_file' => $file
            ]);

        $response->assertSessionHasErrors('csv_file');
    }
}
