How to write controller ?
=========================

建立 Application 的 Controller, 要建立一個 Controller class 如名為 TestController

可編輯 `applications/HelloWorld/Controller/TestController.php` 

置入以下內容

<?php
namespace HelloWorld\Controller;
use Phifty\Controller;

class TestController extends Controller 
{
    function indexAction($name) 
    {
        return 'Hello ' . $name;
    }
}
?>

接著，使用 Router 將 Controller 連接起來，請編輯 `applications/HelloWorld/Application.php`

在 init 方法內置入:

<?php
// ...

    function init()
    {
        $this->route('/hello/:name', 'TestController:index' );
    }

?>

接著，在瀏覽器內輸入: `http://testapp.dev/hello/John` 即可看到 "Hello John" 的內容。

