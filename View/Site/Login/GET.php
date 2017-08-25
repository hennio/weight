<?php

class View_Site_Login_GET extends Abstract_View
{

    public function render()
    {
        $page = 'login';
        $app = Config::get('app');

        $policy = new Policy_LoggedOut($this->app);
        $logged_in = $policy->getData();

        $app->menu_items = Helper_Menu::processMenuItems($app->menu_items, $page, $logged_in);

        return array(
            'app'        => $app,
            'breadcrumb' => 'Log in',
            'error'      => Helper_Message::getError(),
            'username'   => Helper_Message::getField('username'),
        );
    }

}
