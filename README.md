Messing around a little with Codeigniter and the Groupon Widget. This could easily be a helper. I decided to make it a library instad. Don't ask me why.

    $this->load->library('grouponwidget');

    // If none of these values are set, it will fall back to default values
    $settings = array(
        'referral_code' => 'ABC123',
        'rounded' => TRUE,
        'header_color' => '#FF00FF',
        'shell_background_color' => '#FFFF00',
        'shell_text_color' => '#00FFFF',
        'deal_link_color' => '#00FF00',
        'pricetag_background_color' => '#F0F0F0',
        'get_it_background_color' => '#FFCC00',
        'city' => 'dallas'
    );

    echo $this->grouponwidget->generate($settings);