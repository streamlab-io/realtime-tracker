## Download the files 

download all files

## Install  The Dependencies

now type this on your console make sure that your PHP >= 5.6.4

```
composer update
```

## migrate

```
  php artisan migrate
```

## Add App key 

go to streamlab website http://streamlab.io/
then open application get the key and token add them to config/stream_lab.php

```php
  return[
      'app_id'=>'',
      'token'=>''
  ];
```

## Create Cahnnel

make sure you create public channel to your application call "geo"

## Start

```
 php artisan serve
```

and enjoy

