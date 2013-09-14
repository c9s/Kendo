Pager
=====

How to use pager:

<?php
    $posts        = new PostCollection;
    $page         = 1;
    $pageSize     = 10;

    $totalItems   = $posts->queryCount();
    $items        = $posts->page( $page ,$pageSize )->items(); // returns array

    $pager = new RegionPager;
    $pager->currentPage = $page;
    $pager->calculatePages( $totalItems , $pageSize );
?>

And the default css style here:

    .pager {  text-align: right; }
    a.pager-link { 
        margin: 1px 2px; 
        color: #74920F;
        text-decoration: none;
    }
    a.pager-link:hover {
        text-decoration: underline;
    }
    a.pager-next { }
    a.pager-prev { }

