Notification Framework
=============================

Notification Framework is consisted by 3 parts, notification server, subscriber 
and notification channel.

Messages sent to notification server is published from notification channel,
these messages will be sent to mutiple channel subscriber.

Notification Channel
--------------------

```php
<?php
    $channel = new Phifty\Notification\NotificationChannel( 'client01', 'bson_encode' );
    $channel->publish(array( 
        'type' => 'git',
        'message' => 'Message'
    ));
```


Notification Subscriber
-----------------------

```php
<?php


```


Notification Server
---------------





