/**
 * Slovenian translation
 * @author Damjan Rems <d_rems at yahoo.com>
 * @version 2014-12-19
 */
(function(root, factory) {
	if (typeof define === 'function' && define.amd) {
		define(['elfinder'], factory);
	} else if (typeof exports !== 'undefined') {
		module.exports = factory(require('elfinder'));
	} else {
		factory(root.elFinder);
	}
}(this, function(elFinder) {
	elFinder.prototype.i18.sl = {
		translator : 'Damjan Rems &lt;d_rems at yahoo.com&gt;',
		language   : 'Slovenščina',
		direction  : 'ltr',
		dateFormat : 'd.m.Y H:i',
		fancyDateFormat : '$1 H:i',
		messages   : {
			
			/********************************** errors **********************************/
			'error'                : 'Napaka',
			'errUnknown'           : 'Neznana napaka.',
			'errUnknownCmd'        : 'Neznan ukaz.',
			'errJqui'              : 'Napačna jQuery UI nastavitev. Selectable, draggable in droppable dodatki morajo biti vključeni.',
			'errNode'              : 'elFinder potrebuje "DOM Element".',
			'errURL'               : 'Napačna nastavitev elFinder-ja! Manjka URL nastavitev.',
			'errAccess'            : 'Dostop zavrnjen.',
			'errConnect'           : 'Ne morem se priključiti na "backend".',
			'errAbort'             : 'Povezava prekinjena (aborted).',
			'errTimeout'           : 'Povezava potekla (timeout).',
			'errNotFound'          : 'Nisem našel "backend-a".',
			'errResponse'          : 'Napačni "backend" odgovor.',
			'errConf'              : 'Napačna "backend" nastavitev.',
			'errJSON'              : 'JSON modul ni instaliran.',
			'errNoVolumes'         : 'Readable volumes not available.',
			'errCmdParams'         : 'Napačni parametri za ukaz "$1".',
			'errDataNotJSON'       : 'Podatki niso v JSON obliki.',
			'errDataEmpty'         : 'Ni podatkov oz. so prazni.',
			'errCmdReq'            : '"Backend" zahtevek potrebuje ime ukaza.',
			'errOpen'              : '"$1" ni možno odpreti.',
			'errNotFolder'         : 'Objekt ni mapa.',
			'errNotFile'           : 'Objekt ni datoteka.',
			'errRead'              : '"$1" ni možno brati.',
			'errWrite'             : 'Ne morem pisati v "$1".',
			'errPerm'              : 'Dostop zavrnjen.',
			'errLocked'            : '"$1" je zaklenjen(a) in je ni možno preimenovati, premakniti ali izbrisati.',
			'errExists'            : 'Datoteka z imenom "$1" že obstaja.',
			'errInvName'           : 'Napačno ime datoteke.',
			'errFolderNotFound'    : 'Mape nisem našel.',
			'errFileNotFound'      : 'Datoteke nisem našel.',
			'errTrgFolderNotFound' : 'Ciljna mapa "$1" ne obstaja.',
			'errPopup'             : 'Brskalnik je preprečil prikaz (popup) okna. Za vpogled datoteke omogočite nastavitev v vašem brskalniku.',
			'errMkdir'             : 'Ni možno dodati mape "$1".',
			'errMkfile'            : 'Ni možno dodati datoteke "$1".',
			'errRename'            : 'Ni možno preimenovati "$1".',
			'errCopyFrom'          : 'Kopiranje datotek iz "$1" ni dovoljeno.',
			'errCopyTo'            : 'Kopiranje datotek na "$1" ni dovoljeno.',
			'errUpload'            : 'Napaka pri prenosu.',
			'errUploadFile'        : '"$1" ni možno naložiti (upload).',
			'errUploadNoFiles'     : 'Ni datotek za nalaganje (upload).',
			'errUploadTotalSize'   : 'Podatki presegajo največjo dovoljeno velikost.',
			'errUploadFileSize'    : 'Datoteka presega največjo dovoljeno velikost.',
			'errUploadMime'        : 'Datoteke s to končnico niso dovoljene.',
			'errUploadTransfer'    : '"$1" napaka pri prenosu.',
			'errNotReplace'        : 'Object "$1" already exists at this location and can not be replaced by object with another type.',
			'errReplace'           : 'Unable to replace "$1".',
			'errSave'              : '"$1" ni možno shraniti.',
			'errCopy'              : '"$1" ni možno kopirati.',
			'errMove'              : '"$1" ni možno premakniti.',
			'errCopyInItself'      : '"$1" ni možno kopirati samo vase.',
			'errRm'                : '"$1" ni možno izbrisati.',
			'errRmSrc'             : 'Unable remove source file(s).',
			'errExtract'           : 'Datotek iz "$1" ni možno odpakirati.',
			'errArchive'           : 'Napaka pri delanju arhiva.',
			'errArcType'           : 'Nepodprta vrsta arhiva.',
			'errNoArchive'         : 'Datoteka ni arhiv ali vrsta arhiva ni podprta.',
			'errCmdNoSupport'      : '"Backend" ne podpira tega ukaza.',
			'errReplByChild'       : 'Mape “$1” ni možno zamenjati z vsebino mape.',
			'errArcSymlinks'       : 'Zaradi varnostnih razlogov arhiva ki vsebuje "symlinks" ni možno odpakirati.',
			'errArcMaxSize'        : 'Datoteke v arhivu presegajo največjo dovoljeno velikost.',
			'errResize'            : '"$1" ni možno razširiti.',
			'errResizeDegree'      : 'Invalid rotate degree.',
			'errResizeRotate'      : 'Unable to rotate image.',
			'errResizeSize'        : 'Invalid image size.',
			'errResizeNoChange'    : 'Image size not changed.',
			'errUsupportType'      : 'Nepodprta vrsta datoteke.',
			'errNotUTF8Content'    : 'File "$1" is not in UTF-8 and cannot be edited.',  // added 9.11.2011
			'errNetMount'          : 'Unable to mount "$1".', // added 17.04.2012
			'errNetMountNoDriver'  : 'Unsupported protocol.',     // added 17.04.2012
			'errNetMountFailed'    : 'Mount failed.',         // added 17.04.2012
			'errNetMountHostReq'   : 'Host required.', // added 18.04.2012
			'errSessionExpires'    : 'Your session has expired due to inactivity.',
			'errCreatingTempDir'   : 'Unable to create temporary directory: "$1"',
			'errFtpDownloadFile'   : 'Unable to download file from FTP: "$1"',
			'errFtpUploadFile'     : 'Unable to upload file to FTP: "$1"',
			'errFtpMkdir'          : 'Unable to create remote directory on FTP: "$1"',
			'errArchiveExec'       : 'Error while archiving files: "$1"',
			'errExtractExec'       : 'Error while extracting files: "$1"',
			
			/******************************* commands names ********************************/
			'cmdarchive'   : 'Naredi arhiv',
			'cmdback'      : 'Nazaj',
			'cmdcopy'      : 'Kopiraj',
			'cmdcut'       : 'Izreži',
			'cmddownload'  : 'Poberi (download)',
			'cmdduplicate' : 'Podvoji',
			'cmdedit'      : 'Uredi datoteko',
			'cmdextract'   : 'Odpakiraj datoteke iz arhiva',
			'cmdforward'   : 'Naprej',
			'cmdgetfile'   : 'Izberi datoteke',
			'cmdhelp'      : 'Več o',
			'cmdhome'      : 'Domov',
			'cmdinfo'      : 'Lastnosti',
			'cmdmkdir'     : 'Nova mapa',
			'cmdmkfile'    : 'Nova datoteka',
			'cmdopen'      : 'Odpri',
			'cmdpaste'     : 'Prilepi',
			'cmdquicklook' : 'Hitri ogled',
			'cmdreload'    : 'Osveži',
			'cmdrename'    : 'Preimenuj',
			'cmdrm'        : 'Izbriši',
			'cmdsearch'    : 'Poišči datoteke',
			'cmdup'        : 'Mapa nazaj',
			'cmdupload'    : 'Naloži (upload)',
			'cmdview'      : 'Ogled',
			'cmdresize'    : 'Povečaj (pomanjšaj) sliko',
			'cmdsort'      : 'Razvrsti',
			'cmdnetmount'  : 'Priklopite glasnost omrežja',
			'cmdnetunmount': 'Odstranite glasnost', // from v2.1 added 30.04.2012
			'cmdplaces'    : 'V krajih', // added 28.12.2014
			'cmdchmod'     : 'Spremeni način', // from v2.1 added 20.6.2015
			'cmdfullscreen': 'celozaslonski način', // from v2.1.15 added 03.08.2016
			'cmdmove'      : 'Gibanje', // from v2.1.15 added 21.08.2016
			'cmdempty'     : 'Cilj mape', // from v2.1.25 added 22.06.2017
			'cmdundo'      : 'Prekliči', // from v2.1.27 added 31.07.2017
			'cmdredo'      : 'Na novo', // from v2.1.27 added 31.07.2017
			'cmdpreference': 'Osebne nastavitve', // from v2.1.27 added 03.08.2017
			'cmdselectall' : 'izberi vse', // from v2.1.28 added 15.08.2017
			'cmdselectnone': 'prekličevanje izbire', // from v2.1.28 added 15.08.2017
			'cmdselectinvert': 'Povratna izbira', // from v2.1.28 added 15.08.2017
			'cmdopennew'   : 'Odpre se v novem oknu', // from v2.1.38 added 3.4.2018
			'cmdhide'      : 'Skrij (osebna nastavitev)', // from v2.1.41 added 24.7.2018
			'cmdmkdirin':'V novi mapi',
			
			/*********************************** buttons ***********************************/ 
			'btnClose'  : 'Zapri',
			'btnSave'   : 'Shrani',
			'btnRm'     : 'Izbriši',
			'btnApply'  : 'Uporabi',
			'btnCancel' : 'Prekliči',
			'btnNo'     : 'Ne',
			'btnYes'    : 'Da',
			'btnMount'  : 'Mount',
			'btnApprove': 'Mergi la $1 și aprobă', // from v2.1 added 26.04.2012
			'btnUnmount': 'Odstranite glasnost', // from v2.1 added 30.04.2012
			'btnConv'   : 'pretvarja', // from v2.1 added 08.04.2014
			'btnCwd'    : 'Tukaj',      // from v2.1 added 22.5.2015
			'btnVolume' : 'Zvezek',    // from v2.1 added 22.5.2015
			'btnAll'    : 'Vse',       // from v2.1 added 22.5.2015
			'btnMime'   : 'MIME vrste', // from v2.1 added 22.5.2015
			'btnFileName':'Ime datoteke',  // from v2.1 added 22.5.2015
			'btnSaveClose': 'Shranite in zaprite', // from v2.1 added 12.6.2015
			
			/******************************** notifications ********************************/
			'ntfopen'     : 'Odpri mapo',
			'ntffile'     : 'Odpri datoteko',
			'ntfreload'   : 'Osveži vsebino mape',
			'ntfmkdir'    : 'Ustvarjam mapo',
			'ntfmkfile'   : 'Ustvarjam datoteke',
			'ntfrm'       : 'Brišem datoteke',
			'ntfcopy'     : 'Kopiram datoteke',
			'ntfmove'     : 'Premikam datoteke',
			'ntfprepare'  : 'Pripravljam se na kopiranje datotek',
			'ntfrename'   : 'Preimenujem datoteke',
			'ntfupload'   : 'Nalagam (upload) datoteke',
			'ntfdownload' : 'Pobiram (download) datoteke',
			'ntfsave'     : 'Shranjujem datoteke',
			'ntfarchive'  : 'Ustvarjam arhiv',
			'ntfextract'  : 'Razpakiram datoteke iz arhiva',
			'ntfsearch'   : 'Iščem datoteke',
			'ntfresize'   : 'Resizing images',
			'ntfsmth'     : 'Počakaj delam >_<',
			'ntfloadimg'  : 'Nalagam sliko',
			'ntfnetmount' : 'Montaža glasnosti omrežja', // added 18.04.2012
			'ntfdim'      : 'Pridobitev dimenzije slike', // added 20.05.2013
			
			/************************************ dates **********************************/
			'dateUnknown' : 'neznan',
			'Today'       : 'Danes',
			'Yesterday'   : 'Včeraj',
			'msJan'       : 'Jan',
			'msFeb'       : 'Feb',
			'msMar'       : 'Mar',
			'msApr'       : 'Apr',
			'msMay'       : 'Maj',
			'msJun'       : 'Jun',
			'msJul'       : 'Jul',
			'msAug'       : 'Avg',
			'msSep'       : 'Sep',
			'msOct'       : 'Okt',
			'msNov'       : 'Nov',
			'msDec'       : 'Dec',
			'January'     : 'Januar',
			'February'    : 'Februar',
			'March'       : 'Marec',
			'April'       : 'April',
			'May'         : 'Maj',
			'June'        : 'Junij',
			'July'        : 'Julij',
			'August'      : 'Avgust',
			'September'   : 'September',
			'October'     : 'Oktober',
			'November'    : 'November',
			'December'    : 'December',
			'Sunday'      : 'Nedelja', 
			'Monday'      : 'Ponedeljek', 
			'Tuesday'     : 'Torek', 
			'Wednesday'   : 'Sreda', 
			'Thursday'    : 'Četrtek', 
			'Friday'      : 'Petek', 
			'Saturday'    : 'Sobota',
			'Sun'         : 'Ned', 
			'Mon'         : 'Pon', 
			'Tue'         : 'Tor', 
			'Wed'         : 'Sre', 
			'Thu'         : 'Čet', 
			'Fri'         : 'Pet', 
			'Sat'         : 'Sob',
			
			/******************************** sort variants ********************************/
			'sortname'          : 'po imenu', 
			'sortkind'          : 'po vrsti', 
			'sortsize'          : 'po velikosti',
			'sortdate'          : 'po datumu',
			'sortFoldersFirst'  : 'Najprej mape',
			'sortAlsoTreeview':'Tudi Treeview',
			
			/********************************** messages **********************************/
			'confirmReq'      : 'Zahtevana je potrditev',
			'confirmRm'       : 'Ste prepričani, da želite izbrisati datoteko?<br/>POZOR! Tega ukaza ni možno preklicati!',
			'confirmRepl'     : 'Zamenjam staro datoteko z novo?',
			'apllyAll'        : 'Uporabi pri vseh',
			'name'            : 'Ime',
			'size'            : 'Velikost',
			'perms'           : 'Dovoljenja',
			'modify'          : 'Spremenjeno',
			'kind'            : 'Vrsta',
			'read'            : 'beri',
			'write'           : 'piši',
			'noaccess'        : 'ni dostopa',
			'and'             : 'in',
			'unknown'         : 'neznan',
			'selectall'       : 'Izberi vse datoteke',
			'selectfiles'     : 'Izberi datotek(o)e',
			'selectffile'     : 'Izberi prvo datoteko',
			'selectlfile'     : 'Izberi zadnjo datoteko',
			'viewlist'        : 'Seznam',
			'viewicons'       : 'Ikone',
			'places'          : 'Mesta (places)',
			'calc'            : 'Izračun', 
			'path'            : 'Pot do',
			'aliasfor'        : 'Sopomenka (alias) za',
			'locked'          : 'Zaklenjeno',
			'dim'             : 'Dimenzije',
			'files'           : 'Datoteke',
			'folders'         : 'Mape',
			'items'           : 'Predmeti',
			'yes'             : 'da',
			'no'              : 'ne',
			'link'            : 'Povezava',
			'searcresult'     : 'Rezultati iskanja',  
			'selected'        : 'izbrani predmeti',
			'about'           : 'Več o',
			'shortcuts'       : 'Bližnjice',
			'help'            : 'Pomoč',
			'webfm'           : 'Spletni upravitelj datotek',
			'ver'             : 'Verzija',
			'protocolver'     : 'verzija protokola',
			'homepage'        : 'Domača stran',
			'docs'            : 'Dokumentacija',
			'github'          : 'Fork us on Github',
			'twitter'         : 'Sledi na twitterju',
			'facebook'        : 'Pridruži se nam na facebook-u',
			'team'            : 'Tim',
			'chiefdev'        : 'Glavni razvijalec',
			'developer'       : 'razvijalec',
			'contributor'     : 'contributor',
			'maintainer'      : 'vzdrževalec',
			'translator'      : 'prevajalec',
			'icons'           : 'Ikone',
			'dontforget'      : 'In ne pozabi na brisačo',
			'shortcutsof'     : 'Bližnjica onemogočena',
			'dropFiles'       : 'Datoteke spusti tukaj',
			'or'              : 'ali',
			'selectForUpload' : 'Izberi datoteke za nalaganje',
			'moveFiles'       : 'Premakni datoteke',
			'copyFiles'       : 'Kopiraj datoteke',
			'rmFromPlaces'    : 'Izbriši iz mesta (places)',
			'aspectRatio'     : 'Razmerje slike',
			'scale'           : 'Razširi',
			'width'           : 'Širina',
			'height'          : 'Višina',
			'resize'          : 'Povečaj',
			'crop'            : 'Obreži',
			'rotate'          : 'Zavrti',
			'rotate-cw'       : 'Zavrti 90 st. v smeri ure',
			'rotate-ccw'      : 'Zavrti 90 st. v obratni smeri ure',
			'degree'          : 'Stopnja',
			'netMountDialogTitle' : 'Priklopite glasnost omrežja', // added 18.04.2012
			'protocol'            : 'Protocol', // added 18.04.2012
			'host'                : 'Host', // added 18.04.2012
			'port'                : 'Port', // added 18.04.2012
			'user'                : 'User', // added 18.04.2012
			'pass'                : 'Password', // added 18.04.2012
			'selectFolder'    : 'Izberite mapo',
			'emptyIncSearch'  : 'V trenutnem pogledu ni ustreznih člankov. \\ Tipka [Enter] za razširitev iskalnega cilja.', // from v2.1.16 added 5.10.2016
			'emptyLetSearch'  : 'V trenutnem pogledu s podanim znakom ni predmetov.', // from v2.1.23 added 24.3.2017
			'emptyFolder'     : 'Cilj mape', // from v2.1.6 added 30.12.2015
			'emptyFolderDrop' : 'Tukaj dodajte prazno mapo \\ Predmet', // from v2.1.6 added 30.12.2015
			'emptyFolderLTap' : 'Dolgo kliknite tukaj, da dodate prazno mapo \\ Predmet', // from v2.1.6 added 30.12.2015
			'dropFilesBrowser': 'Povlecite in spustite ali prilepite iz brskalnika', // from v2.1 added 30.05.2012
			'dropPasteFiles'  : 'Tu povlecite in spustite ali prilepite datoteke', // from v2.1 added 07.04.2014
			'extentiontype' : 'vrsta podaljška',
			/********************************** mimetypes **********************************/
			'kindUnknown'     : 'Neznan',
			'kindFolder'      : 'Mapa',
			'kindAlias'       : 'Sopomenka (alias)',
			'kindAliasBroken' : 'Nedelujoča sopomenka (alias)',
			// applications
			'kindApp'         : 'Program',
			'kindPostscript'  : 'Postscript dokument',
			'kindMsOffice'    : 'Microsoft Office dokument',
			'kindMsWord'      : 'Microsoft Word dokument',
			'kindMsExcel'     : 'Microsoft Excel dokument',
			'kindMsPP'        : 'Microsoft Powerpoint predstavitev',
			'kindOO'          : 'Open Office dokument',
			'kindAppFlash'    : 'Flash program',
			'kindPDF'         : 'Portable Document Format (PDF)',
			'kindTorrent'     : 'Bittorrent datoteka',
			'kind7z'          : '7z arhiv',
			'kindTAR'         : 'TAR arhiv',
			'kindGZIP'        : 'GZIP arhiv',
			'kindBZIP'        : 'BZIP arhiv',
			'kindXZ'          : 'XZ arhiv',
			'kindZIP'         : 'ZIP arhiv',
			'kindRAR'         : 'RAR arhiv',
			'kindJAR'         : 'Java JAR datoteka',
			'kindTTF'         : 'True Type font',
			'kindOTF'         : 'Open Type font',
			'kindRPM'         : 'RPM paket',
			// texts
			'kindText'        : 'Tekst dokument',
			'kindTextPlain'   : 'Samo tekst',
			'kindPHP'         : 'PHP koda',
			'kindCSS'         : 'Cascading style sheet (CSS)',
			'kindHTML'        : 'HTML dokument',
			'kindJS'          : 'Javascript koda',
			'kindRTF'         : 'Rich Text Format (RTF)',
			'kindC'           : 'C koda',
			'kindCHeader'     : 'C header koda',
			'kindCPP'         : 'C++ koda',
			'kindCPPHeader'   : 'C++ header koda',
			'kindShell'       : 'Unix shell skripta',
			'kindPython'      : 'Python kdoa',
			'kindJava'        : 'Java koda',
			'kindRuby'        : 'Ruby koda',
			'kindPerl'        : 'Perl skripta',
			'kindSQL'         : 'SQL koda',
			'kindXML'         : 'XML dokument',
			'kindAWK'         : 'AWK koda',
			'kindCSV'         : 'Besedilo ločeno z vejico (CSV)',
			'kindDOCBOOK'     : 'Docbook XML dokument',
			// images
			'kindImage'       : 'Slika',
			'kindBMP'         : 'BMP slika',
			'kindJPEG'        : 'JPEG slika',
			'kindGIF'         : 'GIF slika',
			'kindPNG'         : 'PNG slika',
			'kindTIFF'        : 'TIFF slika',
			'kindTGA'         : 'TGA slika',
			'kindPSD'         : 'Adobe Photoshop slika',
			'kindXBITMAP'     : 'X bitmap slika',
			'kindPXM'         : 'Pixelmator slika',
			// media
			'kindAudio'       : 'Avdio medija',
			'kindAudioMPEG'   : 'MPEG zvok',
			'kindAudioMPEG4'  : 'MPEG-4 zvok',
			'kindAudioMIDI'   : 'MIDI zvok',
			'kindAudioOGG'    : 'Ogg Vorbis zvok',
			'kindAudioWAV'    : 'WAV zvok',
			'AudioPlaylist'   : 'MP3 seznam',
			'kindVideo'       : 'Video medija',
			'kindVideoDV'     : 'DV film',
			'kindVideoMPEG'   : 'MPEG film',
			'kindVideoMPEG4'  : 'MPEG-4 film',
			'kindVideoAVI'    : 'AVI film',
			'kindVideoMOV'    : 'Quick Time film',
			'kindVideoWM'     : 'Windows Media film',
			'kindVideoFlash'  : 'Flash film',
			'kindVideoMKV'    : 'Matroska film',
			'kindVideoOGG'    : 'Ogg film'
		}
	};
}));