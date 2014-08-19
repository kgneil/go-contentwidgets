<?php

class GO_Content_Widgets
{
	public $id_base = 'go-content-widgets';
	private $admin;
	private $script_config;
	private $layout_preferences;

	/**
	 * constructor
	 */
	public function __construct()
	{
		add_action( 'init', array( $this, 'init' ) );
	}//end __construct

	/**
	 * hooked to the init action
	 */
	public function init()
	{
		if ( is_admin() )
		{
			$this->admin();
		}//end if

		wp_register_script(
			$this->id_base,
			plugins_url( 'js/lib/go-content-widgets.js', __FILE__ ),
			array( 'jquery' ),
			$this->script_config( 'version' ),
			TRUE
		);

		// @todo this should only happen on `is_single`, but it was giving me trouble so I I'm moving on...
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );

		$data = array(
			'layout_preferences' => get_option( $this->id_base . '-layout-preferences' ),
		);
		// @TODO: tie this to the front-end go-content-widgets JS rather than jquery
		wp_localize_script( 'jquery', 'go_content_stats', $data );
	}//end init

	/**
	 * script config
	 */
	public function script_config( $key = NULL )
	{
		if ( ! $this->script_config )
		{
			$this->script_config = apply_filters( 'go_config', array( 'version' => 1 ), 'go-script-version' );
		}//end if

		if ( $key )
		{
			return isset( $this->script_config[ $key ] ) ? $this->script_config[ $key ] : NULL;
		}//end if

		return $this->script_config;
	}//end script_config

	/**
	 * admin object accessor method
	 */
	public function admin()
	{
		if ( ! $this->admin )
		{
			require_once __DIR__ . '/class-go-content-widgets-admin.php';
			$this->admin = new GO_Content_Widgets_Admin;
		}//end if

		return $this->admin;
	}//end admin

	public function wp_enqueue_scripts()
	{
		wp_enqueue_script( $this->id_base );
	}//end wp_enqueue_scripts

	/**
	 * returns the widget layout preferences for the site
	 */
	public function layout_preferences()
	{
		if ( ! $this->layout_preferences )
		{
			$this->layout_preferences = get_option( $this->id_base . '-layout-preferences', array() );
		}//end if

		return $this->layout_preferences;
	}//end layout_preferences
}//end class

function go_content_widgets()
{
	global $go_content_widgets;

	if ( ! $go_content_widgets )
	{
		$go_content_widgets = new GO_Content_Widgets;
	}//end if

	return $go_content_widgets;
}//end go_content_widgets