<?php

//Add our custom template to the admin's templates dropdown
add_filter('theme_page_templates', 'pluginname_template_as_option', 10, 3);
function pluginname_template_as_option($page_templates, $theme, $post)
{
    $page_templates['templates/category_based.php'] = 'Viberent category-based layout';
    $page_templates['templates/item_based.php'] = 'Viberent item-based layout';

    return $page_templates;
}

//When our custom template has been chosen then display it for the page
add_filter('template_include', 'pluginname_load_template', 99);

function pluginname_load_template($template)
{

    global $post;
    global $wpdb;
    $post_slug = $post->post_name;
    $resulu = $wpdb->get_results("SELECT * from wp_viberent_layout");

    if (isset($resulu[0])) {   
        if ($resulu[0]->selected_layout == "category-based") {
            $custom_template_slug   = 'templates/category_based.php';
        } elseif ($resulu[0]->selected_layout == "item-based") {
            $custom_template_slug   = 'templates/item_based.php';
        }
    }
    
    $page_template_slug = get_page_template_slug($post->ID);

    if ($page_template_slug == $custom_template_slug) {
        return plugin_dir_path(__FILE__) . $custom_template_slug;
    }

    return $template;
}

if (is_admin()) {
  add_action('admin_menu', 'viberent_login_setup_menu');
}

function viberent_login_setup_menu()
{   
    if (function_exists('add_menu_page')) {
      add_menu_page('Viberent Login Page', 'Login To Viberent', 'manage_options', 'viberent-login', 'test_init');
    }
}
