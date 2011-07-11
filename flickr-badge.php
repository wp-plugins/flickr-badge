<?php
/*
Plugin Name: flickr-badge
Plugin URI: http://www.tallphil.co.uk/flickr-badge/
Description: A widget to display a customised <a href="http://www.flickr.com/badge.gne">flickr badge</a> on your WordPress site
Version: 1.0
Author: Phil Ewels
Author URI: http://phil.ewels.co.uk
License: GPL2
*/

/*	Copyright 2011 Philip Ewels (email: phil@tallphil.co.uk)

		This program is free software; you can redistribute it and/or modify
		it under the terms of the GNU General Public License, version 2, as 
		published by the Free Software Foundation.

		This program is distributed in the hope that it will be useful,
		but WITHOUT ANY WARRANTY; without even the implied warranty of
		MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.	See the
		GNU General Public License for more details.

		You should have received a copy of the GNU General Public License
		along with this program; if not, write to the Free Software
		Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA	02110-1301	USA
*/

class flickrBadge extends WP_Widget {
	function flickrBadge() {
		$widget_ops = array('classname' => 'flickrBadge', 'description' => 'Displays a customised flickr badge on your site' );
		$this->WP_Widget('flickrBadge', 'Flickr Badge', $widget_ops);
	}
 
	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'count' => '5', 'display' => 'random', 'size' => 't', 'source' => 'user', 'id' => '', 'tag' => '' ) );
?>
	<p class="description">This widget displays a customised HTML <a href="http://www.flickr.com/badge.gne" target="_blank">flickr badge</a> (public photos only)</p>
	<p class="description">Please note that the widget is <strong>unstyled</strong> and does not allow you to display your buddy icon.</p>
	<p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($instance['title']); ?>" /></label></p>
	<p><label for="<?php echo $this->get_field_id('count'); ?>">Number of photos to display: <select class="widefat" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>">
			<?php for ($i=1; $i<=10; $i++){ ?><option value="<?php echo $i; ?>"<?php selected( $instance['count'], $i ); ?>><?php echo $i; ?></option><?php } ?>
		</select></label></p>
	<p><label for="<?php echo $this->get_field_id('display'); ?>">Sort Photos: <select class="widefat" id="<?php echo $this->get_field_id('display'); ?>" name="<?php echo $this->get_field_name('display'); ?>">
			<option value="random"<?php selected( $instance['display'], 'random' ); ?>>Random Selection</option>
		<option value="latest"<?php selected( $instance['display'], 'latest' ); ?>>Most Recent</option>
	 </select></label></p>
	<p><label for="<?php echo $this->get_field_id('size'); ?>">Photo Size: <select class="widefat" id="<?php echo $this->get_field_id('size'); ?>" name="<?php echo $this->get_field_name('size'); ?>">
			<option value="s"<?php selected( $instance['size'], 's' ); ?>>Square (75px x 75px)</option>
		<option value="t"<?php selected( $instance['size'], 't' ); ?>>Thumbnail (100px on longest side)</option>
		<option value="m"<?php selected( $instance['size'], 'm' ); ?>>Small (240px on longest side)</option>
	 </select></label></p>
	<p><label for="<?php echo $this->get_field_id('source'); ?>">Source of photos: <select class="widefat" id="<?php echo $this->get_field_id('source'); ?>" name="<?php echo $this->get_field_name('source'); ?>">
			<option value="user"<?php selected( $instance['source'], 'user' ); ?>>User</option>
			<option value="group"<?php selected( $instance['source'], 'group' ); ?>>Group</option>
			<option value="all"<?php selected( $instance['source'], 'all' ); ?>>Everyone</option>
	 </select></label></p>
	<p><label for="<?php echo $this->get_field_id('id'); ?>">User / Group ID: <span class="description">(use <a href="http://idgettr.com/" target="_blank">idGettr</a>)</span><input class="widefat" id="<?php echo $this->get_field_id('id'); ?>" name="<?php echo $this->get_field_name('id'); ?>" type="text" value="<?php echo attribute_escape($instance['id']); ?>" /></label></p>
	<p><label for="<?php echo $this->get_field_id('tag'); ?>">Tag / User Set ID (optional): <input class="widefat" id="<?php echo $this->get_field_id('tag'); ?>" name="<?php echo $this->get_field_name('tag'); ?>" type="text" value="<?php echo attribute_escape($instance['tag']); ?>" /></label></p>
	<p class="description">You can either enter a tag filter (text) or a set ID (numeric) above. Flickr doesn't allow both.</p>
	
<?php
	}
 
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = $new_instance['title'];
		$instance['count'] = $new_instance['count'];
		$instance['display'] = $new_instance['display'];
		$instance['size'] = $new_instance['size'];
		$instance['source'] = $new_instance['source'];
		$instance['id'] = $new_instance['id'];
		$instance['tag'] = $new_instance['tag'];
		return $instance;
	}
 
	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
 
		echo $before_widget;
		$title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
		
		$id = $instance['source'].'='.$instance['id']; // do this before adding _tag on to $instance['source']
		if($instance['tag'] !== '') {
			if (is_numeric($instance['tag'])) {
				$instance['source'] = $instance['source'].'_set&set='.$instance['tag'];
			} else {
				$instance['source'] = $instance['source'].'_tag&tag='.$instance['tag'];
			}
		}
 
		if (!empty($title))
			echo $before_title . $title . $after_title;
 
		// WIDGET CODE GOES HERE
		echo '<script type="text/javascript" src="http://www.flickr.com/badge_code_v2.gne?count='.$instance['count'].'&display='.$instance['display'].'&size='.$instance['size'].'&source='.$instance['source'].'&'.$id.'"></script>';
 
		echo $after_widget;
	}
 
}
add_action( 'widgets_init', create_function('', 'return register_widget("flickrBadge");') );

?>