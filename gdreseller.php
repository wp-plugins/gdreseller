<?php
/*  <WP plugin data>
 *  Plugin Name:   GDReseller Version
 *  Plugin URI: https://www.in-design.com/GDReseller
 *  Donate URI: https://www.in-design.com/GDReseller
 *  Description: Allow Godaddy resellers to redirect product sales and perform domain name search through their reseller
 *  storefront
 *  Author: Intuitive Design
 *  Author URI: https://www.in-design.com/
 *  Version: 1.3
 *
 *  GDReseller is a plugin designed out of the need for Godaddy resellers needing an easy way to connect their WP site to
 *  their Godaddy Reseller Storefront. This allows the sending of products and doing domain name searches easily from
 *  your WP site to be performed at your Godaddy Storefront.
 *
 *  GDReseller is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

define( "PLUGIN_PATH", plugin_dir_url( __FILE__ ) );

require_once 'gdr_tax.php';

register_activation_hook( __FILE__, 'activatePlugin' );

function activatePlugin(){
    update_option('footer_text','1');
}
add_action('admin_enqueue_scripts','admin_scripts');

function admin_scripts(){
    wp_enqueue_script( 'jquery' );
    wp_enqueue_script('jscript', PLUGIN_PATH . 'js/jscript.js' );
}

function gdr_post_type() {

// Set UI labels for Custom Post Type
	$labels = array(
		'name'                => __( 'GD Products' ),
		'singular_name'       => __( 'GD Product' ),
		'menu_name'           => __( 'GD Products' ),
		'parent_item_colon'   => __( 'Parent GodaddyReseller' ),
		'all_items'           => __( 'All GD Products' ),
		'view_item'           => __( 'View GD Products' ),
		'add_new_item'        => __( 'Add New GD Product' ),
		'add_new'             => __( 'Add New'),
		'edit_item'           => __( 'Edit GD Product' ),
		'update_item'         => __( 'Update GD Product' ),
		'search_items'        => __( 'Search GD Products'),
		'not_found'           => __( 'GD Product Not Found'),
		'not_found_in_trash'  => __( 'GD Product Not found in Trash'),
	);
	
// Set other options for Custom Post Type
	
	$args = array(
		'label'               => __( 'gdr_form'),
		'description'         => __( 'GodaddyResellers'),
		'labels'              => $labels,
		// Features this CPT supports in Post Editor
		'supports'            => array( 'title', 'editor', 'thumbnail', 'revisions' ),
		// You can associate this CPT with a taxonomy or custom taxonomy. 
		'taxonomies'          => array( 'genres' ),
		/* A hierarchical CPT is like Pages and can have
		* Parent and child items. A non-hierarchical CPT
		* is like Posts.
		*/	
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 20,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'page',
	);
	
	 // Registering your Custom Post Type
	register_post_type( 'gdr_form', $args );

}

/* Hook into the 'init' action so that the function
* Containing our post type registration is not 
* unnecessarily executed. 
*/

add_action( 'init', 'gdr_post_type');
add_action( 'admin_init', 'custom_fields' );

function custom_fields() {
    add_meta_box( 'product_meta_box',
        'Product Details',
        'gdr_display_meta_box',
        'gdr_form', 'normal', 'high'
    );
    add_meta_box( 'donate_meta_box',
        'Donate',
        'gdr_donate_meta_box',
        'gdr_form', 'side', 'high'
    );
}
function gdr_donate_meta_box($product_form){
    echo '<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
	    <input type="hidden" name="cmd" value="_s-xclick"/>
	    <input type="hidden" name="hosted_button_id" value="NUUYQ5PSRXP22"/>
	    <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!"/>
	    <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1"/>
	    </form>';
}
function gdr_display_meta_box( $product_form ) {
    $product_form_form_title = esc_html( get_post_meta( $product_form->ID, 'product_form_form_title', true ) );
    $product_form_form_desc = esc_html( get_post_meta( $product_form->ID, 'product_form_form_desc', true ) );
    $product_form_form_url = esc_html( get_post_meta( $product_form->ID, 'product_form_form_url', true ) );
    $product_form_count = esc_html( get_post_meta( $product_form->ID, 'product_form_count', true ) );
    $product_form_qnt = esc_html( get_post_meta( $product_form->ID, 'product_form_qnt', true ) );
    $product_form_item = esc_html( get_post_meta( $product_form->ID, 'product_form_item', true ) );
    $product_form_reseller_id = esc_html( get_post_meta( $product_form->ID, 'product_form_reseller_id', true ) );
    if($product_form_reseller_id == ""){
	$product_form_reseller_id = get_option( 'reseller_id' );
    }
    $product_form_values =  get_post_meta( $product_form->ID, 'product_form_price_value',true);
    $product_form_names =  get_post_meta( $product_form->ID, 'product_form_price_name' ,true);
    echo '<input type="hidden" name="product_form_noncename" id="product_form_noncename" value="' . 
	wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
    ?>
    <table>
        <tr>
            <td style="width: 100%">Form Title</td>
            <td><input type="text" size="80" name="product_form_form_title" value="<?php echo $product_form_form_title; ?>" /></td>
        </tr>
        <tr>
            <td style="width: 100%">Form Description</td>
            <td><input type="text" size="80" name="product_form_form_desc" value="<?php echo $product_form_form_desc; ?>" /></td>
        </tr>
        <tr>
            <td style="width: 100%">Form Action Url</td>
            <td><input type="text" size="80" name="product_form_form_url" value="<?php echo $product_form_form_url; ?>" /></td>
        </tr>
        <tr>
            <td style="width: 100%">Count</td>
            <td><input type="text" size="80" name="product_form_count" value="<?php echo $product_form_count; ?>" /></td>
        </tr>
        <tr>
            <td style="width: 100%">Quantity</td>
            <td><input type="text" size="80" name="product_form_qnt" value="<?php echo $product_form_qnt; ?>" /></td>
        </tr>
        <tr>
            <td style="width: 100%">Item</td>
            <td><input type="text" size="80" name="product_form_item" value="<?php echo $product_form_item; ?>" /></td>
        </tr>
        <tr>
            <td style="width: 100%">Reseller ID</td>
            <td><input type="text" size="80" name="product_form_reseller_id" value="<?php echo $product_form_reseller_id; ?>" /></td>
        </tr>
	<tr>
            <td style="width: 100%">Price Options</td>
	    <td id="gdr_add_more_tr">
		<?php
		if(count($product_form_values) > 1){
		$i=0;
		foreach($product_form_values as $product_form_value) {
		        
		?>
		<div>Value:<input type="text" size="10" name="product_form_price_value[]" value="<?php echo $product_form_value; ?>" /> Name:<input type="text" size="10" name="product_form_price_name[]" value="<?php echo $product_form_names[$i]; ?>" />
		<?php if($i == 0){ echo  '<button id="gdr_add_more">Add more</button></div>';} else {echo  '<button class="gdr_delete">Delete</button></div>';}
		$i++;
		} } else { ?>
		    <div>Value:<input type="text" size="10" name="product_form_price_value[]" value="" /> Name:<input type="text" size="10" name="product_form_price_name[]" value="" /><button id="gdr_add_more">Add more</button></div>
		<?php   }?>
	    </td>
	</tr> 
    </table>
    <?php
}
add_action( 'save_post', 'gdr_add_form_fields', 10, 2 );
function gdr_add_form_fields( $product_form_id, $product_form) {

    if ( $product_form->post_type == 'gdr_form' ) {
        // Store data in post meta table if present in post data
        if ( isset( $_POST['product_form_form_title'] ) && $_POST['product_form_form_title'] != '' ) {
            update_post_meta( $product_form_id, 'product_form_form_title', $_POST['product_form_form_title'] );
        }
        if ( isset( $_POST['product_form_form_desc'] ) && $_POST['product_form_form_desc'] != '' ) {
            update_post_meta( $product_form_id, 'product_form_form_desc', $_POST['product_form_form_desc'] );
        }
        if ( isset( $_POST['product_form_form_url'] ) && $_POST['product_form_form_url'] != '' ) {
            update_post_meta( $product_form_id, 'product_form_form_url', $_POST['product_form_form_url'] );
        }
        if ( isset( $_POST['product_form_count'] ) && $_POST['product_form_count'] != '' ) {
            update_post_meta( $product_form_id, 'product_form_count', $_POST['product_form_count'] );
        }
        if ( isset( $_POST['product_form_qnt'] ) && $_POST['product_form_qnt'] != '' ) {
            update_post_meta( $product_form_id, 'product_form_qnt', $_POST['product_form_qnt'] );
        }
        if ( isset( $_POST['product_form_item'] ) && $_POST['product_form_item'] != '' ) {
            update_post_meta( $product_form_id, 'product_form_item', $_POST['product_form_item'] );
        }
        if ( isset( $_POST['product_form_reseller_id'] ) && $_POST['product_form_reseller_id'] != '' ) {
            update_post_meta( $product_form_id, 'product_form_reseller_id', $_POST['product_form_reseller_id'] );
        }
	if ( isset( $_POST['product_form_price_value'] ) && $_POST['product_form_price_value'] != '' ) {
            update_post_meta( $product_form_id, 'product_form_price_value', $_POST['product_form_price_value'] );
        }
	if ( isset( $_POST['product_form_price_name'] ) && $_POST['product_form_price_name'] != '' ) {
            update_post_meta( $product_form_id, 'product_form_price_name', $_POST['product_form_price_name'] );
        }
    }
}
function gdr_form_columns($columns) {
       unset($columns['date']);
	$new_columns = array(
                'product_id' => __('Product Id'),
		'form_title' => __('Form Title'),
		'form_desc' => __('Form Desc'),
                'count' => __('Count'),
                'item' => __('Item'),
                'reseller_id' => __('Reseller Id'),
                'shortcode' => __('Shortcode')
	);
    return array_merge($columns, $new_columns);
}
add_filter('manage_gdr_form_posts_columns' , 'gdr_form_columns');

add_action( 'manage_gdr_form_posts_custom_column', 'gdr_manage_form_columns', 10, 2 );

function gdr_manage_form_columns( $column, $post_id ) {

	global $post;

	switch( $column ) {
                
                case 'product_id' :

                echo $post_id;
		break;
                    
		case 'form_title' :

			/* Get the post meta. */
			$duration = get_post_meta( $post_id, 'product_form_form_title', true );

			if ( empty( $duration ) )
				echo __( 'Unknown' );
                        else
                            echo $duration;
			break;

		case 'form_desc' :

			$duration = get_post_meta( $post_id, 'product_form_form_desc', true );

			if ( empty( $duration ) )
				echo __( 'Unknown' );
                        else
                            echo $duration;        

			break;
		case 'count' :

			$duration = get_post_meta( $post_id, 'product_form_count', true );

			if ( empty( $duration ) )
				echo __( 'Unknown' );
                        else
                            echo $duration;        

			break;
		case 'item' :

			$duration = get_post_meta( $post_id, 'product_form_item', true );

			if ( empty( $duration ) )
				echo __( 'Unknown' );
                        else
                            echo $duration;        

			break;
		case 'reseller_id' :

			$duration = get_post_meta( $post_id, 'product_form_reseller_id', true );

			if ( empty( $duration ) )
				echo __( 'Unknown' );
                        else
                            echo $duration;        

			break;
	         case 'shortcode' :

                        echo '[product id="'.$post_id.'"]';        

			break;        

		default :
			break;
	}
}
function gdr_product_func( $atts ) {
    global $wpdb;
    $get_id = $atts['id'];
    $footer_text = '<div class="branding">Plugin designed by <a href="http://netforcelabs.com/">Netforce Labs</a> and commissioned by <a href="https://www.in-design.com">Intuitive Design</a>. More information can be found <a href="https://www.in-design.com/gdreseller">here</a>.</div>';
    $get_title = get_post_meta( $get_id, 'product_form_form_title', true );
    $get_desc = get_post_meta( $get_id, 'product_form_form_desc', true );
    $get_count = get_post_meta( $get_id, 'product_form_count', true );
    $get_item = get_post_meta( $get_id, 'product_form_item', true );
    $get_resler_id = get_post_meta( $get_id, 'product_form_reseller_id', true );
    $get_url = get_post_meta( $get_id, 'product_form_form_url', true );
    $product_form_values =  get_post_meta( $get_id, 'product_form_price_value',true);
    $product_form_names =  get_post_meta( $get_id, 'product_form_price_name' ,true);

    $get_form =  '<div class="form_container">';
    $get_form .=  '<h3>'.$get_title.'</h3>';
    $get_form .= '<p>'.$get_desc.'</p>';
    $get_form .= '<form method="post" action="'.$get_url.'" target="_blank">';
    $get_form .= '<input type="hidden" name="tcount" value="1">';
    $get_form .=  '<input type="hidden" name="qty_1" value="1">';
    $get_form .= '<input type="hidden" name="item_1" value="1">';
    $get_form .= '<input type="hidden" name="prog_id" value="'.$get_resler_id.'">';
    $get_form .= '<select name="pf_id_1">';
	if(count($product_form_values) > 1){
	    $i=0;
	    foreach($product_form_values as $product_form_value) {
		$get_form .=   '<option value="'.$product_form_value.'"> '.$product_form_names[$i].'</option>';
		$i++;
	    }
	    
	}    
    $get_form .=  '</select>';
    $get_form .= '<input type="submit" value="Add to Cart">';
    $get_form .= '</form>';
    if(get_option('footer_text')){
    $get_form .= '<div style="font-size: 75%; background-color: black; color: grey">'.$footer_text;
    $get_form .= '</div>';
    }

    $get_form .= '</div>';
    return $get_form;
}
add_shortcode( 'product', 'gdr_product_func' );
function gdr_domain_search_func($atts){
    global $wpdb;
    $footer_text = '<div class="branding">Plugin designed by <a href="http://netforcelabs.com/">Netforce Labs</a> and commissioned by <a href="https://www.in-design.com">Intuitive Design</a>. More information can be found <a href="https://www.in-design.com/gdreseller">here</a>.</div>';

    $get_id = $atts['id'];
    $get_form_title = $atts['title'];
    $get_form_desc = $atts['desc'];
    $get_form =  '<div class="form_container">';
    $get_form .=  '<h3>'.$get_form_title.'</h3>';
    $get_form .= '<p>'.$get_form_desc.'</p>';
    $get_form .= '<form method="post" action="http://secure.cheapdomainregistration.xyz/domains/search.aspx?prog_id='.$get_id.'" target="_blank">';
    $get_form .= '<input style="display: inline; width: 317px; border:1px solid blue; border-radius: 6px 0px 0px 6px; " maxlength="63" name="domainToCheck" type="text" placeholder="Grab your domain now!" />';
    $get_form .= '<input style="display: inline; text-align: center; width: 15%; height: 48px; border: 1px solid #333333; margin-top: 0px; background: blue; border-radius: 0px 6px 6px 0px; color: #ffffff;" name="submit" type="submit" value="GO!" />';
    $get_form .=  '<input name="checkAvail" type="hidden" value="1"/> 
	    <input name="JScriptOn" type="hidden" value="yes"/> ';
    if(get_option('footer_text')){
    $get_form .= '<div style="font-size: 75%; background-color: black; color: grey">'.$footer_text;
    $get_form .= '</div>';
    }
    $get_form .= '</div>';
    return $get_form;

}
add_shortcode( 'domain_search', 'gdr_domain_search_func' );

// Creating the widget 
class gdr_domain_form_widget extends WP_Widget {

function __construct() {
parent::__construct(
// Base ID of your widget
'gdr_domain_form_widget', 

// Widget name will appear in UI
__('Domain Search Widget', 'domain_form_widget_domain'), 

// Widget description
array( 'description' => __( 'Basic Domain Search Widget', 'domain_form_widget_domain' ), ) 
);
}

// Creating widget front-end
// This is where the action happens
public function widget( $args, $instance ) {
$title = apply_filters( 'widget_title', $instance['title'] );
$desc = apply_filters( 'widget_desc', $instance['desc'] );
$reseller_id = apply_filters( 'widget_reseller_id', $instance['reseller_id'] );
$url = apply_filters( 'widget_url', $instance['url'] );
$footer_text = '<div class="branding">Plugin designed by <a href="http://netforcelabs.com/">Netforce Labs</a> and commissioned by <a href="https://www.in-design.com">Intuitive Design</a>. More information can be found <a href="https://www.in-design.com/gdreseller">here</a>.</div>';
// before and after widget arguments are defined by themes
echo $args['before_widget'];
if ( ! empty( $title ) )
echo $args['before_title'] . $title . $args['after_title'];

// This is where you run the code and display the output

    echo  '<p>'.$desc.'</p>';
    echo  '<form method="post" action="'.$url.$reseller_id.'" target="_blank">';
    echo  '<input style="display: inline; width: 317px; border:1px solid blue; border-radius: 6px 0px 0px 6px; " maxlength="63" name="domainToCheck" type="text" placeholder="Grab your domain now!" />';
    echo  '<input style="display: inline; text-align: center; width: 15%; height: 48px; border: 1px solid #333333; margin-top: 0px; background: blue; border-radius: 0px 6px 6px 0px; color: #ffffff;" name="submit" type="submit" value="GO!" /></form>';
    echo  '<input name="checkAvail" type="hidden" value="1"/> 
	    <input name="JScriptOn" type="hidden" value="yes"/> ';
    if(get_option('footer_text')){
	echo  '<div style="font-size: 75%; background-color: black; color: grey">'.$footer_text;
	echo  '</div>';
    }
echo $args['after_widget'];
}
		
// Widget Backend 
public function form( $instance ) {
if ( isset( $instance[ 'title' ] ) ) {
$title = $instance[ 'title' ];
}
else {
$title = __( 'New title', 'domain_form_widget_domain' );
}
if ( isset( $instance[ 'desc' ] ) ) {
$desc = $instance[ 'desc' ];
}
else {
$desc = __( ' ', 'domain_form_widget_domain' );
}
if ( isset( $instance[ 'reseller_id' ] ) ) {
$reseller_id = $instance[ 'reseller_id' ];
}
else {
$reseller_id = __( ' ', 'domain_form_widget_domain' );
}
if ( isset( $instance[ 'url' ] ) ) {
$url = $instance[ 'url' ];
}
else {
$url = __( ' ', 'domain_form_widget_domain' );
}
// Widget admin form
?>
<p>
<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Form Title:' ); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
<label for="<?php echo $this->get_field_id( 'desc' ); ?>"><?php _e( 'Form Description:' ); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id( 'desc' ); ?>" name="<?php echo $this->get_field_name( 'desc' ); ?>" type="text" value="<?php echo esc_attr( $desc ); ?>" />
<label for="<?php echo $this->get_field_id( 'url' ); ?>"><?php _e( 'Form Url:' ); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id( 'url' ); ?>" name="<?php echo $this->get_field_name( 'url' ); ?>" type="text" value="<?php echo esc_attr( $url ); ?>" />

<label for="<?php echo $this->get_field_id( 'reseller_id' ); ?>"><?php _e( 'Reseller Id:' ); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id( 'reseller_id' ); ?>" name="<?php echo $this->get_field_name( 'reseller_id' ); ?>" type="text" value="<?php echo esc_attr( $reseller_id ); ?>" />
</p>
<?php 
}
	
// Updating widget replacing old instances with new
public function update( $new_instance, $old_instance ) {
$instance = array();
$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
$instance['desc'] = ( ! empty( $new_instance['desc'] ) ) ? strip_tags( $new_instance['desc'] ) : '';
$instance['url'] = ( ! empty( $new_instance['url'] ) ) ? strip_tags( $new_instance['url'] ) : '';
$instance['reseller_id'] = ( ! empty( $new_instance['reseller_id'] ) ) ? strip_tags( $new_instance['reseller_id'] ) : '';
return $instance;
}
}
function gdr_domain_form_widget_load_widget() {
    register_widget( 'gdr_domain_form_widget' );
}
add_action( 'widgets_init', 'gdr_domain_form_widget_load_widget' );

//group reseller taxonomy
add_action( 'init', 'grd_taxonomy', 0 );

//add menu 
add_action( 'admin_menu', 'gdr_menu' );

function gdr_menu() {
    add_options_page( 'GD Options', 'GD Options', 'manage_options', 'gdr_options', 'gdr_menu_options' );
}
function gdr_menu_options(){
    global $wpdb;
    if(isset($_POST['form_submit'])){
	$footer_text = $_POST['footer_text'];
	$reseller_id = $_POST['reseller_id'];
	update_option('footer_text',$footer_text);
	update_option('reseller_id',$reseller_id);
	$get_results = $wpdb->get_results("select * from {$wpdb->prefix}posts where post_type='gdr_form'");
	foreach($get_results as $get_result){
	    update_post_meta($get_result->ID,'product_form_reseller_id',$reseller_id);
	}
    }
    $reseller_id= get_option('reseller_id');
    $footer_text = get_option('footer_text');
    if(isset($footer_text) && $footer_text== '1'){
	$check = 'checked';
    }
    else{
	$check = '';
    }
    echo '<div>';
    echo '<h1>GD Options</h1>';
    echo '<form id="gdr_options_form" action="" method="post">
	    <table class="form-table">
		<tr><th scope="row">
		    <label for="reseller_id">Reseller Id</label>
		    </th>
		    <td>
		    <input type="text" id="reseller_id" name="reseller_id" value="'.$reseller_id.'">
		    </td>
		</tr>
		<tr><th scope="row">
		    <label for="footer_text">Allow Footer Text</label>
		    </th>
		    <td>
			<input type="checkbox" name="footer_text" value="1" '.$check.'> (Please consider supporting us by donating or atleast allowing our code to be shown on your site.)
		    </td>
		</tr>
	    </table>
	    <p class="submit">
		<input name="form_submit" type="submit" value="Submit" class="button button-primary">
	    </p>
	    </form>';
    echo '<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
	    <input type="hidden" name="cmd" value="_s-xclick"/>
	    <input type="hidden" name="hosted_button_id" value="NUUYQ5PSRXP22"/>
	    <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!"/>
	    <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1"/>
	    </form>';    
    echo '</div>';
}
?>