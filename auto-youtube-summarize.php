<?php 
/**
 * Plugin Name: AUTO YOUTUBE SUMMARIZE
 * Plugin URI: https://ruana.co.jp/auto-youtube-summarize
 * Description: This is a better and simple way to auto youtube summary posts.
 * Version: 1.2.7
 * Author: Ruana LLC
 * Author URI: https://ruana.co.jp
 * Text Domain: auto-youtube-summarize
 * Domain Path: /languages/
 *
 * Copyright 2018 Ruana LLC (email : r.kurosu@ruana.co.jp)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
require_once(  plugin_dir_path( __FILE__ )  . 'includes/functions.php' );
require_once(  plugin_dir_path( __FILE__ )  . 'includes/class-auto-youtube-summarize.php' );
require_once( ABSPATH . 'wp-admin/includes/file.php');

define( 'AYS_VERSION', '1.2.7' );
define( 'AYS_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'AYS_PLUGIN_NAME', trim( dirname( AYS_PLUGIN_BASENAME ), '/' ) );
define( 'AYS_PLUGIN_DIR', untrailingslashit( dirname( __FILE__ ) ) );
define( 'AYS_PLUGIN_URL', untrailingslashit( plugins_url( '', __FILE__ ) ) );
define( 'AYS_PREFIX', 'ays_' );

/**
 * Main Class
 */
class AYS_Main{
	protected $textdomain = 'auto-youtube-summarize';
	public function __construct() {
		$this->init();
		add_action( 'admin_menu', array( &$this, 'admin_menu' ) );
		add_action( 'wp_enqueue_scripts', array( &$this, 'load_plugin_css' ) );
    add_action( 'admin_print_styles', array( &$this, 'head_css' ) );
    add_action( 'admin_print_scripts', array( &$this, "head_js" ) );
    add_filter( 'the_content', array( &$this,'powered_by_ays') );

		/**
		 * プラグインの有効化・無効化・削除時
		 */
		register_activation_hook( __FILE__, array( $this, 'plugin_activation' ) );
		register_deactivation_hook( __FILE__, array( $this, 'plugin_deactivation' ) );
		register_uninstall_hook(__FILE__, array( $this, 'plugin_uninstall' ) );

		add_action('ays_cron',array( $this, 'ays_get_movie_schedule_run' ));
		if ( !wp_next_scheduled( 'ays_cron' ) && !AYS_DEBUG ) {
			date_default_timezone_set('Asia/Tokyo');
			wp_schedule_event( time(), "twicedaily",  'ays_cron' );
		}

		add_action( 'wp_list_comments_args', array( &$this, 'comment_custom_display' ));
	}
	public function admin_menu() {
		// add_submenu_page( 'tools.php', 
		// 	$this->_( 'Auto Youtube Summarize', '動画まとめプラグイン' ), 
		// 	$this->_( 'Auto Youtube Summarize', '動画まとめプラグイン' ), 
		// 	'level_7',
		// 	AYS_PLUGIN_NAME,
		// 	array( &$this, 'show_options_page', ) );
		add_options_page(
			$this->_( 'Auto Youtube Summarize', '動画まとめプラグイン' ),
			$this->_( 'Auto Youtube Summarize', '動画まとめプラグイン' ),
			'administrator',
			AYS_PLUGIN_NAME,
			array( &$this, 'show_options_page', ) 
		);
		register_setting(AYS_PREFIX . 'option_group',AYS_PREFIX . 'keywords');
		register_setting(AYS_PREFIX . 'option_group',AYS_PREFIX . 'order');
		register_setting(AYS_PREFIX . 'option_group',AYS_PREFIX . 'authkey');
		register_setting(AYS_PREFIX . 'option_group',AYS_PREFIX . 'publishedAfter');
		register_setting(AYS_PREFIX . 'option_group',AYS_PREFIX . 'maxResults');
		register_setting(AYS_PREFIX . 'option_group',AYS_PREFIX . 'commentName');
		register_setting(AYS_PREFIX . 'option_group',AYS_PREFIX . 'commentCount');
		register_setting(AYS_PREFIX . 'option_group',AYS_PREFIX . 'postCommentCount');
		register_setting(AYS_PREFIX . 'option_group',AYS_PREFIX . 'descriptionIndex');
		register_setting(AYS_PREFIX . 'option_group',AYS_PREFIX . 'postFirstContent');
		register_setting(AYS_PREFIX . 'option_group',AYS_PREFIX . 'postMovieDescription');
		register_setting(AYS_PREFIX . 'option_group',AYS_PREFIX . 'postTemplateText');
		register_setting(AYS_PREFIX . 'option_group',AYS_PREFIX . 'afterTemplateText');
		register_setting(AYS_PREFIX . 'option_group',AYS_PREFIX . 'commentTemplateText');
		register_setting(AYS_PREFIX . 'option_group',AYS_PREFIX . 'commentTemplateName');
	}

	 /**
    * Admin Panel Rooting
    */
  public function setting_url($view = ''){
    $query = array(
      'page' => $this->textdomain
    );
    if( $view ){
      $query['view'] = $view;
    }
    return admin_url('options-general.php?'.http_build_query($query));
  }

  /**
   * 管理画面CSS追加
   */
  public function head_css() {
    if (  isset($_REQUEST["page"]) && $_REQUEST["page"] == AYS_PLUGIN_NAME ) {
      wp_enqueue_style('bootstrap-style', AYS_PLUGIN_URL . '/includes/css/bootstrap.min.css');
    }
  }

  /*
   * 管理画面JS追加
   */
  public function head_js() {
    if (  isset($_REQUEST["page"]) && $_REQUEST["page"] == AYS_PLUGIN_NAME ) {
      wp_enqueue_script( "bootstrap-script",AYS_PLUGIN_URL . '/includes/js/bootstrap.min.js' );
    }
  }



    /**
     * プラグインのメインページ
     */
    public function show_options_page() {
      require_once AYS_PLUGIN_DIR . '/admin/index.php';
    }


	/**
	 * 多言語化
	 */
	private function init() {
		load_plugin_textdomain( $this->textdomain, false, basename( dirname( __FILE__ ) ) . '/languages/' );
		add_action('admin_menu', array( $this, 'exec_ays_get_movie' ));
	}

	/**
	 * CSS
	 */
	public function load_plugin_css() {
		wp_register_style( 'ays_style', plugin_dir_url( __FILE__ ) . 'includes/css/ays_style.css');
		wp_enqueue_style( 'ays_style' );
	}


	/**
	 * 動画の強制抽出スクリプト
	 */
	public function exec_ays_get_movie() {
		add_options_page($this->_('Force Video Extraction Now','動画抽出を今すぐ強制実行'), $this->_('Force Video Extraction Now','動画抽出を今すぐ強制実行'), 'administrator', 'exec_ays_get_movie', array( $this, 'exec_ays_get_movie_page' ));
	}

	/**
	 * 動画の強制抽出実行後のページ
	 */
	public function exec_ays_get_movie_page() {
		?>
		<p>movie post is completed.</p>
		<?php $this->ays_get_movie_schedule_run();
	}

	public function ays_get_movie_schedule_run() {
		if(!AYS_DEBUG){
			$getmovie = new AYS_GetMovie();
			$getmovie->run();
		}
	}

	public function comment_custom_display( $r ) {
		$args  = array(
			'callback'  => array($this,'custom_comment_display'),
		);
		$r = wp_parse_args( $args, $r );
		return $r;
	}
	public function custom_comment_display($comment, $args, $depth) {
		$GLOBALS['comment'] = $comment; ?>
		<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
			<div id="comment-<?php comment_ID(); ?>">
				<div class="comment-author vcard">
					<img alt="" src="<?php echo plugin_dir_url( __FILE__ ) . 'includes/image/user.jpeg' ?>" srcset="<?php echo plugin_dir_url( __FILE__ ) . 'includes/image/user.jpeg' ?>" class="avatar" height="55" width="55">
				</div>
				<div class="comment-meta commentmetadata">
					<?php comment_author_link(); ?> <?php comment_date('Y/m/d/(D) h:i A') ?>
				</div>
				<div class="comment-txt">
					<?php comment_text(); ?>
				</div>
			</div>
		</li>
		<?php
	}

  /**
   * ロード時に読み込む初期設定用の関数
   */
  public function plugin_activation(){

  	update_option(AYS_PREFIX . 'keywords','');
  	update_option(AYS_PREFIX . 'order','relevance');
  	update_option(AYS_PREFIX . 'authkey','');
  	update_option(AYS_PREFIX . 'publishedAfter',7);
  	update_option(AYS_PREFIX . 'maxResults',5);
  	update_option(AYS_PREFIX . 'commentName',$this->_('Noname','名無しさん＠お腹いっぱい'));
  	update_option(AYS_PREFIX . 'commentCount',10);
  	update_option(AYS_PREFIX . 'postCommentCount',7);
  	update_option(AYS_PREFIX . 'descriptionIndex',7);
  	update_option(AYS_PREFIX . 'postFirstContent',$this->_('「%title%」 seems like a topic','%title%って動画が話題らしいぞ'));
  	update_option(AYS_PREFIX . 'postMovieDescription',$this->_('This movie','ほい動画'));

  	$postTemplateText = file(AYS_PLUGIN_DIR . '/includes/init_data/postTemplateText.txt');
  	update_option(AYS_PREFIX . 'postTemplateText',implode("",$postTemplateText));
  	$afterTemplateText = file(AYS_PLUGIN_DIR . '/includes/init_data/afterTemplateText.txt');
  	update_option(AYS_PREFIX . 'afterTemplateText',implode("",$afterTemplateText));
  	$commentTemplateText = file(AYS_PLUGIN_DIR . '/includes/init_data/commentTemplateText.txt');
  	update_option(AYS_PREFIX . 'commentTemplateText',implode("",$commentTemplateText));
  	$commentTemplateName = file(AYS_PLUGIN_DIR . '/includes/init_data/commentTemplateName.txt');
  	update_option(AYS_PREFIX . 'commentTemplateName',implode("",$commentTemplateName));
  }

  /**
   * 無効化時
   */
  public function plugin_deactivation() {

  }
  /**
   * 削除時
   */
  public function plugin_uninstall() {
  	$options = [
  		AYS_PREFIX . 'keywords',
  		AYS_PREFIX . 'order',
  		AYS_PREFIX . 'authkey',
  		AYS_PREFIX . 'publishedAfter',
  		AYS_PREFIX . 'maxResults',
  		AYS_PREFIX . 'commentName',
  		AYS_PREFIX . 'commentCount',
  		AYS_PREFIX . 'postCommentCount',
  		AYS_PREFIX . 'descriptionIndex',
  		AYS_PREFIX . 'postFirstContent',
  		AYS_PREFIX . 'postMovieDescription',
  		AYS_PREFIX . 'postTemplateText',
  		AYS_PREFIX . 'afterTemplateText',
  		AYS_PREFIX . 'commentTemplateText',
  		AYS_PREFIX . 'commentTemplateName',
  	];
  	foreach($options as $option){
  		delete_option($option);
  	}
  }

  public function is_pro(){
    if( $ays_options = get_option('ays_options')){
      $is_pro = $ays_options['pro'];
    }else{
      $is_pro = false;
    }
    return $is_pro;
  }

  public function need_pro_addons(){
    if($this->is_pro()){
      // no echo
    }else{
      echo "<a class=\"nav-link\" href=\"" .$this->setting_url('addon')."\"><u>";
      $this->e('This function needs addons.Please see Add-ons page');
      echo "</u></a>";
    }
  }
  public function powered_by_ays($content){
    if($this->is_pro()){
      return $content;
    }else{
      $content = $content . "<p>powered by <a href='https://wordpress.org/plugins/auto-youtube-summarize/'>Auto Youtube Summarize</a></p>";
      return $content;
    }
  }
  
    /**
     * esc_htmlの配列対応版
     */
    public function esc_htmls( $str ) {
    	if ( is_array( $str ) ) {
    		return array_map( "esc_html", $str );
    	}else {
    		return esc_html( $str );
    	}
    }

    /**
     * Load template file
     *
     * @param string $name
     */
    public function get_template($name){
    	$path = AYS_PLUGIN_DIR."{$name}.php";
    	if( file_exists($path) ){
    		include $path;
    	}
    }

    /**
     * return $_REQUEST
     *
     * @param string $key
     * @return mixed
     */
    public function request($key){
    	if(isset($_REQUEST[$key])){
    		return $_REQUEST[$key];
    	}else{
    		return null;
    	}
    }

    /**
     * 翻訳用
     */
    public function e( $text, $ja = null ) {
    	_e( $text, $this->textdomain );
    }
    public function _( $text, $ja = null ) {
    	return __( $text, $this->textdomain );
    }
  }
  $auto_youtube_summarize = new AYS_Main();