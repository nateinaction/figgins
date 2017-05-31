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

/*
 * Check if users can register and if the default role is set to student
 */

function check_if_user_registration_enabled() {
  $registration = $wpdb->get_var( 'SELECT option_value FROM $wpdb->options WHERE option_name = "users_can_register"' );
  if ($registration == 1) {
    return true;
  }
  return false;
}
function set_user_registration_enabled() {
  $response = $wpdb->query('UPDATE $wpdb->options SET option_value = 1 WHERE option_name = "users_can_register"');
  return $response;
}

function check_if_default_role_is_student() {
  $role = $wpdb->get_var( 'SELECT option_value FROM $wpdb->options WHERE option_name = "default_role"' );
  if ($role == "figgins_student") {
    return true;
  }
  return false;
}
function set_default_role_to_student() {
  $response = $wpdb->query('UPDATE $wpdb->options SET option_value = "figgins_student" WHERE option_name = "default_role"');
  return $response;
}

/*
 * Redirect students when logging in
 */

function student_login_redirect( $redirect_to, $request, $user ) {
  if ( isset( $user->roles ) && is_array( $user->roles ) ) {
    if ( in_array( 'figgins_student', $user->roles ) ) {
    	return '/student';
    }
    return $redirect_to;
  }
  return $redirect_to;
}
add_filter( 'login_redirect', 'student_login_redirect', 10, 3 );

/*
 * Disable admin bar if not administrator
 */

function remove_admin_bar() {
  if (!current_user_can('administrator') && !is_admin()) {
    show_admin_bar(false);
  }
}
add_action('after_setup_theme', 'remove_admin_bar');
