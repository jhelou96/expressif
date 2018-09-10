<?php 

/* UPPOD multimedia Player module v.1.6.5 for Elxis CMS
* Copyright (C) 2011 www.runsite.ru - All rights reserved.
* Licence http://creativecommons.org/licenses/by-sa/3.0/deed.en Creative Commons 3.0 SA
* uppod player (http://uppod.ru/) is  video player for the Web
*
* ---- THIS FILE MUST BE ENCODED AS UTF-8! ----
*
*/

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

define('CX_MODUP_DESC','<h2>Модуль "UPPOD multimedia Player"</h2>
  <p align="justify"><strong>uppod</strong> (<a href="http://uppod.ru/" target="_blank">http://uppod.ru/</a>) мультимедиа плеер для веб сайтов распространяющийся на основании  правил (<a href="http://uppod.ru/rules" target="_blank">http://uppod.ru/rules</a>).
<br />C помощью модуля uppod  v.1.6.3 для Elxis CMS Вы можете показать видео, аудио, фото, радио в любой части сайта на Elxis CMS. <br />Плеер поддерживает плейлисты(<b>modules/mod_uppod/playlist_video.txt</b>), локальные файлы(<b>images/videos/</b>), внешние файлы(<b>http://domein.com/video.flv</b>).<br /><br /> 
<b>Поддержка стандартов</b>:<br /> Flash video (H.263), MPEG-4 (H.264 — HD);<br /> форматы видео: F4V, MP4, MOV, M4A, MP4V, 3GP и 3G2,<br /> аудио — AAC (HE, LC); <br />кодеки видео: On2 VP6, Sorenson Spark, H.264; <br />кодеки аудио: MP3 (11, 22, 44 кГц) .<br /> Поддержка jpg, gif, png<br />поддержка стримминга и псевдопоточной загрузки. <br />
Возможность изменения качества видео<br /><br />
<b>Горячие клавиши</b>: пробел, клик по экрану — пуск / пауза; двойной клик по экрану — вкл. / выкл. полноэкранного режима; стрелки вверх / вниз - регулятор уровня громкости для аудио и масштабирование экрана в других режимах; Del — вкл. / выкл. звука; sss и ppp — скриншоты; qqq - переключение сглаживания для видео; eee - фильтр резкости (не работает для StageVideo). <br /><br />
<span style="color:#ff0000;">Внимание:</span><br />
Плеер поддерживает воспроизведение интернет радио, поэтому на панели управления отсутствует полоса прокрутки!<br />
При использование режима фотоальбома необходимо учесть то что изображения растягиваются в весь экран!<br />
Формат файлов плейлистов может быть как TXT так и XML.<br />
Для просмотра видео (500x400) файл обложки - <strong>video_skin.txt</strong> файл плейлиста <strong>playlist_video.txt</strong><br />
Для прослушивания аудио (400x100) файл обложки - <strong>audio_skin.txt</strong> файл плейлиста <strong>playlist_audio.txt</strong><br />
Для просмотра изображений (500x400) файл обложки - <strong>photo_skin.txt</strong> файл плейлиста <strong>playlist_photo.txt</strong><br /><br />
<strong>Crossdomain</strong><br />
    Политика безопасности, установленная Adobe Flash, требует специального разрешения для некоторых операций при загрузке графических файлов с других доменов (если плеер и файл лежат на разных доменах, варианты написания одного домена с www и без также считаются различными). В частности, невозможно произвести сглаживание изображения при масштабировании картинки. Чтобы разрешить все операции — разместите файл crossdomain.xml в корне сайта, с которого загружаются файлы. Таким образом, путь к файлу должен выглядеть как http://site.ru/crossdomain.xml
    Никаких исправлений в файле не требуется, его достаточно просто сохранить на сайт.<br />
    Также данное ограничение касается других опций — чтения ID3-тегов из MP3-файлов, загрузки SWF-роликов. Эти опции также требуют наличия разрешительного файла.<br />
<br />
Дополнительная информация и помощь по работе с модулем на сайте <a href="http://bisar.ru/eforum/#category4" target="_blank"  title="Дополнительная информация и помощь по работе с модулем uppod" >Bisar Entertainments</a><br />
  Copyright (C) 2011 <a href="http://runsite.ru" target="_blank">www.runsite.ru</a> - All rights reserved - Licence Creative Commons 3.0 SA</p>');
define('CX_MODUP_STYLE','Обложка');
define('CX_MODUP_STYLE_DESC','Имя файла обложки для плеера. txt или xml, по умолчанию video_style.txt в <b>modules/mod_uppod/</b>');
define('CX_MODUP_LIST','Плейлист');
define('CX_MODUP_LIST_DESC','Имя файла со списком видео, аудио, фото файлов для воспроизведения. txt или xml, по умолчанию playlist_video.txt в <b>modules/mod_uppod/</b>');
define('CX_MODUP_WIDTH','Ширина');
define('CX_MODUP_WIDTH_DESC','Ширина  плеера, по умолчанию 500px');
define('CX_MODFLP_HEIGHT','Высота');
define('CX_MODFLP_HEIGHT_DESC','Высота  плеера, по умолчанию 400px');
?>