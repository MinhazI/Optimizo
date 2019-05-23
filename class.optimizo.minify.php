<?php

$pluginDirectory = plugin_dir_path( __FILE__ );

# Including the minifier library by: MatthiasMullie,
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

		$ctime = '0';
		$css   = preg_replace( '/(.eot|.woff2|.woff|.ttf)+[?+](.+?)(\)|\'|\")/ui', "$1" . "#" . $ctime . "$3", $css );

		$css = $this->minifyCSSWithPHP( $css );

		$css = trim( $css );

		# return html
		return $css;
	}

	protected function minifyCSSWithPHP( $css ) {
		$optimizoFunctions = new OptimizoFunctions();

		$cssMinifier = new Minify\CSS( $css );
		$cssMinifier->setMaxImportSize( 20 );
		$minifier = $cssMinifier->minify();
		if ( $minifier !== false ) {
			return $this->compatURL( $minifier );
		}

		return $optimizoFunctions->compatURL( $css );
	}

	protected function minifyJSWithPHP( $js ) {

		$optimizoFunctions = new OptimizoFunctions();

		$jsMinifier = new Minify\JS( $js );
		$minifier   = $jsMinifier->minify();
		if ( $minifier !== false && ( strlen( trim( $js ) ) == strlen( trim( $minifier ) ) || strlen( trim( $minifier ) ) > 0 ) ) {
			return $optimizoFunctions->compatURL( $minifier );
		}

		$js = "\n/*! Optimizo: Minification of the following section failed, so it has been merged instead. */\n" . $js;

		return ( $js );
	}

	public function createCache() {
		$upload            = array();
		$upload['baseDir'] = WP_CONTENT_DIR . '/optimizoCache';
		$upload['baseURL'] = site_url() . '/wp-content/optimizoCache';

		$uploadsDir  = $upload['baseDir'];
		$uploadsURL  = $upload['baseURL'];
		$cacheBase   = $uploadsDir;
		$cacheDir    = $cacheBase;
		$cacheDirURL = $uploadsURL;
		$webpCache = $uploadsDir . '/webp-images';

		$dirPerm = 0777;

		$dirs = array( $cacheBase, $cacheDir, $webpCache );
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
					wp_mkdir_p( $target );
				}
			}
		}

		# return
		return array(
			'cacheBase'   => $cacheBase,
			'cacheDir'    => $cacheDir,
			'cacheDirURL' => $cacheDirURL,
			'webpCache' => $webpCache
		);
	}

	protected function downloadAndMinify( $url, $isInline, $typeToMinify, $handle ) {
		global $wpDomain, $wpHome, $wpHomePath;

		$optimizoDebug = true;

		if ( is_null( $url ) || empty( $url ) ) {
			return false;
		}
		if ( ! in_array( $typeToMinify, array( 'js', 'css' ) ) ) {
			return false;
		}

		if ( is_null( $isInline ) || empty( $isInline ) ) {
			$isInline = '';
		}
		$printHandle = '';
		if ( is_null( $handle ) || empty( $handle ) ) {
			$handle = '';
		} else {
			$printHandle = "[$handle]";
		}

		$debugRequest = array(
			'url'    => $url,
			'inline' => $isInline,
			'type'   => $typeToMinify,
			'handle' => $handle
		);

		$printURL = str_ireplace( array( site_url(), home_url(), 'http:', 'https:' ), '', $url );

		if ( stripos( $url, $wpDomain ) !== false ) {

			$file = str_ireplace( rtrim( $wpHome, '/' ), rtrim( $wpHomePath, '/' ), $url );
			clearstatcache();

			if ( file_exists( $file ) ) {
				if ( $typeToMinify == 'js' ) {
					$code = $this->getJS( $url, file_get_contents( $file ) );
				} else {
					$code = $this->getCSS( $url, file_get_contents( $file ) . $isInline );
				}

				$log = $printURL;
				if ( $optimizoDebug == true ) {
					$log .= "\n ===== Debug: $printHandle was opened from $file ===== \n";
				}
				$log    .= PHP_EOL;
				$return = array( 'request' => $debugRequest, 'log' => $log, 'code' => $code, 'status' => true );

				return json_encode( $return );
			}

			$newURL = str_ireplace( site_url(), home_url(), $url );
			$file   = str_ireplace( rtrim( $wpHome, '/' ), rtrim( $wpHomePath, '/' ), $newURL );
			clearstatcache();
			if ( file_exists( $file ) ) {
				if ( $typeToMinify == 'js' ) {
					$code = $this->getJS( $url, file_get_contents( $file ) );
				} else {
					$code = $this->getCSS( $url, file_get_contents( $file ) . $isInline );
				}

				$log = $printURL;

				if ( $optimizoDebug == true ) {
					$log .= "\n ===== Debug: $printHandle was opened from $file ===== \n";
				}

				$log    .= PHP_EOL;
				$return = array( 'request' => $debugRequest, 'log' => $log, 'code' => $code, 'status' => true );

				return json_encode( $return );
			}
		}

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

			$newURL = str_ireplace( site_url(), home_url(), $url );
			$file   = str_ireplace( rtrim( $wpHome, '/' ), rtrim( $wpHomePath, '/' ), $newURL );
			clearstatcache();
			if ( file_exists( $file ) ) {
				if ( $typeToMinify == 'js' ) {
					$code = $this->getJS( $url, file_get_contents( $file ) );
				} else {
					$code = $this->getCSS( $url, file_get_contents( $file ) . $isInline );
				}

				$log = $printURL;

				if ( $optimizoDebug == true ) {
					$log .= "\n ===== Debug: $printHandle was opened from $file ===== \n";
				}

				$log    .= PHP_EOL;
				$return = array( 'request' => $debugRequest, 'log' => $log, 'code' => $code, 'status' => true );

				return json_encode( $return );
			}
		}

		$log    = $printURL;
		$return = array( 'request' => $debugRequest, 'log' => $log, 'code' => '', 'status' => false );

		return json_encode( $return );
	}

	protected function getJS( $url, $js ) {
		$optimizoFunctions = new OptimizoFunctions();

		$disableJSMinification = false;

		$excludableJSFiles = array(
			'jquery.js',
			'.min.js',
			'-min.js',
			'/uploads/fusion-scripts/',
			'/min/',
			'.packed.js'
		);
		foreach ( $excludableJSFiles as $exclude ) {
			if ( stripos( basename( $url ), $exclude ) !== false ) {
				$disableJSMinification = true;
				break;
			}
		}

		if ( ! $disableJSMinification ) {
			$js = $this->minifyJSWithPHP( $js );
		} else {
			$js = $optimizoFunctions->compatURL( $js );
		}

		$filename = basename( $url );
		$remove   = array( "//# sourceMappingURL=$filename.map", "//# sourceMappingURL = $filename.map" );
		$js       = str_ireplace( $remove, '', $js );

		$js = trim( $js );
		if ( substr( $js, - 1 ) != ';' ) {
			$js = $js . ';';
		}

		return $js . PHP_EOL;
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

		$fontFamilies = array();
		foreach ( $array as $font ) {

			if ( stripos( $font, 'family=' ) !== false ) {

				$font = explode( 'family=', htmlspecialchars_decode( rawurldecode( urldecode( $font ) ) ) );
				$a    = explode( '&v', end( $font ) );
				$font = trim( trim( trim( current( $a ) ), ',' ) );

				if ( stristr( $font, '|' ) !== false ) {
					$multiple = explode( '|', $font );
					if ( count( $multiple ) > 0 ) {
						foreach ( $multiple as $f ) {
							$fontFamilies[] = str_ireplace( 'subsets', 'subset', trim( $f ) );
						}
					}
				} else {
					$fontFamilies[] = str_ireplace( 'subsets', 'subset', trim( $font ) );
				}
			}
		}

		if ( count( $fontFamilies ) == 0 ) {
			return false;
		}

		$fonts   = array();
		$subsets = array();
		foreach ( $fontFamilies as $font ) {

			if ( stripos( $font, 'subset' ) !== false ) {
				$sub  = trim( str_ireplace( '&subset=', '', stristr( $font, '&' ) ) );
				$font = stristr( $font, '&', true );

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

			$fontTypes = array();
			$name      = $font;
			if ( stripos( $font, ':' ) !== false ) {
				$name       = stristr( $font, ':', true );
				$fontWeight = trim( stristr( $font, ':' ), ':' );

				if ( stripos( $font, ',' ) !== false ) {
					$ft        = explode( ',', $fontWeight );
					$fontTypes = array_filter( array_map( 'trim', array_unique( $ft ) ) );
				} else {
					if ( ! empty( $fontWeight ) ) {
						$fontTypes[] = $fontWeight;
					}
				}

			}

			$name = str_ireplace( ' ', '+', trim( $name ) );

			if ( ! isset( $fonts[ $name ] ) ) {
				$fonts[ $name ] = array( 'name' => $name, 'type' => $fontTypes );
			} else {
				$fontTypes      = array_merge( $fontTypes, $fonts[ $name ]['type'] );
				$fonts[ $name ] = array( 'name' => $name, 'type' => $fontTypes );
			}

		}

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

		$merge = '';
		if ( count( $build ) > 0 ) {
			$merge = implode( '|', $build );
		}

		# return
		if ( ! empty( $merge ) ) {
			return 'https://fonts.googleapis.com/css?family=' . $merge;
		} else {
			return false;
		}
	}

	protected function minifyInArray( $url, $ignore ) {
		$url = str_ireplace( array( 'http://', 'https://' ), '//', $url );
		$url = strtok( urldecode( rawurldecode( $url ) ), '?' );

		if ( ! empty( $url ) && is_array( $ignore ) ) {
			foreach ( $ignore as $i ) {
				$i = str_ireplace( array( 'http://', 'https://' ), '//', $i );
				$i = strtok( urldecode( rawurldecode( $i ) ), '?' );
				$i = trim( trim( trim( rtrim( $i, '/' ) ), '*' ) ); 
				if ( stripos( $url, $i ) !== false ) {
					return true;
				}
			}
		}
	}

}