Email class
===========

    <?php
    $email = new Phifty\Email;
    $email->to( $to );
    $email->to( $to );
    $email->from( $from );
    $email->subject(  $subject );

    // render html template
    $email->template( $template , array( 
            "user" => $user   // $assign
        ) );
    $email->send();




