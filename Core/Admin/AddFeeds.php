<?php
namespace PressForward\Core\Admin;
use Intraxia\Jaxion\Contract\Core\HasActions;
use Intraxia\Jaxion\Contract\Core\HasFilters;

use PressForward\Interfaces\SystemUsers;

use PressForward\Core\Admin\PFTemplater as PFTemplater;
use PressForward\Core\Utility\Forward_Tools as Forward_Tools;
use PressForward\Core\Schema\Nominations as Nominations;
use PressForward\Controllers\Metas;

class AddFeeds implements HasActions, HasFilters {
    function __construct( SystemUsers $user_interface ){
        $this->user_interface = $user_interface;

    }

    public function action_hooks() {
        return array(
            array(
                'hook' => 'admin_menu',
                'method' => 'add_plugin_admin_menu',
            ),
        );
    }

    public function filter_hooks(){
        return array(
            array(
                'hook' => 'pf_tabs_pf-add-feeds',
                'method' => 'set_permitted_tools_tabs',
            ),
        );
    }

    public function add_plugin_admin_menu() {

        // Feed-listing page is accessible only to Editors and above
		add_submenu_page(
			PF_MENU_SLUG,
			__('Add Feeds', 'pf'),
			__('Add Feeds', 'pf'),
			get_option('pf_menu_feeder_access', pf_get_defining_capability_by_role('editor')),
			PF_SLUG . '-feeder',
			array($this, 'display_feeder_builder')
		);

    }

    public function display_feeder_builder(){
        if ( isset ( $_GET['tab'] ) ) $tab = $_GET['tab']; else $tab = 'primary_feed_type';
        $user_ID = get_current_user_id();
        $form_head = '<form method="post" action="options.php" enctype="multipart/form-data">';
        $vars = array(
                'current'		=> $tab,
                'user_ID'		=> $user_ID,
                'page_title'	=>	__('PressForward: Add Feeds', 'pf'),
                'page_slug'		=>	'pf-add-feeds',
                'no_save_button' =>	true,
                'form_head'		 => $form_head,
                'settings_field' => PF_SLUG . '_feedlist_group'
            );
        echo pressforward('admin.templates')->get_view(pressforward( 'controller.template_factory' )->build_path(array('settings','settings-page'), false), $vars);

        return;
    }

    public function set_permitted_tools_tabs( $permitted_tabs ){
        $permitted_tabs['primary_feed_type'] = array(
                                        'title' => __('Subscribe to Feeds', 'pf'),
                                        'cap'  => pf_get_defining_capability_by_role('contributor')
                                    );
        $permitted_tabs['alerts'] = array(
                                        'title' => __('Alerts', 'pf'),
                                        'cap'  => pf_get_defining_capability_by_role('administrator')
                                    );
        return $permitted_tabs;
    }

}
