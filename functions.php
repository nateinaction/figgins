<?php
/*
Plugin Name: Figgins
Description: "Self-education is, I firmly believe, the only kind of education there is." - Isaac Asimov
Version:     0.0.1
Author:      Nate Gay
Author URI:  https://worldpeace.io/
License:     GPL3
License URI: https://www.gnu.org/licenses/gpl-3.0.en.html
*/

/*
 * Add Student role on plugin activation
 */

function add_roles_on_plugin_activation() {
  add_role( 'figgins_student', 'Student', array( 'read' => true, 'level_0' => true ) );
}
register_activation_hook( __FILE__, 'add_roles_on_plugin_activation' );

/*
 * Add Lesson post type and Course taxonomy
 */

 function create_custom_post_type() {
   register_post_type( 'figgins_lesson',
     array(
       'labels' => array(
         'name' => __( 'Lessons' ),
         'singular_name' => __( 'Lesson' )
       ),
       'public' => true,
       'has_archive' => true,
       'rewrite' => array('slug' => 'lessons'),
       'menu_position' => 20,
       'menu_icon' => 'dashicons-book-alt'
     )
   );
 }
add_action( 'init', 'create_custom_post_type' );

function create_custom_taxonomy() {
  register_taxonomy( 'figgins_course', 'figgins_lesson',
    array(
      'labels' => array(
        'name' => __( 'Courses' ),
        'singular_name' => __( 'Course' )
      ),
      'public' => true,
      'rewrite' => array('slug' => 'courses'),
  	)
  );
}
add_action( 'init', 'create_custom_taxonomy' );
