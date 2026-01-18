**Message Queue & Webhook Service**

Bu case, Laravel 11 kullanılarak geliştirilmiş bir mesaj gönderim sistemdir.  
Mesajlar veritabanına `pending` olarak kaydedilir, queue üzerinden webhook’a gönderilir ve sonuçlarına göre `sent` olarak güncellenir. 
Başarılı mesajlar Redis’e cache edilir.

**Kullanılan Teknolojiler**

- PHP 8.4
- Laravel 11.31
- MySQL
- Queue (Database Driver)
- Redis (Cache)
- Swagger (l5-swagger v6) (composer install ile beraber gelecektir fakat endpointler için "php artisan l5-swagger:generate" komutunu girmek gerekeir)
- webhook.site

**Proje Çalıştırma Adımları**

- git clone https://github.com/halis36/insider_message_case.git
- composer install
- .env oluşturulacak 
- php artisan key:generate komutu çalıştırılacak
- veritabanı ayarları yapıldıktan sonra "php artisan migrate" komutu çalıştırılacak
- Bununla beraber queue (jobs ve failed_jobs tabloları da otomatik gelecektir)

**Proje Linkleri**

API Base URL: http://127.0.0.1:8000
Swagger UI: http://127.0.0.1:8000/api/documentation#/

**Queue Ayarları**
QUEUE_CONNECTION=database (.env de şeklinde ayarlanmalı)

**Redis Ayarları**
- CACHE_STORE=redis (.env de şeklinde ayarlanmalı)

**webhook.site Ayarları**
- WEBHOOK_URL=https://webhook.site/xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx (env'de url ayarlanmalı)
  - config/services.php içerisinde 
    'webhook' => [
        'url' => env('WEBHOOK_URL'),
   ],  şeklinde ayarlanmalı
- webhook.site panalinde "edit" kısmında bazı ayarlamalar yapılmalı
  - status code = 202
  - content type = Content-Type: application/json
  - content = {
    "message": "Accepted",
    "messageId": "67f2f8a8-ea58-4ed0-a6f9-ff217df4d849" (prod hesapta dinamik olacak burası)
    }
  şeklinde ayarlanmalı

- http://127.0.0.1:8000/api/documentation#/ adresinde
  - POST /api/messages endpointinden **messages** tablosuna "pending" statusu ile kayıt eklenebilir. 
    - örnek json 
     {
      "phone": "+905XXXXXXXXX",
      "content": "Test message"
      }
  - GET /api/messages ile mesajlar listelenebilir

Proje tamamen kurulduktan ve veritabanına kayıtlar eklendikten sonra
1. php artisan queue:work ile kuyruk aktif edilir
2. php artisan messages:send command çalıştırılarak veritabanındaki mesajlar işleme alınır.
