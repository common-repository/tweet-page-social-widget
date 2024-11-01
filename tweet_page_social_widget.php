<?php
/*
Plugin Name: Tweet Page Social Widget
Plugin URI: http://www.marijnrongen.com/wordpress-plugins/tweet-page-social-widget/
Description: Place a Tweet button as a widget and/or shortcode, enables visitors to recommend the page via Twitter.
Version: 1.1
Author: Marijn Rongen
Author URI: http://www.marijnrongen.com
*/

class MR_Tweet_Page_Widget extends WP_Widget {
	function MR_Tweet_Page_Widget() {
		$widget_ops = array( 'classname' => 'MR_Tweet_Page_Widget', 'description' => 'Place a Tweet button as a widget.' );
		$control_ops = array( 'id_base' => 'mr-tweet-page-widget' );
		$this->WP_Widget( 'mr-tweet-page-widget', 'Tweet Page Social Widget', $widget_ops, $control_ops );
	}
	
	function widget( $args, $instance) {
		extract( $args );
		$layout = empty($instance['layout']) ? 'horizontal' : $instance['layout'];
		echo $before_widget;
		echo "\n	<a href=\"http://twitter.com/share\" class=\"twitter-share-button\"";
		if (!empty($instance['url'])) {
			echo ' data-url="'.$instance['url'].'"';
		}
		if (!empty($instance['text'])) {
			echo ' data-text="'.$instance['text'].'"';
		}
		echo ' data-count="'.$instance['layout'].'"';
		if (!empty($instance['via'])) {
			echo ' data-via="'.$instance['via'].'"';
		} 
		if (!empty($instance['related'])) {
			echo ' data-related="'.$instance['related'];
			if (!empty($instance['related_descr'])) {
				echo ':'.$instance['related_descr'];
			}
			echo '"';
		}
		echo '>Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>';
		echo $after_widget;
	}
	
	function shortcode_handler( $atts, $content=null, $code="" ) {
		extract( shortcode_atts( array(
			'text' => '',
			'url' => ''
		), $atts ) );
		if ($text == '') {
			$text = the_title('','',false);
		}
		if ($url != '') {
			$url = urlencode($url);
		} else {
			$url = get_permalink();
		}
		$retval = '<a href="http://twitter.com/share" class="twitter-share-button" data-url="'.$url.'" data-text="'.$text.'" data-count="horizontal"';
		$retval .= '>Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>';
		return $retval;
	}
	
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['layout'] = $new_instance['layout'];
		$instance['via'] = strip_tags(str_replace('@', '', $new_instance['via']));
		$instance['related'] = strip_tags(str_replace('@', '', $new_instance['related']));
		$instance['related_descr'] = strip_tags($new_instance['related_descr']);
		$instance['url'] = strip_tags($new_instance['url']);
		$instance['text'] = strip_tags($new_instance['text']);
		return $instance;
	}
	
	function form($instance) {
		$instance = wp_parse_args((array) $instance, array( 'layout' => 'horizontal', 'via' => '', 'related' => '', 'related_descr' => '', 'url' => '', 'text' => ''));
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'layout' ); ?>">Button style:</label>
			<select id="<?php echo $this->get_field_id( 'layout' ); ?>" name="<?php echo $this->get_field_name( 'layout' ); ?>" class="widefat" style="width:100%;">
				<option <?php if ( "horizontal" == $instance['layout'] ) echo 'selected="selected"'; ?> value="horizontal">Horizontal count</option>
				<option <?php if ( "vertical" == $instance['layout'] ) echo 'selected="selected"'; ?> value="vertical">Vertical count</option>
				<option <?php if ( "none" == $instance['layout'] ) echo 'selected="selected"'; ?> value="none">No count</option>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'via' ); ?>">Primary Twitter account to recommend (<b>Optional</b>):</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'via' ); ?>" name="<?php echo $this->get_field_name( 'via' ); ?>" value="<?php echo $instance['via']; ?>" />
		</p>	
		<p>
			<label for="<?php echo $this->get_field_id( 'related' ); ?>">Second Twitter account to recommend (<b>Optional</b>):</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'related' ); ?>" name="<?php echo $this->get_field_name( 'related' ); ?>" value="<?php echo $instance['related']; ?>" />
		</p>		
		<p>
			<label for="<?php echo $this->get_field_id( 'related_descr' ); ?>">Second Twitter account description (<b>Optional</b>):</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'related_descr' ); ?>" name="<?php echo $this->get_field_name( 'related_descr' ); ?>" value="<?php echo $instance['related_descr']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'text' ); ?>">Text to include in Tweet (<b>Optional</b>, leave empty for the title of the page the button is on):</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'text' ); ?>" name="<?php echo $this->get_field_name( 'text' ); ?>" value="<?php echo $instance['text']; ?>" />
		</p>	
		<p>
			<label for="<?php echo $this->get_field_id( 'url' ); ?>">URL to recommend (<b>Optional</b>, leave empty for the URL of the page the button is on):</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'url' ); ?>" name="<?php echo $this->get_field_name( 'url' ); ?>" value="<?php echo $instance['url']; ?>" />
		</p>	
		<?php
	}
}
add_shortcode( 'tweet', array('MR_Tweet_Page_Widget', 'shortcode_handler') );
add_action('widgets_init', create_function('', 'return register_widget("MR_Tweet_Page_Widget");'));
?>