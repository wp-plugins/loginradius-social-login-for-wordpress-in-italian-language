<?php class LoginRadiusWidget extends WP_Widget {
	/** constructor */
	function LoginRadiusWidget() {
		parent::WP_Widget(
			'LoginRadius', //unique id
			'Login Radius', //title displayed at admin panel
			//Additional parameters
			array( 
				'description' => __( 'Loggati o accedi con Facebook, Twitter, Yahoo, Google e molti altri', 'LoginRadius' ))
			);
	}
	/** This is rendered widget content */
	function widget( $args, $instance ) {
	if( $args == NULL )
		$display_label = true;
	elseif ( is_array( $args ) )
		extract( $args );
		
		if($instance['hide_for_logged_in']==1 && is_user_logged_in()) return;
		
		echo $before_widget;

		if( !empty( $instance['title'] ) && !is_user_logged_in()){
		if( $display_label != false )
		{ $title = apply_filters( 'widget_title', $instance[ 'title' ] );}
		else{
			$title = ''; }
			echo $before_title . $title . $after_title;
		}

		if( !empty( $instance['before_widget_content'] ) ){
			echo $instance['before_widget_content'];
		}

		Login_Radius_Connect_button() ;

		if( !empty( $instance['after_widget_content'] ) ){
			echo $instance['after_widget_content'];
		}

		echo $after_widget;
	}

	/** Everything which should happen when user edit widget at admin panel */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = trim(strip_tags( $new_instance['title']));
		$instance['before_widget_content'] = trim($new_instance['before_widget_content']);
		$instance['after_widget_content'] = trim($new_instance['after_widget_content']);
		$instance['hide_for_logged_in'] = $new_instance['hide_for_logged_in'];

		return $instance;
	}

	/** Widget edit form at admin panel */
	function form( $instance ) {
	$title=get_option('title');
		/* Set up default widget settings. */
	$defaults = array( 'title' => $title, 'before_widget_content' => '', 'after_widget_content' => '' );

		foreach( $instance as $key => $value ) 
			$instance[ $key ] = esc_attr( $value );

		$instance = wp_parse_args( (array)$instance, $defaults );
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Titolo:', 'LoginRadius' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
			<label for="<?php echo $this->get_field_id( 'before_widget_content' ); ?>"><?php _e( 'Contenuto header widget:', 'LoginRadius' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'before_widget_content' ); ?>" name="<?php echo $this->get_field_name( 'before_widget_content' ); ?>" type="text" value="<?php echo $instance['before_widget_content']; ?>" />
			<label for="<?php echo $this->get_field_id( 'after_widget_content' ); ?>"><?php _e( 'Contenuto footer widget:', 'LoginRadius' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'after_widget_content' ); ?>" name="<?php echo $this->get_field_name( 'after_widget_content' ); ?>" type="text" value="<?php echo $instance['after_widget_content']; ?>" />
			<br /><br /><label for="<?php echo $this->get_field_id( 'hide_for_logged_in' ); ?>"><?php _e( 'Nascondi per gli utenti loggati:', 'LoginRadius' ); ?> </label>
	<input type="checkbox" id=" <?php echo $this->get_field_id( 'hide_for_logged_in' );?> " name="<?php echo $this->get_field_name( 'hide_for_logged_in' );?>" type="text" value="1" <?php if($instance['hide_for_logged_in']==1) echo 'checked="checked"';?> />
		</p>
<?php }}
add_action( 'widgets_init', create_function( '', 'return register_widget( "LoginRadiusWidget" );' ));?>