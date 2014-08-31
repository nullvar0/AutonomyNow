<?php
// Copyright 2011 Toby Zerner, Simon Zerner
// This file is part of esoTalk. Please see the included license file for usage information.

if (!defined("IN_ESOTALK")) exit;

/**
 * Displays the conversation list, including the filter area (search form, gambits, and channel breadcrumb.)
 *
 * @package esoTalk
 */
?>
<!-- Banner -->
<div class="banner">
	<div class="banner-img home-banner"></div>
</div>
<!-- /Banner -->
<?php $this->renderView("home/list", $data); ?>
<div class="sidebar">
	<div class="about rounded-corners">
		<h1><?php echo T("home.about.title"); ?></h1>
		<p><?php echo T("home.about"); ?></p>
		<a class="read-more" href="<?php echo URL('pages/1-about-page'); ?>"><?php echo T("home.more"); ?></a>
	</div>
	<div class="twitter-feed">
		<a class="twitter-timeline" href="https://twitter.com/AutonomyNow" data-widget-id="504876757996957697">Tweets by @AutonomyNow</a>
		<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
	</div>
</div>