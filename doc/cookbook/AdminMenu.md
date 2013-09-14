
# AdminUI Menu Usage

    kernel()->event->register( 'adminui.init_menu' , function($menu) use ($self) {
        $plugin = kernel()->plugin('HotelBundle');
        $sect = $menu->addSection( '飯店管理');
        $sect->addMenuItem( '飯店資料' , array( 'href' => '/bs/hotel')  );
        $sect->addMenuItem( '飯店地區' , array( 'href' => '/bs/hotel_area')  );
        $sect->addMenuItem( '飯店類型' , array( 'href' => '/bs/hotel_category')  );
    });
