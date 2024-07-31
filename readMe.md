

## WOO COMMERCE COMMAND
1. ```bash 
   php artisan fetch:woocommerce-orders
   ```
2. ```bash
    composer dump-autoload
   ```
3. ```bash
    php artisan queue:work
   ```
4. ```bash
    php artisan storage:link
   ```


## COMPOSER COMMAND
1. ``` composer dump-auto```


### cron job
1. php artisan fetch:woocommerce-orders


### set up at server
1. /domains/virtuouscarat.com/public_html/admin/artisan schedule:run
2. /domains/virtuouscarat.com/public_html/admin/artisan queue:work


### diagnostic error or command
1. php artisan queue:work --tries=3
2. php artisan queue:work --tries=3 --timeout=600
3. php artisan queue:work --tries=3 --timeout=600 --stop-when-empty

#### error
1. SHOW PROCESSLIST;
2. KILL <1234>;
