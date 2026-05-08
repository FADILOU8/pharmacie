from pathlib import Path

files = [
    'app/Http/Controllers/ExpirationAlertController.php',
    'app/Http/Controllers/PatientController.php',
    'app/Http/Controllers/SaleController.php',
    'app/Http/Controllers/PrescriptionController.php',
    'app/Http/Controllers/OrderController.php',
    'app/Http/Controllers/MedicineController.php',
    'app/Http/Controllers/SupplierController.php',
    'app/Http/Controllers/DashboardController.php',
    'app/Http/Controllers/ExportController.php',
]

replace_text = "Pharmacy::where('user_id', Auth::id())->first();"
replace_with = "Auth::user()->currentPharmacy();"
replace_text2 = "Auth::user()->pharmacy"
replace_with2 = "Auth::user()->currentPharmacy()"

for file_path in files:
    p = Path(file_path)
    text = p.read_text(encoding='utf-8')
    new_text = text.replace(replace_text, replace_with).replace(replace_text2, replace_with2)
    if new_text != text:
        p.write_text(new_text, encoding='utf-8')
        print(f'Updated {file_path}')
    else:
        print(f'No changes in {file_path}')
