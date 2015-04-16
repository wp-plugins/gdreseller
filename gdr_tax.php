<?php
function grd_taxonomy() {

// Add new taxonomy, make it hierarchical like categories
//first do the translations part for GUI

  $labels = array(
    'name' => _x( 'Reseller Groups', 'taxonomy general name' ),
    'singular_name' => _x( 'Reseller Group', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Reseller Group' ),
    'all_items' => __( 'All Reseller Groups' ),
    'parent_item' => __( 'Parent Reseller Group' ),
    'parent_item_colon' => __( 'Parent Reseller Group:' ),
    'edit_item' => __( 'Edit Reseller Group' ), 
    'update_item' => __( 'Update Reseller Group' ),
    'add_new_item' => __( 'Add New Reseller Group' ),
    'new_item_name' => __( 'New Reseller Group Name' ),
    'menu_name' => __( 'Reseller Groups' ),
  ); 	

// Now register the taxonomy

  register_taxonomy('reseller_group',array('gdr_form'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'reseller_group' ),
  ));

}
add_shortcode( 'group', 'gdr_taxonomy_func' );
function gdr_taxonomy_func($attrs){
    global $wpdb;
    $get_slug = $attrs['slug'];

        wp_reset_query();
        $args = array('post_type' => 'gdr_form',
            'tax_query' => array(
                array(
                    'taxonomy' => 'reseller_group',
                    'field' => 'slug',
                    'terms' => $get_slug,
                ),
            ),
         );
    
         $loop = new WP_Query($args);
         if($loop->have_posts()) {   
            while($loop->have_posts()) : $loop->the_post();
            $get_id =  $loop->post->ID;
            $atts['id']=$get_id;
            echo gdr_product_func($atts);
            endwhile;
         }

}
function gdr_reseller_columns($columns) {
	$new_columns = array(
                'shortcode' => __('Shortcode')
	);
    return array_merge($columns, $new_columns);
}
add_filter('manage_edit-reseller_group_columns' , 'gdr_reseller_columns');
add_filter("manage_reseller_group_custom_column", 'gdr_reseller_group_columns', 10, 3);
 
function gdr_reseller_group_columns($out, $column_name, $term_id) {
    $term = get_term($term_id, 'reseller_group');
    switch ($column_name) {
        case 'shortcode': 
            // get header image url
           echo '[group slug="'.$term->slug.'"]';
            break;
 
        default:
            break;
    }
    return $out;    
}
?>