<!-- edit popup template -->
<script id="edit-popup-template" type="text/template">
	<div class="color-picker-holder"></div>
	<div class="create-animations" data-display="none">
		<div class="head">Element Trigger</div>
		<p>Please select which element trigger<br />should start your animation.</p>
		<ul>
			<li><a class="editor-action semplice-button white-button create-animation" data-action-type="animate" data-action="add" data-id="{{id}}" data-event="on_load">When in view</a></li>
			<li><a class="editor-action semplice-button white-button create-animation" data-action-type="animate" data-action="add" data-id="{{id}}" data-event="on_hover">Mouseover</a></li>
			<li><a class="editor-action semplice-button white-button create-animation" data-action-type="animate" data-action="add" data-id="{{id}}" data-event="on_click">Click (tap)</a></li>
			<li><a class="editor-action semplice-button white-button create-animation" data-action-type="animate" data-action="add" data-id="{{id}}" data-event="on_scroll">Scrolling in view</a></li>
		</ul>
		<div class="animation-trigger-help"></div>
	</div>
	<div class="ep-content">
		<div class='ep-switch'>
			<ul>
				<li><a class='ep-content load-edit-popup active-switch' data-layout='content' data-switch="content" data-id="{{contentId}}" data-module="{{module}}"><?php echo semplice_get_module_svgs(); ?><span class="switch-tooltip">Content</span></a></li>
				<li><a class='ep-column load-edit-popup' data-layout='column' data-switch="column" data-id="{{contentId}}"><?php echo get_svg('backend', '/icons/ep_switch_column'); ?><span class="switch-tooltip">Column</span></a></li>
				<li><a class='ep-section load-edit-popup' data-layout='section' data-switch="section" data-id="{{contentId}}"><?php echo get_svg('backend', '/icons/ep_switch_section'); ?><span class="switch-tooltip">Section</span></a></li>
			</ul>
		</div>
		<div class="inner edit-popup-inner" data-popup-id="{{id}}">
			<div class="handlebar"><div class="handlebar-inner"><!-- draggable handle --></div></div>
			<div class="ep-options-wrapper">
				<div class="regular-options">
					<nav class="ep-tabs-nav">
						<ul>
							{{tabsNav}}
							<li><a class="close-edit-popup" data-module="{{module}}" data-id="{{id}}"><!-- close ep --></a></li>
						</ul>
					</nav>
					<div class="edit-popup-help"><div class="close-popup-notice" data-mode="help"><?php echo get_svg('backend', '/icons/ep_close_help'); ?></div><div class="content"></div></div>
					<div class="ep-tabs">
					</div>
					<ul class="actions ep-actions">
						<li class="ep-actions-item"><a class="editor-action duplicate mep-icon" data-action-type="duplicate" data-action="{{mode}}" data-id="{{id}}"><!-- Duplicate --></a><div class="tooltip tooltip-duplicate">Duplicate</div></li>
						<li class="ep-actions-item"><a class="editor-action delete mep-icon" data-layout-type="{{mode}}" data-action-type="popup" data-action="delete" data-id="{{id}}"><!-- Delete --></a><div class="tooltip tooltip-delete">Delete</div></li>
						<?php
							if(semplice_theme('edition') == 'single') {
								echo '<li class="save-block-single admin-click-handler ep-actions-item" data-handler="execute" data-action="studioFeatures" data-action-type="popup" data-feature="blocks"><a class="save-block mep-icon"><!-- Save Block --></a><div class="tooltip tooltip-save">Save as block</div></li>';
							} else {
								echo '<li class="ep-actions-item" data-is-masterblock="{{masterBlock}}"><a class="save-block mep-icon editor-action" data-action-type="popup" data-action="saveBlock" data-content-id="{{sectionId}}" data-masterblock-id="{{masterBlockId}}"><!-- Save Block --></a><div class="tooltip tooltip-save">{{blockTooltip}}</div></li>';
							}
						?>
						<li class="preview-animation">
							<a class="editor-action semplice-button mep-icon preview-animation-link" data-action-type="animate" data-action="preview" data-id="{{id}}" data-step="all" data-module="{{module}}"><span><?php echo get_svg('backend', '/icons/animate_preview'); ?></span> Preview</a>
							<a class="save-animate-preset editor-action" data-action-type="popup" data-action="saveAnimatePreset" data-content-id="{{id}}"><div class="tooltip animate-tooltip tooltip-animate-save">Save as preset</div></a>
							<a class="editor-action preview-animation-delete" data-action-type="popup" data-action="deleteAnimation" data-id="{{id}}" data-layout-type="{{mode}}"><div class="tooltip animate-tooltip tooltip-animate-remove">Delete Animation</div></a>
						</li>
					</ul>
					<div class="locked">
						<div class="locked-inner"><?php echo get_svg('backend', '/icons/locked_content'); ?> Locked <span>content</span>
							<div class="tooltip tooltip-all">Locked content for blog templates can't be duplicated, saved as a block or removed.</div>
							<div class="tooltip tooltip-block">Sections with locked content can't be saved as a block.</div>
						</div>
					</div>
				</div>
				<div class="ep-expand-options"></div>
			</div>
	</div>
</script>
<!-- add section -->
<script id="section-template" type="text/template">
	<section id="{{sectionId}}" class="content-block{{classes}}" data-column-mode-xs="single" data-column-mode-sm="single">
		<div class="container">
			<div id="{{rowId}}" class="row">
				<div id="{{columnId}}" class="column" data-xl-width="12">
					<div class="column-edit-head">
						<a class="column-handle"><?php echo get_svg('backend', '/icons/column_reorder'); ?></a>
						<p>Col</p>
					</div>
					<div class="content-wrapper">
						<div id="{{contentId}}" class="column-content" data-module="{{module}}">
							{{content}}
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</script>
<!-- add section with spacer column-->
<script id="section-spacer-column-template" type="text/template">
	<section id="{{sectionId}}" class="content-block" data-column-mode-xs="single" data-column-mode-sm="single">
		<div class="container">
			<div id="{{rowId}}" class="row">
				<div id="{{columnId}}" class="column spacer-column" data-xl-width="12">
					<div class="column-edit-head">
						<a class="column-handle"><?php echo get_svg('backend', '/icons/column_reorder'); ?></a>
						<p>Col</p>
					</div>
				</div>
			</div>
		</div>
	</section>
</script>
<!-- add empty section -->
<script id="empty-section-template" type="text/template">
	<section id="{{sectionId}}" class="content-block" data-column-mode-xs="single" data-column-mode-sm="single">
		<div class="container">
			<div id="{{rowId}}" class="row">
			</div>
		</div>
	</section>
</script>
<!-- add column -->
<script id="column-template" type="text/template">
	<div id="{{columnId}}" class="column" data-xl-width="12">
		<div class="column-edit-head">
			<a class="column-handle"><?php echo get_svg('backend', '/icons/column_reorder'); ?></a>
			<p>Col</p>
		</div>
		<div class="content-wrapper">
			<div id="{{contentId}}" class="column-content" data-module="{{module}}">
				{{content}}
			</div>
		</div>
	</div>
</script>
<!-- add spacer column -->
<script id="spacer-column-template" type="text/template">
	<div id="{{columnId}}" class="column spacer-column" data-xl-width="12">
		<div class="column-edit-head">
			<a class="column-handle"><?php echo get_svg('backend', '/icons/column_reorder'); ?></a>
			<p>Col</p>
		</div>
		<div class="column-count"></div>
	</div>
</script>
<!-- add content -->
<script id="content-template" type="text/template">
	<div id="{{contentId}}" class="column-content" data-module="{{module}}">
		{{content}}
	</div>
</script>
<!-- add column -->
<script id="cover-template" type="text/template">
	<div id="{{columnId}}" class="column" data-xl-width="12">
		<div class="column-edit-head">
			<a class="column-handle"><?php echo get_svg('backend', '/icons/column_reorder'); ?></a>
			<p>Col</p>
		</div>
		<div class="content-wrapper">
			<div id="{{contentId}}" class="column-content" data-module="{{module}}">
				{{content}}
			</div>
		</div>
	</div>
</script>
<!-- activate editor -->
<script id="active-editor" type="text/template">
	<a href="#edit/{{postId}}" id="editor-active">
		<div class="inner">
			<h5>Last Edited Post</h5>
			<h4>{{postTitle}}</h4>
		</div>
	</a>
</script>
<!-- publish dropdown -->
<script id="publish-dropdown-template" type="text/template">
	{{ options }}
	<a class="editor-action" data-action="post" data-action-type="save" data-save-mode="publish">Update</a>
</script>
<!-- default cover tmeplate -->
<script id="default-cover-template" type="text/template">
	<?php echo semplice_default_cover('hidden'); ?>
</script>
<!-- empty editor -->
<script id="empty-editor-template" type="text/template">
	<div id="empty-editor">
		<div class="drag-and-drop"><img src="<?php echo get_template_directory_uri() . '/assets/images/admin/empty_editor_drag.png'; ?>"></div>
		<div class="content">
			<h3 class="text">Drag an item from the topbar &amp;<br />and drop here to add content.</h3>
			<div class="semplice-template">
				<p>or</p>
				<div class="select-template-wrapper">
					<div class="st-arrow"></div>
					<select class="select-template">
						<?php echo semplice_get_template_dropdown(); ?>
					</select>
				</div>
			</div>
		</div>
		<div class="help-videos">
			<a href="https://www.semplice.com/videos#content-editor-overview" target="_blank">Watch Tutorial</a>
			<!--
			<span>or</span>
			<a class="demo-content" href="link/to/video/tutorial" target="_blank">Load demo content</a>
			-->
		</div>
	</div>
</script>
<!-- select covers -->
<script id="select-covers-template" type="text/template">
	<div class="grid-categories select-covers">
		<div class="content">
			<nav class="editor-action" data-handler="execute" data-action-type="coverslider" data-action="add">
				<ul class="cover-list">{{posts}}</ul>
			</nav>
		</div>
	</div>
</script>
<!-- advaned portfolio grid presets -->
<script id="apg-presets-template" type="text/template">
	<div class="apg-presets">
		<ul class="content apg-presets-content">
			<li class="apg-load-preset" data-preset="horizontal-fullscreen" data-content-id="{{id}}">
				<div class="apg-inner">
					<img alt="horizontal-fullscreen" class="preset-img" src="<?php echo get_template_directory_uri() . '/assets/images/admin/portfoliogrid/horizontal_fullscreen.png'; ?>">
					<div class="apg-preset-hover"><p>Horizontal<br />Fullscreen</p></div>
				</div>
			</li>
			<li class="apg-load-preset" data-preset="text" data-content-id="{{id}}">
				<div class="apg-inner">
					<img alt="text-grid" class="preset-img" src="<?php echo get_template_directory_uri() . '/assets/images/admin/portfoliogrid/text.png'; ?>">
					<div class="apg-preset-hover"><p>Text Grid</p></div>
				</div>
			</li>
			<li class="apg-load-preset" data-preset="splitscreen" data-content-id="{{id}}">
				<div class="apg-inner">
					<img alt="splitscreen-grid" class="preset-img" src="<?php echo get_template_directory_uri() . '/assets/images/admin/portfoliogrid/splitscreen.png'; ?>">
					<div class="apg-preset-hover"><p>Splitscreen</p></div>
				</div>
			</li>
			<li class="apg-load-preset" data-preset="table" data-content-id="{{id}}">
				<div class="apg-inner">
					<img alt="table-grid" class="preset-img" src="<?php echo get_template_directory_uri() . '/assets/images/admin/portfoliogrid/table.png'; ?>">
					<div class="apg-preset-hover"><p>Table Grid</p></div>
				</div>
			</li>
		</ul>
	</div>
</script>
<!-- advaned portfolio grid missing thumb -->
<script id="apg-missing-thumb-template" type="text/template">
	<div class="missing-thumbnail">
		<p>Missing thumbnail for<br />"{{postTitle}}"</p>
		<div class="semplice-button admin-click-handler no-ep trigger-apg-thumb-upload" data-handler="execute" data-action="init" data-action-type="mediaLibrary" data-media-type="image" data-media-type="image" data-upload="epPostThumbnail" name="post_thumbnail" data-content-id="{{contentId}}" data-post-id="{{postId}}">Upload Thumbnail</div>
		<img alt="missing-thumbnail" src="<?php echo get_template_directory_uri() . '/assets/images/admin/apg_missing_thumbnail.png'; ?>">
	</div>
</script>
<!-- revision list item template -->
<script id="revision-list-item-template" type="text/template">
	<li id="{{revisionId}}" class="revision-list-item">
		<a class="load-revision editor-action" data-action-type="revisions" data-action="load" data-revision-id="{{revisionId}}">{{revisionTitle}}</a>
		<div class="revision-options">
			<a class="rename-revision editor-action" data-action-type="popup" data-action="renameRevision" data-revision-id="{{revisionId}}"></a>
			<a class="remove-revision editor-action" data-action-type="popup" data-action="deleteRevision" data-revision-id="{{revisionId}}"></a>
		</div>
	</li>
</script>
<!-- revisions unsaved changes -->
<script id="revision-unsaved-changes-template" type="text/template">
<div id="semplice-exit" class="popup">
	<div class="popup-inner">
		<div class="popup-content">
			<div class="important">
				<?php echo get_svg('backend', '/icons/popup_important'); ?>
			</div>
			<h3>Unsaved Changes</h3>
			<p>Do you want to save your progress to the active version before continuing?</p>
		</div>
		<div class="popup-footer">
			<a class="editor-action cancel" data-handler="execute" data-action-type="revisions" data-action="forceLoad" data-revision-id="{{revisionId}}">Don't Save</a>
			<a class="editor-action confirm semplice-button" data-handler="execute" data-action-type="save" data-action="post" data-save-mode="draft" data-change-status="no" data-load-revision="yes" data-revision-id="{{revisionId}}">Save &amp; continue</a>
		</div>					
	</div>
</div>
</script>
<!-- before after before -->
<script id="ba-before-template" type="text/template">
	<?php echo semplice_get_ba_content('', 'before', false); ?>
</script>
<!-- before after after -->
<script id="ba-after-template" type="text/template">
	<?php echo semplice_get_ba_content('', 'after', false); ?>
</script>
<!-- list animate presets -->
<script id="list-animate-presets-template" type="text/template">
<div class="animate-presets-list" data-active-list="premade">
	<div class="apl-nav">
		<a class="editor-action preset-list active-preset-list" data-action-type="animate" data-action="togglePresetList" data-type="premade">Premade</a>
		<a class="editor-action preset-list " data-action-type="animate" data-action="togglePresetList" data-type="global">Custom</a>
	</div>
	<ul class="premade">
		{{premade}}
	</ul>
	<ul class="global ep-ap-list">
		{{global}}
	</ul>
</div>
</script>
<!-- custom animate presets empty state -->
<script id="animate-presets-emtpy-template" type="text/template">
	<div class="apl-empty">
		<div class="apl-empty-inner">
			<div class="head-image"></div>
			<p class="apl-empty-head">Custom Presets</p>
			<p>You haven't created any custom presets yet. Use the + icon below to save a custom preset after finishing your animation.</p>
		</div>
	</div>
</script>
<script id="fluidtext-toolbar-template" type="text/template">
	<div class="fluidtext-toolbar">
		<div class="wysiwyg-breakpoint-notice"><div class="bg" data-bg-bp="lg"></div><div class="text">Format Sizings for Desktop</div></div>
		<div class="setting first-setting">
			<span class="toolbar-title lh-title"></span>
			<div class="option">
				<div class="option-inner">
					<div class="attribute">
						<div class="range-slider-wrapper">
							<input type="number" class="fluidtext" data-handler="fluidText" data-type="range-slider" value="{{fluidLineHeight}}" data-input-type="range-slider" data-range-slider="fluidText" min="1" max="1000" name="fluid-line-height" data-content-id="{{id}}">
						</div>
						<div class="tooltip tt-line-height">Line Height</div>
					</div>
				</div>
			</div>
		</div>
		<div class="setting">
			<span class="toolbar-title ls-title"></span>
			<div class="option">
				<div class="option-inner">
					<div class="attribute">
						<div class="range-slider-wrapper">
							<input type="number" class="fluidtext" data-handler="fluidText" data-type="range-slider" value="{{fluidLetterSpacing}}" data-input-type="range-slider" data-range-slider="fluidText" data-negative="true" data-divider="10" min="0" max="1000" name="fluid-letter-spacing" data-content-id="{{id}}">
						</div>
						<div class="tooltip tt-letter-spacing">Letter Spacing</div>
					</div>
				</div>
			</div>
		</div>
		<div class="setting">
			<span class="toolbar-title">Font Size</span><span>Min</span>
			<div class="option">
				<div class="option-inner">
					<div class="attribute">
						<div class="range-slider-wrapper">
							<input type="number" class="fluidtext" data-handler="fluidText" data-type="range-slider" value="{{minFontSize}}" data-input-type="range-slider" data-range-slider="fluidText" min="6" max="999" name="min-font-size" data-content-id="{{id}}">
						</div>
						<div class="tooltip tt-min-font-size">Minium Font Size (px)</div>
					</div>
				</div>
			</div>
		</div>
		<div class="setting">
			<span>Max</span>
			<div class="option">
				<div class="option-inner">
					<div class="attribute">
						<div class="range-slider-wrapper">
							<input type="number" class="fluidtext" data-handler="fluidText" data-type="range-slider" value="{{maxFontSize}}" data-input-type="range-slider" data-range-slider="fluidText" min="6" max="999" name="max-font-size" data-content-id="{{id}}">
						</div>
						<div class="tooltip tt-max-font-size">Maximum Font Size (px)</div>
					</div>
				</div>
			</div>
		</div>
		<div class="setting">
			<span>Fluid</span>
			<div class="option">
				<div class="option-inner">
					<div class="attribute">
						<div class="range-slider-wrapper">
							<input type="number" class="fluidtext" data-handler="fluidText" data-type="range-slider" value="{{fluidFontSize}}" data-input-type="range-slider" data-range-slider="fluidText" min="1" max="1000" name="fluid-font-size" data-content-id="{{id}}">
						</div>
						<div class="tooltip tt-fluid-font-size">Calculated Size: <b class="fluid-calculated">124</b>px</div>
					</div>
				</div>
			</div>
		</div>
		<div class="fluidtext-resize">
			<input type="range" min="1" max="100" value="100" step=".1" class="fluidtext-resize-slider" data-content-id="{{id}}">
			<div class="fluidtext-slider-values">
				<div class="ft-resize-width">
					Width: <span class="ft-vp-width"></span>
				</div>
				<div class="ft-resize-fontsize">
					Font Size: <span class="ft-font-size">/span>
				</div>
			</div>
		</div>
	</div>
</script>
<script id="fluidtext-formats-template" type="text/template">
	<div class="fluidtext-format">
		<a class="fluidtext-format-list">Formats</a>
		<ul class="fluidtext-format-ul">
			<li class="fluidtext-format-change" data-format="p">Paragraph</li>
			<li class="fluidtext-format-change" data-format="h1">Heading 1</li>
			<li class="fluidtext-format-change" data-format="h2">Heading 2</li>
			<li class="fluidtext-format-change" data-format="h3">Heading 3</li>
			<li class="fluidtext-format-change" data-format="h4">Heading 4</li>
			<li class="fluidtext-format-change" data-format="h5">Heading 5</li>
			<li class="fluidtext-format-change" data-format="h6">Heading 6</li>
		</ul>
	</div>
</script>
<script id="accordion-item-template" type="text/template">
	<div class="accordion-item" data-accordion-id="{{accordionId}}">
		<div class="title" data-font="inter_bold">
			<div class="title-span" contenteditable="true" data-accordion-editable="editable" data-name="title">{{title}}</div>
			<div class="icon">
				<span class="expand">{{iconExpand}}</span>
				<span class="collapse">{{iconCollapse}}</span>
			</div>
		</div>
		<div class="description">
			<div class="description-inner" data-font="inter_light" contenteditable="true" data-accordion-editable="editable" data-name="description">{{description}}</div>
		</div>
		<div class="actions">
			<a class="editor-action accordion-remove" data-action-type="accordion" data-action="remove" data-content-id="{{id}}" data-remove-id="{{accordionId}}"></a>
			<a class="accordion-handle ui-sortable-handle"></a>
		</div>
	</div>
</script>

