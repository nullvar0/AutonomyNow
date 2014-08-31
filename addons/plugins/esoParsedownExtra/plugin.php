<?php

if (!defined("IN_ESOTALK")) exit;

ET::$pluginInfo["esoParsedownExtra"] = array(
    "name"        => "esoParsedownExtra",
    "description" => "This plugin uses Parsedown + ParsedownExtra libraries to render text (http://parsedown.org/).",
    "version"     => "1.0",
    "author"      => "Kassius Iakxos",
    "authorEmail" => "kassius@users.noreply.github.com",
    "authorURL"   => "http://github.com/kassius",
    "license"     => "GPLv2"
);

error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);

require_once(PATH_PLUGINS."/esoParsedownExtra/Parsedown.php");
require_once(PATH_PLUGINS."/esoParsedownExtra/ParsedownExtra.php");
require_once(PATH_CORE."/lib/ETFormat.class.php");

class PDETFormat extends ETFormat
{
	public function format()
	{
		if (C("esoTalk.format.mentions")) $this->mentions();
		if (!$this->inline) $this->quotes();
		$this->closeTags();
		
		return $this;
	}

	public function links()
	{
		// Convert normal links - http://www.example.com, www.example.com - using a callback function.
		$this->content = preg_replace_callback(
			"/(?<=\s|^|>|&lt;)(\w+:\/\/)?([\w\-\.]+\.(?:AC|AD|AE|AERO|AF|AG|AI|AL|AM|AN|AO|AQ|AR|ARPA|AS|ASIA|AT|AU|AW|AX|AZ|BA|BB|BD|BE|BF|BG|BH|BI|BIZ|BJ|BM|BN|BO|BR|BS|BT|BV|BW|BY|BZ|CA|CAT|CC|CD|CF|CG|CH|CI|CK|CL|CM|CN|CO|COM|COOP|CR|CU|CV|CW|CX|CY|CZ|DE|DJ|DK|DM|DO|DZ|EC|EDU|EE|EG|ER|ES|ET|EU|FI|FJ|FK|FM|FO|FR|GA|GB|GD|GE|GF|GG|GH|GI|GL|GM|GN|GOV|GP|GQ|GR|GS|GT|GU|GW|GY|HK|HM|HN|HR|HT|HU|ID|IE|IL|IM|IN|INFO|INT|IO|IQ|IR|IS|IT|JE|JM|JO|JOBS|JP|KE|KG|KH|KI|KM|KN|KP|KR|KW|KY|KZ|LA|LB|LC|LI|LK|LR|LS|LT|LU|LV|LY|MA|MC|MD|ME|MG|MH|MIL|MK|ML|MM|MN|MO|MOBI|MP|MQ|MR|MS|MT|MU|MUSEUM|MV|MW|MX|MY|MZ|NA|NAME|NC|NE|NET|NF|NG|NI|NL|NO|NP|NR|NU|NZ|OM|ORG|PA|PE|PF|PG|PH|PK|PL|PM|PN|POST|PR|PRO|PS|PT|PW|PY|QA|RE|RO|RS|RU|RW|SA|SB|SC|SD|SE|SG|SH|SI|SJ|SK|SL|SM|SN|SO|SR|ST|SU|SV|SX|SY|SZ|TC|TD|TEL|TF|TG|TH|TJ|TK|TL|TM|TN|TO|TP|TR|TRAVEL|TT|TV|TW|TZ|UA|UG|UK|US|UY|UZ|VA|VC|VE|VG|VI|VN|VU|WF|WS|XXX|YE|YT|ZA|ZM|ZW)(?:[\.\/#][^\s<]*?)?)(?=\)\s|[\s\.,?!>]*(?:\s|&gt;|>|$))/i",
			array($this, "linksCallback"), $this->content);

		// Convert email links.
		$this->content = preg_replace("/[\w-\.]+@([\w-]+\.)+[\w-]{2,4}/i", "<a href='mailto:$0' class='link-email'>$0</a>", $this->content);

		return $this;
	}
	
}

class ETPlugin_esoParsedownExtra extends ETPlugin
{
	public $content;
	public $PDETFormat;
	public $PDEParser;

	public function init()
	{
		$this->PDETFormat = new PDETFormat;
		$this->PDEParser = new ParsedownExtra;
	}

	/**
	 * Add an event handler to the "getEditControls" method of the conversation controller to add markDown
	 * formatting buttons to the edit controls.
	 *
	 * @return void
	 */
	/*public function handler_conversationController_getEditControls($sender, &$controls, $id)
	{
		addToArrayString($controls, "fixed", "<a href='javascript:markDown.fixed(\"$id\");void(0)' title='".T("Code")."' class='control-fixed'><i class='icon-code'></i></a>", 0);
		addToArrayString($controls, "image", "<a href='javascript:markDown.image(\"$id\");void(0)' title='".T("Image")."' class='control-img'><i class='icon-picture'></i></a>", 0);
		addToArrayString($controls, "link", "<a href='javascript:markDown.link(\"$id\");void(0)' title='".T("Link")."' class='control-link'><i class='icon-link'></i></a>", 0);
		addToArrayString($controls, "strike", "<a href='javascript:markDown.strikethrough(\"$id\");void(0)' title='".T("Strike")."' class='control-s'><i class='icon-strikethrough'></i></a>", 0);
		addToArrayString($controls, "header", "<a href='javascript:markDown.header(\"$id\");void(0)' title='".T("Header")."' class='control-h'><i class='icon-h-sign'></i></a>", 0);
		addToArrayString($controls, "italic", "<a href='javascript:markDown.italic(\"$id\");void(0)' title='".T("Italic")."' class='control-i'><i class='icon-italic'></i></a>", 0);
		addToArrayString($controls, "bold", "<a href='javascript:markDown.bold(\"$id\");void(0)' title='".T("Bold")."' class='control-b'><i class='icon-bold'></i></a>", 0);

	}*/

	public function handler_format_beforeFormat($sender)
	{
		$this->PDETFormat->content = $sender->get();
	}

	public function handler_format_afterFormat($sender)
	{
		$this->PDETFormat->links(); 
		
		$search = array("\r&gt; ","\n&gt; ");
		$this->PDETFormat->content = str_replace($search, "\n> ", $this->PDETFormat->content);
		$this->PDETFormat->content = $this->PDEParser->text($this->PDETFormat->content);

		$this->PDETFormat->content = str_replace("\r", "\n", $this->PDETFormat->content);
		while(strstr($this->PDETFormat->content,"\n\n") !== FALSE) { $this->PDETFormat->content = str_replace("\n\n", "", $this->PDETFormat->content); }

		$this->PDETFormat->format();
		$this->PDETFormat->content = str_replace("\\\"", "\"", $this->PDETFormat->content);

		$sender->content = $this->PDETFormat->content;
	}

	public function handler_conversationController_renderBefore($sender)
	{
		$sender->addCSSFile($this->resource("markdown.css"));
		//$sender->addJSFile($this->resource("markdown.js"));
	}

}

?>
