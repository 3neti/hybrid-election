# Cheat Sheet
```bash
php artisan migrate:fresh
php artisan preflight-er --force
php artisan app:cast-ballot --local "BAL-001|PRESIDENT:SJ_002;SENATOR:JD_001,JL_004"
php artisan prepare-er
php artisan certify-er "uuid-juan|ABC123" "id=uuid-pedro,signature=ABC123" "id=uuid-maria,signature=ABC123"
php artisan certify-er --dir=storage/app/public/signatures
```
