# 📊 Bulk Stock Upload Documentation

## 🎯 Overview
The bulk upload feature allows you to upload multiple stock items at once using CSV or Excel files. It supports complex product names with special characters like quotes ("), fractions (3/4), and decimal numbers (4.5).

## 📋 Supported Features

### ✅ File Formats
- **CSV files** (.csv, .txt) - Fully supported
- **Excel files** (.xlsx, .xls) - Fully supported

### 🔧 Special Character Handling
- **Quotes in product names**: `"4"" Gold Lakshmi"` → `4" Gold Lakshmi`
- **Fractional measurements**: `"2 3/4"" Kuruvi"` → `2 3/4" Kuruvi`
- **Decimal measurements**: `"4.5"" Bhagubali"` → `4.5" Bhagubali`
- **Regular text**: No changes applied

### 📝 Required Fields
- `item_name` - Product name (required)
- `category` - Product category (required)
- `price` - Product price (required, must be > 0)

### 📝 Optional Fields
- `description` - Product description
- `quantity` - Stock quantity (defaults to 0)
- `original_price` - Original price for discount calculation
- `discount_percentage` - Discount percentage (0-100)
- `special_discount_percentage` - Special discount percentage (0-100)
- `is_active` - Active status (1/0, true/false, yes/no)
- `show_on_shop` - Show on shop (1/0, true/false, yes/no)
- `is_popular` - Popular product flag (1/0, true/false, yes/no)
- `is_latest` - Latest product flag (1/0, true/false, yes/no)
- `expires_at` - Expiration date (YYYY-MM-DD HH:MM:SS)
- `ordered_count` - Number of times ordered
- `last_released_at` - Last release date
- `next_release_at` - Next release date
- `youtube_url` - YouTube video URL
- `image` - Image filename/path

## 📄 CSV File Format

### Header Row (Required)
```csv
item_name,category,description,quantity,price,original_price,discount_percentage,special_discount_percentage,is_active,show_on_shop,is_popular,is_latest,expires_at,ordered_count,last_released_at,next_release_at,youtube_url,image
```

### Sample Data Rows
```csv
"4"" Gold Lakshmi","SINGLE FLASH","1 Pkt/5 Pcs",,31,120,70,15,1,0,0,0,,,,,,
"2 3/4"" Kuruvi","SINGLE FLASH","1 Pkt/5 Pcs",,7,28,70,15,1,1,0,0,,,,,,
"4"" Lakshmi","SINGLE FLASH","1 Pkt/5 Pcs",,15,60,70,15,1,1,0,0,,,,,,
Red Bijili,"BIJILI CRACKERS","1 Pkt/50 Pcs",,18,72,70,15,1,1,0,0,,,,,,
Hydro Bomb,BOMB,"1 Box/10 Pcs",,67,264,70,15,1,1,0,0,,,,,,
```

## 🚀 How to Use

### 1. **Download Template**
1. Go to Admin → Stock Management
2. Click "Import CSV" button
3. Click "Download CSV Template" or "Download Excel Template"
4. Open the template in your preferred application

### 2. **Prepare Your Data**
1. Fill in the template with your stock data
2. Ensure required fields (item_name, category, price) are populated
3. Use proper formatting for dates: `YYYY-MM-DD HH:MM:SS`
4. Use 1/0 or true/false for boolean fields

### 3. **Upload File**
1. Click "Import CSV" button
2. Select your prepared file
3. Review the preview (first 4 rows shown)
4. Click "Confirm Import" to process

### 4. **Review Results**
- Green message: Successful imports
- Yellow message: Skipped rows (empty or invalid)
- Red message: Failed imports with error details

## ⚠️ Important Notes

### Data Validation
- **Price validation**: Must be greater than 0
- **Quantity validation**: Cannot be negative
- **Required fields**: item_name, category must not be empty
- **Discount validation**: Must be between 0-100%

### Special Character Handling
- Products with quotes in names should be properly escaped in CSV
- Example: For a product named `4" Gold Lakshmi`, use `"4"" Gold Lakshmi"` in CSV
- Excel files handle special characters automatically

### Error Handling
- Empty rows are automatically skipped
- Invalid data types are converted when possible
- Detailed error messages for troubleshooting
- Batch processing continues even if some rows fail

## 🔧 Technical Details

### File Size Limits
- **CSV files**: Up to 2MB
- **Excel files**: Up to 2MB
- **Processing**: 100 rows per batch for optimal performance

### Supported Categories
The system accepts any category name. Common categories include:
- SINGLE FLASH
- BIJILI CRACKERS
- BOMB
- SPARKLERS
- FLOWER POTS - Regular
- FLOWER POTS - Premium
- CHAKKARAS - Premium
- And many more...

### Date Formats
Supported date formats for `expires_at`, `last_released_at`, `next_release_at`:
- `2025-12-31 23:59:59`
- `2025-12-31`
- `12/31/2025`
- Any format recognized by PHP's DateTime parser

## 🛠️ Troubleshooting

### Common Issues

**1. "Missing required fields" error**
- Ensure item_name, category are not empty
- Check for extra spaces or special characters

**2. "Price must be greater than 0" error**
- Verify price column has valid numeric values
- Remove any currency symbols or text

**3. "File format not supported" error**
- Ensure file extension is .csv, .txt, .xlsx, or .xls
- Check file is not corrupted

**4. "No data found" error**
- Verify file has header row
- Check file encoding (UTF-8 recommended)
- Ensure at least one data row exists

### Best Practices

1. **Test with small files first** (5-10 rows)
2. **Use the provided templates** for correct formatting
3. **Keep backups** of your original data
4. **Review preview** before confirming import
5. **Check error messages** for specific row issues

## 📈 Performance Tips

- **Batch uploads**: Upload in smaller batches (100-500 rows) for better performance
- **File optimization**: Remove unnecessary columns to reduce file size
- **Network considerations**: Stable internet connection recommended for large files

## 🔐 Security Features

- **File validation**: Only allowed file types accepted
- **Data sanitization**: All input data is cleaned and validated
- **Error logging**: Failed imports are logged for debugging
- **User authentication**: Only admin users can perform bulk uploads

## 📞 Support

If you encounter issues:
1. Check the error messages in the upload result
2. Verify your data format against the template
3. Test with a smaller file first
4. Review this documentation for troubleshooting tips

---

*Last updated: July 2025*
