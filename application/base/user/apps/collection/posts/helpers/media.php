<?php

/**

 * Media Helpers

 *

 * This file contains the class Media

 * with methods to upload and load medias from the database

 *

 * @author Scrisoft

 * @package Midrub

 * @since 0.0.8.3

 */



// Define the page namespace

namespace MidrubBase\User\Apps\Collection\Posts\Helpers;



// Constants

defined('BASEPATH') OR exit('No direct script access allowed');



// Define the namespaces to use

use MidrubBase\Classes\Media as MidrubBaseClassesMedia;



/*

 * Media class provides the methods to upload and load medias from the database

 * 

 * @author Scrisoft

 * @package Midrub

 * @since 0.0.8.3

*/

class Media {

    

    /**

     * Class variables

     *

     * @since 0.0.8.3

     */

    protected $CI;



    /**

     * Initialise the Class

     *

     * @since 0.0.8.3

     */

    public function __construct() {

        

        // Get codeigniter object instance

        $this->CI =& get_instance();

        

    }



    /**

     * The public method upload_media_in_storage uploads media in the user storage

     *

     * @since 0.0.8.3

     * 

     * @return void

     */

    public function upload_media_in_storage() {

        

        // Load Media Model

        $this->CI->load->model('media');

        

        // Verify if post data was sent

        if ( $this->CI->input->post() ) {



            // Add form validation

            $this->CI->form_validation->set_rules('cover', 'Cover', 'trim|required');



            // Get data

            $cover = $this->CI->input->post('cover');



            // Verify if data is correct

            if ( ( $this->CI->form_validation->run() !== false )  ) {

                

                // Upload media

                (new MidrubBaseClassesMedia\Upload)->upload(array(

                    'cover' => $cover,

                    'allowed_extensions' => array('image/png', 'image/jpeg', 'image/gif', 'video/mp4', 'video/webm', 'video/avi')

                ));

                exit();



            }

            

        }



        // Prepare the no media message

        $data = array(

            'success' => FALSE,

            'message' => $this->CI->lang->line('error_occurred')

        );



        // Display the no media message

        echo json_encode($data);  

        

    }    

    

    /**

     * The public method load_medias_by_page loads medias by page

     *

     * @since 0.0.8.3

     * 

     * @return void

     */

    public function load_medias_by_page() {

        

        // Check if data was submitted

        if ( $this->CI->input->post() ) {

            

            // Add form validation

            $this->CI->form_validation->set_rules('page', 'Page', 'trim|numeric|required');

            

            // Get data

            $page = $this->CI->input->post('page');

            

            // Verify if the request is good

            if ( $this->CI->form_validation->run() !== false ) {

        

                // Set limit

                $limit = 16;

                

                // If page not exists will be 1

                if ( !$page ) {

                    $page = 1;

                }

                

                // Decrease the page

                $page--;



                // Use the base model for a simply sql query

                $get_medias = $this->CI->base_model->get_data_where(

                    'media',

                    '*',

                    array(

                        'user_id' => $this->CI->user_id

                    ),

                    array(),

                    array(),

                    array(),

                    array(

                        'order' => array('media_id', 'desc'),

                        'start' => ($page * 16),

                        'limit' => 16

                    )

                );

                

                // Verify for media

                if ( $get_medias ) {



                    // Use the base model for a simply sql query

                    $medias = $this->CI->base_model->get_data_where(

                        'media',

                        'COUNT(media_id) AS total',

                        array(

                            'user_id' => $this->CI->user_id

                        )

                    );

                    

                    // Prepare response

                    $data = array(

                        'success' => TRUE,

                        'total' => $medias[0]['total'],

                        'medias' => $get_medias,

                        'page' => ($page + 1)

                    );

                    

                    // Display the response

                    echo json_encode($data);

                    

                } else {

                    

                    // Prepare response

                    $data = array(

                        'success' => FALSE

                    );

                    

                    // Display the response

                    echo json_encode($data);

                    

                }



                exit();

                

            }

            

        }

        

        // Prepare the no media message

        $data = array(

            'success' => FALSE,

            'message' => $this->CI->lang->line('error_occurred')

        );



        // Display the no media message

        echo json_encode($data);  

        

    }



    /**

     * The public method delete_media deletes media

     *

     * @since 0.0.8.3

     * 

     * @return void

     */

    public function delete_media() {

        

        // Get media's input

        $media_id = $this->CI->input->get('media_id');

        

        // If any media missing returns error message

        if ( $media_id ) {

            

            // Delete media

            (new MidrubBaseClassesMedia\Delete)->delete_file(array(

                'media_id' => $media_id

            ));

            exit();

            

        }

        

        // Prepare the no media message

        $data = array(

            'success' => FALSE,

            'message' => $this->CI->lang->line('error_occurred')

        );



        // Display the no media message

        echo json_encode($data);  

        

    }



}



/* End of media.php */