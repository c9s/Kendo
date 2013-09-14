Ibiyaya Website Upgrade Note
============================

From rev: e3b90f8e6084a4062e8cb13ba8ec22b63e2a7b95

1. Remove $env member from controller

        - $cId = $this->env->request->category_id;
        + $cId = $this->request->param('category_id');

2. Replace WidgetLoader with AssetLoader

        - $widget = WidgetLoader::load('jQueryTools');
        + kernel()->asset->loader->load('jquerytools');

   And in template, write `include_assets`..

3. Rename `run` method to `indexAction` for application
   controllers.

4. Use assetkit

5. Locale update

        - $lang = kernel()->currentLang();
        - $lang = kernel()->locale->current();

6. Config update

7. News Plugin
    `with_icon` and `with_image` were added.

8. Replace webapp() with kernel()

9. Update webroot/index.php with phifty/webroot/index.php

10. Remove `Collection->fetch`

11. Rename `bundles` to `applications`

12. Replace twig templates
    
    - Kernel.currentLang
    + Kernel.locale.current

13. Upgrade Pager

    $page = $this->request->param('page') ?: 1;
    $newsItems = new NewsCollection;
    $newsItems->where(array( 'lang' => kernel()->locale->current() ))->order('id','desc');

    $count = $newsItems->queryCount();
    $pager = new Pager($page,$count,10);
    $newsItems->page( $page, $pager->pageSize );

14. Check route path

    {id} => :id


15. Remove lang() and replace with locale
