<?php
/**
 * Timber starter-theme
 * https://github.com/timber/starter-theme
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.1
 */

/**
 * If you are installing Timber as a Composer dependency in your theme, you'll need this block
 * to load your dependencies and initialize Timber. If you are using Timber via the WordPress.org
 * plug-in, you can safely delete this block. 
 */
$composer_autoload = __DIR__ . '/vendor/autoload.php';
if ( file_exists($composer_autoload) ) {
	require_once( $composer_autoload );
	$timber = new Timber\Timber();
}

/**
 * This ensures that Timber is loaded and available as a PHP class.
 * If not, it gives an error message to help direct developers on where to activate
 */
if ( ! class_exists( 'Timber' ) ) {

	add_action( 'admin_notices', function() {
		echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url( admin_url( 'plugins.php#timber' ) ) . '">' . esc_url( admin_url( 'plugins.php' ) ) . '</a></p></div>';
	});

	add_filter('template_include', function( $template ) {
		return get_stylesheet_directory() . '/static/no-timber.html';
	});
	return;
}

/**
 * Sets the directories (inside your theme) to find .twig files
 */
Timber::$dirname = array( 'templates', 'views' );

/**
 * By default, Timber does NOT autoescape values. Want to enable Twig's autoescape?
 * No prob! Just set this value to true
 */
Timber::$autoescape = false;


/**
 * We're going to configure our theme inside of a subclass of Timber\Site
 * You can move this to its own file and include here via php's include("MySite.php")
 */
class StarterSite extends Timber\Site {
	/** Add timber support. */
	public function __construct() {
		add_action( 'after_setup_theme', array( $this, 'theme_supports' ) );
		add_filter( 'timber/context', array( $this, 'add_to_context' ) );
		add_filter( 'timber/twig', array( $this, 'add_to_twig' ) );
		add_action( 'init', array( $this, 'register_post_types' ) );
		add_action( 'init', array( $this, 'register_taxonomies' ) );
		parent::__construct();
	}
	/** This is where you can register custom post types. */
	public function register_post_types() {

	}
	/** This is where you can register custom taxonomies. */
	public function register_taxonomies() {

	}

	/** This is where you add some context
	 *
	 * @param string $context context['this'] Being the Twig's {{ this }}.
	 */
	public function add_to_context( $context ) {
		$profile_pic = get_theme_mod('logo_image_c'); 
		$image_profile = wp_get_attachment_image_src( $profile_pic, array('300','300'),'true' );
		$context['headerlogo'] = $image_profile[0];
		$context['foo'] = 'bar';
		$context['footer_logo'] = Timber::get_widgets('footer-logo');
		$context['footer_donation_logo'] = Timber::get_widgets('footer-donation-logo');
		$context['footer_widget'] = Timber::get_widgets('footer-text-widget');
		$context['stuff'] = 'I am a value set in your functions.php file';
		$context['notes'] = 'These values are available everytime you call Timber::context();';
		
		$active_lang = pll_current_language();
		if($active_lang == 'en')
		{
			$context['lang'] = '';
		}else{
			$context['lang'] = $active_lang;
		}	
		
		$context['menu'] = new Timber\Menu('menu_'.$active_lang);
		$context['site'] = $this;
		$context['languages_drop'] = Timber::get_widgets('sidebar-1');
		$context['languages_drop_mobile'] = Timber::get_widgets('menu_lan_swi');		
		return $context;
	}

	public function theme_supports() {
		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5', array(
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
		);

		/*
		 * Enable support for Post Formats.
		 *
		 * See: https://codex.wordpress.org/Post_Formats
		 */
		add_theme_support(
			'post-formats', array(
				'aside',
				'image',
				'video',
				'quote',
				'link',
				'gallery',
				'audio',
			)
		);

		add_theme_support( 'menus' );
	}

	/** This Would return 'foo bar!'.
	 *
	 * @param string $text being 'foo', then returned 'foo bar!'.
	 */
	public function myfoo( $text ) {
		$text .= ' bar!';
		return $text;
	}

	/** This is where you can add your own functions to twig.
	 *
	 * @param string $twig get extension.
	 */
	public function add_to_twig( $twig ) {
		$twig->addExtension( new Twig_Extension_StringLoader() );
		$twig->addFilter( new Twig_SimpleFilter( 'myfoo', array( $this, 'myfoo' ) ) );
		$twig->addFunction( new Twig_SimpleFunction( 'pl_e', 'pl_e' ) );
		$twig->addFunction( new Twig_SimpleFunction( 'pl__', 'pl__' ) );
		
		return $twig;
	}
	

}

new StarterSite();







/*********************************************************** customizer *****************************************************************************/

function tcx_register_theme_customizer( $wp_customize ) {
 
 	$wp_customize->add_setting('banner_title', array(
	 'default'        => '',
	 ));
	
	
	$wp_customize->add_control( 'banner_title', array(
    'label' => __( 'Bnnner Heading Here' ),
    'section' => 'header_image',
    'type' => 'text',
	)  );
    $wp_customize->add_section('profile_settings_section', array(
	  'title'          => 'Logo Image section'
	 ));
	//adding setting for footer text area
	$wp_customize->add_setting('logo_image_c', array(
	 'default'        => '',
	 ));
	
	
	$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'logo_image_c', array(
    'label' => __( 'Logo Image Here' ),
    'section' => 'profile_settings_section',
    'mime_type' => 'image',
) ) );


	
	

	//adding setting for footer text area
	/*$wp_customize->add_setting('video', array(
	 'default'        => '',
	 ));*/
	
	
	/*$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'video', array(
    'label' => __( 'Video Here' ),
    'section' => 'video_section',
    'mime_type' => 'Video',
) ) );*/
	$wp_customize->add_setting( 'themeslug_url_setting_id', array(
	  'capability' => 'edit_theme_options',
	  'sanitize_callback' => 'themeslug_sanitize_url',
	) );
	
	
	
	function themeslug_sanitize_url( $url ) {
	  return esc_url_raw( $url );
	}


	$wp_customize->add_section('content_section', array(
	  'title'          => 'Content section',
	 ));
	$wp_customize->add_setting('content_title', array(
	 'default'        => '',
	 ));
	 $wp_customize->add_setting('content', array(
	 'default'        => '',
	 ));
	
	
	




class Multi_Input_Custom_control extends WP_Customize_Control{
	public $type = 'multi_input';
	public function enqueue(){
		wp_enqueue_script( 'custom_controls', get_template_directory_uri().'/js/theme-customizer-new.js', array( 'jquery' ),'', true );
		
	}
	public function render_content(){
		?>
		<label class="customize_multi_input">
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<input type="hidden" id="<?php echo esc_attr($this->id); ?>" name="<?php echo esc_attr($this->id); ?>" value="<?php echo esc_attr($this->value()); ?>" class="customize_multi_value_field" data-customize-setting-link="<?php echo esc_attr($this->id); ?>"/>
			<div class="customize_multi_fields">
				<div class="set">
					<input type="text" value="" class="customize_multi_single_field" style="width:90%; display:inline-block; margin-bottom:15px;"/>
					<a href="#" class="customize_multi_remove_field">X</a>
				</div>
			</div>
			<a href="#" class="button button-primary customize_multi_add_field"><?php esc_attr_e('Add More', 'mytheme') ?></a>
		</label>
		<?php
	}
}

//namespace tad\customizer\controls;

if (!class_exists('\WP_Customize_Image_Control')) {
    return null;
}
class MultiImageControl extends \WP_Customize_Image_Control
{
    public $type = 'multi-image';

    public function enqueue()
    {
        wp_enqueue_media();
		wp_enqueue_script( 'custom_controls', get_template_directory_uri().'/js/theme-customizer-new.js', array( 'jquery' ),'', true );
        // enqueue the script
        // enqueue the style
    }

    public function render_content()
    {
        // get the set values if any
		$imageSrcs = array();
        $imageSrcs = explode(",",$this->value());
			
		
        if (is_null($imageSrcs)) {
            $imageSrcs = array();
        }
        // render the title
        $this->theTitle();
		
       $this->theUploadedImages($imageSrcs);
	    $this->theButtons();
	   
    }
    protected function theTitle()
    {
        ?>
        <label>
            <span class="customize-control-title">
                <?php echo esc_html($this->label); ?> 
            </span> 
        </label>
        <?php
    }
    
	

	 
}
/**
Multiple input field
**/
$wp_customize->add_setting('multi_field', array(
	'default'           => '',
	
));



	//adding setting for footer text area
	$wp_customize->add_setting('site_info', array(
	 'default'        => '',
	 ));
	
	
	

$wp_customize->remove_section( 'colors' );
$wp_customize->remove_section( 'background_image' );
$wp_customize->remove_section( 'static_front_page' );
$wp_customize->remove_section( 'custom_css' );
 
}
add_action( 'customize_register', 'tcx_register_theme_customizer' );

/**
 * Add a sidebar.
 */
function wpdocs_theme_slug_widgets_init() {
	
	
	register_sidebar( array(
        'name'          => __( 'language switcher menu', 'textdomain' ),
        'id'            => 'menu_lan_swi',
        'description'   => __( 'language switcher menu in mobile.', 'textdomain' ),
        'before_widget' => '<li id="%1$s" class="widget %2$s">',
        'after_widget'  => '</li>',
        'before_title'  => '<h2 class="widgettitle">',
        'after_title'   => '</h2>',
    ) );
	
    register_sidebar( array(
        'name'          => __( 'Main Sidebar', 'textdomain' ),
        'id'            => 'sidebar-1',
        'description'   => __( 'Widgets in this area will be shown on all posts and pages.', 'textdomain' ),
        'before_widget' => '<li id="%1$s" class="widget %2$s">',
        'after_widget'  => '</li>',
        'before_title'  => '<h2 class="widgettitle">',
        'after_title'   => '</h2>',
    ) );
	
	
	register_sidebar( array(
        'name'          => __( 'Footer Widget logo', 'textdomain' ),
        'id'            => 'footer-logo',
        'description'   => __( 'Footer logo', 'textdomain' ),
        'before_widget' => '<li id="%1$s" class="widget %2$s">',
        'after_widget'  => '</li>',
        'before_title'  => '<h2 class="widgettitle">',
        'after_title'   => '</h2>',
    ) );
	
	register_sidebar( array(
        'name'          => __( 'Footer Widget text', 'textdomain' ),
        'id'            => 'footer-text-widget',
        'description'   => __( 'Footer context html', 'textdomain' ),
        'before_widget' => '<li id="%1$s" class="widget %2$s">',
        'after_widget'  => '</li>',
        'before_title'  => '<h2 class="widgettitle">',
        'after_title'   => '</h2>',
    ) );
	
	register_sidebar( array(
        'name'          => __( 'Footer Widget image', 'textdomain' ),
        'id'            => 'footer-donation-logo',
        'description'   => __( 'Footer context html', 'textdomain' ),
        'before_widget' => '<li id="%1$s" class="widget %2$s">',
        'after_widget'  => '</li>',
        'before_title'  => '<h2 class="widgettitle">',
        'after_title'   => '</h2>',
    ) );
}
add_action( 'widgets_init', 'wpdocs_theme_slug_widgets_init' );


function register_theme_menus() {

    register_nav_menus(
        array(
            'primary-menu'  => __( 'Primary Menu', 'treehouse-portfolio' )          
        )
    );

}
add_action( 'init', 'register_theme_menus' );



/**
 * Outputs localized string if polylang exists or  output's not translated one as a fallback
 *
 * @param $string
 *
 * @return  void
 */
function pl_e( $string = '' ) {
    if ( function_exists( 'pll_e' ) ) {
        pll_e( $string );
    } else {
        echo $string;
    }
}

/**
 * Returns translated string if polylang exists or  output's not translated one as a fallback
 *
 * @param $string
 *
 * @return string
 */
function pl__( $string = '' ) {
    if ( function_exists( 'pll__' ) ) {
        return pll__( $string );
    }

    return $string;
}

// these function prefixes can be either you are comfortable with.


function your_prefix_after_setup_theme() {

	// register our translatable strings - again first check if function exists.
 
	 if ( function_exists( 'pll_register_string' ) ) {
 
		pll_register_string('Sorry, we couldn\'t find what you\'re looking for.', 'Sorry, we couldn\'t find what you\'re looking for.', 'amandla-theme', false);
		pll_register_string('First', 'First', 'amandla-theme', false);
		pll_register_string('Previous', 'Previous', 'amandla-theme', false);
		pll_register_string('Next', 'Next', 'amandla-theme', false);
		pll_register_string('Last', 'Last', 'amandla-theme', false);
		pll_register_string('This is my archive', 'This is my archive', 'amandla-theme', false);
		pll_register_string('Add comment', 'Add comment', 'amandla-theme', false);
		pll_register_string('Email', 'Email', 'amandla-theme', false);
		pll_register_string('Website', 'Website', 'amandla-theme', false);
		pll_register_string('Your comment will be revised by the site if needed.', 'Your comment will be revised by the site if needed.', 'amandla-theme', false);
		pll_register_string('says', 'says', 'amandla-theme', false);
		pll_register_string('replies', 'replies', 'amandla-theme', false);
		pll_register_string('Donate', 'Donate', 'amandla-theme', false);
		pll_register_string('signatures required', 'signatures required', 'amandla-theme', false);
		pll_register_string('JUST STARTED', 'JUST STARTED', 'amandla-theme', false);
		pll_register_string('SIGNATURES', 'SIGNATURES', 'amandla-theme', false);
		pll_register_string('SIGN NOW', 'SIGN NOW', 'amandla-theme', false);
		pll_register_string('Read more', 'Read more', 'amandla-theme', false);
		pll_register_string('Password', 'Password', 'amandla-theme', false);
		pll_register_string('Submit', 'Submit', 'amandla-theme', false);
		pll_register_string('Author Archives:', 'Author Archives:', 'amandla-theme', false);
		pll_register_string('Unsubscribe here', 'Unsubscribe here', 'amandla-theme', false);
	 }
 }
  add_action( 'after_setup_theme', 'your_prefix_after_setup_theme' );