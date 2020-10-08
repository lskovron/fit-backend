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
		wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css', array(), time() );

		// Enqueue Child theme's stylesheet.
		// Setting 'parent-style' as a dependency will ensure that the child theme stylesheet loads after it.
		wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array( $parent_style ), time() );

		if(is_page(49)) {
			wp_enqueue_script( 'highcharts', 'https://code.highcharts.com/highcharts.js');
			wp_enqueue_script( 'highcharts-variable-pie', 'https://code.highcharts.com/modules/variable-pie.js', array('jquery','highcharts'));
			wp_enqueue_script( 'highcharts-exporting', 'https://code.highcharts.com/modules/exporting.js', array('jquery','highcharts'));
			wp_enqueue_script( 'ajax-post', get_stylesheet_directory_uri() . '/scripts.js', array('jquery','highcharts','highcharts-variable-pie','highcharts-exporting'), time() );
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
    $origins[] = 'https://assessment.thefitexperience.com';
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
	unset ( $columns ['title'] );
	$columns ['participant'] = 'Name';
	$columns ['email'] = 'Email';
	$columns ['gender'] = 'Gender';
	$columns ['age'] = 'Age';
	$columns ['income'] = 'Income';
	$columns ['height'] = 'Height';
	$columns ['weight'] = 'Weight';
	$columns ['marital'] = 'Marital Status';
	$columns ['language'] = 'Language';
	$columns ['location'] = 'Location';
	$columns ['education'] = 'Education';
	$columns ['ethnicity'] = 'Ethnicity';
	$columns ['family'] = 'Family';

	$columns ['overall-score'] = 'Overall';
	$columns ['balance-score'] = 'Balance';
	$columns ['cognitive-score'] = 'Cognitive';
	$columns ['emotional-score'] = 'Emotional';
	$columns ['physical-score'] = 'Physical';
	$columns ['financial-score'] = 'Financial';
	$columns ['spiritual-score'] = 'Spiritual';

	$columns ['learning-strategies'] = '(C) Learning Strategies & Memory';
	$columns ['intellectual-engagement'] = '(C) Intellectual Engagement';
	$columns ['effort-control'] = '(C) Effort Control';
	$columns ['attention'] = '(C) Attention';
	$columns ['autonomy'] = '(C) Autonomy';
	$columns ['social-cognition'] = '(C) Social Cognition';

	$columns ['current-emotional-health'] = '(E) Current Emotional Health';
	$columns ['self-compassion'] = '(E) Self Compassion & Emotional Awareness';
	$columns ['stress-resilience'] = '(E) Stress Resilience';
	$columns ['gratitude-positivity'] = '(E) Gratitude and Positivity';
	$columns ['social-engagement'] = '(E) Social Engagement';
	$columns ['mindset'] = '(E) Mindset';

	$columns ['aerobic-activity'] = '(P) Aerobic Activity';
	$columns ['nutrition'] = '(P) Nutrition';
	$columns ['nutrition-knowledge'] = '(P) Nutritional Knowledge';
	$columns ['activity-level'] = '(P) Activity Levels';
	$columns ['self-image'] = '(P) Self Image';
	$columns ['sleep-habits'] = '(P) Sleep Habits';
	$columns ['strength-training'] = '(P) Strength Training';

	$columns ['long-term'] = '(F) Long Term Perspective';
	$columns ['short-term'] = '(F) Short Term Perspective';
	$columns ['reduce-sadness'] = '(F) Reduce Sadness';
	$columns ['increase-happiness'] = '(F) Increase Happiness';
	$columns ['non-pecuniary'] = '(F) Non-pecuniary';

	$columns ['connection'] = '(S) Connection';
	$columns ['compassion-empathy'] = '(S) Compassion and Empathy';
	$columns ['forgiveness'] = '(S) Forgiveness';
	$columns ['purpose'] = '(S) Purpose';
	$columns ['presence'] = '(S) Presence';

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
		add_post_meta ( $id, 'overall-score', $scores['overall-score'] );
		add_post_meta ( $id, 'balance-score', $scores['balance-score'] );
		add_post_meta ( $id, 'cognitive-score', $scores['cognitive-score'] );
		add_post_meta ( $id, 'emotional-score', $scores['emotional-score'] );
		add_post_meta ( $id, 'physical-score', $scores['physical-score'] );
		add_post_meta ( $id, 'financial-score', $scores['financial-score'] );
		add_post_meta ( $id, 'spiritual-score', $scores['spiritual-score'] );

		add_post_meta ( $id, 'participant', $scores ['participant'] );
		add_post_meta ( $id, 'email', $scores ['email'] );
		add_post_meta ( $id, 'gender', $scores ['gender'] );
		add_post_meta ( $id, 'age', $scores ['age'] );
		add_post_meta ( $id, 'income', $scores ['income'] );
		add_post_meta ( $id, 'height', $scores ['height'] );
		add_post_meta ( $id, 'weight', $scores ['weight'] );
		add_post_meta ( $id, 'marital', $scores ['marital'] );
		add_post_meta ( $id, 'location', $scores ['location'] );
		add_post_meta ( $id, 'language', $scores ['language'] );
		add_post_meta ( $id, 'education', $scores ['education'] );
		add_post_meta ( $id, 'ethnicity', $scores ['ethnicity'] );
		add_post_meta ( $id, 'family', $scores ['family'] );

	
		add_post_meta ( $id, 'learning-strategies', $scores ['learning-strategies'] );
		add_post_meta ( $id, 'intellectual-engagement', $scores ['intellectual-engagement'] );
		add_post_meta ( $id, 'effort-control', $scores ['effort-control'] );
		add_post_meta ( $id, 'attention', $scores ['attention'] );
		add_post_meta ( $id, 'autonomy', $scores ['autonomy'] );
		add_post_meta ( $id, 'social-cognition', $scores ['social-cognition'] );
	
		add_post_meta ( $id, 'current-emotional-health', $scores ['current-emotional-health'] );
		add_post_meta ( $id, 'self-compassion', $scores ['self-compassion'] );
		add_post_meta ( $id, 'stress-resilience', $scores ['stress-resilience'] );
		add_post_meta ( $id, 'gratitude-positivity', $scores ['gratitude-positivity'] );
		add_post_meta ( $id, 'social-engagement', $scores ['social-engagement'] );
		add_post_meta ( $id, 'mindset', $scores ['mindset'] );
	
		add_post_meta ( $id, 'aerobic-activity', $scores ['aerobic-activity'] );
		add_post_meta ( $id, 'nutrition', $scores ['nutrition'] );
		add_post_meta ( $id, 'nutrition-knowledge', $scores ['nutrition-knowledge'] );
		add_post_meta ( $id, 'activity-level', $scores ['activity-level'] );
		add_post_meta ( $id, 'self-image', $scores ['self-image'] );
		add_post_meta ( $id, 'sleep-habits', $scores ['sleep-habits'] );
		add_post_meta ( $id, 'strength-training', $scores ['strength-training'] );
	
		add_post_meta ( $id, 'long-term', $scores ['long-term'] );
		add_post_meta ( $id, 'short-term', $scores ['short-term'] );
		add_post_meta ( $id, 'reduce-sadness', $scores ['reduce-sadness'] );
		add_post_meta ( $id, 'increase-happiness', $scores ['increase-happiness'] );
		add_post_meta ( $id, 'non-pecuniary', $scores ['non-pecuniary'] );
	
		add_post_meta ( $id, 'connection', $scores ['connection'] );
		add_post_meta ( $id, 'compassion-empathy', $scores ['compassion-empathy'] );
		add_post_meta ( $id, 'forgiveness', $scores ['forgiveness'] );
		add_post_meta ( $id, 'purpose', $scores ['purpose'] );
		add_post_meta ( $id, 'presence', $scores ['presence'] );
		return $id;
	} else {
		var_dump($id);
	}
}

//add post adax
add_action ( 'wp_ajax_submit_results', 'submit_results_callback'  );
add_action ( 'wp_ajax_nopriv_submit_results', 'submit_results_callback' );
add_action ( 'wp_ajax_send_email', 'send_email_callback'  );
add_action ( 'wp_ajax_nopriv_send_email', 'send_email_callback' );
add_action ( 'wp_head', 'create_ajax_base_info' );

function create_ajax_base_info() {
	if(is_page(49)):
	?>
	<script type="text/javascript">
		var SubmitResultsAjax = {
			"ajaxurl": "<?php echo admin_url('admin-ajax.php') ?>",
			"submit_results_nonce": "<?php echo wp_create_nonce('submit_results_nonce') ?>"
		};
		var SendEmailAjax = {
			"ajaxurl": "<?php echo admin_url('admin-ajax.php') ?>",
			"send_email_nonce": "<?php echo wp_create_nonce('send_email_nonce') ?>"
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

require_once dirname( __FILE__ ) . '/email.php';

function send_email_callback() {
	$nonce = $_POST ['send_email_nonce'];
	if (! wp_verify_nonce ( $nonce, 'send_email_nonce' )) {
		die ();
	}
	$data = $_POST['args'];

	$email_html = return_email_template($data);
	// 	$data["highest-dim"],
	// 	$data["lowest-dim"],
	// 	$data["cognitive"],
	// 	$data["emotional"],
	// 	$data["physical"],
	// 	$data["financial"],
	// 	$data["spiritual"],
	// 	$data["overall"],
	// 	$data["email"],
	// 	$data["participant"],
	// 	$data["balance"],
	// 	$data["urlString"],
	// );


	$to = $data["email"];
	$subject = 'FIT Assessment Results - '.$data["participant"];
	$body = $email_html;
	$headers = array('Content-Type: text/html; charset=UTF-8');
 
	$sent_email = wp_mail( $to, $subject, $body, $headers );

	var_dump($email_html);
	// echo 'email sent';
	die();
}



//admin scripts
add_action('admin_head', 'my_custom_fonts');

function my_custom_fonts() {
  echo '<style>
	#menu-posts-emotional {
		border-top: 1px solid white!important;
	}
	#menu-posts-spiritual {
		border-bottom: 1px solid white!important;
	}
    #menu-posts-cognitive,
    #menu-posts-emotional,
    #menu-posts-physical,
    #menu-posts-financial,
    #menu-posts-spiritual {
		background: #02188d;
	}
	#menu-posts-fit-submission {
		background: #005d00;
		border-top: 1px solid white!important;
		border-bottom: 1px solid white!important;
	}
  </style>';
}