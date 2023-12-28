<?php
// Block attempts to load this script directly
if(strpos($_SERVER['REQUEST_URI'], basename(__FILE__)) !== false) exit();

$served_path = dirname($_SERVER['SCRIPT_URI']);
?><!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />
	<title>Index of <?php //Decode the URI...
	$url=array_slice(explode('/', $_SERVER['REQUEST_URI']), 1, -1);
	echo $_SERVER['HTTP_HOST'] . '/' . implode('/', $url) . (count($url)?'/':''); ?></title>
	<link rel="stylesheet" href="<?= $served_path ?>/design.css" type="text/css"></link>
	<meta name="viewport" content="width=device-width, user-scalable=no">
</head>
<body>
<h1>Index of <a href="/"><?php echo $_SERVER['HTTP_HOST']; ?>/</a><?php $before='/';
foreach($url as $part)
{
	echo '<a href="' . $before . $part . '">' . htmlspecialchars(urldecode($part)) . '/</a>';
	$before.= $part . '/';
} ?></h1>
<!-- Gallery -->
<script src="<?= $served_path ?>/script.js"></script>
<!-- Core CSS file -->
<link rel="stylesheet" href="<?= $served_path ?>/photoswipe/photoswipe.css">
<link rel="stylesheet" href="<?= $served_path ?>/photoswipe/default-skin/default-skin.css">

<script src="<?= $served_path ?>/photoswipe/photoswipe.min.js"></script>
<script src="<?= $served_path ?>/photoswipe/photoswipe-ui-default.min.js"></script>

<!-- Root element of PhotoSwipe. Must have class pswp. -->
<div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
    <!-- Background of PhotoSwipe.
         It's a separate element as animating opacity is faster than rgba(). -->
    <div class="pswp__bg"></div>

    <!-- Slides wrapper with overflow:hidden. -->
    <div class="pswp__scroll-wrap">
        <!-- Container that holds slides.
            PhotoSwipe keeps only 3 of them in the DOM to save memory.
            Don't modify these 3 pswp__item elements, data is added later on. -->
        <div class="pswp__container">
            <div class="pswp__item"></div>
            <div class="pswp__item"></div>
            <div class="pswp__item"></div>
        </div>

        <!-- Default (PhotoSwipeUI_Default) interface on top of sliding area. Can be changed. -->
        <div class="pswp__ui pswp__ui--hidden">
            <div class="pswp__top-bar">
                <!--  Controls are self-explanatory. Order can be changed. -->
                <div class="pswp__counter"></div>
                <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>
                <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>
                <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>

                <!-- Preloader demo https://codepen.io/dimsemenov/pen/yyBWoR -->
                <!-- element will get class pswp__preloader--active when preloader is running -->
                <div class="pswp__preloader">
                    <div class="pswp__preloader__icn">
                      <div class="pswp__preloader__cut">
                        <div class="pswp__preloader__donut"></div>
                      </div>
                    </div>
                </div>
            </div>
            <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)">
            </button>
            <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)">
            </button>
			<div class="pswp__caption">
				<div class="pswp__caption__center"></div>
			</div>
        </div>
    </div>
</div>
<div id="gallery"></div>
<!-- End gallery -->
<hr />
