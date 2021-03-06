<?php

/**

 * Accounts Manager Helpers

 *

 * This file contains the class Account_manager

 * with methods to manage the accounts in the Posts Accounts Manager

 *

 * @author Scrisoft

 * @package Midrub

 * @since 0.0.7.4

 */



// Define the page namespace

namespace MidrubBase\User\Apps\Collection\Posts\Helpers;



// Constants

defined('BASEPATH') OR exit('No direct script access allowed');



// Define the namespaces to use

use MidrubBase\User\Apps\Collection\Posts\Helpers as MidrubBaseUserAppsCollectionHelpers;



/*

 * Account_manager class provides the methods for the Posts Accounts Manager

 * 

 * @author Scrisoft

 * @package Midrub

 * @since 0.0.7.4

*/

class Account_manager {

    

    /**

     * Class variables

     *

     * @since 0.0.7.4

     */

    protected $CI;



    /**

     * Initialise the Class

     *

     * @since 0.0.7.4

     */

    public function __construct() {

        

        // Get codeigniter object instance

        $this->CI =& get_instance();



        // Load the lists model

        $this->CI->load->ext_model(MIDRUB_BASE_USER_APPS_POSTS . 'models/', 'Lists_model', 'lists_model');

        

    }

    

    /**

     * The public method load_networks loads all social networks available for user

     * 

     * @since 0.0.7.4

     * 

     * @return void

     */ 

    public function load_networks() {



        $classes = '';

        

        $social_networks = '';



        // List all available networks

        foreach ( glob(MIDRUB_BASE_USER . 'networks/*.php') as $filename ) {



            // Get the class's name

            $className = str_replace(array(MIDRUB_BASE_USER . 'networks/', '.php'), '', $filename);



            // Check if the administrator has disabled the $className social network

            if ( !get_option($className) || !plan_feature($className) ) {

                continue;

            }



            // Create an array

            $array = array(

                'MidrubBase',

                'User',

                'Networks',

                ucfirst($className)

            );



            // Implode the array above

            $cl = implode('\\', $array);



            // Get method

            $get = (new $cl());



            // Verify if the social networks is available

            if ( $get->check_availability() ) {



                // Get the number of accounts

                $con = $this->CI->networks_model->get_network_data($className, $this->CI->user_id);



                // Get network info

                $info = $get->get_info();

                

                // Verify if the network is enabled

                if ( !in_array('post', $info['types']) && !in_array('rss', $info['types']) && !in_array('insights', $info['types']) ) {

                    continue;

                }



                // Default number of accounts

                $num_accounts = 0;



                // Verify if network has accounts

                if ( !empty($con[0]->num) ) {

                    $num_accounts = $con[0]->num;

                }



                // Get the network's name

                $netw = ucwords(str_replace('_', ' ', $className));



                $network_selected = '';



                // Verify if this is the first class

                if ( !$classes ) {

                    $network_selected = ' class="network-selected"';

                    $classes = $className;

                }



                // Add networks html to the list

                $social_networks .= '<li' . $network_selected . '>'

                        . '<a href="#" data-network="' . $className . '">'

                            . '<div>' . $netw . '</div>'

                            . '<span style="background-color:' . $info['color'] . '">' . $num_accounts . ' ' . $this->CI->lang->line('accounts') . '</span>'

                        . '</a>'

                    . '</li>';

                



            }



        }

        

        // Verify if network exists 

        if ( $social_networks ) {

            

            // Get Active Accounts

            $active_accounts = $this->CI->networks_model->get_accounts_by_network( $this->CI->user_id, $classes, 1 );

            

            $settings = (new MidrubBaseUserAppsCollectionHelpers\Accounts)->get_network_info($classes);

            

            $icon = '';

            

            if ( $settings ) {

                

                $icon = $settings['icon'];

                $hidden = $settings['hidden'];

                        

            } else {

                $hidden = '';

            }

            

            $display_hidden_content = '';

            

            if ( $hidden ) {

                $display_hidden_content = ' manage-accounts-display-hidden-content';

            }

            

            // Prepare the connect button

            $connect_button_text = (isset($settings['custom_connect']))?$settings['custom_connect']:$this->CI->lang->line('new_account');

            

            // Add network to the list

            $social_data = '<div class="row">'

                                . '<div class="col-xl-3">'

                                    . '<ul class="nav nav-tabs tabs-left accounts-manager-available-networks">'

                                        . $social_networks

                                    . '</ul>'

                                . '</div>'

                                . '<div class="col-xl-5">'

                                    . '<div class="row manage-accounts-search-form">'

                                        . '<div class="col-xl-7 col-sm-7 col-7">'

                                            . '<div class="input-group accounts-manager-search">'

                                                . '<div class="input-group-prepend">'

                                                    . '<i class="icon-magnifier"></i>'

                                               . ' </div>'

                                                . '<input type="text" class="form-control accounts-manager-search-for-accounts" data-network="' . $classes . '" placeholder="' . $this->CI->lang->line('search_for_accounts')  . '">'

                                                . '<button type="button" class="cancel-accounts-manager-search input-group-append">'

                                                    . '<i class="icon-close"></i>'

                                                . '</button>'

                                            . '</div>'

                                        . '</div>'

                                        . '<div class="col-xl-5 col-sm-5 col-5">'

                                            . '<button type="button" class="manage-accounts-new-account' . $display_hidden_content . '">'

                                                . $icon . ' ' . $connect_button_text

                                            . '</button>'

                                        . '</div>'

                                    . '</div>'

                                    . '<div class="row">'

                                        . '<div class="col-xl-12 manage-accounts-hidden-content">'

                                            . $hidden

                                        . '</div>'

                                    . '</div>'                    

                                    . '<div class="row">'

                                        . '<div class="col-xl-12 manage-accounts-all-accounts">'

                                            . (new MidrubBaseUserAppsCollectionHelpers\Accounts)->get_active_accounts($this->CI->networks_model->get_accounts_by_network( $this->CI->user_id, $classes, 0 ), 0) . (new MidrubBaseUserAppsCollectionHelpers\Accounts)->get_active_accounts($active_accounts, 1)

                                        . '</div>'

                                    . '</div>'

                                    /*======================= CLIENT LINK ========================= */

                                    . '<div class="row">'

                                        . '<div class="col-xl-12 manage-link-for-group">'

                                            . (new MidrubBaseUserAppsCollectionHelpers\Accounts)->get_link_options($this->CI->lists_model->get_groups($this->CI->user_id, 0, 10), $classes)

                                        . '</div>'

                                    . '</div>'

                                    /*======================= /CLIENT LINK ========================= */

                                . '</div>'

                                . '<div class="col-xl-4">'

                                    . '<div class="col-xl-12 manage-accounts-network-instructions">'

                                        . (new MidrubBaseUserAppsCollectionHelpers\Accounts)->get_network_instructions( $classes )

                                    . '</div>'

                                . '</div>'

                            . '</div>';



            // Get groups list

            $groups_list = $this->CI->lists_model->get_groups($this->CI->user_id, 0, 1000);

            

            $groups = '';

            

            // Verify if group exists

            if ( $groups_list ) {

            

                foreach ( $groups_list as $group ) {



                    $groups .= '<button class="dropdown-item" type="button" data-id="' . $group->list_id . '">'

                                    . $group->name

                                . '</button>';



                }

                

            }

            

            // Add group to the list

            $groups_data = '<div class="row">'

                                . '<div class="col-xl-3">'

                                    . '<ul class="nav nav-tabs tabs-left accounts-manager-available-networks">'

                                        . $social_networks

                                    . '</ul>'

                                . '</div>'

                                . '<div class="col-xl-9">'

                                    . '<div class="row">'

                                        . '<div class="col-xl-6">'

                                            . '<div class="col-xl-12">'

                                                . '<div class="row">'

                                                    . '<div class="col-xl-12 input-group accounts-manager-search-accounts">'

                                                        . '<div class="input-group-prepend">'

                                                            . '<i class="icon-magnifier"></i>'

                                                        . '</div>'

                                                        . '<input type="text" class="form-control accounts-manager-search-for-accounts" placeholder="' . $this->CI->lang->line('search_for_accounts')  . '">'

                                                        . '<button type="button" class="cancel-accounts-manager-search input-group-append">'

                                                            . '<i class="icon-close"></i>'

                                                        . '</button>'

                                                    . '</div>'

                                                . '</div>'

                                                . '<div class="row">'

                                                    . '<div class="col-xl-12 manage-accounts-groups-all-accounts">'

                                                        . (new MidrubBaseUserAppsCollectionHelpers\Accounts)->get_active_accounts($active_accounts, 2)

                                                    . '</div>'

                                                . '</div>'

                                            . '</div>'

                                        . '</div>'

                                        . '<div class="col-xl-6">'

                                            . form_open('user/app/posts', array('class' => 'create-new-group-form', 'data-csrf' => $this->CI->security->get_csrf_token_name()))

                                            . '<div class="col-xl-12">'

                                                . '<div class="row accounts-manager-groups-create-group">'

                                                    . '<div class="col-xl-7 col-sm-7 col-7 input-group">'

                                                        . '<div class="input-group-prepend">'

                                                            . '<i class="icon-folder-alt"></i>'

                                                        . '</div>'

                                                        . '<input type="text" class="form-control accounts-manager-groups-enter-group-name" placeholder="' . $this->CI->lang->line('enter_group_name') . '" required>'

                                                    . '</div>'

                                                    . '<div class="col-xl-5 col-sm-5 col-5">'

                                                        . '<button type="submit" class="accounts-manager-groups-save-group">'

                                                            . '<i class="far fa-save"></i> ' . $this->CI->lang->line('save_group')

                                                        . '</button>'

                                                    . '</div>'

                                                . '</div>'

                                                . '<div class="row accounts-manager-groups-select-group">'

                                                    . '<div class="col-xl-12">'

                                                        . '<div class="btn-group">'

                                                            . '<button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'

                                                                . $this->CI->lang->line('select_group')

                                                            . '</button>'

                                                            . '<div class="dropdown-menu dropdown-menu-right">'

                                                                . $groups

                                                            . '</div>'

                                                        . '</div>'

                                                    . '</div>'

                                                    . '<div class="col-xl-12 clean">'

                                                        . '<ul class="accounts-manager-groups-available-accounts">'

                                                        . '</ul>'

                                                    . '</div>'

                                                    . '<div class="col-xl-12">'

                                                        . '<button type="button" class="btn btn-danger accounts-manager-delete-group">'

                                                            . '<i class="icon-trash"></i> ' . $this->CI->lang->line('delete_group')

                                                        . '</button>'

                                                    . '</div>'                                               

                                                . '</div>'

                                            . '</div>'

                                            . form_close()

                                        . '</div>'

                                    . '</div>'

                                . '</div>'

                            . '</div>';

            

            // Send success message

            $data = array(

                'success' => TRUE,

                'social_data' => $social_data,

                'groups_data' => $groups_data

            );



            echo json_encode($data);

        

        }

        

    }  



    /**============================= CLIENT_LINK =============================== */

    /**

     * The public method get_accounts gets social network's accounts

     * 

     * @since 0.0.7.4

     * 

     * @return void

     */ 

    public function save_redirect_url() {

        $data['status'] = 'error';

        $data['msg'] = '';



        $redirect_url = $this->CI->input->get('redirect');
        
        $group_id = $this->CI->input->get('group_id');

        $has_redirect = $this->CI->db->from('client_links')->select('redirect_link')->where('user_id', $this->CI->user_id)->where('group_id', $group_id)->get()->num_rows();

        if($has_redirect > 0) {
            $result = $this->CI->db->from('client_links')->set('redirect_link', $redirect_url)->where('user_id', $this->CI->user_id)->where('group_id', $group_id)->update();
        } else {
            $insert_data = array(
                'user_id' => $this->CI->user_id,
                'group_id' => $group_id,
                'redirect_link' => $redirect_url
            );

            $result = $this->CI->db->insert('client_links', $insert_data);
        }


        if($result) {

            $data['status'] = 'success';   

            $data['msg'] = 'Successfully saved!';

        } else {

            $data['msg'] = 'Redirect url save failed! Contact developer team!';

        }



        echo json_encode($data);

    }   

    public function get_redirect_url() {
        $data['status'] = 'error';

        $group_id = $this->CI->input->get('group_id');


        $result = $this->CI->db->from('client_links')->select('redirect_link')->where('user_id', $this->CI->user_id)->where('group_id', $group_id)->get()->result();
        
        if(!empty($result)) {
            $data['redirect_link'] = $result[0]->redirect_link;
        } else {
            $data['redirect_link'] = '';
        }
        
        $data['status'] = 'success';   

        echo json_encode($data);
    }

    /**============================= /CLIENT_LINK =============================== */



    /**

     * The public method get_accounts gets social network's accounts

     * 

     * @since 0.0.7.4

     * 

     * @return void

     */ 

    public function get_accounts() {

        

        // Get network's input

        $network = $this->CI->input->get('network', TRUE);

        

        // Get type's input

        $type = $this->CI->input->get('type', TRUE);

        

        if ( $network && $type ) {

            

        $data = array(

            'success' => TRUE,

            'type' => $type

        );

        

        if ( $type === 'accounts_manager' ) {

            

            $settings = (new MidrubBaseUserAppsCollectionHelpers\Accounts)->get_network_info($network);

            

            $icon = '';

            

            if ( $settings ) {

                $icon = $settings['icon'];

                $data['hidden'] = $settings['hidden'];

            } else {

                $data['hidden'] = '';

            }

            

            $display_hidden_content = '';

            

            if ( $data['hidden'] ) {

                $display_hidden_content = ' manage-accounts-display-hidden-content';

            }

            

            $connect_button_text = (isset($settings['custom_connect']))?$settings['custom_connect']:$this->CI->lang->line('new_account');

            

            $data['active'] = (new MidrubBaseUserAppsCollectionHelpers\Accounts)->get_active_accounts($this->CI->networks_model->get_accounts_by_network( $this->CI->user_id, $network, 0 ), 0) . (new MidrubBaseUserAppsCollectionHelpers\Accounts)->get_active_accounts($this->CI->networks_model->get_accounts_by_network( $this->CI->user_id, $network, 1 ), 1);

            $data['instructions'] = (new MidrubBaseUserAppsCollectionHelpers\Accounts)->get_network_instructions($network);

            $data['search_form'] = '<div class="col-xl-7">'

                                        . '<div class="input-group accounts-manager-search">'

                                            . '<div class="input-group-prepend">'

                                                . '<i class="icon-magnifier"></i>'

                                            . '</div>'

                                            . '<input type="text" class="form-control accounts-manager-search-for-accounts" data-network="' . $network . '" placeholder="' . $this->CI->lang->line('search_for_accounts')  . '">'

                                            . '<button type="button" class="cancel-accounts-manager-search input-group-append">'

                                            . '<i class="icon-close"></i>'

                                        . '</button>'

                                        . '</div>'

                                    . '</div>'

                                    . '<div class="col-xl-5">'

                                        . '<button type="button" class="manage-accounts-new-account' . $display_hidden_content . '">'

                                            . $icon . ' ' . $connect_button_text

                                        . '</button>'

                                    . '</div>';

            /**========================= CLIENT_LINK ================================ */                        

            $lists = $this->CI->lists_model->get_groups($this->CI->user_id, 0, 10);



            $first_group_id = $this->CI->lists_model->get_groups($this->CI->user_id, 0, 1);

            

            if(!empty($first_group_id)) {

                $first_group_id = $first_group_id[0]->list_id;

            } else {

                $first_group_id = 0;

            }

            $redirect_link = '';
            

            $link_option = '<hr/><h5><strong>Client Link</strong><h5/>'

                            . '<p>Send link to clients to connect their social account to add to your selected group without needing their login credentials.</p><br>'

                            . '<h6><strong>Group</strong></h6>';

            if(!$lists) {

                $link_option .= '<p>Sorry! We can not find any group. Please make new group for sharing your link with clients!</p>';

            } else {

                $link_option .= '<select class="form-control" id="external_group_select">';

                foreach($lists as $list) {

                    $link_option .= '<option value=' . $list->list_id . '>' . $list->name . '</option>';

                }

    

                $link_option .= '</select><br>'; 

                $link_option .= '<div class="login-link-input">'

                                    . '<h6><strong>Connect</strong><br><small class="text-muted">Share this link with clients to connect there account</small></h6>'

                                    . '<input type="text" class="form-control col-xs-5" name="login_link" value="' . site_url('user/app/posts?id=') . $this->CI->user_id . '&n=' . $network . '&g=' . $first_group_id . '" readonly>'

                                    . '<input type="hidden" id="logined_user_id" value="' . $this->CI->user_id . '">'

                                    . '<input type="hidden" id="link_network" value="' . $network . '">'

                                . '</div>'

                                . '<br/>'

                                . '<div class="redirect-link-input">'

                                    . '<h6><strong>Redirect</strong><br><small class="text-muted">The URL they will be redirected after they connect (e.g. WhatsApp support link?)</small></h6>'

                                    . '<input type="text" class="form-control col-xs-5" name="redirect_link" value="' . $redirect_link . '">'

                                . '</div>'

                                . '<button class="btn btn-primary save-redirect-btn" style="float: right; margin-top: 10px;"><i class="fa fa-save"></i> Save</button>';

            }



            $data['client_link'] = $link_option;



        } else {



        /**========================= /CLIENT_LINK ================================ */ 



                $data['active'] = (new MidrubBaseUserAppsCollectionHelpers\Accounts)->get_active_accounts($this->CI->networks_model->get_accounts_by_network( $this->CI->user_id, $network, 1 ), 2);



            }



            echo json_encode($data);

        

        } else {

            

            $data = array(

                'success' => FALSE,

                'message' => $this->CI->lang->line('error_occurred')

            );



            echo json_encode($data);  

            

        }

        

    }  

    

}



/* End of file accounts_manager.php */