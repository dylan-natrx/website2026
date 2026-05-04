<?php

// -----------------------------------------
// semplice
// admin/editor/module.php
// -----------------------------------------

if(!class_exists('module_api')) {
	class module_api {

		// public vars
		public $module;
		public $animate_api;
		public $detect;
		public $post_id;
		public $posts_filter;
		public $script_execution;

		public function __construct() {
			global $detect;
			$this->detect = $detect;

			// include animate api
			require get_template_directory() . '/admin/editor/animate.php';

			// defaults for public vars
			$this->post_id = 0;
			$this->posts_filter = false;
			$this->script_execution = 'normal';
		}

		// -----------------------------------------
		// generate html output while iterate through ram
		// -----------------------------------------

		public function generate_output($ram, $section_id, $section) {
	
			// assign public vars
			$this->post_id = isset($ram['post_id']) ? $ram['post_id'] : 0;
			$this->posts_filter = isset($ram['posts_filter']) ? $ram['posts_filter'] : false;
			$this->script_execution = isset($ram['script_execution']) ? $ram['script_execution'] : 'normal';

			// output
			$output = array(
				'html'	=> '',
				'css'	=> '',
				'motion'	=> array(
					'css' => '',
					'js'  => '',
				),
				'images' => array()
			);
			$custom_height = '';
			$css_classes = '';
			$section_class = 'content-block';
			$section_element = $section_id;
			$append_content = '';
			$section_content = '';
			// add section element to ram
			$ram['section_element'] = $section_element;

			// section height
			$ram['section_height'] = array(
				'mode' => 'dynamic',
				'height' => 'auto',
			);

			// check if section is in ram
			if(isset($ram[$section_id])) {

				// assign styles
				$styles = $ram[$section_id]['styles']['xl'];

				// css classes
				if(isset($ram[$section_id]['classes']) && !empty($ram[$section_id]['classes'])) {
					$css_classes = ' ' . $ram[$section_id]['classes'];
				}

				// section only module?
				if(isset($ram[$section_id]['type']) && $ram[$section_id]['type'] == 'section-module') {
					$css_classes .= ' section-module';
				}

				// locked section
				if(isset($ram[$section_id]['locked']) && true === semplice_boolval($ram[$section_id]['locked'])) {
					$css_classes .= ' locked-section';
				}

				// section height
				if(isset($ram[$section_id]['layout']['data-height'])) {
					if($ram[$section_id]['layout']['data-height'] == 'fullscreen') {
						$ram['section_height'] = array(
							'mode' => 'fullscreen',
							'height' => 'auto',
						);
					} else if($ram[$section_id]['layout']['data-height'] == 'custom') {
						$ram['section_height'] = array(
							'mode' => 'custom',
							'height' => $ram[$section_id]['customHeight']['xl']['height'],
						);
						$output['css'] .= $this->generate_css('container', $ram[$section_id]['customHeight'], $section_id, false);
					}
				}

				// desktop visibility
				if(isset($ram[$section_id]['options']['desktop_visibility']) && $ram[$section_id]['options']['desktop_visibility'] == 'hidden' && $ram['visibility'] == 'frontend') {
					$output['css'] .= '@media screen and (min-width: 1170px) { #' . $section_id . ' { display: none !important; }}';
				}

				// get row content
				$row_content = $this->row_iterate($ram, $section);

				// cover
				if($section_id == 'cover') {
					// cover visibility
					$cover_visibility = isset($ram['cover_visibility']) ? $ram['cover_visibility'] : 'hidden';
					// add cover visibility to layout attributes
					$ram[$section_id]['layout']['data-cover'] = $cover_visibility;
					// set cover as section class
					$section_class = 'semplice-cover';
					// is cover empty?
					$first_key = key($section);
					if(isset($section['columns']) && empty($section['columns']) || isset($section[$first_key]['columns']) && empty($section[$first_key]['columns'])) {
						$section_class .= ' is-empty-cover';
					}
					// arrow atts
					$arrow_visibility = isset($styles['arrow_visibility']) ? $styles['arrow_visibility'] : 'visible';
					$arrow_cover = isset($styles['arrow_cover']) ? $styles['arrow_cover'] : false;
					$arrow_width = isset($styles['arrow_width']) ? $styles['arrow_width'] : '2.9444rem';
					// add width to css
					$output['css'] .= '#content-holder .semplice-cover .show-more svg, #content-holder .semplice-cover .show-more img { width: ' . $arrow_width . '; }';
					// is custom arrow?
					if(false !== $arrow_cover) {
						$arrow_image = '<img src="' . semplice_get_image($arrow_cover, 'full') . '" alt="custom-cover-arrow">';
					} else {
						$arrow_image = get_svg('frontend', '/icons/arrow_down');
						// add color css
						if(isset($styles['arrow_color'])) {
							$output['css'] .= '#content-holder .semplice-cover .show-more svg { fill: ' . $styles['arrow_color'] . '; }';
						}
					}
					// arrow output
					$append_content .= '<a class="show-more show-more-' . $arrow_visibility . ' semplice-event" data-event-type="helper" data-event="scrollToContent">' . $arrow_image . '</a>';
					// frontend only options
					if($ram['visibility'] == 'frontend') {
						// transparent navbar while in cover
						$output['css'] .= '.cover-transparent { background: rgba(0,0,0,0) !important; }';
						// start at top
						$output['css'] .= '#content-holder .sections { margin-top: 0px !important; }';
						// on the frontend, change the section element id to avoid problems in the coverslider and single page app motions
						$section_element = 'cover-' . $this->post_id;
					}
				}

				// add background image to images array
				if(isset($styles['background-image'])) {
					$output['images'][$styles['background-image']] = semplice_image_with_sizes($styles['background-image']);
				}

				// get container background video
				$container_video = '';
				if(isset($ram[$section_id]['containerStyles'])) {
					// container styles short
					$container_styles = $ram[$section_id]['containerStyles']['xl'];
					// get background video
					$video = $this->background_video($container_styles, $section_element, $ram['visibility'], true);
					// add to content
					$container_video = $video['html'];
					$output['css'] .= $video['css'];
					// add background image to images array
					if(isset($container_styles['background-image'])) {
						$output['images'][$container_styles['background-image']] = semplice_image_with_sizes($container_styles['background-image']);
					}
				}

				// add section content
				$section_content = '<div class="container">' . $container_video . $row_content['html'] . '</div>';

				// get bg video or image on frontend
				if(isset($styles['background_type']) && $styles['background_type'] == 'vid') {
					// get background video
					$video = $this->background_video($styles, $section_element, $ram['visibility'], false);
					// add to content
					$append_content .= $video['html'];
					$output['css'] .= $video['css'];
				} else if($section_id == 'cover') {
					if($ram['visibility'] == 'frontend') {
						// get cover image
						$append_content .= '<div class="cover-image-wrapper fp-bg"' . $this->get_cover_image_atts($styles) . '><div class="cover-image"></div></div>';
						// is tilt scale set?
						if(isset($styles['mousemove_effect']) && $styles['mousemove_effect'] == 'tilt') {
							// set default perspective
							if(!empty($styles['tilt_perspective'])) {
								$output['css'] .= '#' . $section_element . ' .semplice-cover-inner { transform: translateZ(0) perspective(' . $styles['tilt_perspective'] . 'px); }';
							}
							// image scale
							if(!empty($styles['tilt_scale'])) {
								$output['css'] .= '#' . $section_element . ' .cover-image-wrapper { transform: scale(' . ($styles['tilt_scale'] / 100) . ') !important; }';
							}
						}
						// only append styles css for the frontend
						if($ram['visibility'] != 'editor') {
							// cover img css
							$output['css'] .= $this->generate_css('cover', $ram[$section_id]['styles'], $section_element, false);
							// get container styles
							if(isset($ram[$section_id]['containerStyles'])) {
								$output['css'] .= $this->generate_css('container', $ram[$section_id]['containerStyles'], $section_element, false);
							}
						}
						
					} else {
						// just add the divs for admin cover effects, rest will be done in js
						$append_content = '<div class="cover-image-wrapper fp-bg"><div class="cover-image"></div></div>';
					}
				}

				// get cover effects for the frontend
				if($ram['visibility'] == 'frontend') {
					// get cover effects
					$ram[$section_id]['layout'] = $this->get_cover_effects($styles, $ram[$section_id]['layout']);
				}	

				// masterblock
				$masterblock = '';
				if(isset($ram[$section_id]['masterblock']) && strpos($ram[$section_id]['masterblock'], 'master_') !== false) {
					$masterblock = ' data-delete-master="' . $ram[$section_id]['masterblock'] . '"';
				}

				// append cover or background video
				$section_content = $append_content . $section_content;

				// if cover, add cover inner wrapper
				if($section_id == 'cover') {
					$section_content = '<div class="semplice-cover-inner"' . $this->get_cover_effect_settings($styles) . '>' . $section_content . '</div>';
				}

				// section start
				$output['html'] = '
					<section id="' . $section_element . '" class="' . $section_class . $css_classes . '"' . $masterblock . ' ' . $this->get_attributes($ram[$section_id]['layout']) . '>
						' . $section_content . '
					</section>				
				';

				// section css styles (not on cover)
				if($ram['visibility'] != 'editor') {
					if($section_id != 'cover' && $ram['visibility'] != 'editor') {
						// get section styles
						$output['css'] .= $this->generate_css('section', $ram[$section_id]['styles'], $section_id, false);
						// get container styles
						if(isset($ram[$section_id]['containerStyles'])) {
							$output['css'] .= $this->generate_css('container', $ram[$section_id]['containerStyles'], $section_id, false);
						}
					}
				}

				// section styles
				$output['css'] .= $row_content['css'];

				// get motion css / js
				$output = $this->animate_api->get_motion_output($output, $ram, $section_id, 'section', $this->post_id);

				// add motion css from rows
				$output['motion']['css'] .= $row_content['motion']['css'];

				// add motion js from rows
				$output['motion']['js'] .= $row_content['motion']['js'];

				// add images
				$output['images'] = $output['images'] + $row_content['images'];
			}

			// output
			return $output;
		}

		// -----------------------------------------
		// get cover image atts
		// -----------------------------------------

		public function get_cover_image_atts($styles) {
			$image = '';
			$size = 'auto';
			if(!empty($styles['background-image'])) {
				// get background image
				if(!is_numeric($styles['background-image'])) {
					$image = semplice_get_external_image($styles['background-image']);
					$image = array($image['url'], $image['width'], $image['height']);
				} else {
					$image = wp_get_attachment_image_src($styles['background-image'], 'full');
				}
				if(!empty($styles['background-size']) && $styles['background-size'] == 'cover') {
					$size = 'cover';
				}
				// image
				$image = ' data-src="' . $image[0] . '" data-width="' . $image[1] . '" data-height="' . $image[2] . '" data-size="' . $size . '"';
			}
			// return
			return $image;
		}

		// -----------------------------------------
		// cover effects
		// -----------------------------------------

		public function get_cover_effects($styles, $layout) {
			// backwards compatibility for older effects structure. Check if effect is set, if not look if older effects are already set
			if(isset($styles['image_effect'])) {
				$layout['data-cover-effect'] = $styles['image_effect'];
			} else if(isset($styles['zoom']) && $styles['zoom'] == 'on') {
				$layout['data-cover-effect'] = 'zoom';
			} else if(isset($styles['parallax']) && $styles['parallax'] == 'on') {
				$layout['data-cover-effect'] = 'parallax';
			}
			// mouse move effects
			if(isset($styles['mousemove_effect'])) {
				$layout['data-cover-mousemove'] = $styles['mousemove_effect'];
			}
			// return
			return $layout;
		}

		// -----------------------------------------
		// get cover effect setting
		// -----------------------------------------

		public function get_cover_effect_settings($styles) {
			// output
			$output = array();
			// settings
			$settings = array('tilt_scale', 'tilt_max', 'tilt_perspective', 'displacement_map', 'displacement_type', 'displacement_dir', 'displacement_speed', 'displacement_sprite_x', 'displacement_sprite_y', 'displacement_filter_x', 'displacement_filter_y', 'displacement_max_growth', 'displacement_grow_speed');
			// iterate settings
			foreach ($settings as $setting) {
				if(isset($styles[$setting])) {
					if($setting == 'displacement_map') {
						$image = wp_get_attachment_image_src($styles[$setting], 'full');
						if(false !== $image && is_array($image)) {
							$output[$setting] = $image[0];
						}
					} else {
						$output[$setting] = $styles[$setting];
					}
				}
			}
			// output
			return ' data-effect-settings=\'' . json_encode($output) . '\'';
		}

		// -----------------------------------------
		// row, iterate through columns
		// -----------------------------------------

		public function row_iterate($ram, $section) {

			// output
			$output = array(
				'html' => '',
				'column_html' => '',
				'css'  => '',
				'motion'	=> array(
					'css' => '',
					'js'  => '',
				),
				'images' => array(),
			);

			// is old single row mode?
			if(isset($section['columns'])) {
				// iterate columns
				foreach($section['columns'] as $column_id => $column_content) {
					$output = $this->get_columns($output, $ram, $column_id, $column_content);
				}
				// add row to output
				$output['html'] = '<div id="' . $section['row_id'] . '" class="row">' . $output['column_html'] . '</div>';
			} else {
				foreach($section as $row_id => $columns) {
					// rest column html before iterate
					$output['column_html'] = '';
					// iterate columns
					foreach($columns['columns'] as $column_id => $column_content) {
						// column html
						$output = $this->get_columns($output, $ram, $column_id, $column_content);
					}
					// add row to output
					$output['html'] .= '<div id="' . $row_id . '" class="row">' . $output['column_html'] . '</div>';
				}
			}

			return $output;
		}

		// -----------------------------------------
		// get columns and iterate through them
		// -----------------------------------------

		public function get_columns($output, $ram, $column_id, $column_content) {
			// column html
			$columns_content = $this->column_iterate($ram, $column_id, $column_content);
			// add to html output
			$output['column_html'] .= $columns_content['html'];
			// add to css output
			$output['css'] .= $columns_content['css'];
			// motion css
			$output['motion']['css'] .= $columns_content['motion']['css'];
			// motion js
			$output['motion']['js'] .= $columns_content['motion']['js'];
			// add to images
			$output['images'] = $output['images'] + $columns_content['images'];
			// return output
			return $output;
		}

		// -----------------------------------------
		// iterate through column contents
		// -----------------------------------------

		public function column_iterate($ram, $column_id, $column_content) {

			// output
			$output = array(
				'html' => '',
				'css'  => '',
				'motion'	=> array(
					'css' => '',
					'js'  => '',
				),
				'images' => array(),
			);
			$content_html = '';
			$column_classes = '';

			// check if column id is in ram
			if(isset($ram[$column_id])) {

				// only append styles css for the frontend
				if($ram['visibility'] != 'editor') {
					$output['css'] = $this->generate_css('column', $ram[$column_id]['styles'], $column_id, false);
				}

				// motion css / js
				$output = $this->animate_api->get_motion_output($output, $ram, $column_id, 'column', $this->post_id);

				// get column content
				foreach($column_content as $content_id) {

					// look if content id is in ram
					if(isset($ram[$content_id])) {
						// set module file
						$module = get_template_directory() . '/admin/editor/modules/' . $ram[$content_id]['module'] . '.php';

						// add section element to ram content
						$ram[$content_id]['section_element'] = '#' . $ram['section_element'];

						// add fullscreen
						$ram[$content_id]['section_height'] = $ram['section_height'];

						// check if module exists
						if(file_exists($module)) {
							$values = array(
								'module_name'		=> $ram[$content_id]['module'],
								'content_id'  		=> $content_id,
								'content'	  		=> $ram[$content_id],
							);
							
							// get content
							$content = $this->content($values, $ram['visibility'], false);

							// add to html output
							$content_html .= $content['html'];

							// add module specific css
							$output['css'] .= $content['css'];

							// only append styles css for the frontend
							if($ram['visibility'] != 'editor') {
								$output['css'] .= $this->generate_css('content', $ram[$content_id]['styles'], $content_id, $ram[$content_id]['module']);
							}

							// motion css / js
							$output = $this->animate_api->get_motion_output($output, $ram, $content_id, 'content', $this->post_id);

							// add to images
							$output['images'] = $output['images'] + $content['images'];
						}				
					}
				}
				
				// css classes
				if(isset($ram[$column_id]['classes']) && !empty($ram[$column_id]['classes'])) {
					$column_classes .= ' ' . $ram[$column_id]['classes'];
				}

				// column type
				if(isset($ram[$column_id]['type']) && $ram[$column_id]['type'] == 'spacer') {
					$column_classes .= ' spacer-column';
				}

				// locked column
				if(isset($ram[$column_id]['locked']) && true === semplice_boolval($ram[$column_id]['locked'])) {
					$column_classes .= ' locked-column';
				}

				// column start
				$output['html'] = '<div id="' . $column_id . '" class="column' . $column_classes . '" ';

				// column width
				foreach ($ram[$column_id]['width'] as $width => $value) {
					if(!empty($width)) {
						$output['html'] .= 'data-' . $width . '-width="' . $value . '" ';
					}
				}

				// column end
				$output['html'] .= $this->get_attributes($ram[$column_id]['layout']) . '>';

				// get styles
				$styles = $ram[$column_id]['styles']['xl'];

				// get bg video
				$video = $this->background_video($styles, $column_id, $ram['visibility'], false);

				// add background image to images array
				if(isset($styles['background-image'])) {
					$output['images'][$styles['background-image']] = semplice_image_with_sizes($styles['background-image']);
				}

				// add video css
				$output['css'] .= $video['css'];

				// add video html
				$output['html'] .= $video['html'];

				// define wrapper video
				$wrapper_video = array('html' => '');

				// content wrapper styles
				if(isset($ram[$column_id]['wrapperStyles'])) {
					// shortname
					$wrapper_styles = $ram[$column_id]['wrapperStyles'];

					// only append styles css for the frontend
					if($ram['visibility'] != 'editor') {
						$output['css'] .= $this->generate_css('contentwrapper', $ram[$column_id]['wrapperStyles'], $column_id, false);
					}

					// get bg video
					$wrapper_video = $this->background_video($ram[$column_id]['wrapperStyles']['xl'], $column_id, $ram['visibility'], false);

					// add video css
					$output['css'] .= $wrapper_video['css'];

					// add background image to images array
					if(isset($wrapper_styles['xl']['background-image'])) {
						$output['images'][$wrapper_styles['xl']['background-image']] = semplice_image_with_sizes($wrapper_styles['xl']['background-image']);
					}
				}

				// column edit head
				if($ram['visibility'] == 'editor') {
					$type = '';
					if(isset($ram[$column_id]['type']) && $ram[$column_id]['type'] == 'spacer') {
						$type = 'Spacer ';
					}
					$output['html'] .= $this->column_edit_head($column_id, $type);	
				}

				// content wrapper
				$output['html'] .= '
					<div class="content-wrapper">
						' . $wrapper_video['html'] . '
						' . $content_html . '
					</div>
				';

				// column end
				$output['html'] .= '</div>';
			}

			return $output;
		}

		// -----------------------------------------
		// create content
		// -----------------------------------------

		public function content($values, $visibility, $is_duplicate) {

			// output
			$output = array(
				'html' => '',
				'css'  => '',
				'images' => array()
			);

			// atts
			$atts = array();

			// vars
			$css_classes = '';

			// public values to content
			$values['content']['post_id'] = isset($this->post_id) ? $this->post_id : 0;
			$values['content']['posts_filter'] = isset($this->posts_filter) ? $this->posts_filter : 1;
			$values['content']['script_execution'] = isset($this->script_execution) ? $this->script_execution : 'normal';

			// visibility
			if($visibility == 'editor') {
				$module_content = editor_api::$modules[$values['module_name']]->output_editor($values['content'], $values['content_id']);
			} else {
				$module_content = editor_api::$modules[$values['module_name']]->output_frontend($values['content'], $values['content_id']);
			}

			// css
			if($module_content['css'] && !empty($module_content['css'])) {
				$output['css'] = $module_content['css'];
			}

			// styles short
			$styles = $values['content']['styles'];

			// get css if is duplicate
			if($is_duplicate) {
				// only append styles css for the frontend
				if($visibility != 'editor') {
					$output['css'] .= $this->generate_css('content', $styles, $values['content_id'], $values['content']['module']);
				}
			}

			// add background image to images array
			if(isset($styles['xl']['background-image'])) {
				$output['images'][$styles['xl']['background-image']] = semplice_image_with_sizes($styles['xl']['background-image']);
			}

			// css classes
			if(isset($values['content']['classes']) && !empty($values['content']['classes'])) {
				$css_classes .= ' ' . $values['content']['classes'];
			}

			// locked content
			if(isset($values['content']['locked']) && true === semplice_boolval($values['content']['locked'])) {
				$css_classes .= ' locked-content';
			}

			// add css classes for individual modules
			switch($values['module_name']) {
				case "advancedportfoliogrid":
					// get preset
					$preset = 'horizontal-fullscreen';
					if(isset($values['content']['options']['preset']) && !empty($values['content']['options']['preset'])) {
						$preset = $values['content']['options']['preset'];
					}
					$atts['data-apg-preset'] = $preset;
				break;
			}

			// generate output
			$output['html'] = '
				<div id="' . $values['content_id'] . '" class="column-content' . $css_classes . '" data-module="' . $values['module_name'] . '" ' . $this->get_attributes($atts) . '>
					' . $module_content['html'] . '
				</div>
			';

			// return output
			return $output;
		}

		// -----------------------------------------
		// column edit head
		// -----------------------------------------

		public function column_edit_head($column_id, $type) {
			return '
				<div class="column-edit-head">
					<a class="column-handle">' . get_svg('backend', '/icons/column_reorder') . '</a>
					<p>' . $type . 'Col</p>
				</div>
			';
		}

		// -----------------------------------------
		// generate data attributes
		// -----------------------------------------

		public function get_attributes($values) {

			// vars
			$attributes = '';

			foreach ($values as $attribute => $value) {
				if ((array) $value !== $value) {
					$attributes .= $attribute . '="' . $value . '" ';
				}	
			}

			return $attributes;
		}

		// -----------------------------------------
		// styles
		// -----------------------------------------

		public function generate_css($mode, $css, $content_id, $module) {
			// define output
			$output = '';

			// save border dir for mobile if set
			$border_dir = 'all';
			if(isset($css['xl']['border-direction']) && $css['xl']['border-direction'] != 'all') {
				$border_dir = $css['xl']['border-direction'];
			}

			// desktop
			if(!empty($css['xl'])) {
				$output .= '#content-holder ' . $this->container_styles($mode, $css['xl'], $content_id, $module, 'xl');
			}

			// get breakpoints
			$breakpoints = semplice_get_breakpoints();

			// iterate breakpoints
			foreach ($breakpoints as $breakpoint => $width) {
				if(!empty($css[$breakpoint])) {
					// set border dir for breakpoint
					$css[$breakpoint]['border-direction'] = $border_dir;
					// desktop
					$output .= '@media screen' . $width['min'] . $width['max'] . ' { #content-holder ' . $this->container_styles($mode, $css[$breakpoint], $content_id, $module, $breakpoint) . '}';
				}
			}

			return $output;
		}

		// -----------------------------------------
		// module container styles
		// -----------------------------------------

		public function container_styles($mode, $styles, $id, $module, $breakpoint) {
			// element css open
			switch($mode) {
				case 'container':
					$css = '#' . $id . ' .container {';
				break;
				case 'contentwrapper':
					$css = '#' . $id . ' .content-wrapper {';
				break;
				case 'branding':
					$css = $id . ' {';
				break;
				case 'cover':
					$css = '#' . $id . ' .cover-image {';
				break;
				default:
					$css = '#' . $id . ' {';
				break;
			}

			// directions
			$directions = array('top', 'right', 'bottom', 'left');

			// branding specific
			if($mode != 'branding') {
				foreach ($directions as $direction) {
					if(isset($styles['padding-' . $direction])) {
						$css .= 'padding-' . $direction . ': ' . $styles['padding-' . $direction] . ';';
					}
					if(isset($styles['margin-' . $direction])) {
						$css .= 'margin-' . $direction . ': ' . $styles['margin-' . $direction] . ';';
					}
				}
			}

			if($mode != 'content') {
				// border width
				$css .= $this->get_border($styles);
				// container shadow
				$css .= $this->get_shadow($styles, false);
			}

			// get bg image and attributes. for non xl only get image and position
			if($breakpoint != 'xl') {
				$css .= semplice_responsive_bg_img($styles);
			} else {
				$css .= semplice_get_bg_css($styles);
			}
			
			// height
			if(isset($styles['height'])) {
				$css .= 'height: ' . $styles['height'] . ';';
			}

			// opacity
			if(isset($styles['opacity'])) {
				$css .= 'opacity: ' . $styles['opacity'] . ';';
			}

			// z-index
			if(isset($styles['z-index'])) {
				$css .= 'z-index: ' . $styles['z-index'] . ';';
			}

			// blend mode
			if($mode == 'section' && isset($styles['mix-blend-mode'])) {
				$css .= 'mix-blend-mode: ' . $styles['mix-blend-mode'] . ';';
			}

			// order
			if(isset($styles['order'])) {
				$css .= 'order: ' . $styles['order'] . ';';
			}

			// element css close
			$css .= '}';

			// apply to is content
			if($mode == 'content') {
				$css .= '#content-holder #' . $id . ' .is-content {';
				$css .= $this->get_shadow($styles, $module);
				if($module != 'button') {
					$css .= $this->get_border($styles);
				}
				$css .= '}';
			}

			return $css;
		}

		// -----------------------------------------
		// get border
		// -----------------------------------------

		public function get_border($styles) {

			$css = '';

			// border width based on direction
			if(isset($styles['border-width'])) {
				$width = $styles['border-width'];
				if(isset($styles['border-direction']) && $styles['border-direction'] != 'all') {
					$directions = array('top' => 0, 'right' => 0, 'bottom' => 0, 'left' => 0);
					$directions[$styles['border-direction']] = $width;
					$width = $directions['top'] . ' ' . $directions['right'] . ' ' . $directions['bottom'] . ' ' . $directions['left'];
				}
				$css .= 'border-width: ' . $width . ';';
			}

			// border style
			if(isset($styles['border-style'])) {
				$css .= 'border-style: ' . $styles['border-style'] . ';';
			}

			// border radius
			if(isset($styles['border-radius'])) {
				$css .= 'border-radius: ' . $styles['border-radius'] . ';';
			}

			// border color
			if(isset($styles['border-color'])) {
				$css .= 'border-color: ' . $styles['border-color'] . ';';
			}

			return $css;
		}

		// -----------------------------------------
		// get shadow
		// -----------------------------------------

		public function get_shadow($styles, $module) {
			// box sahdow
			if(isset($styles['box-shadow']) && $module != 'paragraph') {
				return 'box-shadow: ' . $styles['box-shadow'] . ';';
			} else if(isset($styles['text-shadow']) && $module == 'paragraph' || isset($styles['text-shadow']) && $module == 'text') {
				// only show shadow if values are set
				if(strpos($styles['text-shadow'], '0rem 0rem 0rem') === false) {
					return 'text-shadow: ' . $styles['text-shadow'] . ';';
				}
			}
		}

		// -----------------------------------------
		// bg video
		// -----------------------------------------

		public function background_video($styles, $id, $visibility, $is_cs) {
			// define video
			$video = array(
				'html' => '',
				'css' => ''
			);
			// has video?
			if(isset($styles['background_type']) && $styles['background_type'] == 'vid') {
				// define default
				$video['html'] = semplice_background_video($styles, $visibility, true);
				// get fallback
				$video['css'] = semplice_background_video_fallback($styles, $id, $visibility, $is_cs);
			}
			return $video;					
		}
	}

	// instance
	$this->module_api = new module_api;
}

?>