Cookbook
========

Getting Request varaibles in controller
---------------------------------------

<?php
use Phifty\Controller;
class YourController extends Controller {
    // inside your controller class
    function indexAction() {

        $id = $this->request['id'];
        $id = $this->request->param('id');    // is the same as: isset($_REQUEST['id']) ? $_REQUEST['id'] : null;

        $postId = $this->request->post->id;   // is the same as: isset($_POST['id']) ? : $_POST['id'] : null;

        // $_SERVER['HTTP_HOST'];
        $host = $this->request->server->HTTP_HOST;

        // $_SESSION['user_id'];
        $host = $this->request->session->user_id;

    }
}
?>

Custom Controller View
----------------------

Normally you can render template directly in controller class:

<?php
use Phifty\Controller;
class YourController extends Controller {
    public function indexAction() {
        return $this->render('path/to/your_template.html.twig');
    }
}
?>

Which is the same as below

<?php
use Phifty\Controller;
class YourController extends Controller {
    public function indexAction()
    {
        return $this->createView()
            ->render('path/to/your_template.html.twig');
    }
}
?>

Or with custom view class:

<?php
use Phifty\Controller;
class YourController extends Controller {
    public function catalogAction()
    {
        // create view instance with VStock\View\MemberView class.
        $view = $this->createView('VStock\View\MemberView');

        // or default with Phifty\View class
        $view = $this->createView();

        $otcIndustries = new IndustryCollection;
        $otcIndustries->where()
                ->equal('market_code','otc');

        $siiIndustries = new IndustryCollection;
        $siiIndustries->where()
                ->equal('market_code','sii');

        $view->industries = array(
            'otc' => $otcIndustries,
            'sii' => $siiIndustries,
        );

        return $view->render('dashboard/catalog.html.twig');
    }
}
?>

In your custom view class (`applications/VStock/View/MemberView.php`):

<?php
    namespace VStock\View;
    use Stock\Model\TransactionCollection;
    use Phifty\View;

    class MemberView extends View
    {
        function init()
        {
            // register your custom template variable.
            $this->member = $member;
            $this->recentTransactions = $recentTxns;
        }
    }
}

?>

