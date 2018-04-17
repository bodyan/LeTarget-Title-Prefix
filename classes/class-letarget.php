<?php

class LeTargetTitlePrefix {

	public function init() 
	{
        $class = __CLASS__;
        new $class;
    }
	
	public $options;
    public function __construct()
    {
		$this->options = get_option( 'letarget_option_name' );
		add_action( 'admin_menu', array( $this, 'letarget_admin_menu_page' ) );
		add_action( 'admin_init', array( $this, 'page_init' ) );
		add_filter( 'the_title', array( $this, 'custom_prefix' ), 10, 2 );
	}
	
	public function letarget_admin_menu_page() 
	{
		global $_wp_last_object_menu;
		$_wp_last_object_menu++;  
		
		add_menu_page(
			'Le Target Title Prefix Plugin',		// page title
			'Le Target',							// menu title
			'manage_options',						// capability
			'letarget-prefix',						// menu slug
			array( $this, 'letarget_main_page' ),	// callback function	
			'dashicons-admin-site',					// icon
			$_wp_last_object_menu					// position
		);
		
	}
		
	public function letarget_main_page() 
	{
        ?>
        <div class="wrap">
            <h1>LeTarget Custom Title Prefix</h1>
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'letarget_option_group' );
                do_settings_sections( 'letarget-prefix' );
                submit_button();
            ?>
            </form>
        </div>
        <?php
	}

    public function page_init()
    {        
        register_setting(
            'letarget_option_group',		// Option group
            'letarget_option_name',			// Option name
            array( $this, 'sanitize' ) 
        );

        add_settings_section(
            'enabled',
            'Active:',
            array( $this, 'enable_callback' ), 
			'letarget-prefix', 
			'setting_section_id' 
        );  

        add_settings_section(
            'position', 
            'Position:', 
            array( $this, 'position_callbak' ), 
            'letarget-prefix', 
            'setting_section_id'        
        );      

        add_settings_section(
            'prefix', 
            'Custom Title Prefix', 
            array( $this, 'prefix_callback' ), 
            'letarget-prefix', 
            'setting_section_id'
        );      
    }

    public function sanitize( $input )
    {
		$new_input = array();
		if( isset( $input['enabled'] ) )
            $new_input['enabled'] = sanitize_text_field( $input['enabled'] );
		
		if( isset( $input['position'] ) )
            $new_input['position'] = sanitize_text_field( $input['position'] );

        if( isset( $input['prefix'] ) )
            $new_input['prefix'] = sanitize_text_field( $input['prefix'] );

        return $new_input;
    }

    public function enable_callback()
    {
         printf(
            '<input type="checkbox" id="enabled" name="letarget_option_name[enabled]" value="enabled" %s/>',
            isset( $this->options['enabled'] ) ? 'checked' : ''
        );
    }

    public function position_callbak()
    {
		printf(
			'At the beginning: <input type="radio" id="position" name="letarget_option_name[position]" value="start"  %s/>',
			$checked = ( $this->options['position'] === 'start' ) ? 'checked' : ''
		);

		printf(
			'At the end: <input type="radio" id="position" name="letarget_option_name[position]" value="end"  %s/>',
			$checked = ( $this->options['position'] === 'end' ) ? 'checked' : ''
		);
    }

    public function prefix_callback()
    {
        printf(
            '<input type="text" id="prefix" name="letarget_option_name[prefix]" value="%s" />',
            isset( $this->options['prefix'] ) ? esc_attr( $this->options['prefix']) : ''
        );
	}

	public function custom_prefix ($value, $id)
	{
		if( get_post_type( $id ) !== 'post' || $this->options['enabled'] !== 'enabled') return $value;
		if($this->options['prefix'] == '' || strlen($value) == 0) return $value;
		return $title = ($this->options['position'] === 'start') ? $this->options['prefix'].' '.$value : $value.' '.$this->options['prefix'];
	}

	
}