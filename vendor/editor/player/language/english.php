<?php 

/* UPPOD multimedia Player module v.1.6.5 for Elxis CMS
* Copyright (C) 2011 www.runsite.ru - All rights reserved.
* Licence http://creativecommons.org/licenses/by-sa/3.0/deed.en Creative Commons 3.0 SA
* uppod player (http://uppod.ru/) is video player for the Web
*
* ---- THIS FILE MUST BE ENCODED AS UTF-8!---
*
*/

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

define ('CX_MODUP_DESC', '<h2>Module "UPPOD multimedia Player" </h2>
  <p align = "justify"> <strong> uppod </strong> (<a href = "http://uppod.ru/" target = "_ blank"> http://uppod.ru / </a>) multimedia a player for a web sites extending on the basis of rules (<a href = "http://uppod.ru/rules" target = "_ blank"> http://uppod.ru/rules </a>).
<br/> With the module uppod v.1.6.3 for Elxis CMS you can show video, audio, photo, radio in any part of a site on Elxis CMS. <br/> the Player supports playlists (<b>modules/mod_uppod/playlist_video.txt</b>), local files (<b> images/videos / </b>), external files (<b> http://domein.com/video.flv </b>). <br/> <br/> 
<b> Support of standards </b>: <br/> Flash video (H.263), MPEG-4 (H.264 - HD); <br/> video formats: F4V, MP4, MOV, M4A, MP4V, 3GP and 3G2, <br/> audio - AAC (HE, LC); <br/> video codec: On2 VP6, Sorenson Spark, H.264; <br/> audio codec: MP3 (11, 22, 44 kHz). <br/> images - jpg, gif, png <br/> support strimming and pseudo-line loading. <br/> 
Ability to change the video quality <br /> <br />
<b> Hot keys </b>: a space, cliques on the screen - start-up / a pause; double cliques on the screen - incl. / off full screen mode; arrows up/ down - a regulator of audio level for audio and screen scaling in other modes; Del - incl. / off a sound; sss and ppp - screenshots; qqq - switching of smoothing for video; eee - the sharpness filter (does not work for StageVideo). <br/> <br/>
<span style = "color:#ff0000;"> Attention: </span> <br/>
The player supports reproduction the Internet radio, therefore on a control bar there is no scroll bar! <br/>
At use of a mode of a photo album it is necessary to consider that images are stretched in full screen on Width or Height! <br/>
The format of files of playlists can be TXT and XML. <br/>
For video viewing (500x400) a skin file - <strong> video_skin.txt </strong> a file of the playlist <strong> playlist_video.txt </strong> <br/>
For audio listening (400x100) a skin file - <strong> audio_skin.txt </strong> a file of the playlist <strong> playlist_audio.txt </strong> <br/>
For viewing of images (500x400) a skin file - <strong> photo_skin.txt </strong> a file of the playlist <strong> playlist_photo.txt </strong> <br/>
<strong> Crossdomain </strong> <br/>
    The security policy established Adobe Flash, demands the special permission for some operations at loading of graphic files from other domains (if the player and a file lie on different domains, variants of a writing of one domain with www and without also are considered various). In particular, it is impossible to make image smoothing at picture scaling. To resolve all operations - place a file <strong>crossdomain.xml</strong> in a site root from which files are loaded. Thus, the way to a file should look as http://site.com/crossdomain.xml
    Any corrections in a file it is not required, it simply enough to keep on a site. <br/>
    Also the given restriction concerns other options - readings ID3-tag from MP3-files, loadings of SWF-rollers. These options also demand presence of an allowing file. <br/>
<br/>
The additional information and the help on work with the module on a site <a href = "http://bisar.ru/eforum/#category4" target = "_ blank" title = "the Additional information and the help on work with the module uppod"> Bisar Entertainments </a> <br/>
  Copyright (C) 2011 <a href = "http://runsite.ru" target = "_ blank"> www.runsite.ru </a> - All rights reserved - Licence Creative Commons 3.0 SA </p>');
define ('CX_MODUP_STYLE', 'the Skin');
define ('CX_MODUP_STYLE_DESC', 'the Name of a file of a skin for a player. txt or xml, by default video_style.txt in <b> modules/mod_uppod / </b>');
define ('CX_MODUP_LIST', 'the Playlist');
define ('CX_MODUP_LIST_DESC', ' the Name of a file with video list, audio, a photo of files for reproduction. txt or xml, by default playlist_video.txt in <b> modules/mod_uppod / </b>');
define ('CX_MODUP_WIDTH', 'Width');
define ('CX_MODUP_WIDTH_DESC', 'Width of a player, by default 500px');
define ('CX_MODFLP_HEIGHT', 'Height');
define ('CX_MODFLP_HEIGHT_DESC', 'player Height, by default 400px');
?>