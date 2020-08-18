<?php
/**
 * Child theme functions
 *
 * Functions file for child theme, enqueues parent and child stylesheets by default.
 *
 * @since	1.0.0
 * @package aa
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'aa_enqueue_styles' ) ) {
	// Add enqueue function to the desired action.
	add_action( 'wp_enqueue_scripts', 'aa_enqueue_styles' );

	/**
	 * Enqueue Styles.
	 *
	 * Enqueue parent style and child styles where parent are the dependency
	 * for child styles so that parent styles always get enqueued first.
	 *
	 * @since 1.0.0
	 */
	function aa_enqueue_styles() {
		// Parent style variable.
		$parent_style = 'parent-style';

		// Enqueue Parent theme's stylesheet.
		wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );

		// Enqueue Child theme's stylesheet.
		// Setting 'parent-style' as a dependency will ensure that the child theme stylesheet loads after it.
		wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array( $parent_style ) );

		if(is_page(49)) {
			wp_enqueue_script( 'highcharts', 'https://code.highcharts.com/highcharts.js');
			wp_enqueue_script( 'highcharts-variable-pie', 'https://code.highcharts.com/modules/variable-pie.js', array('jquery','highcharts'));
			wp_enqueue_script( 'highcharts-exporting', 'https://code.highcharts.com/modules/exporting.js', array('jquery','highcharts'));
			wp_enqueue_script( 'ajax-post', get_stylesheet_directory_uri() . '/scripts.js', array('jquery','highcharts','highcharts-variable-pie','highcharts-exporting'), 1.1);
		}
	}
}

// Register Custom Post Type
function custom_post_types() {

	$labels = array(
		'name'                  => 'Emotional',
		'singular_name'         => 'Emotional',
	);
	$args = array(
		'label'                 => 'Emotional',
		'labels'                => $labels,
		'supports'              => array( 'title'),
		'public'                => true,
		'show_in_rest'          => true,
		'menu_icon' => 'dashicons-heart'
	);
	register_post_type( 'emotional', $args );
	
	$labels = array(
		'name'                  => 'Congitive',
		'singular_name'         => 'Congitive',
	);
	$args = array(
		'label'                 => 'Congitive',
		'labels'                => $labels,
		'supports'              => array( 'title'),
		'public'                => true,
		'show_in_rest'          => true,
		'menu_icon' => 'dashicons-format-status'
	);
	register_post_type( 'cognitive', $args );
	
	$labels = array(
		'name'                  => 'Physical',
		'singular_name'         => 'Physical',
	);
	$args = array(
		'label'                 => 'Physical',
		'labels'                => $labels,
		'supports'              => array( 'title'),
		'public'                => true,
		'show_in_rest'          => true,
		'menu_icon' => 'dashicons-carrot'
	);
	register_post_type( 'physical', $args );
	
	$labels = array(
		'name'                  => 'Financial',
		'singular_name'         => 'Financial',
	);
	$args = array(
		'label'                 => 'Financial',
		'labels'                => $labels,
		'supports'              => array( 'title'),
		'public'                => true,
		'show_in_rest'          => true,
		'menu_icon' => 'dashicons-chart-line'
	);
	register_post_type( 'financial', $args );
	
	$labels = array(
		'name'                  => 'Spiritual',
		'singular_name'         => 'Spiritual',
	);
	$args = array(
		'label'                 => 'Spiritual',
		'labels'                => $labels,
		'supports'              => array( 'title'),
		'public'                => true,
		'show_in_rest'          => true,
		'menu_icon' => 'dashicons-admin-site'
	);
	register_post_type( 'spiritual', $args );

}
add_action( 'init', 'custom_post_types', 0 );

add_filter( 'allowed_http_origins', 'add_allowed_origins' );
function add_allowed_origins( $origins ) {
    $origins[] = 'http://localhost:3000';
    return $origins;
}

function add_cors_http_header(){
    header("Access-Control-Allow-Origin: *");
}
add_action('init','add_cors_http_header');


add_filter( 'rest_cognitive_collection_params', 'my_prefix_add_rest_orderby_params', 10, 1 );
add_filter( 'rest_emotional_collection_params', 'my_prefix_add_rest_orderby_params', 10, 1 );
add_filter( 'rest_physical_collection_params', 'my_prefix_add_rest_orderby_params', 10, 1 );
add_filter( 'rest_financial_collection_params', 'my_prefix_add_rest_orderby_params', 10, 1 );
add_filter( 'rest_spiritual_collection_params', 'my_prefix_add_rest_orderby_params', 10, 1 );

function my_prefix_add_rest_orderby_params( $params ) {
    $params['orderby']['enum'][] = 'menu_order';

    return $params;
}







//add entry results
function register_submission() {
	register_post_type ( 'fit-submission', 
	array (
		'label' => 'FIT Submissions',
		'menu_icon' => 'dashicons-list-view',
		'menu_position' => 3,
		'public' => false,
		'show_ui' => true,
		'show_in_menu' => true,
		'capability_type' => 'post',
		'capabilities' => array (
			'create_posts' => false 
		),
		'map_meta_cap' => true,
		'hierarchical' => false,
		'rewrite' => array (
			'slug' => 'fit-submissions',
			'with_front' => true 
		),
		'query_var' => false,
		'exclude_from_search' => true,
		'supports' => array (
			'custom-fields' 
		),
		'labels' => array (
			'name' => 'Submissions',
			'singular_name' => 'Submission',
		) 
	) );
}

add_action ( 'init', 'register_submission' );


add_filter ( 'manage_edit-fit-submission_columns', 'set_custom_edit_fit_submission_columns' );
add_action ( 'manage_fit-submission_posts_custom_column', 'custom_fit_submission_column', 10, 2 );

function set_custom_edit_fit_submission_columns($columns) {
	// unset ( $columns ['cb'] );
	unset ( $columns ['title'] );
	// unset ( $columns ['date'] );
	// $columns [DripFollowersConstants::COL_ORDER_CUSTOMER] = __ ( 'Customer' );
	// $columns [DripFollowersConstants::COL_ORDER_CONTACT_EMAIL] = __ ( 'Contact Email' );
	// $columns [DripFollowersConstants::COL_ORDER_SERVICE] = __ ( 'Service' );
	// $columns [DripFollowersConstants::COL_ORDER_PACK] = __ ( 'Pack' );
	// $columns [DripFollowersConstants::COL_ORDER_WITH_UPSELL] = __ ( 'Upsell' );
	// $columns [DripFollowersConstants::COL_ORDER_NUMBER] = __ ( 'Number' );
	// $columns [DripFollowersConstants::COL_ORDER_INITIAL_COUNT] = __ ( 'Init. Count' );
	// $columns [DripFollowersConstants::COL_ORDER_PROGRESS] = __ ( 'Progress' );
	// $columns [DripFollowersConstants::COL_ORDER_TARGET] = __ ( 'Target' );
	// $columns [DripFollowersConstants::COL_ORDER_PROVIDER] = __ ( 'Provider' );
	// $columns [DripFollowersConstants::COL_ORDER_PAYMENT_STATUS] = __ ( 'Payment Status' );
	// $columns [DripFollowersConstants::COL_ORDER_PAYMENT_AMOUNT] = __ ( 'Payment Amount' );
	// $columns [DripFollowersConstants::COL_ORDER_TASK_ID] = __ ( 'API Task ID' );
	// $columns [DripFollowersConstants::COL_ORDER_DATE] = __ ( 'Date' );
	// $columns [DripFollowersConstants::COL_ORDER_REMARKS] = __ ( 'Remarks' );
	$columns ['cognitive-score'] = 'Cognitive';
	$columns ['emotional-score'] = 'Emotional';
	$columns ['physical-score'] = 'Physical';
	$columns ['financial-score'] = 'Financial';
	$columns ['spiritual-score'] = 'Spiritual';

	$columns ['learning-strategies'] = '(C) Learning Strategies';
	$columns ['intellectual-engagement'] = '(C) Intellectual Engagement';
	$columns ['effort-control'] = '(C) Effort Control';
	$columns ['attention'] = '(C) Attention';
	$columns ['autonomy'] = '(C) Autonomy';
	$columns ['social-cognition'] = '(C) Social Cognition';

	$columns ['current-emotional-health'] = '(E) Current Emotional Health';
	$columns ['self-compassion'] = '(E) Self Compassion';
	$columns ['emotional-self-awareness'] = '(E) Emotional Self Awareness';
	$columns ['stress-resilience'] = '(E) Stress Resilience';
	$columns ['gratitude-positivity'] = '(E) Gratitude and Positivity';
	$columns ['social-support'] = '(E) Social Support';

	$columns ['bmi'] = '(P) BMI';
	$columns ['nutritional-implementation'] = '(P) Nutritional Implementation';
	$columns ['nutritional-knowledge'] = '(P) Nutritional Knowledge';
	$columns ['activity-level'] = '(P) Activity Level';
	$columns ['self-image'] = '(P) Self Image';
	$columns ['sleep-habits'] = '(P) Sleep Habits';
	$columns ['social-activity'] = '(P) Social Activity';

	$columns ['long-term'] = '(F) Long Term';
	$columns ['short-term'] = '(F) Short Term';
	$columns ['sadness'] = '(F) BMI';
	$columns ['hapiness'] = '(F) Hapiness';
	return $columns;
}

function custom_fit_submission_column($column, $post_id) {
	echo get_post_meta ( $post_id, $column, true );
}

function create_new_submission($scores){
	$order = array (
		'post_type' => 'fit-submission',
		'post_status' => 'publish'
	);
	$id = wp_insert_post ( $order, true );
	if (! is_wp_error ( $id )) {
		add_post_meta ( $id, 'cognitive-score', $scores['cognitive-score'] );
		add_post_meta ( $id, 'emotional-score', $scores['emotional-score'] );
		add_post_meta ( $id, 'physical-score', $scores['physical-score'] );
		add_post_meta ( $id, 'financial-score', $scores['financial-score'] );
		add_post_meta ( $id, 'spiritual-score', $scores['spiritual-score'] );

		add_post_meta ( $id, 'learning-strategies', $scores['learning-strategies'] );
		add_post_meta ( $id, 'intellectual-engagement', $scores['intellectual-engagement'] );
		add_post_meta ( $id, 'effort-control', $scores['effort-control'] );
		add_post_meta ( $id, 'attention', $scores['attention'] );
		add_post_meta ( $id, 'autonomy', $scores['autonomy'] );
		add_post_meta ( $id, 'social-cognition', $scores['social-cognition'] );

		add_post_meta ( $id, 'current-emotional-health', $scores['current-emotional-health'] );
		add_post_meta ( $id, 'self-compassion', $scores['self-compassion'] );
		add_post_meta ( $id, 'emotional-self-awareness', $scores['emotional-self-awareness'] );
		add_post_meta ( $id, 'stress-resilience', $scores['stress-resilience'] );
		add_post_meta ( $id, 'social-support', $scores['social-support'] );
		add_post_meta ( $id, 'gratitude-positivity', $scores['gratitude-positivity'] );

		add_post_meta ( $id, 'bmi', $scores['bmi'] );
		add_post_meta ( $id, 'nutritional-implementation', $scores['nutritional-implementation'] );
		add_post_meta ( $id, 'nutritional-knowledge', $scores['nutritional-knowledge'] );
		add_post_meta ( $id, 'activity-level', $scores['activity-level'] );
		add_post_meta ( $id, 'self-image', $scores['self-image'] );
		add_post_meta ( $id, 'social-activity', $scores['social-activity'] );
		add_post_meta ( $id, 'long-term', $scores['long-term'] );
		add_post_meta ( $id, 'short-term', $scores['short-term'] );
		add_post_meta ( $id, 'hapiness', $scores['hapiness'] );
		add_post_meta ( $id, 'sadness', $scores['sadness'] );
		return $id;
	} else {
		var_dump($id);
	}
}

//add post adax
add_action ( 'wp_ajax_submit_results', 'submit_results_callback'  );
add_action ( 'wp_ajax_nopriv_submit_results', 'submit_results_callback' );
add_action ( 'wp_head', 'create_ajax_base_info' );

function create_ajax_base_info() {
	if(is_page(49)):
	?>
	<script type="text/javascript">
		var SubmitResultsAjax = {
			"ajaxurl": "<?php echo admin_url('admin-ajax.php') ?>",
			"submit_results_nonce": "<?php echo wp_create_nonce('submit_results_nonce') ?>"
		};
	</script>
	<?php
	endif;
}

function submit_results_callback() {
	$nonce = $_POST ['submit_results_nonce'];
	if (! wp_verify_nonce ( $nonce, 'submit_results_nonce' )) {
		die ();
	}
	$scores = $_POST['args'];
	create_new_submission($scores);

	echo 'submission posted';

	die();
}