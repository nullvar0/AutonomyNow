<?php
// Copyright 2011 Toby Zerner, Simon Zerner
// This file is part of esoTalk. Please see the included license file for usage information.

if (!defined("IN_ESOTALK")) exit;

/**
 * Displays a single conversation row in the context of a list of results.
 *
 * @package esoTalk
 */

$conversation = $data["conversation"];

// Work out the class name to apply to the row.
$className = "channel-".$conversation["channelId"];
if ($conversation["starred"]) $className .= " starred";
if ($conversation["unread"] and ET::$session->user) $className .= " unread";
if ($conversation["startMemberId"] == ET::$session->user) $className .= " mine";
foreach ($conversation["labels"] as $label) $className .= " label-$label";

?>

<?php $conversationURL = conversationURL($conversation["conversationId"], $conversation["title"]); ?>

<li id='c<?php echo $conversation["conversationId"]; ?>'>
<div class='col-conversation'>
<div class='header'><?php
echo "<span class='action'>".avatar(array(
		"memberId" => $conversation["lastPostMemberId"],
		"username" => $conversation["lastPostMember"],
		"avatarFormat" => $conversation["lastPostMemberAvatarFormat"],
		"email" => $conversation["lastPostMemberEmail"]
	), "thumb"),
	"</span>";
?>
<strong class='title'><a href='<?php echo URL($conversationURL.((ET::$session->user and $conversation["unread"]) ? "/unread" : "")) ?>'> <?php echo highlight(sanitizeHTML($conversation["title"]), ET::$session->get("highlight")) ?>	</a></strong>
<div class="info">posted <?php echo relativeTime($conversation["lastPostTime"], true) ?> by <?php echo memberLink($conversation["lastPostMemberId"], $conversation["lastPostMember"]); ?></div>
</div>
<?php 
	// If we're highlighting search terms (i.e. if we did a fulltext search), then output a "show matching posts" link.
	if (ET::$session->get("highlight")){echo "<span class='controls'><a href='".URL($conversationURL."/?search=".urlencode($data["fulltextString"]))."' class='showMatchingPosts'>".T("Show matching posts")."</a></span>";}
	echo "<div class='excerpt thing'>".ET::formatter()->init($conversation["firstPost"])->clip(1000)->format()->get()."<div style='clear:both;'/></div>"; 
?>
</div>
</li>
