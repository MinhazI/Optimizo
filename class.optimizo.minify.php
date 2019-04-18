<?php

$pluginDirectory = plugin_dir_path( __FILE__ );

# Including the minfier library by: MatthiasMullie,
# link: https://github.com/matthiasmullie/minify
$pathToMatthias = $pluginDirectory . 'inc/matthiasmullie';

require_once( 'class.optimizo.php' );

require_once $pathToMatthias . '/minify/src/Minify.php';
require_once $pathToMatthias . '/minify/src/CSS.php';
require_once $pathToMatthias . '/minify/src/JS.php';
require_once $pathToMatthias . '/minify/src/Exception.php';
require_once $pathToMatthias . '/minify/src/Exceptions/BasicException.php';
require_once $pathToMatthias . '/minify/src/Exceptions/FileImportException.php';
require_once $pathToMatthias . '/minify/src/Exceptions/IOException.php';
require_once $pathToMatthias . '/path-converter/src/ConverterInterface.php';
require_once $pathToMatthias . '/path-converter/src/Converter.php';

#The below list is thanks to: https://www.xcartmods.co.uk/google-fonts-list.php
$googleFontsWhiteList = array(
	'ABeeZee',
	'Abel',
	'Abhaya Libre',
	'Abril Fatface',
	'Aclonica',
	'Acme',
	'Actor',
	'Adamina',
	'Advent Pro',
	'Aguafina Script',
	'Akronim',
	'Aladin',
	'Aldrich',
	'Alef',
	'Alegreya',
	'Alegreya SC',
	'Alegreya Sans',
	'Alegreya Sans SC',
	'Alex Brush',
	'Alfa Slab One',
	'Alice',
	'Alike',
	'Alike Angular',
	'Allan',
	'Allerta',
	'Allerta Stencil',
	'Allura',
	'Almendra',
	'Almendra Display',
	'Almendra SC',
	'Amarante',
	'Amaranth',
	'Amatic SC',
	'Amatica SC',
	'Amethysta',
	'Amiko',
	'Amiri',
	'Amita',
	'Anaheim',
	'Andada',
	'Andika',
	'Angkor',
	'Annie Use Your Telescope',
	'Anonymous Pro',
	'Antic',
	'Antic Didone',
	'Antic Slab',
	'Anton',
	'Arapey',
	'Arbutus',
	'Arbutus Slab',
	'Architects Daughter',
	'Archivo',
	'Archivo Black',
	'Archivo Narrow',
	'Aref Ruqaa',
	'Arima Madurai',
	'Arimo',
	'Arizonia',
	'Armata',
	'Arsenal',
	'Artifika',
	'Arvo',
	'Arya',
	'Asap',
	'Asap Condensed',
	'Asar',
	'Asset',
	'Assistant',
	'Astloch',
	'Asul',
	'Athiti',
	'Atma',
	'Atomic Age',
	'Aubrey',
	'Audiowide',
	'Autour One',
	'Average',
	'Average Sans',
	'Averia Gruesa Libre',
	'Averia Libre',
	'Averia Sans Libre',
	'Averia Serif Libre',
	'Bad Script',
	'Bahiana',
	'Baloo',
	'Baloo Bhai',
	'Baloo Bhaijaan',
	'Baloo Bhaina',
	'Baloo Chettan',
	'Baloo Da',
	'Baloo Paaji',
	'Baloo Tamma',
	'Baloo Tammudu',
	'Baloo Thambi',
	'Balthazar',
	'Bangers',
	'Barrio',
	'Basic',
	'Battambang',
	'Baumans',
	'Bayon',
	'Belgrano',
	'Bellefair',
	'Belleza',
	'BenchNine',
	'Bentham',
	'Berkshire Swash',
	'Bevan',
	'Bigelow Rules',
	'Bigshot One',
	'Bilbo',
	'Bilbo Swash Caps',
	'BioRhyme',
	'BioRhyme Expanded',
	'Biryani',
	'Bitter',
	'Black Ops One',
	'Bokor',
	'Bonbon',
	'Boogaloo',
	'Bowlby One',
	'Bowlby One SC',
	'Brawler',
	'Bree Serif',
	'Bubblegum Sans',
	'Bubbler One',
	'Buda',
	'Buenard',
	'Bungee',
	'Bungee Hairline',
	'Bungee Inline',
	'Bungee Outline',
	'Bungee Shade',
	'Butcherman',
	'Butterfly Kids',
	'Cabin',
	'Cabin Condensed',
	'Cabin Sketch',
	'Caesar Dressing',
	'Cagliostro',
	'Cairo',
	'Calligraffitti',
	'Cambay',
	'Cambo',
	'Candal',
	'Cantarell',
	'Cantata One',
	'Cantora One',
	'Capriola',
	'Cardo',
	'Carme',
	'Carrois Gothic',
	'Carrois Gothic SC',
	'Carter One',
	'Catamaran',
	'Caudex',
	'Caveat',
	'Caveat Brush',
	'Cedarville Cursive',
	'Ceviche One',
	'Changa',
	'Changa One',
	'Chango',
	'Chathura',
	'Chau Philomene One',
	'Chela One',
	'Chelsea Market',
	'Chenla',
	'Cherry Cream Soda',
	'Cherry Swash',
	'Chewy',
	'Chicle',
	'Chivo',
	'Chonburi',
	'Cinzel',
	'Cinzel Decorative',
	'Clicker Script',
	'Coda',
	'Coda Caption',
	'Codystar',
	'Coiny',
	'Combo',
	'Comfortaa',
	'Coming Soon',
	'Concert One',
	'Condiment',
	'Content',
	'Contrail One',
	'Convergence',
	'Cookie',
	'Copse',
	'Corben',
	'Cormorant',
	'Cormorant Garamond',
	'Cormorant Infant',
	'Cormorant SC',
	'Cormorant Unicase',
	'Cormorant Upright',
	'Courgette',
	'Cousine',
	'Coustard',
	'Covered By Your Grace',
	'Crafty Girls',
	'Creepster',
	'Crete Round',
	'Crimson Text',
	'Croissant One',
	'Crushed',
	'Cuprum',
	'Cutive',
	'Cutive Mono',
	'Damion',
	'Dancing Script',
	'Dangrek',
	'David Libre',
	'Dawning of a New Day',
	'Days One',
	'Dekko',
	'Delius',
	'Delius Swash Caps',
	'Delius Unicase',
	'Della Respira',
	'Denk One',
	'Devonshire',
	'Dhurjati',
	'Didact Gothic',
	'Diplomata',
	'Diplomata SC',
	'Domine',
	'Donegal One',
	'Doppio One',
	'Dorsa',
	'Dosis',
	'Dr Sugiyama',
	'Droid Sans',
	'Droid Sans Mono',
	'Droid Serif',
	'Duru Sans',
	'Dynalight',
	'EB Garamond',
	'Eagle Lake',
	'Eater',
	'Economica',
	'Eczar',
	'El Messiri',
	'Electrolize',
	'Elsie',
	'Elsie Swash Caps',
	'Emblema One',
	'Emilys Candy',
	'Encode Sans',
	'Encode Sans Condensed',
	'Encode Sans Expanded',
	'Encode Sans Semi Condensed',
	'Encode Sans Semi Expanded',
	'Engagement',
	'Englebert',
	'Enriqueta',
	'Erica One',
	'Esteban',
	'Euphoria Script',
	'Ewert',
	'Exo',
	'Exo 2',
	'Expletus Sans',
	'Fanwood Text',
	'Farsan',
	'Fascinate',
	'Fascinate Inline',
	'Faster One',
	'Fasthand',
	'Fauna One',
	'Faustina',
	'Federant',
	'Federo',
	'Felipa',
	'Fenix',
	'Finger Paint',
	'Fira Mono',
	'Fira Sans',
	'Fira Sans Condensed',
	'Fira Sans Extra Condensed',
	'Fjalla One',
	'Fjord One',
	'Flamenco',
	'Flavors',
	'Fondamento',
	'Fontdiner Swanky',
	'Forum',
	'Francois One',
	'Frank Ruhl Libre',
	'Freckle Face',
	'Fredericka the Great',
	'Fredoka One',
	'Freehand',
	'Fresca',
	'Frijole',
	'Fruktur',
	'Fugaz One',
	'GFS Didot',
	'GFS Neohellenic',
	'Gabriela',
	'Gafata',
	'Galada',
	'Galdeano',
	'Galindo',
	'Gentium Basic',
	'Gentium Book Basic',
	'Geo',
	'Geostar',
	'Geostar Fill',
	'Germania One',
	'Gidugu',
	'Gilda Display',
	'Give You Glory',
	'Glass Antiqua',
	'Glegoo',
	'Gloria Hallelujah',
	'Goblin One',
	'Gochi Hand',
	'Gorditas',
	'Goudy Bookletter 1911',
	'Graduate',
	'Grand Hotel',
	'Gravitas One',
	'Great Vibes',
	'Griffy',
	'Gruppo',
	'Gudea',
	'Gurajada',
	'Habibi',
	'Halant',
	'Hammersmith One',
	'Hanalei',
	'Hanalei Fill',
	'Handlee',
	'Hanuman',
	'Happy Monkey',
	'Harmattan',
	'Headland One',
	'Heebo',
	'Henny Penny',
	'Herr Von Muellerhoff',
	'Hind',
	'Hind Guntur',
	'Hind Madurai',
	'Hind Siliguri',
	'Hind Vadodara',
	'Holtwood One SC',
	'Homemade Apple',
	'Homenaje',
	'IM Fell DW Pica',
	'IM Fell DW Pica SC',
	'IM Fell Double Pica',
	'IM Fell Double Pica SC',
	'IM Fell English',
	'IM Fell English SC',
	'IM Fell French Canon',
	'IM Fell French Canon SC',
	'IM Fell Great Primer',
	'IM Fell Great Primer SC',
	'Iceberg',
	'Iceland',
	'Imprima',
	'Inconsolata',
	'Inder',
	'Indie Flower',
	'Inika',
	'Inknut Antiqua',
	'Irish Grover',
	'Istok Web',
	'Italiana',
	'Italianno',
	'Itim',
	'Jacques Francois',
	'Jacques Francois Shadow',
	'Jaldi',
	'Jim Nightshade',
	'Jockey One',
	'Jolly Lodger',
	'Jomhuria',
	'Josefin Sans',
	'Josefin Slab',
	'Joti One',
	'Judson',
	'Julee',
	'Julius Sans One',
	'Junge',
	'Jura',
	'Just Another Hand',
	'Just Me Again Down Here',
	'Kadwa',
	'Kalam',
	'Kameron',
	'Kanit',
	'Kantumruy',
	'Karla',
	'Karma',
	'Katibeh',
	'Kaushan Script',
	'Kavivanar',
	'Kavoon',
	'Kdam Thmor',
	'Keania One',
	'Kelly Slab',
	'Kenia',
	'Khand',
	'Khmer',
	'Khula',
	'Kite One',
	'Knewave',
	'Kotta One',
	'Koulen',
	'Kranky',
	'Kreon',
	'Kristi',
	'Krona One',
	'Kumar One',
	'Kumar One Outline',
	'Kurale',
	'La Belle Aurore',
	'Laila',
	'Lakki Reddy',
	'Lalezar',
	'Lancelot',
	'Lateef',
	'Lato',
	'League Script',
	'Leckerli One',
	'Ledger',
	'Lekton',
	'Lemon',
	'Lemonada',
	'Libre Barcode 128',
	'Libre Barcode 128 Text',
	'Libre Barcode 39',
	'Libre Barcode 39 Extended',
	'Libre Barcode 39 Extended Text',
	'Libre Barcode 39 Text',
	'Libre Baskerville',
	'Libre Franklin',
	'Life Savers',
	'Lilita One',
	'Lily Script One',
	'Limelight',
	'Linden Hill',
	'Lobster',
	'Lobster Two',
	'Londrina Outline',
	'Londrina Shadow',
	'Londrina Sketch',
	'Londrina Solid',
	'Lora',
	'Love Ya Like A Sister',
	'Loved by the King',
	'Lovers Quarrel',
	'Luckiest Guy',
	'Lusitana',
	'Lustria',
	'Macondo',
	'Macondo Swash Caps',
	'Mada',
	'Magra',
	'Maiden Orange',
	'Maitree',
	'Mako',
	'Mallanna',
	'Mandali',
	'Manuale',
	'Marcellus',
	'Marcellus SC',
	'Marck Script',
	'Margarine',
	'Marko One',
	'Marmelad',
	'Martel',
	'Martel Sans',
	'Marvel',
	'Mate',
	'Mate SC',
	'Maven Pro',
	'McLaren',
	'Meddon',
	'MedievalSharp',
	'Medula One',
	'Meera Inimai',
	'Megrim',
	'Meie Script',
	'Merienda',
	'Merienda One',
	'Merriweather',
	'Merriweather Sans',
	'Metal',
	'Metal Mania',
	'Metamorphous',
	'Metrophobic',
	'Michroma',
	'Milonga',
	'Miltonian',
	'Miltonian Tattoo',
	'Miniver',
	'Miriam Libre',
	'Mirza',
	'Miss Fajardose',
	'Mitr',
	'Modak',
	'Modern Antiqua',
	'Mogra',
	'Molengo',
	'Molle',
	'Monda',
	'Monofett',
	'Monoton',
	'Monsieur La Doulaise',
	'Montaga',
	'Montez',
	'Montserrat',
	'Montserrat Alternates',
	'Montserrat Subrayada',
	'Moul',
	'Moulpali',
	'Mountains of Christmas',
	'Mouse Memoirs',
	'Mr Bedfort',
	'Mr Dafoe',
	'Mr De Haviland',
	'Mrs Saint Delafield',
	'Mrs Sheppards',
	'Mukta',
	'Mukta Mahee',
	'Mukta Malar',
	'Mukta Vaani',
	'Muli',
	'Mystery Quest',
	'NTR',
	'Neucha',
	'Neuton',
	'New Rocker',
	'News Cycle',
	'Niconne',
	'Nixie One',
	'Nobile',
	'Nokora',
	'Norican',
	'Nosifer',
	'Nothing You Could Do',
	'Noticia Text',
	'Noto Sans',
	'Noto Serif',
	'Nova Cut',
	'Nova Flat',
	'Nova Mono',
	'Nova Oval',
	'Nova Round',
	'Nova Script',
	'Nova Slim',
	'Nova Square',
	'Numans',
	'Nunito',
	'Nunito Sans',
	'Odor Mean Chey',
	'Offside',
	'Old Standard TT',
	'Oldenburg',
	'Oleo Script',
	'Oleo Script Swash Caps',
	'Open Sans',
	'Open Sans Condensed',
	'Oranienbaum',
	'Orbitron',
	'Oregano',
	'Orienta',
	'Original Surfer',
	'Oswald',
	'Over the Rainbow',
	'Overlock',
	'Overlock SC',
	'Overpass',
	'Overpass Mono',
	'Ovo',
	'Oxygen',
	'Oxygen Mono',
	'PT Mono',
	'PT Sans',
	'PT Sans Caption',
	'PT Sans Narrow',
	'PT Serif',
	'PT Serif Caption',
	'Pacifico',
	'Padauk',
	'Palanquin',
	'Palanquin Dark',
	'Pangolin',
	'Paprika',
	'Parisienne',
	'Passero One',
	'Passion One',
	'Pathway Gothic One',
	'Patrick Hand',
	'Patrick Hand SC',
	'Pattaya',
	'Patua One',
	'Pavanam',
	'Paytone One',
	'Peddana',
	'Peralta',
	'Permanent Marker',
	'Petit Formal Script',
	'Petrona',
	'Philosopher',
	'Piedra',
	'Pinyon Script',
	'Pirata One',
	'Plaster',
	'Play',
	'Playball',
	'Playfair Display',
	'Playfair Display SC',
	'Podkova',
	'Poiret One',
	'Poller One',
	'Poly',
	'Pompiere',
	'Pontano Sans',
	'Poppins',
	'Port Lligat Sans',
	'Port Lligat Slab',
	'Pragati Narrow',
	'Prata',
	'Preahvihear',
	'Press Start 2P',
	'Pridi',
	'Princess Sofia',
	'Prociono',
	'Prompt',
	'Prosto One',
	'Proza Libre',
	'Puritan',
	'Purple Purse',
	'Quando',
	'Quantico',
	'Quattrocento',
	'Quattrocento Sans',
	'Questrial',
	'Quicksand',
	'Quintessential',
	'Qwigley',
	'Racing Sans One',
	'Radley',
	'Rajdhani',
	'Rakkas',
	'Raleway',
	'Raleway Dots',
	'Ramabhadra',
	'Ramaraja',
	'Rambla',
	'Rammetto One',
	'Ranchers',
	'Rancho',
	'Ranga',
	'Rasa',
	'Rationale',
	'Ravi Prakash',
	'Redressed',
	'Reem Kufi',
	'Reenie Beanie',
	'Revalia',
	'Rhodium Libre',
	'Ribeye',
	'Ribeye Marrow',
	'Righteous',
	'Risque',
	'Roboto',
	'Roboto Condensed',
	'Roboto Mono',
	'Roboto Slab',
	'Rochester',
	'Rock Salt',
	'Rokkitt',
	'Romanesco',
	'Ropa Sans',
	'Rosario',
	'Rosarivo',
	'Rouge Script',
	'Rozha One',
	'Rubik',
	'Rubik Mono One',
	'Ruda',
	'Rufina',
	'Ruge Boogie',
	'Ruluko',
	'Rum Raisin',
	'Ruslan Display',
	'Russo One',
	'Ruthie',
	'Rye',
	'Sacramento',
	'Sahitya',
	'Sail',
	'Saira',
	'Saira Condensed',
	'Saira Extra Condensed',
	'Saira Semi Condensed',
	'Salsa',
	'Sanchez',
	'Sancreek',
	'Sansita',
	'Sarala',
	'Sarina',
	'Sarpanch',
	'Satisfy',
	'Scada',
	'Scheherazade',
	'Schoolbell',
	'Scope One',
	'Seaweed Script',
	'Secular One',
	'Sedgwick Ave',
	'Sedgwick Ave Display',
	'Sevillana',
	'Seymour One',
	'Shadows Into Light',
	'Shadows Into Light Two',
	'Shanti',
	'Share',
	'Share Tech',
	'Share Tech Mono',
	'Shojumaru',
	'Short Stack',
	'Shrikhand',
	'Siemreap',
	'Sigmar One',
	'Signika',
	'Signika Negative',
	'Simonetta',
	'Sintony',
	'Sirin Stencil',
	'Six Caps',
	'Skranji',
	'Slabo 13px',
	'Slabo 27px',
	'Slackey',
	'Smokum,',
	'Smythe',
	'Sniglet',
	'Snippet',
	'Snowburst One',
	'Sofadi One',
	'Sofia',
	'Sonsie One',
	'Sorts Mill Goudy',
	'Source Code Pro',
	'Source Sans Pro',
	'Source Serif Pro',
	'Space Mono',
	'Special Elite',
	'Spectral',
	'Spicy Rice',
	'Spinnaker',
	'Spirax',
	'Squada One',
	'Sree Krushnadevaraya',
	'Sriracha',
	'Stalemate',
	'Stalinist One',
	'Stardos Stencil',
	'Stint Ultra Condensed',
	'Stint Ultra Expanded',
	'Stoke',
	'Strait',
	'Sue Ellen Francisco',
	'Suez One',
	'Sumana',
	'Sunshiney',
	'Supermercado One',
	'Sura',
	'Suranna',
	'Suravaram',
	'Suwannaphum',
	'Swanky and Moo Moo',
	'Syncopate',
	'Tangerine',
	'Taprom',
	'Tauri',
	'Taviraj',
	'Teko',
	'Telex',
	'Tenali Ramakrishna',
	'Tenor Sans',
	'Text Me One',
	'The Girl Next Door',
	'Tienne',
	'Tillana',
	'Timmana',
	'Tinos',
	'Titan One',
	'Titillium Web',
	'Trade Winds',
	'Trirong',
	'Trocchi',
	'Trochut',
	'Trykker',
	'Tulpen One',
	'Ubuntu',
	'Ubuntu Condensed',
	'Ubuntu Mono',
	'Ultra',
	'Uncial Antiqua',
	'Underdog',
	'Unica One',
	'UnifrakturCook',
	'UnifrakturMaguntia',
	'Unkempt',
	'Unlock',
	'Unna',
	'VT323',
	'Vampiro One',
	'Varela',
	'Varela Round',
	'Vast Shadow',
	'Vesper Libre',
	'Vibur',
	'Vidaloka',
	'Viga',
	'Voces',
	'Volkhov',
	'Vollkorn',
	'Voltaire',
	'Waiting for the Sunrise',
	'Wallpoet',
	'Walter Turncoat',
	'Warnes',
	'Wellfleet',
	'Wendy One',
	'Wire One',
	'Work Sans',
	'Yanone Kaffeesatz',
	'Yantramanav',
	'Yatra One',
	'Yellowtail',
	'Yeseva One',
	'Yesteryear',
	'Yrsa',
	'Zeyada',
	'Zilla Slab',
	'Zilla Slab Highlight'
);

use MatthiasMullie\Minify;

class OptimizoMinify {

	protected function getCSS( $url, $css ) {

		if ( ! empty( $url ) ) {
			$css = preg_replace( "/url\(\s*['\"]?(?!data:)(?!http)(?![\/'\"])(.+?)['\"]?\s*\)/ui", "url(" . dirname( $url ) . "/$1)", $css );
		}

		# remove query strings from fonts (for better seo, but add a small cache buster based on most recent updates)
		$ctime = '0'; # last update or zero
		$css   = preg_replace( '/(.eot|.woff2|.woff|.ttf)+[?+](.+?)(\)|\'|\")/ui', "$1" . "#" . $ctime . "$3", $css ); # fonts cache buster

		# minify CSS
		$css = $this->minifyCSSWithPHP( $css );

		# add css comment
		$css = trim( $css );

		# return html
		return $css;
	}

	protected function minifyCSSWithPHP( $css ) {
		$cssMinifier = new Minify\CSS( $css );
		$cssMinifier->setMaxImportSize( 15 ); # [css only] embed assets up to 15 Kb (default 5Kb) - processes gif, png, jpg, jpeg, svg & woff
		$minifier = $cssMinifier->minify();
		if ( $minifier !== false ) {
			return $this->compatURL( $minifier );
		}
		return $this->compatURL( $css );
	}

	protected function minifyJSWithPHP ( $js ){

	$jsMinifier = new Minify\JS( $js );
	$minifier      = $jsMinifier->minify();
	if ( $minifier !== false && ( strlen( trim( $js ) ) == strlen( trim( $minifier ) ) || strlen( trim( $minifier ) ) > 0 ) ) {
		return $this->compatURL( $minifier );
	}

	# if we are here, something went  wrong and minification didn't work
	$js = "\n/*! Optimizo: Minification of the following section failed, so it has been merged instead. */\n" . $js;

	return ( $js );
}

	public function createCache() {
		$upload            = array();
		$upload['basedir'] = WP_CONTENT_DIR . '/optimizoCache';
		$upload['baseurl'] = site_url() . '/wp-content/optimizoCache';
		# create
		$uploadsdir  = $upload['basedir'];
		$uploadsurl  = $upload['baseurl'];
		$cachebase   = $uploadsdir;
		$cachedir    = $cachebase;
		$cachedirurl = $uploadsurl;
		# get permissions from uploads directory
		$dirPerm = 0777;

//		if ( is_dir( $uploadsdir ) ) {
//			$this->removeDirectory($uploadsdir);
//			@mkdir( $uploadsdir );
//		}

		# mkdir and check if umask requires chmod
		$dirs = array( $cachebase, $cachedir );
		foreach ( $dirs as $target ) {
			if ( ! is_dir( $target ) ) {
				if ( @mkdir( $target, $dirPerm, true ) ) {
					if ( $dirPerm != ( $dirPerm & ~umask() ) ) {
						$folder_parts = explode( '/', substr( $target, strlen( dirname( $target ) ) + 1 ) );
						for ( $i = 1, $c = count( $folder_parts ); $i <= $c; $i ++ ) {
							@chmod( dirname( $target ) . '/' . implode( '/', array_slice( $folder_parts, 0, $i ) ), $dirPerm );
						}
					}
				} else {
					# fallback
					wp_mkdir_p( $target );
				}
			}
		}

		# return
		return array(
			'cachebase'   => $cachebase,
			'cachedir'    => $cachedir,
			'cachedirurl' => $cachedirurl
		);
	}

	protected function downloadAndMinify( $url, $isInline, $typeToMinify, $handle ) {
		global $wpDomain, $wpHome, $wpHomePath;

		$optimizoDebug = true;

		# must have
		if ( is_null( $url ) || empty( $url ) ) {
			return false;
		}
		if ( ! in_array( $typeToMinify, array( 'js', 'css' ) ) ) {
			return false;
		}

		# defaults
		if ( is_null( $isInline ) || empty( $isInline ) ) {
			$isInline = '';
		}
		$printHandle = '';
		if ( is_null( $handle ) || empty( $handle ) ) {
			$handle = '';
		} else {
			$printHandle = "[$handle]";
		}

		# debug request
		$debugRequest = array(
			'furl'   => $url,
			'inline' => $isInline,
			'type'   => $typeToMinify,
			'handle' => $handle
		);

		# filters and defaults
		$printURL = str_ireplace( array( site_url(), home_url(), 'http:', 'https:' ), '', $url );

		if ( stripos( $url, $wpDomain ) !== false ) {
			# default
			$file = str_ireplace( rtrim( $wpHome, '/' ), rtrim( $wpHomePath, '/' ), $url );
			clearstatcache();

			if ( file_exists( $file ) ) {
				if ( $typeToMinify == 'js' ) {
					$code = $this->getJS( $url, file_get_contents( $file ) );
				} else {
					$code = $this->getCSS( $url, file_get_contents( $file ) . $isInline );
				}

				# log, save and return
				$log = $printURL;
				if ( $optimizoDebug == true ) {
					$log .= "\n ===== Debug: $printHandle was opened from $file ===== \n";
				}
				$log    .= PHP_EOL;
				$return = array( 'request' => $debugRequest, 'log' => $log, 'code' => $code, 'status' => true );

				return json_encode( $return );
			}

			# failover when home_url != site_url
			$nfurl = str_ireplace( site_url(), home_url(), $url );
			$file  = str_ireplace( rtrim( $wpHome, '/' ), rtrim( $wpHomePath, '/' ), $nfurl );
			clearstatcache();
			if ( file_exists( $file ) ) {
				if ( $typeToMinify == 'js' ) {
					$code = $this->getJS( $url, file_get_contents( $file ) );
				} else {
					$code = $this->getCSS( $url, file_get_contents( $file ) . $isInline );
				}

				# log, save and return
				$log = $printURL;

				if ( $optimizoDebug == true ) {
					$log .= "\n ===== Debug: $printHandle was opened from $file ===== \n";
				}

				$log    .= PHP_EOL;
				$return = array( 'request' => $debugRequest, 'log' => $log, 'code' => $code, 'status' => true );

				return json_encode( $return );
			}
		}


		# fallback when home_url != site_url
		if ( stripos( $url, $wpDomain ) !== false && home_url() != site_url() ) {
			$nfurl = str_ireplace( site_url(), home_url(), $url );
			$code  = $this->downloadFunction( $nfurl );
			if ( $code !== false && ! empty( $code ) && strtolower( substr( $code, 0, 9 ) ) != "<!doctype" ) {
				if ( $typeToMinify == 'js' ) {
					$code = $this->getJS( $url, $code );
				} else {
					$code = $this->getCSS( $url, $code . $isInline );
				}

				# log, save and return
				$log = $printURL;

				if ( $optimizoDebug == true ) {
					$log .= "\n ===== Debug: $printHandle was opened from $file ===== \n";
				}

				$log    .= PHP_EOL;
				$return = array( 'request' => $debugRequest, 'log' => $log, 'code' => $code, 'status' => true );

				return json_encode( $return );
			}
		}


		# if remote urls failed... try to open locally again, regardless of OS in use
		if ( stripos( $url, $wpDomain ) !== false ) {

			# default
			$f = str_ireplace( rtrim( $wpHome, '/' ), rtrim( $wpHomePath, '/' ), $url );
			clearstatcache();
			if ( file_exists( $f ) ) {
				if ( $typeToMinify == 'js' ) {
					$code = $this->getJS( $url, file_get_contents( $f ) );
				} else {
					$code = $this->getCSS( $url, file_get_contents( $f ) . $isInline );
				}

				# log, save and return
				$log = $printURL;

				if ( $optimizoDebug == true ) {
					$log .= "\n ===== Debug: $printHandle was opened from $file ===== \n";
				}

				$log    .= PHP_EOL;
				$return = array( 'request' => $debugRequest, 'log' => $log, 'code' => $code, 'status' => true );

				return json_encode( $return );
			}

			# failover when home_url != site_url
			$nfurl = str_ireplace( site_url(), home_url(), $url );
			$file  = str_ireplace( rtrim( $wpHome, '/' ), rtrim( $wpHomePath, '/' ), $nfurl );
			clearstatcache();
			if ( file_exists( $file ) ) {
				if ( $typeToMinify == 'js' ) {
					$code = $this->getJS( $url, file_get_contents( $file ) );
				} else {
					$code = $this->getCSS( $url, file_get_contents( $file ) . $isInline );
				}

				# log, save and return
				$log = $printURL;

				if ( $optimizoDebug == true ) {
					$log .= "\n ===== Debug: $printHandle was opened from $file ===== \n";
				}

				$log    .= PHP_EOL;
				$return = array( 'request' => $debugRequest, 'log' => $log, 'code' => $code, 'status' => true );

				return json_encode( $return );
			}
		}


		# else fail
		$log    = $printURL;
		$return = array( 'request' => $debugRequest, 'log' => $log, 'code' => '', 'status' => false );

		return json_encode( $return );
	}

	# minify js on demand (one file at one time, for compatibility)
	protected function getJS( $url, $js ) {

		$disableJSMinification = false;

		# exclude minification on already minified files + jquery (because minification might break those)
		$excludableJSFiles = array( 'jquery.js', '.min.js', '-min.js', '/uploads/fusion-scripts/', '/min/', '.packed.js' );
		foreach ( $excludableJSFiles as $exclude ) {
			if ( stripos( basename( $url ), $exclude ) !== false ) {
				$disableJSMinification = true;
				break;
			}
		}

		if ( ! $disableJSMinification ) {
			$js = $this->minifyJSWithPHP( $js );
		} else {
			$js = $this->compatURL($js);
		}


		# try to remove source mapping files
		$filename = basename( $url );
		$remove   = array( "//# sourceMappingURL=$filename.map", "//# sourceMappingURL = $filename.map" );
		$js       = str_ireplace( $remove, '', $js );

		# needed when merging js files
		$js = trim( $js );
		if ( substr( $js, - 1 ) != ';' ) {
			$js = $js . ';';
		}

		# return html
		return $js . PHP_EOL;
	}

	protected function downloadFunction( $url ) {
		# info (needed for google fonts woff files + hinted fonts) as well as to bypass some security filters
		$uagent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2486.0 Safari/537.36 Edge/13.10586';

		# fetch via wordpress functions
		$response = wp_remote_get( $url, array(
			'user-agent'  => $uagent,
			'timeout'     => 7,
			'httpversion' => '1.1',
			'sslverify'   => false
		) );
		$res_code = wp_remote_retrieve_response_code( $response );
		if ( $res_code == '200' ) {
			$data = wp_remote_retrieve_body( $response );
			if ( strlen( $data ) > 1 ) {
				return $data;
			}
		}

		# verify
		if ( ! isset( $res_code ) || empty( $res_code ) || $res_code == false || is_null( $res_code ) ) {
			return false;
		}

		# stop here, error 4xx or 5xx
		if ( $res_code[0] == '4' || $res_code[0] == '5' ) {
			return false;
		}

		# fallback fail
		return false;
	}

	protected function checkIfGoogleFontsExist( $font ) {
		global $googleFontsWhiteList;

		#normalize
		$googleFontsWhiteList = array_map( 'strtolower', $googleFontsWhiteList );
		$font                 = str_ireplace( '+', ' ', strtolower( $font ) );

		# check
		if ( in_array( $font, $googleFontsWhiteList ) ) {
			return true;
		}

		# fallback
		return false;
	}

	protected function concatenateGoogleFonts( $array ) {
		# extract unique font families
		$families = array();
		foreach ( $array as $font ) {

			# must have
			if ( stripos( $font, 'family=' ) !== false ) {

				# get fonts name, type and subset, remove wp query strings
				$font = explode( 'family=', htmlspecialchars_decode( rawurldecode( urldecode( $font ) ) ) );
				$a    = explode( '&v', end( $font ) );
				$font = trim( trim( trim( current( $a ) ), ',' ) );

				# reprocess if fonts are already concatenated in this url
				if ( stristr( $font, '|' ) !== false ) {
					$multiple = explode( '|', $font );
					if ( count( $multiple ) > 0 ) {
						foreach ( $multiple as $f ) {
							$families[] = str_ireplace( 'subsets', 'subset', trim( $f ) );
						}
					}
				} else {
					$families[] = str_ireplace( 'subsets', 'subset', trim( $font ) );
				}
			}
		}

		# return if empty
		if ( count( $families ) == 0 ) {
			return false;
		}

		# process names, types, subsets
		$fonts   = array();
		$subsets = array();
		foreach ( $families as $font ) {

			# extract the subsets
			if ( stripos( $font, 'subset' ) !== false ) {
				$sub  = trim( str_ireplace( '&subset=', '', stristr( $font, '&' ) ) );      # second part of the string, after &
				$font = stristr( $font, '&', true );                                   # font name, before &

				# subsets to array, unique, trim
				if ( stripos( $sub, ',' ) !== false ) {
					$ft = explode( ',', $sub );
					$ft = array_filter( array_map( 'trim', array_unique( $ft ) ) );
					foreach ( $ft as $s ) {
						$subsets[ $s ] = $s;
					}
				} else {
					if ( ! empty( $sub ) ) {
						$subsets[ $sub ] = $sub;
					}
				}

			}

			# check for font name and weights
			$ftypes = array();
			$name   = $font;
			if ( stripos( $font, ':' ) !== false ) {
				$name = stristr( $font, ':', true );       # font name, before :
				$fwe  = trim( stristr( $font, ':' ), ':' );   # second part of the string, after :

				# ftypes to array, unique, trim
				if ( stripos( $font, ',' ) !== false ) {
					$ft     = explode( ',', $fwe );
					$ftypes = array_filter( array_map( 'trim', array_unique( $ft ) ) );
				} else {
					if ( ! empty( $fwe ) ) {
						$ftypes[] = $fwe;
					}
				}

			}

			# name filter
			$name = str_ireplace( ' ', '+', trim( $name ) );

			# save fonts list, merge fontweights
			if ( ! isset( $fonts[ $name ] ) ) {
				$fonts[ $name ] = array( 'name' => $name, 'type' => $ftypes );
			} else {
				$ftypes         = array_merge( $ftypes, $fonts[ $name ]['type'] );
				$fonts[ $name ] = array( 'name' => $name, 'type' => $ftypes );
			}

		}

		# build font names with font weights, if allowed
		$build = array();
		foreach ( $fonts as $farr ) {
			if ( $this->checkIfGoogleFontsExist( $farr['name'] ) == true ) {
				$f = $farr['name'];
				if ( count( $farr['type'] ) > 0 ) {
					$f .= ':' . implode( ',', $farr['type'] );
				}
				$build[] = $f;
			}
		}

		# merge, append subsets
		$merge = '';
		if ( count( $build ) > 0 ) {
			$merge = implode( '|', $build );
			if ( count( $subsets ) > 0 ) {
				$merge .= '&subset=' . implode( ',', $subsets );
			}
		}

		# return
		if ( ! empty( $merge ) ) {
			return 'https://fonts.googleapis.com/css?family=' . $merge;
		} else {
			return false;
		}
	}

	protected function minifyInArray( $url, $ignore ) {
		$url = str_ireplace( array( 'http://', 'https://' ), '//', $url ); # better compatibility
		$url = strtok( urldecode( rawurldecode( $url ) ), '?' ); # no query string, decode entities

		if ( ! empty( $url ) && is_array( $ignore ) ) {
			foreach ( $ignore as $i ) {
				$i = str_ireplace( array( 'http://', 'https://' ), '//', $i ); # better compatibility
				$i = strtok( urldecode( rawurldecode( $i ) ), '?' ); # no query string, decode entities
				$i = trim( trim( trim( rtrim( $i, '/' ) ), '*' ) ); # wildcard char removal
				if ( stripos( $url, $i ) !== false ) {
					return true;
				}
			}
		}
	}

}