<?php
namespace vendor\editor;

use config\Config;

include("lang/" . Config::getLang() . ".php");

//----------------------------------------------------------------------------------------------------|
/*****************************************************************************************************|
 * Project:     Mega Class BBODE                                                                      |
//----------------------------------------------------------------------------------------------------|
 * @link http://megatpl.com/                                                                          |
 * @copyright 2012.                                                                                   |
 * @author Eng Hossam Hamed <eng.h.hamed@gmail.com>                                                   |
  * @author Eng Hossam Hamed <megatpl@gmail.com>                                                      |
 * @package Megatpl                                                                                   |
 * @version 1.0                                                                                       |
//----------------------------------------------------------------------------------------------------|
//----------------------------------------------------------------------------------------------------|
******************************************************************************************************/
###################################################################################################### Mega Class BBCODE 
/**
* @ignore
*/
if (!defined('IN_MEGA_BBCODE'))
{
	exit;
}

class BBCode
{
    private $path;
	private $lang;
    
    public function __construct()
    {
        $this->path = Config::getPath() . '/vendor/editor';
		$this->lang = translate();
    }
   
   
    public function bbcodesmilies(){
        $html = '
            <img onclick="insert_text(\':)\', true);return false;" src="'.$this->path.'/smilies/grin.png" alt=":D" title="' . $this->lang['grin'] . '" />
            <img onclick="insert_text(\':smile:\', true);return false;" src="'.$this->path.'/smilies/smile.png" alt=":)" title="' . $this->lang['smile'] . '" />
            <img onclick="insert_text(\';)\', true);return false;" src="'.$this->path.'/smilies/wink.png" alt=";)" title="' . $this->lang['wink'] . '" />
            <img onclick="insert_text(\':(\', true);return false;" src="'.$this->path.'/smilies/sad.png" alt=":(" title="' . $this->lang['sad'] . '" />
            <img onclick="insert_text(\':o\', true);return false;" src="'.$this->path.'/smilies/surprise.png" alt=":o" title="' . $this->lang['surprised'] . '" />
            <img onclick="insert_text(\':shock:\', true);return false;" src="'.$this->path.'/smilies/eek.png" alt=":shock:" title="' . $this->lang['shocked'] . '" />
            <img onclick="insert_text(\':?\', true);return false;" src="'.$this->path.'/smilies/confuse.png" alt=":?" title="' . $this->lang['confused'] . '" />
            <img onclick="insert_text(\'8-)\', true);return false;" src="'.$this->path.'/smilies/cool.png" alt="8-)" title="' . $this->lang['cool'] . '" />
            <img onclick="insert_text(\':lol:\', true);return false;" src="'.$this->path.'/smilies/lol.png" alt=":lol:" title="' . $this->lang['lol'] . '" />
            <img onclick="insert_text(\':x\', true);return false;" src="'.$this->path.'/smilies/mad.png" alt=":x" title="' . $this->lang['mad'] . '" />
            <img onclick="insert_text(\':P\', true);return false;" src="'.$this->path.'/smilies/razz.png" alt=":P" title="' . $this->lang['tongue'] . '" />
            <img onclick="insert_text(\':red:\', true);return false;" src="'.$this->path.'/smilies/red.png" alt=":oops:" title="' . $this->lang['shy'] . '" />
            <img onclick="insert_text(\':cry:\', true);return false;" src="'.$this->path.'/smilies/cry.png" alt=":cry:" title="' . $this->lang['cry'] . '" />
            <img onclick="insert_text(\':evil:\', true);return false;" src="'.$this->path.'/smilies/evil.png" alt=":evil:" title="' . $this->lang['devil'] . '" />
            <img onclick="insert_text(\':twisted:\', true);return false;" src="'.$this->path.'/smilies/twist.png" alt=":twisted:" title="' . $this->lang['twisted'] . '" />
            <img onclick="insert_text(\':roll:\', true);return false;" src="'.$this->path.'/smilies/roll.png" alt=":roll:" title="' . $this->lang['annoyed'] . '" />
            <img onclick="insert_text(\':fat:\', true);return false;" src="'.$this->path.'/smilies/fat.png" alt=":fat:" title="' . $this->lang['fat'] . '" />
            <img onclick="insert_text(\':|\', true);return false;" src="'.$this->path.'/smilies/neutral.png" alt=":|" title="' . $this->lang['neutral'] . '" />
        ';
        return $html;
    }
	
    public function bbcodecolors($numtd,$w,$h){
        $html = '
        <script type="text/javascript">
            // <![CDATA[
            colorPalette('.$numtd.', '.$w.', '.$h.');
            // ]]>
            </script>
        ';
    return $html;
    }
    
    public function bbcodcharmap($numtd,$w,$h){
        $html = '
        <script type="text/javascript">
            // <![CDATA[
            bbcodcharmap('.$numtd.', '.$w.', '.$h.');
            // ]]>
            </script>
        ';
    return $html;
    }
	
    public function bbcodefontsize(){
        $html = '
        <script type="text/javascript">
            // <![CDATA[
            bbcodefontsize();
            // ]]>
            </script>
        ';
    return $html;
    }
	
    public function bbcodefontfamily()
    {
        $html = '
        <script type="text/javascript">
            // <![CDATA[
            bbcodefontfamily();
            // ]]>
            </script>
        ';
        return $html;
    }
	
    public function bbcodeparagraph()
    {
        $html = '
        <script type="text/javascript">
            // <![CDATA[
            bbcodeparagraph();
            // ]]>
            </script>
        ';
        return $html;
    }
	
    public function bbcodebuttons(){
        
        $html = '
                <ul class="bbcodebuttons bbleft">
                    <li class="first" accesskey="b" onclick="bbstyle(0)" title="' . $this->lang['bold'] . '"><img src="'.$this->path.'/images/bold.png" /></li>
            	    <li accesskey="i" onclick="bbstyle(2)" title="' . $this->lang['italic'] . '"><img src="'.$this->path.'/images/italic.png" /></li>
            	    <li accesskey="u" onclick="bbstyle(4)" title="' . $this->lang['underlined'] . '"><img src="'.$this->path.'/images/underline.png" /></li>
					<li accesskey="u" onclick="bbstyle(38)" title="' . $this->lang['Title'] . '"><img src="'.$this->path.'/images/h.png" /></li>
				</ul>
				<ul class="bbcodebuttons bbleft">
                    <li accesskey="jr" onclick="bbstyle(30)" title="' . $this->lang['alignLeft'] . '"><img src="'.$this->path.'/images/left.png" /></li>
            	    <li accesskey="jc" onclick="bbstyle(28)" title="' . $this->lang['alignCenter'] . '"><img src="'.$this->path.'/images/center.png" /></li>
                    <li accesskey="q" onclick="bbstyle(8)" title="' . $this->lang['justify'] . '"><img src="'.$this->path.'/images/justify.png" /></li>
            	    <li accesskey="jl" onclick="bbstyle(26)" title="' . $this->lang['alignRight'] . '"><img src="'.$this->path.'/images/right.png" /></li>
                </ul>
                <ul class="bbcodebuttons bbleft">
                    <li class="first" accesskey="q" onclick="bbstyle(6)" title="' . $this->lang['quote'] . '"><img src="'.$this->path.'/images/quote.png" /></li>
            	    <li onclick="bbstyle(14,\'image\')" title="' . $this->lang['image'] . '"><img src="'.$this->path.'/images/image.png" /></li>
            	    <li onclick="bbstyle(16,\'url\')" title="' . $this->lang['link'] . '"><img src="'.$this->path.'/images/link.png" /></li>
            	    <li onclick="bbstyle(22,\'email\')" title="' . $this->lang['email'] . '"><img src="'.$this->path.'/images/mail.png" /></li>
                    <li onclick="bbstyle(24,\'video\')" title="' . $this->lang['video'] . '"><img src="'.$this->path.'/images/video.png" /></li>
                    <li onclick="bbstyle(36,\'audio\')" title="' . $this->lang['audio'] . '"><img src="'.$this->path.'/images/audio.png" /></li>
                    <li onclick="bbstyle(18,\'flash\')" title="' . $this->lang['flash'] . '"><img src="'.$this->path.'/images/flash.png" /></li>
					<li class="first" accesskey="p" onclick="bbstyle(12)" title="' . $this->lang['syntacticColoration'] . '"><img src="'.$this->path.'/images/php.png" /></li>
                </ul>
                <ul class="bbcodebuttons bbleft">
                    
                    <li accesskey="hr" onclick="bbstyle(10)" title="' . $this->lang['horizontalLine'] . '"><img src="'.$this->path.'/images/hr.png" /></li>
                    <li onclick="bbstyle(32)" title="' . $this->lang['list'] . '"><img src="'.$this->path.'/images/inserlist.png" /></li>
                    <li onclick="bbstyle(34)" title="' . $this->lang['bullet'] . '"><img src="'.$this->path.'/images/insertlilist.png" /></li>
                </ul>
                <ul class="bbcodebuttons bbleft">
                    <li class="first" onclick="showhide(&#39;text[ru]_color&#39;);" title="' . $this->lang['colors'] . '"><img src="'.$this->path.'/images/color.png" id="bbcodecolorsbtn" /><div id="text[ru]_color" class="bb_boxed" style="display: none; ">'.$this->bbcodecolors('8','12','12').'</div></li>
                    <li onclick="showhide(&#39;text[ru]_smilies&#39;);" title="' . $this->lang['smilies'] . '"><img src="'.$this->path.'/images/smiley.png" id="bbcodesmiliesbtn" /><div id="text[ru]_smilies" class="bb_boxed" style="display: none; ">'.$this->bbcodesmilies($this->path).'</div></li>
                </ul>
                ';
        return $html;
    }
	
    public function parsebbcode($string)
    {
		$string = htmlspecialchars($string);
		
		//Code
		 $string = preg_replace('~\[code\](.*?)\[\/code\]~is', '<pre><code>\\1</code></pre>', $string);
        // i b u s h
        $string = preg_replace('~\[i\](.*?)\[\/i\]~is', '<i>\\1</i>', $string);
        $string = preg_replace('~\[b\](.*?)\[\/b\]~is', '<b>\\1</b>', $string);
        $string = preg_replace('~\[u\](.*?)\[\/u\]~is', '<u>\\1</u>', $string);
        $string = preg_replace('~\[s\](.*?)\[\/s\]~is', '<s>\\1</s>', $string);
		$string = preg_replace('~\[h\](.*?)\[\/h\]~is', '<div class="page-header"><h3 id="\\1">\\1</h3></div>', $string);
        // align
        $string = preg_replace('~\[right\](.*?)\[\/right\]~is', '<div style="text-align: right">\\1</div>', $string);
        $string = preg_replace('~\[center\](.*?)\[\/center\]~is', '<div style="text-align: center">\\1</div>', $string);
        $string = preg_replace('~\[left\](.*?)\[\/left\]~is', '<div style="text-align: left" dir="ltr">\\1</div>', $string);
        $string = preg_replace('~\[justify\](.*?)\[\/justify\]~is', '<div style="text-align: justify">\\1</div>', $string);
        // quote
        $string = preg_replace('~\[quote\](.*?)\[\/quote\]~is', '<div class="quote"><div>quote : </div>\\1</div>', $string);
        // image
        $string = preg_replace('~\[img\](.*?)\[\/img\]~is', '<img src="\\1" />', $string);
        // audio
        $string = preg_replace('~\[audio\](.*?)\[\/audio\]~is', '
        <object id="videoplayer4665" type="application/x-shockwave-flash" data="'.$this->path.'/player/uppod.swf" width="400" height="35">
            <param name="bgcolor" value="#ffffff" />
            <param name="allowFullScreen" value="true" />
            <param name="allowScriptAccess" value="always" />
            <param name="movie" value="/" />
            <param name="flashvars" value="m=audio&amp;st='.$this->path.'/player/audio.txt&amp;file=\\1" />
        </object>', $string);
        // video
        $string = preg_replace('~\[video\](.*?)\[\/video\]~is', '
        <object id="videoplayer4665" type="application/x-shockwave-flash" data="'.$this->path.'/player/uppod.swf" width="475" height="281">
            <param name="bgcolor" value="#ffffff" />
            <param name="allowFullScreen" value="true" />
            <param name="allowScriptAccess" value="always" />
            <param name="movie" value="/" />
            <param name="flashvars" value="m=videoo&amp;st='.$this->path.'/player/video.txt&amp;file=\\1" />
        </object>', $string);
        // flash
        $string = preg_replace('~\[flash\=(.*?)\,(.*?)\](.*?)\[\/flash\]~is', '
        <object align="middle" width="\\1" height="\\2" id="bubbles" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000">
        <param value="sameDomain" name="allowScriptAccess">
        <param value="\\3" name="movie">
        <param value="high" name="quality"><param value="#ffffff" name="bgcolor">
        <param value="transparent" name="wmode">
        <embed width="\\1" height="\\2" align="middle" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" allowscriptaccess="sameDomain" name="bubbles" wmode="transparent" bgcolor="#ffffff" quality="high" src="\\3">
        </object>
        ', $string);
        // email
        $string = preg_replace('~\[email\=(.*?)\](.*?)\[\/email\]~is', '<a href="mailto:\\1">\\2</a>', $string);
        $string = preg_replace('~\[email\](.*?)\[\/email\]~is', '<a href="mailto:\\1">\\1</a>', $string);
        // url
        $string = preg_replace('~\[url\=(.*?)\](.*?)\[\/url\]~is', '<a href="\\1">\\2</a>', $string);
        $string = preg_replace('~\[url\](.*?)\[\/url\]~is', '<a href="\\1">\\1</a>', $string);
        // size
        $string = preg_replace('~\[font\=(.*?)\](.*?)\[\/font\]~is', '<span style="font-family:\\1;">\\2</span>', $string);
        // font
        $string = preg_replace('~\[size\=(.*?)\](.*?)\[\/size\]~is', '<font size="\\1">\\2</font>', $string);
        // color
        $string = preg_replace('~\[color\=(.*?)\](.*?)\[\/color\]~is', '<span style="color:\\1;">\\2</span>', $string);
        // hr br
        $string = preg_replace('~\[hr]~is', '<hr />', $string);
        // ul li
        $string = preg_replace('~\[ul\](.*?)\[\/ul\]~is', '<ul>\\1</ul>', $string);
        $string = preg_replace('~\[li\](.*?)\[\/li\]~is', '<li>\\1</li>', $string);
        // h1 h2 h3 h4 h5 h6
        $string = preg_replace('~\[h1\](.*?)\[\/h1\]~is', '<h1>\\1</h1>', $string);
        $string = preg_replace('~\[h2\](.*?)\[\/h2\]~is', '<h2>\\1</h2>', $string);
        $string = preg_replace('~\[h3\](.*?)\[\/h3\]~is', '<h3>\\1</h3>', $string);
        $string = preg_replace('~\[h4\](.*?)\[\/h4\]~is', '<h4>\\1</h4>', $string);
        $string = preg_replace('~\[h5\](.*?)\[\/h5\]~is', '<h5>\\1</h5>', $string);
        $string = preg_replace('~\[h6\](.*?)\[\/h6\]~is', '<h6>\\1</h6>', $string);
        $string = str_replace("\n", "<br />", $string);
        // smilies
        $string  = preg_replace('~\:\)~is','<img src="'.$this->path.'/smilies/grin.png" alt=":D" title="grin" />', $string);
        $string  = preg_replace('~\:smile\:~is','<img src="'.$this->path.'/smilies/smile.png" alt=":)" title="smile" />', $string);
        $string  = preg_replace('~\;\)~is','<img src="'.$this->path.'/smilies/wink.png" alt=";)" title="wink" />', $string);
        $string  = preg_replace('~\:\(~is','<img src="'.$this->path.'/smilies/sad.png" alt=":(" title="sad" />', $string);
        $string  = preg_replace('~\:o~is','<img src="'.$this->path.'/smilies/surprise.png" alt=":o" title="surprise" />', $string);
        $string  = preg_replace('~\:shock\:~is','<img src="'.$this->path.'/smilies/eek.png" alt=":shock:" title="eek" />', $string);
        $string  = preg_replace('~\:\?~is','<img src="'.$this->path.'/smilies/confuse.png" alt=":?" title="confuse" />', $string);
        $string  = preg_replace('~8\-\)~is','<img src="'.$this->path.'/smilies/cool.png" alt="8-)" title="cool" />', $string);
        $string  = preg_replace('~\:lol\:~is','<img src="'.$this->path.'/smilies/lol.png" alt=":lol:" title="lol" />', $string);
        $string  = preg_replace('~\:x~is','<img src="'.$this->path.'/smilies/mad.png" alt=":x" title="mad" />', $string);
        $string  = preg_replace('~\:P~is','<img src="'.$this->path.'/smilies/razz.png" alt=":P" title="razz" />', $string);
        $string  = preg_replace('~\:red\:~is','<img src="'.$this->path.'/smilies/red.png" alt=":oops:" title="red" />', $string);
        $string  = preg_replace('~\:cry\:~is','<img src="'.$this->path.'/smilies/cry.png" alt=":cry:" title="cry" />', $string);
        $string  = preg_replace('~\:evil\:~is','<img src="'.$this->path.'/smilies/evil.png" alt=":evil:" title="twist" />', $string);
        $string  = preg_replace('~\:twisted\:~is','<img src="'.$this->path.'/smilies/twist.png" alt=":twisted:" title="twist" />', $string);
        $string  = preg_replace('~\:roll\:~is','<img src="'.$this->path.'/smilies/roll.png" alt=":roll:" title="roll" />', $string);
        $string  = preg_replace('~\:fat\:~is','<img src="'.$this->path.'/smilies/fat.png" alt=":fat:" title="fat" />', $string);
        $string  = preg_replace('~\:\|~is','<img src="'.$this->path.'/smilies/neutral.png" alt=":|" title="neutral" />', $string);
    	
		return $string;
    }
}   
?>