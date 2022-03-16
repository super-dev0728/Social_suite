<?php
/**
 * Share Controller
 *
 * PHP Version 5.6
 *
 * Error_page contains the Error_page class to display errors page
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License
 * @link     https://www.midrub.com/
 */
if ( !defined('BASEPATH') ) {
    exit('No direct script access allowed');
}

/**
 * Share class - contains the method to display errors page
 *
 * @category Social
 * @package  Midrub
 * @author   Jackson
 * @license  https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License
 * @link     https://www.midrub.com/
 */
class Share extends MY_Controller {
    
    /**
     * Initialise the Error_page controller
     */
    public function __construct() {
        parent::__construct();
        
        // Load URL Helper
        $this->load->helper('url');
        
    }
    
    /**
     * The public method show_error displays an error's page
     * 
     * @param string $type contains the error's type
     * 
     * @return void
     */
    public function instagram() {
        $data['img'] = $this->input->get('p');
        
        $this->load->view('share/main', $data);
    }  
        
}

/* End of file Error.php */