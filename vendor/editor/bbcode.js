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
//##################################################################################################### Mega Class BBCODE 
//------------------------------------------------------------------------------------------------
// <![CDATA[
	//var form_name = 'postform';
	//var text_name = 'post';
	// Define the bbCode tags
	var bbcode = new Array();
	var bbtags = new Array(
        '[b]','[/b]',
        '[i]','[/i]',
        '[u]','[/u]',
        '[quote]','[/quote]',
        '[justify]','[/justify]',
        '[hr]','',
        '[code]\n','\n[/code]',
        '[img]','[/img]',
        '[url]','[/url]',
        '[flash=500,350]','[/flash]',
        '[size=]','[/size]',
        '[email]','[/email]',
        '[video]','[/video]',
        '[right]','[/right]',
        '[center]','[/center]',
        '[left]','[/left]',
        '[ul]\n','\n[/ul]',
        '[li]','[/li]',
        '[audio]','[/audio]',
		'[h]', '[/h]'
    );
// ]]>
//------------------------------------------------------------------------------------------------
var imageTag      = true;
var theSelection  = true;
var bbcodeEnabled = true;
//------------------------------------------------------------------------------------------------
var clientPC = navigator.userAgent.toLowerCase(); // Get client info
var clientVer = parseInt(navigator.appVersion); // Get browser version
var is_ie = ((clientPC.indexOf('msie') != -1) && (clientPC.indexOf('opera') == -1));
var is_win = ((clientPC.indexOf('win') != -1) || (clientPC.indexOf('16bit') != -1));
var baseHeight;
//------------------------------------------------------------------------------------------------
function initInsertions() 
{
	var doc;
	if (document.forms[form_name])
	{
		doc = document;
	}
	else 
	{
		doc = opener.document;
	}
	var textarea = doc.forms[form_name].elements[text_name];
	if (is_ie && typeof(baseHeight) != 'number')
	{
		textarea.focus();
		baseHeight = doc.selection.createRange().duplicate().boundingHeight;
		if (!document.forms[form_name])
		{
			document.body.focus();
		}
	}
}
//------------------------------------------------------------------------------------------------
function bbstyle(bbnumber,bbcode)
{	
	if (bbnumber != -1){
		bbfontstyle(bbtags[bbnumber], bbtags[bbnumber+1],bbcode);
	} 
	else {
		insert_text('[*]');
		document.forms[form_name].elements[text_name].focus();
	}
}
//------------------------------------------------------------------------------------------------
function bbfontstyle(bbopen, bbclose,bbcode){
    if(bbcode){
        if(bbcode == 'image'){
            var returntext = prompt("Please enter the URL of your image:","http://",true);
            if(returntext == 'http://' || returntext == 'null' || !returntext)
            {
                return fales;
            }
            bbopen = bbopen + returntext;
        }
        if(bbcode == 'url'){
            var returntext = prompt("Please enter the URL of your link:","http://",true);
            if(returntext == 'http://' || returntext == 'null' || !returntext)
            {
                return fales;
            }
            bbopen = bbopen + returntext;
        }
        if(bbcode == 'email'){
            var returntext = prompt("Please enter the email address for the link:","",true);
            if(returntext == '' || returntext == 'null' || !returntext)
            {
                return fales;
            }
            bbopen = bbopen + returntext;
        }
        if(bbcode == 'video'){
            var returntext = prompt("Please enter the URL of your video:","http://",true);
            if(returntext == 'http://' || returntext == 'null' || !returntext)
            {
                return fales;
            }
            bbopen = bbopen + returntext;
        }
        if(bbcode == 'audio'){
            var returntext = prompt("Please enter the URL of your audio:","http://",true);
            if(returntext == 'http://' || returntext == 'null' || !returntext)
            {
                return fales;
            }
            bbopen = bbopen + returntext;
        }
        if(bbcode == 'flash'){
            var returntext = prompt("Please enter the URL of your flash:","http://",true);
            if(returntext == 'http://' || returntext == 'null' || !returntext)
            {
                return fales;
            }
            bbopen = bbopen + returntext;
        }
    }
	theSelection = false;
	var textarea = document.forms[form_name].elements[text_name];
	textarea.focus();
	if ((clientVer >= 4) && is_ie && is_win)
	{
		theSelection = document.selection.createRange().text;
		if (theSelection)
		{
			document.selection.createRange().text = bbopen + theSelection + bbclose;
			document.forms[form_name].elements[text_name].focus();
			theSelection = '';
			return;
		}
	}
	else if (document.forms[form_name].elements[text_name].selectionEnd && (document.forms[form_name].elements[text_name].selectionEnd - document.forms[form_name].elements[text_name].selectionStart > 0))
	{
		mozWrap(document.forms[form_name].elements[text_name], bbopen, bbclose);
		document.forms[form_name].elements[text_name].focus();
		theSelection = '';
		return;
	}
	var caret_pos = getCaretPosition(textarea).start;
	var new_pos = caret_pos + bbopen.length;		
	insert_text(bbopen + bbclose);
	if (!isNaN(textarea.selectionStart))
	{
		textarea.selectionStart = new_pos;
		textarea.selectionEnd = new_pos;
	}	
	else if (document.selection)
	{
		var range = textarea.createTextRange(); 
		range.move("character", new_pos); 
		range.select();
		storeCaret(textarea);
	}
	textarea.focus();
	return;
}
//------------------------------------------------------------------------------------------------
function insert_text(text, spaces, popup)
{
	var textarea;
	if (!popup) 
	{
		textarea = document.forms[form_name].elements[text_name];
	} 
	else 
	{
		textarea = opener.document.forms[form_name].elements[text_name];
	}
	if (spaces) 
	{
		text = ' ' + text + ' ';
	}
	if (!isNaN(textarea.selectionStart) && !is_ie)
	{
		var sel_start = textarea.selectionStart;
		var sel_end = textarea.selectionEnd;
		mozWrap(textarea, text, '');
		textarea.selectionStart = sel_start + text.length;
		textarea.selectionEnd = sel_end + text.length;
	}
	else if (textarea.createTextRange && textarea.caretPos)
	{
		if (baseHeight != textarea.caretPos.boundingHeight) 
		{
			textarea.focus();
			storeCaret(textarea);
		}
		var caret_pos = textarea.caretPos;
		caret_pos.text = caret_pos.text.charAt(caret_pos.text.length - 1) == ' ' ? caret_pos.text + text + ' ' : caret_pos.text + text;
	}
	else
	{
		textarea.value = textarea.value + text;
	}
	if (!popup) 
	{
		textarea.focus();
	}
}
//------------------------------------------------------------------------------------------------
function mozWrap(txtarea, open, close)
{
	var selLength = (typeof(txtarea.textLength) == 'undefined') ? txtarea.value.length : txtarea.textLength;
	var selStart = txtarea.selectionStart;
	var selEnd = txtarea.selectionEnd;
	var scrollTop = txtarea.scrollTop;
	if (selEnd == 1 || selEnd == 2) 
	{
		selEnd = selLength;
	}
	var s1 = (txtarea.value).substring(0,selStart);
	var s2 = (txtarea.value).substring(selStart, selEnd);
	var s3 = (txtarea.value).substring(selEnd, selLength);
	txtarea.value = s1 + open + s2 + close + s3;
	txtarea.selectionStart = selStart + open.length;
	txtarea.selectionEnd = selEnd + open.length;
	txtarea.focus();
	txtarea.scrollTop = scrollTop;
	return;
}
//------------------------------------------------------------------------------------------------
function storeCaret(textEl)
{
	if (textEl.createTextRange)
	{
		textEl.caretPos = document.selection.createRange().duplicate();
	}
}
//------------------------------------------------------------------------------------------------
function bbcodefontsize(){
	for (b = 1; b <= 7; b++){
		document.writeln('<div onclick="bbfontstyle(\'[size='+b+']\', \'[/size]\');return false;" style="text-align:center;line-height:1.0" title="'+ b +'"><font size="'+ b +'">'+ b +'</font></div>');
	}
}
//------------------------------------------------------------------------------------------------
function bbcodefontfamily(){
    var family = new Array(
    "Arial", 
    "Arial Black", 
    "Arial Narrow", 
    "Book Antiqua", 
    "Century Gothic", 
    "Comic Sans MS", 
    "Courier New", 
    "Fixedsys", 
    "Franklin Gothic Medium", 
    "Garamond", 
    "Georgia", 
    "Impact", 
    "Lucida Console", 
    "Lucida Sans Unicode", 
    "Microsoft Sans Serif", 
    "Palatino Linotype", 
    "System", 
    "Tahoma", 
    "Times New Roman", 
    "Trebuchet MS", 
    "Verdana"
    );
	for (b = 0; b <= family.length; b++){
		document.writeln('<div onclick="bbfontstyle(\'[font='+family[b]+']\', \'[/font]\');return false;" title="'+family[b]+'" style="font-family: '+family[b]+';">'+family[b]+'</div>');
	}
}
//------------------------------------------------------------------------------------------------
function bbcodeparagraph(){
    var fontsize = new Array(
    "0",
    "26",
    "20",
    "15",
    "13",
    "11",
    "10"
    );
	for (b = 1; b <= 6; b++){
		document.writeln('<div onclick="bbfontstyle(\'[h'+b+']\', \'[/h'+b+']\');return false;" style="font-size:'+fontsize[b]+'px;font-weight:bold;" title="H'+[b]+'">Heading '+b+'</div>');
	}
}
//------------------------------------------------------------------------------------------------
function colorPalette(td, width, height){
    var colors = new Array(
    "#000000","#A0522D","#556B2F","#006400","#483D8B","#000080","#4B0082","#2F4F4F","#8B0000","#FF8C00",
    "#808000","#008000","#008080","#0000FF","#708090","#696969","#FF0000","#F4A460","#9ACD32","#2E8B57",
    "#48D1CC","#4169E1","#800080","#808080","#FF00FF","#FFA500","#FFFF00","#00FF00","#00FFFF","#00BFFF",
    "#9932CC","#C0C0C0","#FFC0CB","#F5DEB3","#FFFACD","#98FB98","#AFEEEE","#ADD8E6","#DDA0DD","#FFFFFF"
    );
    var tr = 0;
	document.writeln('<table cellspacing="0" cellpadding="0" border="0" dir="ltr" style="border:1px solid #aca899;background:#ffffff;"><tr>');
			for (b = 0; b < colors.length; b++){tr++;
				document.write('<td><div style="margin:1px 0 0 1px;background:' + colors[b] + ';display: block;border:1px solid #aca899;cursor: pointer;width: ' + width + 'px; height: ' + height + 'px;"');
				document.write('onclick="bbfontstyle(\'[color=' + colors[b] + ']\', \'[/color]\'); return false">');
				document.writeln('</div></td>');
                if (td == tr){document.writeln('</tr><tr>'); tr = 0}
			}
	document.writeln('</tr></table>');
}
//------------------------------------------------------------------------------------------------
function bbcodcharmap(td, width, height){

    
    var charmap = [
	['&nbsp;',    '&#160;',  true, 'no-break space'],
	['&amp;',     '&#38;',   true, 'ampersand'],
	['&quot;',    '&#34;',   true, 'quotation mark'],
// finance
	['&cent;',    '&#162;',  true, 'cent sign'],
	['&euro;',    '&#8364;', true, 'euro sign'],
	['&pound;',   '&#163;',  true, 'pound sign'],
	['&yen;',     '&#165;',  true, 'yen sign'],
// signs
	['&copy;',    '&#169;',  true, 'copyright sign'],
	['&reg;',     '&#174;',  true, 'registered sign'],
	['&trade;',   '&#8482;', true, 'trade mark sign'],
	['&permil;',  '&#8240;', true, 'per mille sign'],
	['&micro;',   '&#181;',  true, 'micro sign'],
	['&middot;',  '&#183;',  true, 'middle dot'],
	['&bull;',    '&#8226;', true, 'bullet'],
	['&hellip;',  '&#8230;', true, 'three dot leader'],
	['&prime;',   '&#8242;', true, 'minutes / feet'],
	['&Prime;',   '&#8243;', true, 'seconds / inches'],
	['&sect;',    '&#167;',  true, 'section sign'],
	['&para;',    '&#182;',  true, 'paragraph sign'],
	['&szlig;',   '&#223;',  true, 'sharp s / ess-zed'],
// quotations
	['&lsaquo;',  '&#8249;', true, 'single left-pointing angle quotation mark'],
	['&rsaquo;',  '&#8250;', true, 'single right-pointing angle quotation mark'],
	['&laquo;',   '&#171;',  true, 'left pointing guillemet'],
	['&raquo;',   '&#187;',  true, 'right pointing guillemet'],
	['&lsquo;',   '&#8216;', true, 'left single quotation mark'],
	['&rsquo;',   '&#8217;', true, 'right single quotation mark'],
	['&ldquo;',   '&#8220;', true, 'left double quotation mark'],
	['&rdquo;',   '&#8221;', true, 'right double quotation mark'],
	['&sbquo;',   '&#8218;', true, 'single low-9 quotation mark'],
	['&bdquo;',   '&#8222;', true, 'double low-9 quotation mark'],
	['&lt;',      '&#60;',   true, 'less-than sign'],
	['&gt;',      '&#62;',   true, 'greater-than sign'],
	['&le;',      '&#8804;', true, 'less-than or equal to'],
	['&ge;',      '&#8805;', true, 'greater-than or equal to'],
	['&ndash;',   '&#8211;', true, 'en dash'],
	['&mdash;',   '&#8212;', true, 'em dash'],
	['&macr;',    '&#175;',  true, 'macron'],
	['&oline;',   '&#8254;', true, 'overline'],
	['&curren;',  '&#164;',  true, 'currency sign'],
	['&brvbar;',  '&#166;',  true, 'broken bar'],
	['&uml;',     '&#168;',  true, 'diaeresis'],
	['&iexcl;',   '&#161;',  true, 'inverted exclamation mark'],
	['&iquest;',  '&#191;',  true, 'turned question mark'],
	['&circ;',    '&#710;',  true, 'circumflex accent'],
	['&tilde;',   '&#732;',  true, 'small tilde'],
	['&deg;',     '&#176;',  true, 'degree sign'],
	['&minus;',   '&#8722;', true, 'minus sign'],
	['&plusmn;',  '&#177;',  true, 'plus-minus sign'],
	['&divide;',  '&#247;',  true, 'division sign'],
	['&frasl;',   '&#8260;', true, 'fraction slash'],
	['&times;',   '&#215;',  true, 'multiplication sign'],
	['&sup1;',    '&#185;',  true, 'superscript one'],
	['&sup2;',    '&#178;',  true, 'superscript two'],
	['&sup3;',    '&#179;',  true, 'superscript three'],
	['&frac14;',  '&#188;',  true, 'fraction one quarter'],
	['&frac12;',  '&#189;',  true, 'fraction one half'],
	['&frac34;',  '&#190;',  true, 'fraction three quarters'],
// math / logical
	['&fnof;',    '&#402;',  true, 'function / florin'],
	['&int;',     '&#8747;', true, 'integral'],
	['&sum;',     '&#8721;', true, 'n-ary sumation'],
	['&infin;',   '&#8734;', true, 'infinity'],
	['&radic;',   '&#8730;', true, 'square root'],
	['&sim;',     '&#8764;', false,'similar to'],
	['&cong;',    '&#8773;', false,'approximately equal to'],
	['&asymp;',   '&#8776;', true, 'almost equal to'],
	['&ne;',      '&#8800;', true, 'not equal to'],
	['&equiv;',   '&#8801;', true, 'identical to'],
	['&isin;',    '&#8712;', false,'element of'],
	['&notin;',   '&#8713;', false,'not an element of'],
	['&ni;',      '&#8715;', false,'contains as member'],
	['&prod;',    '&#8719;', true, 'n-ary product'],
	['&and;',     '&#8743;', false,'logical and'],
	['&or;',      '&#8744;', false,'logical or'],
	['&not;',     '&#172;',  true, 'not sign'],
	['&cap;',     '&#8745;', true, 'intersection'],
	['&cup;',     '&#8746;', false,'union'],
	['&part;',    '&#8706;', true, 'partial differential'],
	['&forall;',  '&#8704;', false,'for all'],
	['&exist;',   '&#8707;', false,'there exists'],
	['&empty;',   '&#8709;', false,'diameter'],
	['&nabla;',   '&#8711;', false,'backward difference'],
	['&lowast;',  '&#8727;', false,'asterisk operator'],
	['&prop;',    '&#8733;', false,'proportional to'],
	['&ang;',     '&#8736;', false,'angle'],
// undefined
	['&acute;',   '&#180;',  true, 'acute accent'],
	['&cedil;',   '&#184;',  true, 'cedilla'],
	['&ordf;',    '&#170;',  true, 'feminine ordinal indicator'],
	['&ordm;',    '&#186;',  true, 'masculine ordinal indicator'],
	['&dagger;',  '&#8224;', true, 'dagger'],
	['&Dagger;',  '&#8225;', true, 'double dagger'],
// alphabetical special chars
	['&Agrave;',  '&#192;',  true, 'A - grave'],
	['&Aacute;',  '&#193;',  true, 'A - acute'],
	['&Acirc;',   '&#194;',  true, 'A - circumflex'],
	['&Atilde;',  '&#195;',  true, 'A - tilde'],
	['&Auml;',    '&#196;',  true, 'A - diaeresis'],
	['&Aring;',   '&#197;',  true, 'A - ring above'],
	['&AElig;',   '&#198;',  true, 'ligature AE'],
	['&Ccedil;',  '&#199;',  true, 'C - cedilla'],
	['&Egrave;',  '&#200;',  true, 'E - grave'],
	['&Eacute;',  '&#201;',  true, 'E - acute'],
	['&Ecirc;',   '&#202;',  true, 'E - circumflex'],
	['&Euml;',    '&#203;',  true, 'E - diaeresis'],
	['&Igrave;',  '&#204;',  true, 'I - grave'],
	['&Iacute;',  '&#205;',  true, 'I - acute'],
	['&Icirc;',   '&#206;',  true, 'I - circumflex'],
	['&Iuml;',    '&#207;',  true, 'I - diaeresis'],
	['&ETH;',     '&#208;',  true, 'ETH'],
	['&Ntilde;',  '&#209;',  true, 'N - tilde'],
	['&Ograve;',  '&#210;',  true, 'O - grave'],
	['&Oacute;',  '&#211;',  true, 'O - acute'],
	['&Ocirc;',   '&#212;',  true, 'O - circumflex'],
	['&Otilde;',  '&#213;',  true, 'O - tilde'],
	['&Ouml;',    '&#214;',  true, 'O - diaeresis'],
	['&Oslash;',  '&#216;',  true, 'O - slash'],
	['&OElig;',   '&#338;',  true, 'ligature OE'],
	['&Scaron;',  '&#352;',  true, 'S - caron'],
	['&Ugrave;',  '&#217;',  true, 'U - grave'],
	['&Uacute;',  '&#218;',  true, 'U - acute'],
	['&Ucirc;',   '&#219;',  true, 'U - circumflex'],
	['&Uuml;',    '&#220;',  true, 'U - diaeresis'],
	['&Yacute;',  '&#221;',  true, 'Y - acute'],
	['&Yuml;',    '&#376;',  true, 'Y - diaeresis'],
	['&THORN;',   '&#222;',  true, 'THORN'],
	['&agrave;',  '&#224;',  true, 'a - grave'],
	['&aacute;',  '&#225;',  true, 'a - acute'],
	['&acirc;',   '&#226;',  true, 'a - circumflex'],
	['&atilde;',  '&#227;',  true, 'a - tilde'],
	['&auml;',    '&#228;',  true, 'a - diaeresis'],
	['&aring;',   '&#229;',  true, 'a - ring above'],
	['&aelig;',   '&#230;',  true, 'ligature ae'],
	['&ccedil;',  '&#231;',  true, 'c - cedilla'],
	['&egrave;',  '&#232;',  true, 'e - grave'],
	['&eacute;',  '&#233;',  true, 'e - acute'],
	['&ecirc;',   '&#234;',  true, 'e - circumflex'],
	['&euml;',    '&#235;',  true, 'e - diaeresis'],
	['&igrave;',  '&#236;',  true, 'i - grave'],
	['&iacute;',  '&#237;',  true, 'i - acute'],
	['&icirc;',   '&#238;',  true, 'i - circumflex'],
	['&iuml;',    '&#239;',  true, 'i - diaeresis'],
	['&eth;',     '&#240;',  true, 'eth'],
	['&ntilde;',  '&#241;',  true, 'n - tilde'],
	['&ograve;',  '&#242;',  true, 'o - grave'],
	['&oacute;',  '&#243;',  true, 'o - acute'],
	['&ocirc;',   '&#244;',  true, 'o - circumflex'],
	['&otilde;',  '&#245;',  true, 'o - tilde'],
	['&ouml;',    '&#246;',  true, 'o - diaeresis'],
	['&oslash;',  '&#248;',  true, 'o slash'],
	['&oelig;',   '&#339;',  true, 'ligature oe'],
	['&scaron;',  '&#353;',  true, 's - caron'],
	['&ugrave;',  '&#249;',  true, 'u - grave'],
	['&uacute;',  '&#250;',  true, 'u - acute'],
	['&ucirc;',   '&#251;',  true, 'u - circumflex'],
	['&uuml;',    '&#252;',  true, 'u - diaeresis'],
	['&yacute;',  '&#253;',  true, 'y - acute'],
	['&thorn;',   '&#254;',  true, 'thorn'],
	['&yuml;',    '&#255;',  true, 'y - diaeresis'],
	['&Alpha;',   '&#913;',  true, 'Alpha'],
	['&Beta;',    '&#914;',  true, 'Beta'],
	['&Gamma;',   '&#915;',  true, 'Gamma'],
	['&Delta;',   '&#916;',  true, 'Delta'],
	['&Epsilon;', '&#917;',  true, 'Epsilon'],
	['&Zeta;',    '&#918;',  true, 'Zeta'],
	['&Eta;',     '&#919;',  true, 'Eta'],
	['&Theta;',   '&#920;',  true, 'Theta'],
	['&Iota;',    '&#921;',  true, 'Iota'],
	['&Kappa;',   '&#922;',  true, 'Kappa'],
	['&Lambda;',  '&#923;',  true, 'Lambda'],
	['&Mu;',      '&#924;',  true, 'Mu'],
	['&Nu;',      '&#925;',  true, 'Nu'],
	['&Xi;',      '&#926;',  true, 'Xi'],
	['&Omicron;', '&#927;',  true, 'Omicron'],
	['&Pi;',      '&#928;',  true, 'Pi'],
	['&Rho;',     '&#929;',  true, 'Rho'],
	['&Sigma;',   '&#931;',  true, 'Sigma'],
	['&Tau;',     '&#932;',  true, 'Tau'],
	['&Upsilon;', '&#933;',  true, 'Upsilon'],
	['&Phi;',     '&#934;',  true, 'Phi'],
	['&Chi;',     '&#935;',  true, 'Chi'],
	['&Psi;',     '&#936;',  true, 'Psi'],
	['&Omega;',   '&#937;',  true, 'Omega'],
	['&alpha;',   '&#945;',  true, 'alpha'],
	['&beta;',    '&#946;',  true, 'beta'],
	['&gamma;',   '&#947;',  true, 'gamma'],
	['&delta;',   '&#948;',  true, 'delta'],
	['&epsilon;', '&#949;',  true, 'epsilon'],
	['&zeta;',    '&#950;',  true, 'zeta'],
	['&eta;',     '&#951;',  true, 'eta'],
	['&theta;',   '&#952;',  true, 'theta'],
	['&iota;',    '&#953;',  true, 'iota'],
	['&kappa;',   '&#954;',  true, 'kappa'],
	['&lambda;',  '&#955;',  true, 'lambda'],
	['&mu;',      '&#956;',  true, 'mu'],
	['&nu;',      '&#957;',  true, 'nu'],
	['&xi;',      '&#958;',  true, 'xi'],
	['&omicron;', '&#959;',  true, 'omicron'],
	['&pi;',      '&#960;',  true, 'pi'],
	['&rho;',     '&#961;',  true, 'rho'],
	['&sigmaf;',  '&#962;',  true, 'final sigma'],
	['&sigma;',   '&#963;',  true, 'sigma'],
	['&tau;',     '&#964;',  true, 'tau'],
	['&upsilon;', '&#965;',  true, 'upsilon'],
	['&phi;',     '&#966;',  true, 'phi'],
	['&chi;',     '&#967;',  true, 'chi'],
	['&psi;',     '&#968;',  true, 'psi'],
	['&omega;',   '&#969;',  true, 'omega'],
// symbols
	['&alefsym;', '&#8501;', false,'alef symbol'],
	['&piv;',     '&#982;',  false,'pi symbol'],
	['&real;',    '&#8476;', false,'real part symbol'],
	['&thetasym;','&#977;',  false,'theta symbol'],
	['&upsih;',   '&#978;',  false,'upsilon - hook symbol'],
	['&weierp;',  '&#8472;', false,'Weierstrass p'],
	['&image;',   '&#8465;', false,'imaginary part'],
// arrows
	['&larr;',    '&#8592;', true, 'leftwards arrow'],
	['&uarr;',    '&#8593;', true, 'upwards arrow'],
	['&rarr;',    '&#8594;', true, 'rightwards arrow'],
	['&darr;',    '&#8595;', true, 'downwards arrow'],
	['&harr;',    '&#8596;', true, 'left right arrow'],
	['&crarr;',   '&#8629;', false,'carriage return'],
	['&lArr;',    '&#8656;', false,'leftwards double arrow'],
	['&uArr;',    '&#8657;', false,'upwards double arrow'],
	['&rArr;',    '&#8658;', false,'rightwards double arrow'],
	['&dArr;',    '&#8659;', false,'downwards double arrow'],
	['&hArr;',    '&#8660;', false,'left right double arrow'],
	['&there4;',  '&#8756;', false,'therefore'],
	['&sub;',     '&#8834;', false,'subset of'],
	['&sup;',     '&#8835;', false,'superset of'],
	['&nsub;',    '&#8836;', false,'not a subset of'],
	['&sube;',    '&#8838;', false,'subset of or equal to'],
	['&supe;',    '&#8839;', false,'superset of or equal to'],
	['&oplus;',   '&#8853;', false,'circled plus'],
	['&otimes;',  '&#8855;', false,'circled times'],
	['&perp;',    '&#8869;', false,'perpendicular'],
	['&sdot;',    '&#8901;', false,'dot operator'],
	['&lceil;',   '&#8968;', false,'left ceiling'],
	['&rceil;',   '&#8969;', false,'right ceiling'],
	['&lfloor;',  '&#8970;', false,'left floor'],
	['&rfloor;',  '&#8971;', false,'right floor'],
	['&lang;',    '&#9001;', false,'left-pointing angle bracket'],
	['&rang;',    '&#9002;', false,'right-pointing angle bracket'],
	['&loz;',     '&#9674;', true, 'lozenge'],
	['&spades;',  '&#9824;', true, 'black spade suit'],
	['&clubs;',   '&#9827;', true, 'black club suit'],
	['&hearts;',  '&#9829;', true, 'black heart suit'],
	['&diams;',   '&#9830;', true, 'black diamond suit'],
	['&ensp;',    '&#8194;', false,'en space'],
	['&emsp;',    '&#8195;', false,'em space'],
	['&thinsp;',  '&#8201;', false,'thin space'],
	['&zwnj;',    '&#8204;', false,'zero width non-joiner'],
	['&zwj;',     '&#8205;', false,'zero width joiner'],
	['&lrm;',     '&#8206;', false,'left-to-right mark'],
	['&rlm;',     '&#8207;', false,'right-to-left mark'],
	['&shy;',     '&#173;',  false,'soft hyphen']
];


    var tr = 0;
	document.writeln('<table cellspacing="0" cellpadding="0" border="0" dir="ltr" style="border:1px solid #aca899;background:#ffffff;"><tr>');
			for (i=0; i<244; i++) {tr++;
				document.write('<td align="center"><div title="'+charmap[i][3]+'" onclick="bbfontstyle(\''+charmap[i][1]+'\', \'\'); return false" style="margin:1px 0 0 1px;border: 1px solid #AAA;font-size: 12px;vertical-align: middle;line-height: 18px;width: ' + width + 'px; height: ' + height + 'px;">');
				document.write(charmap[i][1]);
				document.writeln('</div></td>');
                if (td == tr){document.writeln('</tr><tr>'); tr = 0}
			}
	document.writeln('</tr></table>');
}
//------------------------------------------------------------------------------------------------
function caretPosition()
{
	var start = null;
	var end = null;
}
//------------------------------------------------------------------------------------------------
function getCaretPosition(txtarea){
	var caretPos = new caretPosition();
	if(txtarea.selectionStart || txtarea.selectionStart == 0){
		caretPos.start = txtarea.selectionStart;
		caretPos.end = txtarea.selectionEnd;
	}
	else if(document.selection){
		var range = document.selection.createRange();
		var range_all = document.body.createTextRange();
		range_all.moveToElementText(txtarea);
		var sel_start;
		for (sel_start = 0; range_all.compareEndPoints('StartToStart', range) < 0; sel_start++){		
			range_all.moveStart('character', 1);
		}
		txtarea.sel_start = sel_start;
		caretPos.start = txtarea.sel_start;
		caretPos.end = txtarea.sel_start;			
	}
	return caretPos;
}
//------------------------------------------------------------------------------------------------
function gid(elemid) 
{ 
    return document.getElementById(elemid); 
}
//------------------------------------------------------------------------------------------------
function showhide(id, disable) 
{
	gid(id).style.display = gid(id).style.display == "none" ? "block" : "none";
}
//------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------