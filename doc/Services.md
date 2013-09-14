Services
========

Service Class 內部必須定義 `register` method 來向 `Phifty\Kernel` 註冊 service。

Kernel 本身是一個 object container ，可以註冊 accessor builder ，也就是說:

<?php
    $kernel = kernel();
    $kernel->foo = function() { 
        return new Foo(123);
    };

    $kernel->foo;  // Foo(123)

    $kernel->foo;  // Foo(123)
?>

在 Service 內部就是去 kernel 註冊 accessor 來使用。

以 RouterService 為例:

<?php
namespace Phifty\Service;
use Roller\Router;

class RouterService 
    implements ServiceInterface
{
    public function getId() { return 'Router'; }
    public function register($kernel, $options = array() ) 
    {
        $kernel->router = function() use ($kernel) {
            $uuid = $kernel->config->get('framework','uuid');
            return new Router(null, array( 
                'route_class' => 'Phifty\Routing\Route',
                // 'cache_id' => $uuid,
            ));
        };
    }
}
?>

## Service Config 

請見 config/framework.yml

    Services:
      CurrentUserService:
        OptionName: value
        OptionName2: value

