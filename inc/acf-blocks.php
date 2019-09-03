<?php
/**
 * Register Blocks
 *
 */
 
// Add Custom Blocks Panel in Gutenberg
function tkmb_block_categories( $categories, $post ) {
    return array_merge(
        $categories,
        array(
            array(
                'slug' => 'tkmb-blocks',
                'title' => __( 'TKM Blocks', 'acfblocks-master' ),
            ),
        )
    );
}
add_filter( 'block_categories', 'tkmb_block_categories', 10, 2 ); 
 
// Register Blocks
add_action('acf/init', 'tkmb_blocks');
function tkmb_blocks() {

    // check function exists.
    if( function_exists('acf_register_block_type') ) {

        // register a testimonial block.
        acf_register_block_type(array(
            'name'              => 'tkmb-testimonial',
            'mode'				=> 'preview',
            'title'             => __('Testimonial'),
            'description'       => __('Let others know what your clients or customers say about you.'),
            'render_template'   => plugin_dir_path( __FILE__ ) . 'block-templates/testimonial.php',
            'category'          => 'tkmb-blocks',
            'icon'              => '<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"></defs><title/><g data-name="22-chat" id="_22-chat"><polygon class="acfb_svg_icon" points="31 3 1 3 1 23 8 23 14 29 14 23 31 23 31 3"/><line class="acfb_svg_icon" x1="7" x2="25" y1="9" y2="9"/><line class="acfb_svg_icon" x1="7" x2="25" y1="13" y2="13"/><line class="acfb_svg_icon" x1="7" x2="25" y1="17" y2="17"/></g></svg>',
            'enqueue_assets' => function(){
			  wp_enqueue_style( 'acfb-blocks-css', plugin_dir_url( __FILE__ ) . 'css/acfblocks.css' );
			},
        )); 

?>
