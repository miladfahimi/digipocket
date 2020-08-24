/**
 * hr translation
 * @version 2016-04-18
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
	elFinder.prototype.i18.hr = {
		translator : '',
		language   : 'Croatian',
		direction  : 'ltr',
		dateFormat : 'd.m.Y. H:i', // Mar 13, 2012 05:27 PM
		fancyDateFormat : '$1 H:i', // will produce smth like: Today 12:25 PM
		messages   : {

			/********************************** errors **********************************/
			'error'                : 'Greška',
			'errUnknown'           : 'Nepoznata greška.',
			'errUnknownCmd'        : 'Nepoznata naredba.',
			'errJqui'              : 'Kriva jQuery UI konfiguracija. Selectable, draggable, i droppable komponente moraju biti uključene.',
			'errNode'              : 'elFinder zahtjeva DOM element da bi bio stvoren.',
			'errURL'               : 'Krivo konfiguriran elFinder. Opcija URL nije postavljena.',
			'errAccess'            : 'Zabranjen pristup.',
			'errConnect'           : 'Nije moguće spajanje na server.',
			'errAbort'             : 'Prekinuta veza.',
			'errTimeout'           : 'Veza je istekla.',
			'errNotFound'          : 'Server nije pronađen.',
			'errResponse'          : 'Krivi odgovor servera.',
			'errConf'              : 'Krivo konfiguriran server',
			'errJSON'              : 'Nije instaliran PHP JSON modul.',
			'errNoVolumes'         : 'Disk nije dostupan.',
			'errCmdParams'         : 'Krivi parametri za naredbu "$1".',
			'errDataNotJSON'       : 'Podaci nisu tipa JSON.',
			'errDataEmpty'         : 'Nema podataka.',
			'errCmdReq'            : 'Backend request requires command name.',
			'errOpen'              : 'Ne mogu otvoriti "$1".',
			'errNotFolder'         : 'Objekt nije mapa.',
			'errNotFile'           : 'Objekt nije dokument.',
			'errRead'              : 'Ne mogu pročitati "$1".',
			'errWrite'             : 'Ne mogu pisati u "$1".',
			'errPerm'              : 'Pristup zabranjen',
			'errLocked'            : '"$1" je zaključan i ne može biti preimenovan, premješten ili obrisan.',
			'errExists'            : 'Dokument s imenom "$1" već postoji.',
			'errInvName'           : 'Krivo ime dokumenta',
			'errFolderNotFound'    : 'Mapa nije pronađena',
			'errFileNotFound'      : 'Dokument nije pronađen',
			'errTrgFolderNotFound' : 'Mapa "$1" nije pronađena',
			'errPopup'             : 'Browser prevented opening popup window. To open file enable it in browser options.',
			'errMkdir'             : 'Ne mogu napraviti mapu "$1".',
			'errMkfile'            : 'Ne mogu napraviti dokument "$1".',
			'errRename'            : 'Ne mogu preimenovati "$1".',
			'errCopyFrom'          : 'Kopiranje s diska "$1" nije dozvoljeno.',
			'errCopyTo'            : 'Kopiranje na disk "$1" nije dozvoljeno.',
			'errMkOutLink'         : 'Nije moguće stvoriti vezu izvan korijena volumena.', // from v2.1 added 03.10.2015
			'errUpload'            : 'Greška pri prebacivanju dokumenta na server.',  // old name - errUploadCommon
			'errUploadFile'        : 'Ne mogu prebaciti "$1" na server', // old name - errUpload
			'errUploadNoFiles'     : 'Nema dokumenata za prebacivanje na server',
			'errUploadTotalSize'   : 'Dokumenti prelaze maksimalnu dopuštenu veličinu.', // old name - errMaxSize
			'errUploadFileSize'    : 'Dokument je prevelik.', //  old name - errFileMaxSize
			'errUploadMime'        : 'Ovaj tip dokumenta nije dopušten.',
			'errUploadTransfer'    : '"$1" greška pri prebacivanju',
			'errUploadTemp'        : 'Ne mogu napraviti privremeni dokument za prijenos na server', // from v2.1 added 26.09.2015
			'errNotReplace'        : 'Objekt "$ 1" već postoji na ovom mjestu i ne može ga zamijeniti objekt s drugom vrstom.', // new
			'errReplace'           : 'Ne mogu zamijeniti "$1".',
			'errSave'              : 'Ne mogu spremiti "$1".',
			'errCopy'              : 'Ne mogu kopirati "$1".',
			'errMove'              : 'Ne mogu premjestiti "$1".',
			'errCopyInItself'      : 'Ne mogu kopirati "$1" na isto mjesto.',
			'errRm'                : 'Ne mogu ukloniti "$1".',
			'errRmSrc'             : 'Ne mogu ukloniti izvorni kod.',
			'errExtract'           : 'Nije moguće izdvojiti datoteke iz "$1".',
			'errArchive'           : 'Arhiva nije moguća za stvaranje.',
			'errArcType'           : 'Nepodržana vrsta arhive.',
			'errNoArchive'         : 'Datoteka nije arhivska ili ima nepodržanu vrstu arhive.',
			'errCmdNoSupport'      : 'Backend ne podržava ovu naredbu.',
			'errReplByChild'       : 'Mapu "$ 1" ne može se zamijeniti stavkom koju sadrži.',
			'errArcSymlinks'       : 'Iz sigurnosnih razloga kojima je odbijeno raspakiranje arhiva sadrži simboličke veze ili datoteke s nedozvoljenim imenima.', // edited 24.06.2012
			'errArcMaxSize'        : 'Arhiv datoteka prelazi maksimalno dopuštenu veličinu.',
			'errResize'            : 'Promjena veličine nije moguća "$1".',
			'errResizeDegree'      : 'Nevažeći stupanj rotacije.',  // added 7.3.2013
			'errResizeRotate'      : 'Nije moguće rotirati sliku.',  // added 7.3.2013
			'errResizeSize'        : 'Nevažeća veličina slike.',  // added 7.3.2013
			'errResizeNoChange'    : 'Veličina slike nije promijenjena.',  // added 7.3.2013
			'errUsupportType'      : 'Nepodržana vrsta datoteke.',
			'errNotUTF8Content'    : 'Datoteka "$ 1" nije u UTF-8 i ne može se uređivati.',  // added 9.11.2011
			'errNetMount'          : 'Nije moguće montirati "$1".', // added 17.04.2012
			'errNetMountNoDriver'  : 'Nepodržani protokol.',     // added 17.04.2012
			'errNetMountFailed'    : 'Mount nije uspio.',         // added 17.04.2012
			'errNetMountHostReq'   : 'Domaćin je potreban.', // added 18.04.2012
			'errSessionExpires'    : 'Vaša sesija je istekla zbog neaktivnosti.',
			'errCreatingTempDir'   : 'Privremeni direktorij nije moguće stvoriti: "$1"',
			'errFtpDownloadFile'   : 'Preuzimanje datoteke s FTP-a nije moguće: "$1"',
			'errFtpUploadFile'     : 'Prijenos datoteke na FTP nije moguće: "$1"',
			'errFtpMkdir'          : 'Nije moguće stvoriti udaljeni direktorij na FTP-u: "$1"',
			'errArchiveExec'       : 'Pogreška prilikom arhiviranja datoteka: "$1"',
			'errExtractExec'       : 'Pogreška prilikom izdvajanja datoteka: "$1"',
			'errNetUnMount'        : 'Deaktiviranje nije moguće', // from v2.1 added 30.04.2012
			'errConvUTF8'          : 'Nije pretvarač u UTF-8', // from v2.1 added 08.04.2014
			'errFolderUpload'      : 'Probajte Google Chrome, ako želite učitati mapu.', // from v2.1 added 26.6.2015
			'errSearchTimeout'     : 'Isteklo vrijeme dok tražite "1 $". Rezultat pretraživanja je djelomičan.', // from v2.1 added 12.1.2016
			'errReauthRequire'     : 'Potrebno je ponovno odobrenje.', // from v2.1.10 added 3.24.2016

			/******************************* commands names ********************************/
			'cmdarchive'   : 'Arhiviraj',
			'cmdback'      : 'Nazad',
			'cmdcopy'      : 'Kopiraj',
			'cmdcut'       : 'Izreži',
			'cmddownload'  : 'Preuzmi',
			'cmdduplicate' : 'Dupliciraj',
			'cmdedit'      : 'Uredi dokument',
			'cmdextract'   : 'Raspakiraj arhivu',
			'cmdforward'   : 'Naprijed',
			'cmdgetfile'   : 'Odaberi dokumente',
			'cmdhelp'      : 'O programu',
			'cmdhome'      : 'Početak',
			'cmdinfo'      : 'informacija',
			'cmdmkdir'     : 'Nova mapa',
			'cmdmkdirin'   : 'U novu mapu', // from v2.1.7 added 19.2.2016
			'cmdmkfile'    : 'Nova файл',
			'cmdopen'      : 'Otvori',
			'cmdpaste'     : 'Zalijepi',
			'cmdquicklook' : 'Pregled',
			'cmdreload'    : 'Ponovo učitaj',
			'cmdrename'    : 'Preimenuj',
			'cmdrm'        : 'Obriši',
			'cmdsearch'    : 'Pronađi',
			'cmdup'        : 'Roditeljska mapa',
			'cmdupload'    : 'Prebaci dokumente na server',
			'cmdview'      : 'Pregledaj',
			'cmdresize'    : 'Promjeni veličinu i rotiraj',
			'cmdsort'      : 'Sortiraj',
			'cmdnetmount'  : 'Spoji se na mrežni disk', // added 18.04.2012
			'cmdnetunmount': 'Odspoji disk', // from v2.1 added 30.04.2012
			'cmdplaces'    : 'Do Mjesta', // added 28.12.2014
			'cmdchmod'     : 'Promijenite način rada', // from v2.1 added 20.6.2015
			'cmdopendir'   : 'Otvori mapu', // from v2.1 added 13.1.2016
			'cmdhide':'Sakrij (prednost)',
			'cmdselectall':'Označi sve',
			'cmdselectnone':'Nevyber nič',
			'cmdselectinvert':'Invert Izbor',
			'cmdopennew':'Otvori novu',
			'cmdempty':'Prazno',
			'cmdfullscreen':'Celá obrazovka',

			/*********************************** buttons ***********************************/
			'btnClose'  : 'Zatvori',
			'btnSave'   : 'Spremi',
			'btnRm'     : 'Ukloni',
			'btnApply'  : 'Primjeni',
			'btnCancel' : 'Odustani',
			'btnNo'     : 'Ne',
			'btnYes'    : 'Da',
			'btnMount'  : 'montiranje',  // added 18.04.2012
			'btnApprove': 'Goto $1 & approve', // from v2.1 added 26.04.2012
			'btnUnmount': 'isključivanje', // from v2.1 added 30.04.2012
			'btnConv'   : 'Pretvoriti', // from v2.1 added 08.04.2014
			'btnCwd'    : 'Evo',      // from v2.1 added 22.5.2015
			'btnVolume' : 'Volumen',    // from v2.1 added 22.5.2015
			'btnAll'    : 'Sve',       // from v2.1 added 22.5.2015
			'btnMime'   : 'MIME Tip', // from v2.1 added 22.5.2015
			'btnFileName':'Ime dokumenta',  // from v2.1 added 22.5.2015
			'btnSaveClose': 'Spremi i zatvori', // from v2.1 added 12.6.2015
			'btnBackup' : 'Rezervna kopija', // fromv2.1 added 28.11.2015

			/******************************** notifications ********************************/
			'ntfopen'     : 'Otvori mapu',
			'ntffile'     : 'Otvori dokument',
			'ntfreload'   : 'Ponovo učitaj sadržaj mape',
			'ntfmkdir'    : 'Radim mapu',
			'ntfmkfile'   : 'Radim dokumente',
			'ntfrm'       : 'Brišem dokumente',
			'ntfcopy'     : 'Kopiram dokumente',
			'ntfmove'     : 'Mičem dokumente',
			'ntfprepare'  : 'Priprema za kopiranje dokumenata',
			'ntfrename'   : 'Preimenuj dokumente',
			'ntfupload'   : 'Pohranjujem dokumente na server',
			'ntfdownload' : 'Preuzimam dokumente',
			'ntfsave'     : 'Spremi dokumente',
			'ntfarchive'  : 'Radim arhivu',
			'ntfextract'  : 'Izdvajanje datoteka iz arhive',
			'ntfsearch'   : 'Tražim dokumente',
			'ntfresize'   : 'Promjena veličine slike',
			'ntfsmth'     : 'Doing something',
			'ntfloadimg'  : 'Učitavam sliku',
			'ntfnetmount' : 'Montaža volumena mreže', // added 18.04.2012
			'ntfnetunmount': 'Isključivanje mrežnog volumena', // from v2.1 added 30.04.2012
			'ntfdim'      : 'Stjecanje dimenzija slike', // added 20.05.2013
			'ntfreaddir'  : 'Informacije o čitanju mape', // from v2.1 added 01.07.2013
			'ntfurl'      : 'Dobivanje URL veze', // from v2.1 added 11.03.2014
			'ntfchmod'    : 'Promjena načina rada datoteke', // from v2.1 added 20.6.2015
			'ntfpreupload': 'Provjera naziva datoteke za prijenos', // from v2.1 added 31.11.2015
			'ntfzipdl'    : 'Izrada datoteke za preuzimanje', // from v2.1.7 added 23.1.2016

			/************************************ dates **********************************/
			'dateUnknown' : 'nepoznato',
			'Today'       : 'Danas',
			'Yesterday'   : 'Jučer',
			'msJan'       : 'Sij',
			'msFeb'       : 'Vel',
			'msMar'       : 'Ožu',
			'msApr'       : 'Tra',
			'msMay'       : 'Svi',
			'msJun'       : 'Lip',
			'msJul'       : 'Srp',
			'msAug'       : 'Kol',
			'msSep'       : 'Ruj',
			'msOct'       : 'Lis',
			'msNov'       : 'Stu',
			'msDec'       : 'Pro',
			'January'     : 'Siječanj',
			'February'    : 'Veljača',
			'March'       : 'Ožujak',
			'April'       : 'Travanj',
			'May'         : 'Svibanj',
			'June'        : 'Lipanj',
			'July'        : 'Srpanj',
			'August'      : 'Kolovoz',
			'September'   : 'Rujan',
			'October'     : 'Listopad',
			'November'    : 'Studeni',
			'December'    : 'Prosinac',
			'Sunday'      : 'Nedjelja',
			'Monday'      : 'Ponedjeljak',
			'Tuesday'     : 'Utorak',
			'Wednesday'   : 'Srijeda',
			'Thursday'    : 'Četvrtak',
			'Friday'      : 'Petak',
			'Saturday'    : 'Subota',
			'Sun'         : 'Ned',
			'Mon'         : 'Pon',
			'Tue'         : 'Uto',
			'Wed'         : 'Sri',
			'Thu'         : 'Čet',
			'Fri'         : 'Pet',
			'Sat'         : 'Sub',

			/******************************** sort variants ********************************/
			'sortname'          : 'po imenu',
			'sortkind'          : 'po tipu',
			'sortsize'          : 'po veličini',
			'sortdate'          : 'po datumu',
			'sortFoldersFirst'  : 'Prvo mape',
			'sortAlsoTreeview'  :'Tiež Treeview',

			/********************************** new items **********************************/
			'untitled file.txt' : 'NoviDokument.txt', // added 10.11.2015
			'untitled folder'   : 'NovaMapa',   // added 10.11.2015
			'Archive'           : 'NovaArhiva',  // from v2.1 added 10.11.2015
			'fullscreen':'Celá obrazovka',

			/********************************** messages **********************************/
			'confirmReq'      : 'Potvrda',
			'confirmRm'       : 'Jeste li sigurni?',
			'confirmRepl'     : 'Zamijeni stare dokumente novima?',
			'confirmConvUTF8' : 'Not in UTF-8<br/>Convert to UTF-8?<br/>Contents become UTF-8 by saving after conversion.', // from v2.1 added 08.04.2014
			'confirmNotSave'  : 'Izmijenjeno je. <br/> Izgubivanje posla ako ne spremite promjene.', // from v2.1 added 15.7.2015
			'apllyAll'        : 'Primjeni na sve ',
			'name'            : 'Ime',
			'size'            : 'Veličina',
			'perms'           : 'Dozvole',
			'modify'          : 'Modificiran',
			'kind'            : 'Tip',
			'read'            : 'čitanje',
			'write'           : 'pisanje',
			'noaccess'        : 'bez pristupa',
			'and'             : 'i',
			'unknown'         : 'nepoznato',
			'selectall'       : 'Odaberi sve',
			'selectfiles'     : 'Odaberi dokument(e)',
			'selectffile'     : 'Odaberi prvi dokument',
			'selectlfile'     : 'Odaberi zadnji dokument',
			'viewlist'        : 'Lista',
			'viewicons'       : 'Ikone',
			'places'          : 'Mjesta',
			'calc'            : 'Računaj',
			'path'            : 'Put',
			'aliasfor'        : 'Drugo ime za',
			'locked'          : 'Zaključano',
			'dim'             : 'Dimenzije',
			'files'           : 'Dokumenti',
			'folders'         : 'Mape',
			'items'           : 'Items',
			'yes'             : 'da',
			'no'              : 'ne',
			'link'            : 'poveznica',
			'searcresult'     : 'Rezultati pretrage',
			'selected'        : 'selected items',
			'about'           : 'Info',
			'shortcuts'       : 'Prečaci',
			'help'            : 'Pomoć',
			'webfm'           : 'Upravitelj web datoteka',
			'ver'             : 'Verzija',
			'protocolver'     : 'verzija protokola',
			'homepage'        : 'Projekt doma',
			'docs'            : 'Dokumentacija',
			'github'          : 'Fork us on Github',
			'twitter'         : 'Pratite nas na Twitteru',
			'facebook'        : 'Pridruži nam se na Facebooku',
			'team'            : 'Tim',
			'chiefdev'        : 'glavni developer',
			'developer'       : 'razvijač',
			'contributor'     : 'suradnik',
			'maintainer'      : 'održavatelj',
			'translator'      : 'prevoditelj',
			'icons'           : 'Ikone',
			'dontforget'      : 'and don\'t forget to take your towel',
			'shortcutsof'     : 'Prečaci isključeni',
			'dropFiles'       : 'Ovdje ispusti dokumente',
			'or'              : 'ili',
			'selectForUpload' : 'Odaberi dokumente koje prebacuješ na server',
			'moveFiles'       : 'Premjesti dokumente',
			'copyFiles'       : 'Kopiraj dokumente',
			'rmFromPlaces'    : 'Remove from places',
			'aspectRatio'     : 'Aspect ratio',
			'scale'           : 'Skaliraj',
			'width'           : 'Širina',
			'height'          : 'Visina',
			'resize'          : 'Promjena veličine',
			'crop'            : 'Crop',
			'rotate'          : 'Rotate',
			'rotate-cw'       : 'Rotate 90 degrees CW',
			'rotate-ccw'      : 'Rotate 90 degrees CCW',
			'degree'          : '°',
			'netMountDialogTitle' : 'Montirajte glasnoću mreže', // added 18.04.2012
			'protocol'            : 'Protocol', // added 18.04.2012
			'host'                : 'Host', // added 18.04.2012
			'port'                : 'Port', // added 18.04.2012
			'user'                : 'User', // added 18.04.2012
			'pass'                : 'Password', // added 18.04.2012
			'confirmUnmount'      : 'Jeste li isključeni $1?',  // from v2.1 added 30.04.2012
			'dropFilesBrowser': 'Ispusti ili Zalijepi datoteke iz preglednika', // from v2.1 added 30.05.2012
			'dropPasteFiles'  : 'Ovdje baci ili zalijepi datoteke i URL-ove', // from v2.1 added 07.04.2014
			'encoding'        : 'Encoding', // from v2.1 added 19.12.2014
			'locale'          : 'Locale',   // from v2.1 added 19.12.2014
			'searchTarget'    : 'Target: $1',                // from v2.1 added 22.5.2015
			'searchMime'      : 'Traži unosom MIME tipa', // from v2.1 added 22.5.2015
			'owner'           : 'Vlasnik', // from v2.1 added 20.6.2015
			'group'           : 'Grupa', // from v2.1 added 20.6.2015
			'other'           : 'Other', // from v2.1 added 20.6.2015
			'execute'         : 'Izvrši', // from v2.1 added 20.6.2015
			'perm'            : 'Dozvole', // from v2.1 added 20.6.2015
			'mode'            : 'Mode', // from v2.1 added 20.6.2015
			'emptyFolder'     : 'Mapa je prazna', // from v2.1.6 added 30.12.2015
			'emptyFolderDrop' : 'Mapa je prazna\\A Dovuci dokumente koje želiš dodati', // from v2.1.6 added 30.12.2015
			'emptyFolderLTap' : 'Mapa je prazna\\A Pritisni dugo za dodavanje dokumenata', // from v2.1.6 added 30.12.2015
			'quality'         : 'Kvaliteta', // from v2.1.6 added 5.1.2016
			'autoSync'        : 'Auto sync',  // from v2.1.6 added 10.1.2016
			'moveUp'          : 'Gore',  // from v2.1.6 added 18.1.2016
			'getLink'         : 'Preuzmi URL vezu', // from v2.1.7 added 9.2.2016
			'selectedItems'   : 'Odabrani predmeti ($1)', // from v2.1.7 added 2.19.2016
			'folderId'        : 'Folder ID', // from v2.1.10 added 3.25.2016
			'offlineAccess'   : 'Allow offline access', // from v2.1.10 added 3.25.2016
			'reAuth'          : 'To re-authenticate', // from v2.1.10 added 3.25.2016
			'Code Editor'     :  'Uređivač koda',
			'preference'      :'prednosť',
			'fullscreen'      :'Celá obrazovka',
            'extentiontype'   :'tip proširenja',
			/********************************** mimetypes **********************************/
			'kindUnknown'     : 'nepoznat',
			'kindFolder'      : 'Mapa',
			'kindAlias'       : 'Drugo ime',
			'kindAliasBroken' : 'Broken alias',
			// applications
			'kindApp'         : 'Aplikacija',
			'kindPostscript'  : 'Postscript document',
			'kindMsOffice'    : 'Microsoft Office dokument',
			'kindMsWord'      : 'Microsoft Word dokument',
			'kindMsExcel'     : 'Microsoft Excel dokument',
			'kindMsPP'        : 'Microsoft Powerpoint prezentacija',
			'kindOO'          : 'Open Office dokument',
			'kindAppFlash'    : 'Flash aplikacija',
			'kindPDF'         : 'Portable Document Format (PDF)',
			'kindTorrent'     : 'Bittorrent dokument',
			'kind7z'          : '7z arhiva',
			'kindTAR'         : 'TAR arhiva',
			'kindGZIP'        : 'GZIP arhiva',
			'kindBZIP'        : 'BZIP arhiva',
			'kindXZ'          : 'XZ arhiva',
			'kindZIP'         : 'ZIP arhiva',
			'kindRAR'         : 'RAR arhiva',
			'kindJAR'         : 'Java JAR dokument',
			'kindTTF'         : 'True Type font',
			'kindOTF'         : 'Open Type font',
			'kindRPM'         : 'RPM paket',
			// texts
			'kindText'        : 'Tekst arhiva',
			'kindTextPlain'   : 'Obični tekst',
			'kindPHP'         : 'PHP source',
			'kindCSS'         : 'Cascading style sheet',
			'kindHTML'        : 'HTML document',
			'kindJS'          : 'Javascript source',
			'kindRTF'         : 'Rich Text Format',
			'kindC'           : 'C source',
			'kindCHeader'     : 'C header source',
			'kindCPP'         : 'C++ source',
			'kindCPPHeader'   : 'C++ header source',
			'kindShell'       : 'Unix shell script',
			'kindPython'      : 'Python source',
			'kindJava'        : 'Java source',
			'kindRuby'        : 'Ruby source',
			'kindPerl'        : 'Perl skripta',
			'kindSQL'         : 'SQL source',
			'kindXML'         : 'XML dokument',
			'kindAWK'         : 'AWK source',
			'kindCSV'         : 'vrijednosti razdvojene zarezom',
			'kindDOCBOOK'     : 'Docbook XML dokument',
			'kindMarkdown'    : 'Markdown tekst', // added 20.7.2015
			// images
			'kindImage'       : 'slika',
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
			'kindAudio'       : 'Audio',
			'kindAudioMPEG'   : 'MPEG audio',
			'kindAudioMPEG4'  : 'MPEG-4 audio',
			'kindAudioMIDI'   : 'MIDI audio',
			'kindAudioOGG'    : 'Ogg Vorbis audio',
			'kindAudioWAV'    : 'WAV audio',
			'AudioPlaylist'   : 'MP3 lista',
			'kindVideo'       : 'Video ',
			'kindVideoDV'     : 'DV video',
			'kindVideoMPEG'   : 'MPEG video',
			'kindVideoMPEG4'  : 'MPEG-4 video',
			'kindVideoAVI'    : 'AVI video',
			'kindVideoMOV'    : 'Quick Time video',
			'kindVideoWM'     : 'Windows Media video',
			'kindVideoFlash'  : 'Flash video',
			'kindVideoMKV'    : 'Matroska video',
			'kindVideoOGG'    : 'Ogg video'
		}
	};
}));