<?php
// This file is part of esoTalk. Please see the included license file for usage information.

if (!defined("IN_ESOTALK")) exit;

/**
 * Displays a single page.
 *
 * @package esoTalk
 */

$page = $data["page"];
?>
<div class='content'>
	<?php if (!empty($page["content"])): ?>
		<?php echo $page["content"]; ?>
	<?php endif; ?>
</div>

</div>