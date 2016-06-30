<?php

class MIXSocialLinks extends WP_Widget {


	function __construct() {
		parent::__construct(
			'social_links', 
			'Social Links',
			array( 'description' => 'add social links',  )
		);

	}

	function widget( $args, $instance ) {

		?>
		
			<ul class="clearfix">
				<?php 
					foreach ($instance as $key => $link){
						if(!empty($link)){
							$class = $key;
							if($key == 'google'){
								$class = 'google-plus';
							}
							echo "<li><a href='".$link."' target='_blank'><i class='fa fa-".$class."'></i></a></li>";
						}
					}
				?>
			</ul>

		<?php
	}

	function form( $instance ) {

		?>
		<div class="social-form">
		<?php 
		foreach ($instance as $key => $value){
			if($value !== null):
			?>
			<p>
				<label for="<?php echo $this->get_field_id( $key ); ?>"><?php echo $key; ?></label> 
				<input class="widefat" id="<?php echo $this->get_field_id( $key ); ?>" name="<?php echo $this->get_field_name( $key ); ?>" type="text" value="<?php echo esc_attr( $value ); ?>">
				<a class="del-social" href="javascript:void(0)">del</a>
			</p>
			<?php
			endif;
		}
		?>
		</div>
		<button class="social-add-button">add</button>
		<script>
		function removeSocial(){
			if(jQuery(this).hasClass("new")){
				jQuery(this).parents("div.new").remove();
			}else{
				jQuery(this).parent().remove();
			}
		}

			jQuery(".del-social").click(removeSocial);
			jQuery(".social-add-button").click(function(e){
				e.preventDefault();
				var preName = '<?php echo $this->get_field_name("new"); ?>';
				var i = 0;
				var rows = jQuery(this).siblings(".social-form").find(".new").length;
				if(rows){
					i = rows/2;
				}
				
				var title = '[new]['+i+'][title]';
				var link = '[new]['+i+'][link]';

				title = preName.replace('[new]', title)
				link = preName.replace('[new]', link)
				var row = "<div class='new'><p>Title<input class='widefat' class='new-link title' name='"+title+"' type='text' value=''></p><p>Link<input class='widefat' class='new-link link' name='"+link+"' type='text' value=''><a class='del-social new' href='javascript:void(0)'>del</a></p></div>";
				jQuery(this).siblings(".social-form").append(row);
                		jQuery(this).siblings(".social-form").find("p:last-child").children(".del-social").click(removeSocial);
			});
		</script>
		<?php 
	}

	function update( $new_instance, $old_instance ) {
		foreach($new_instance as $key => $inst){
			if($key != 'new'){
				if(!empty($key)){
					$instance[$key] = ( ! empty( $inst ) ) ? strip_tags( $inst ) : '';
				}
			}else{
				foreach ($inst as $soc){
					if(!empty($soc['title'])){
						$instance[$soc['title']] = $soc['link'];
					}
				}
			}
			
		}
		return $instance;
	}

} 

class MIXPhoneByUserCountry extends WP_Widget {


	function __construct() {
		parent::__construct(
			'phone_by_country', 
			'Phone by country',
			array( 'description' => 'Phone according user country',  )
		);

	}

	function widget( $args, $instance ) {
			$userCountry = '';
			if(function_exists('MIXGetCountryByUserIp')){
				$userCountry = MIXGetCountryByUserIp();
			}
			$phone = '';

			if(!empty($instance)&&is_array($instance)){
				$i = 0;
				foreach($instance as $country => $pNumber){
					if($i === 0){
						$phone = $pNumber;
					}
					$i++;
					if(!empty($userCountry['countryCode'])){
						if($userCountry['countryCode'] == $country){
							$phone = $pNumber;
						}
					}	
				}
			}

		?>
		
			<li class="tell-number-box">
	            <a href=""><?=$phone ?></a>
	        </li>

		<?php
	}

	function form( $instance ) {
		if(isset($instance['title'])){
			unset($instance['title']);
		}
		?>
		<div class="phone-country-form">
		<?php 

		foreach ($instance as $key => $value){
			if($value !== null):
			?>
			<p>
				<label for="<?php echo $this->get_field_id( $key ); ?>"><?php echo $key; ?></label> 
				<input class="widefat" id="<?php echo $this->get_field_id( $key ); ?>" name="<?php echo $this->get_field_name( $key ); ?>" type="text" value="<?php echo esc_attr( $value ); ?>">
				<a class="del-country" href="javascript:void(0)">del</a>
			</p>
			<?php
			endif;
		}
		?>
		</div>
		<button class="country-add-button">add</button>
		<script>
		function removeCountry(){
			if(jQuery(this).hasClass("new")){
				jQuery(this).parents("div.new").remove();
			}else{
				jQuery(this).parent().remove();
			}
		}

			jQuery(".del-country").click(removeCountry);
			jQuery(".country-add-button").click(function(e){
				e.preventDefault();
				var preName = '<?php echo $this->get_field_name("new"); ?>';
				
				var i = 0;
				var rows = jQuery(this).siblings(".phone-country-form").find(".new").length;
				if(rows){
					i = rows/2;
				}
				
				var title = '[new]['+i+'][country-code]';
				var link = '[new]['+i+'][phone-number]';

				title = preName.replace('[new]', title);
				link = preName.replace('[new]', link);
				var row = "<div class='new'><p>country code<input class='widefat' class='new-link country-code' name='"+title+"' type='text' value=''></p><p>phone number<input class='widefat' class='new-link phone-number' name='"+link+"' type='text' value=''><a class='del-country new' href='javascript:void(0)'>del</a></p></div>";
				jQuery(".phone-country-form").append(row);
				jQuery(".phone-country-form p:last-child").children(".del-country").click(removeCountry);
			});
		</script>
		<?php 
	}

	function update( $new_instance, $old_instance ) {
		foreach($new_instance as $key => $inst){
			if($key != 'new'){
				if(!empty($key)){
					$instance[$key] = ( ! empty( $inst ) ) ? strip_tags( $inst ) : '';
				}
			}else{
				foreach ($inst as $soc){
					if(!empty($soc['country-code'])){
						$country = strtoupper($soc['country-code']);
						$instance[$country] = $soc['phone-number'];
					}
				}
			}
			
		}
		return $instance;
	}

} 

class CustomlanguageSwitcher extends WP_Widget {


	function __construct() {
		parent::__construct(
			'custom-lang-switcher', 
			'Custom language Switcher',
			array( 'description' => 'add custom language switcher',  )
		);

	}

	function widget( $args, $instance ) {
			$langs = icl_get_languages('skip_missing=N&orderby=KEY&order=DIR&link_empty_to=str');
		?>
		
			<ul class="nav navbar-nav navbar-middle menu-middle lang-menu">
				<li>
					<div class='selectBox'>
						<span class='selected'></span>
						<div class="selectOptions" >
							<?php 
								foreach($langs as $lang){
									$href = $lang['url'];
									if($lang['active'] == 1){
										$href = 'javascript:void(0)';
									}
									?>
									<span <?php if($lang['active'] == 1){echo 'style="display: none"';} ?> class="selectOption <?php if($lang['active'] == 1){echo 'active';} ?>"><a href='<?=$href  ?>'><?=ucfirst($lang['language_code']) ?></a></span>
									<?php
								}
							?>
						</div>
					</div>
				</li>
			</ul>

		<?php
	}

} 

class NewCustomlanguageSwitcher extends WP_Widget {


	function __construct() {
		parent::__construct(
			'custom-lang-switcher-new', 
			'New Custom language Switcher',
			array( 'description' => 'add custom language switcher for new template',  )
		);

	}

	function widget( $args, $instance ) {
			$langs = icl_get_languages('skip_missing=N&orderby=KEY&order=DIR&link_empty_to=str');

		?>
			<li class="language-box">
                <ul class="">
                    <li>
                        <div class='selectBox'>
                            <span class='selected'></span>
                            <div style="display: none;" class="selectOptions" >
                            	<?php 
								foreach($langs as $lang){
									$href = $lang['url'];
									if($lang['active'] == 1){
										$href = 'javascript:void(0)';
									}
									?>
									<span <?php if($lang['active'] == 1){echo 'style="display: none"';} ?> class="selectOption <?php if($lang['active'] == 1){echo 'active';} ?>"><a href='<?=$href  ?>'><img src=<?=$lang['country_flag_url'] ?> /><?=ucfirst($lang['native_name']) ?></a></span>
									<?php
								}
							?>
                            </div>
                        </div>
                    </li>
                </ul>
            </li>

			<!-- <ul class="nav navbar-nav navbar-middle menu-middle lang-menu">
				<li>
					<div class='selectBox'>
						<span class='selected'></span>
						<div class="selectOptions" >
							<?php 
								foreach($langs as $lang){
									$href = $lang['url'];
									if($lang['active'] == 1){
										$href = 'javascript:void(0)';
									}
									?>
									<span <?php if($lang['active'] == 1){echo 'style="display: none"';} ?> class="selectOption <?php if($lang['active'] == 1){echo 'active';} ?>"><a href='<?=$href  ?>'><?=ucfirst($lang['language_code']) ?></a></span>
									<?php
								}
							?>
						</div>
					</div>
				</li>
			</ul> -->

		<?php
	}

} 


class MIXPhoneNumber extends WP_Widget {


	function __construct() {
		parent::__construct(
			'mix-phone-number', 
			'Phone Number',
			array( 'description' => 'add phone number',  )
		);

	}

	function widget( $args, $instance ) {
			$phone = (!empty($instance['phone']))?$instance['phone']:'';
		?>
		
			<ul class="nav navbar-nav navbar-middle menu-middle">
				<li><a class="phone" href="tel:<?=$phone ?>"><i class="fa fa-phone"></i><?=$phone ?></a></li>
			</ul>

		<?php
	}

	function form( $instance ) {
		$phone = (!empty($instance['phone']))?$instance['phone']:'';
		?>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'phone' ); ?>">Phone</label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'phone' ); ?>" name="<?php echo $this->get_field_name( 'phone' ); ?>" type="text" value="<?php echo esc_attr( $phone ); ?>">
		</p>
		
		<?php 
	}

	function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['phone'] = ( ! empty( $new_instance['phone'] ) ) ? strip_tags( $new_instance['phone'] ) : '';

		return $instance;
	}

}

class MIXNewPhoneNumber extends WP_Widget {


	function __construct() {
		parent::__construct(
			'mix-new-phone-number', 
			'New Phone Number',
			array( 'description' => 'Phone Number for new template',  )
		);

	}

	function widget( $args, $instance ) {
			$phone = (!empty($instance['phone']))?$instance['phone']:'';
		?>
		
			<li class="tell-number-box">
	            <a href=""><?=$phone ?></a>
	        </li>

		<?php
	}

	function form( $instance ) {
		$phone = (!empty($instance['phone']))?$instance['phone']:'';
		?>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'phone' ); ?>">Phone</label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'phone' ); ?>" name="<?php echo $this->get_field_name( 'phone' ); ?>" type="text" value="<?php echo esc_attr( $phone ); ?>">
		</p>
		
		<?php 
	}

	function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['phone'] = ( ! empty( $new_instance['phone'] ) ) ? strip_tags( $new_instance['phone'] ) : '';

		return $instance;
	}

} 

class MIXTopPosts extends WP_Widget {


	function __construct() {
		parent::__construct(
			'mix-top-posts', 
			'Top posts',
			array( 'description' => 'listing of most viewed posts',  )
		);

	}

	function widget( $args, $instance ) {
			$args = array('posts_per_page' => 3);
			if(function_exists('pvc_get_most_viewed_posts')):
			$posts = pvc_get_most_viewed_posts($args);

		?>
			<span class="category-blog">top posts</span>
			<ul class="top-list">
				<?php 
					foreach($posts as $post){
						$views = pvc_get_post_views($post->ID);
						?>
						<li><a href="<?php the_permalink() ?>"><span class="main-text"><?=$post->post_title ?></span><span class="second-text"><?=$views ?> views</span></a> </li>
						<?php
					}
				?>
			</ul>
			

		<?php
			endif;
	}

} 


function MIX_register_widgets() {
	register_widget( 'MIXSocialLinks' );
	register_widget( 'CustomlanguageSwitcher' );
	register_widget( 'MIXPhoneNumber' );
	register_widget( 'MIXNewPhoneNumber' );
	register_widget( 'NewCustomlanguageSwitcher' );
	register_widget( 'MIXTopPosts' );
	register_widget( 'MIXPhoneByUserCountry' );
}
add_action( 'widgets_init', 'MIX_register_widgets' );
