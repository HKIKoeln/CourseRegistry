<?php
$this->Html->script('https://code.jquery.com/jquery-1.12.4.min.js', array(
	'integrity' => 'sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=',
	'crossorigin' => 'anonymous',
	'inline' => false));
$this->Html->script('jssor.slider.mini', array('inline' => false));
?>
<style>
	/* jssor slider bullet navigator skin 05 css */
	/*
	.jssorb05 div           (normal)
	.jssorb05 div:hover     (normal mouseover)
	.jssorb05 .av           (active)
	.jssorb05 .av:hover     (active mouseover)
	.jssorb05 .dn           (mousedown)
	*/
	.jssorb05 {
		position: absolute;
	}
	.jssorb05 div, .jssorb05 div:hover, .jssorb05 .av {
		position: absolute;
		/* size of bullet elment */
		width: 16px;
		height: 16px;
		background: url(img/b05.png) no-repeat;
		overflow: hidden;
		cursor: pointer;
	}
	.jssorb05 div { background-position: -7px -7px; }
	.jssorb05 div:hover, .jssorb05 .av:hover { background-position: -37px -7px; }
	.jssorb05 .av { background-position: -67px -7px; }
	.jssorb05 .dn, .jssorb05 .dn:hover { background-position: -97px -7px; }
</style>
<script>
    jQuery(document).ready(function ($) {
        var options = {
			$AutoPlay: true,
			$Idle: 6000,
			$SlideDuration: 1000,
			$FillMode: 1,
			$BulletNavigatorOptions: {
                $Class: $JssorBulletNavigator$,
                $ChanceToShow: 2
            }
		};
        var jssor_slider1 = new $JssorSlider$('slider1_container', options);
		
		//responsive code begin
        //you can remove responsive code if you don't want the slider scales
        //while window resizing
        function ScaleSlider() {
            var parentWidth = $('#slider1_container').parent().width();
            if (parentWidth) {
                jssor_slider1.$ScaleWidth(parentWidth);
            }
            else
                window.setTimeout(ScaleSlider, 30);
        }
        //Scale slider after document ready
        ScaleSlider();
                                        
        //Scale slider while window load/resize/orientationchange.
        $(window).bind("load", ScaleSlider);
        $(window).bind("resize", ScaleSlider);
        $(window).bind("orientationchange", ScaleSlider);
        //responsive code end
    });
</script>


<div id="slider1_container" style="position: relative; top: 0px; left: 0px; width: 900px; height: 400px; margin-bottom: 1.5em;">
    
    <div u="slides" style="cursor: move; position: absolute; overflow: hidden; left: 0px; top: 0px; width: 900px; height: 400px;">
        <div>
			<?php echo $this->element('projects/barchart'); ?>
		</div>
		<div>
			<?php
			echo $this->Html->image('dhpr_slideshow/00002.jpg', array(
				'u' => 'image',
				'width' => 565,
				'height' => 400
			));
			?>
		</div>
		<div>
			<?php
			echo $this->Html->image('dhpr_slideshow/first slide DODH.jpg', array(
				'u' => 'image',
				'width' => 467,
				'height' => 349
			));
			?>
		</div>
		<div>
			<?php
			echo $this->Html->image('dhpr_slideshow/treemap.jpg', array(
				'u' => 'image',
				'width' => 646,
				'height' => 400
			));
			?>
		</div>
		<div>
			<?php
			echo $this->Html->image('dhpr_slideshow/_disciplines.png', array(
				'u' => 'image',
				'width' => 618,
				'height' => 400
			));
			?>
		</div>
        <div>
			<?php
			echo $this->Html->image('dhpr_slideshow/eHumanities.png', array(
				'u' => 'image',
				'width' => 428,
				'height' => 85
			));
			?>
		</div>
    </div>
	
	<div class="jssorb05" data-u="navigator" style="bottom: 20px; right: 20px;">
		<div u="prototype"></div>
	</div>
</div>