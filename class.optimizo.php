<?php
/**
 * Created by PhpStorm.
 * User: minhaz
 * Date: 2/14/19
 * Time: 10:54 AM
 */

global $pluginDirectory;

#To avoid PHP regular expression issues
@ini_set( 'pcre.backtrack_limit', 5000000 );
@ini_set( 'pcre.recursion_limit', 5000000 );

$pluginDirectory = plugin_dir_path( __FILE__ );

# Including the minfier library by: MatthiasMullie,
# link: https://github.com/matthiasmullie/minify
$pathToMatthias = $pluginDirectory . 'inc/matthiasmullie';

require_once $pathToMatthias . '/minify/src/Minify.php';
require_once $pathToMatthias . '/minify/src/CSS.php';
require_once $pathToMatthias . '/minify/src/JS.php';
require_once $pathToMatthias . '/minify/src/Exception.php';
require_once $pathToMatthias . '/minify/src/Exceptions/BasicException.php';
require_once $pathToMatthias . '/minify/src/Exceptions/FileImportException.php';
require_once $pathToMatthias . '/minify/src/Exceptions/IOException.php';
require_once $pathToMatthias . '/path-converter/src/ConverterInterface.php';
require_once $pathToMatthias . '/path-converter/src/Converter.php';

global $googleFontsWhiteList;
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

class OptimizoClass {

	function activate() {
		$this->addToWPConfig();
		$this->writeToHtaccess();
		$this->createCache();
	}

	function deactivate() {

		$this->removeFromWPConfig();
		$this->removeFromHtaccess();
		$this->removeCache();

	}

	function addToWPConfig() {

		/*
		 * This is a function which will be used to add anything to WordPress WP-Config file which includes all the configurations for a WordPress installation.
		 * Currently it only includes the function to add the "WP-CACHE" as true in the file.
		 */

		$wp_config_file = @file_get_contents( ABSPATH . "wp-config.php" );

		if ( preg_match( "/Optimizo's configuration for cache/", $wp_config_file ) ) {

		} else if ( preg_match( "/WP_CACHE/", $wp_config_file ) ) {
			$wp_config_file = @file_get_contents( ABSPATH . "wp-config.php" );
			$wp_config_file = str_replace( "define('WP_CACHE', true);", "/** Optimizo's configuration for cache **/ \ndefine('WP_CACHE', true);", $wp_config_file );
			if ( ! @file_put_contents( ABSPATH . "wp-config.php", $wp_config_file ) ) {

			}
		} else {
			$wp_config_file = @file_get_contents( ABSPATH . "wp-config.php" );
			$wp_config_file = str_replace( "/** MySQL hostname */", "/** Optimizo's configuration for cache **/ \ndefine('WP_CACHE', true);\n\n/** MySQL hostname */", $wp_config_file );
			if ( ! @file_put_contents( ABSPATH . "wp-config.php", $wp_config_file ) ) {

			}
		}
	}

	function writeToHtaccess() {

		/*
		 * This function will be used to write the rewrite access rules and other rules for caching and G-Zip to the server's .htaccess file
		 * The first part of the IF condition is to see if the WordPress installation directory has the .htaccess file which includes the rules for any server.
		 * IF the server doesn't include it, Optimizo will automatically create and save the .htaccess file on the server and it will also include the rules for caching and GZip and other stuff to reduce the PLT
		 * IF the server does include .htaccess file, Optimizo will just add the rules that are essential to help reduction of PLT and it will save the file.
		 */

		if ( ! file_exists( ABSPATH . ".htaccess" ) ) {
			$htaccessData = "# BEGIN WordPress" . "\n" .
			                '<IfModule mod_rewrite.c>' . "\n" .
			                'RewriteEngine On' . "\n" .
			                'RewriteBase /' . "\n" .
			                'RewriteRule ^index\.php$ - [L]' . "\n" .
			                'RewriteCond %{REQUEST_FILENAME} !-f' . "\n" .
			                'RewriteCond %{REQUEST_FILENAME} !-d' . "\n" .
			                'RewriteRule . /index.php [L]' . "\n" .
			                '</IfModule>' . "\n" .
			                "# END WordPress" . "\n\n" .
			                "# BEGIN Optimizo's rules" . "\n" .
			                '<FilesMatch "\.(webm|ogg|mp4|ico|pdf|flv|jpg|jpeg|png|gif|webp|js|css|swf|x-html|css|xml|js|woff|woff2|ttf|svg|eot)(\.gz)?$">' . "\n" .
			                '<IfModule mod_expires.c>' . "\n" .
			                'AddType application/font-woff2 .woff2' . "\n" .
			                'ExpiresActive On' . "\n" .
			                'ExpiresDefault A0' . "\n" .
			                'ExpiresByType video/webm A10368000' . "\n" .
			                'ExpiresByType video/ogg A10368000' . "\n" .
			                'ExpiresByType video/mp4 A10368000' . "\n" .
			                'ExpiresByType image/webp A10368000' . "\n" .
			                'ExpiresByType image/gif A10368000' . "\n" .
			                'ExpiresByType image/png A10368000' . "\n" .
			                'ExpiresByType image/jpg A10368000' . "\n" .
			                'ExpiresByType image/jpeg A10368000' . "\n" .
			                'ExpiresByType image/ico A10368000' . "\n" .
			                'ExpiresByType image/svg+xml A10368000' . "\n" .
			                'ExpiresByType text/css A10368000' . "\n" .
			                'ExpiresByType text/javascript A10368000' . "\n" .
			                'ExpiresByType application/javascript A10368000' . "\n" .
			                'ExpiresByType application/x-javascript A10368000' . "\n" .
			                'ExpiresByType application/font-woff2 A10368000' . "\n" .
			                '</IfModule>' . "\n" .
			                '<IfModule mod_headers.c>' . "\n" .
			                'Header set Expires "max-age=A10368000, public"' . "\n" .
			                'Header unset ETag' . "\n" .
			                'Header set Connection keep-alive' . "\n" .
			                'FileETag None' . "\n" .
			                '</IfModule>' . "\n" .
			                '</FilesMatch>' . "\n" .
			                '<IfModule mod_deflate.c>' . "\n" .
			                "# Compress HTML, CSS, JavaScript, Text, XML and fonts" . "\n" .
			                'AddOutputFilterByType DEFLATE application/javascript' . "\n" .
			                'AddOutputFilterByType DEFLATE application/rss+xml' . "\n" .
			                'AddOutputFilterByType DEFLATE application/vnd.ms-fontobject' . "\n" .
			                'AddOutputFilterByType DEFLATE application/x-font' . "\n" .
			                'AddOutputFilterByType DEFLATE application/x-font-opentype' . "\n" .
			                'AddOutputFilterByType DEFLATE application/x-font-otf' . "\n" .
			                'AddOutputFilterByType DEFLATE application/x-font-truetype' . "\n" .
			                'AddOutputFilterByType DEFLATE application/x-font-ttf' . "\n" .
			                'AddOutputFilterByType DEFLATE application/x-javascript' . "\n" .
			                'AddOutputFilterByType DEFLATE application/xhtml+xml' . "\n" .
			                'AddOutputFilterByType DEFLATE application/xml' . "\n" .
			                'AddOutputFilterByType DEFLATE font/opentype' . "\n" .
			                'AddOutputFilterByType DEFLATE font/otf' . "\n" .
			                'AddOutputFilterByType DEFLATE font/ttf' . "\n" .
			                'AddOutputFilterByType DEFLATE image/svg+xml' . "\n" .
			                'AddOutputFilterByType DEFLATE image/x-icon' . "\n" .
			                'AddOutputFilterByType DEFLATE text/css' . "\n" .
			                'AddOutputFilterByType DEFLATE text/html' . "\n" .
			                'AddOutputFilterByType DEFLATE text/javascript' . "\n" .
			                'AddOutputFilterByType DEFLATE text/plain' . "\n" .
			                'AddOutputFilterByType DEFLATE text/xml' . "\n" .

			                "# Remove browser bugs (only needed for really old browsers)" . "\n" .
			                'BrowserMatch ^Mozilla/4 gzip-only-text/html' . "\n" .
			                'BrowserMatch ^Mozilla/4\.0[678] no-gzip' . "\n" .
			                'BrowserMatch \bMSIE !no-gzip !gzip-only-text/html' . "\n" .
			                'Header append Vary User-Agent' . "\n" .
			                '</IfModule>' . "\n" .
			                "# END Optimizo's rules" . "\n";

			fopen( ABSPATH . ".htaccess", "r+" );
			file_put_contents( ABSPATH . ".htaccess", $htaccessData );


			fclose( ABSPATH . ".htaccess" );
		} else {
			$htaccessData = "#BEGIN Optimizo's rules" . "\n" .
			                '<FilesMatch "\.(webm|ogg|mp4|ico|pdf|flv|jpg|jpeg|png|gif|webp|js|css|swf|x-html|css|xml|js|woff|woff2|ttf|svg|eot)(\.gz)?$">' . "\n" .
			                '<IfModule mod_expires.c>' . "\n" .
			                'AddType application/font-woff2 .woff2' . "\n" .
			                'ExpiresActive On' . "\n" .
			                'ExpiresDefault A0' . "\n" .
			                'ExpiresByType video/webm A10368000' . "\n" .
			                'ExpiresByType video/ogg A10368000' . "\n" .
			                'ExpiresByType video/mp4 A10368000' . "\n" .
			                'ExpiresByType image/webp A10368000' . "\n" .
			                'ExpiresByType image/gif A10368000' . "\n" .
			                'ExpiresByType image/png A10368000' . "\n" .
			                'ExpiresByType image/jpg A10368000' . "\n" .
			                'ExpiresByType image/jpeg A10368000' . "\n" .
			                'ExpiresByType image/ico A10368000' . "\n" .
			                'ExpiresByType image/svg+xml A10368000' . "\n" .
			                'ExpiresByType text/css A10368000' . "\n" .
			                'ExpiresByType text/javascript A10368000' . "\n" .
			                'ExpiresByType application/javascript A10368000' . "\n" .
			                'ExpiresByType application/x-javascript A10368000' . "\n" .
			                'ExpiresByType application/font-woff2 A10368000' . "\n" .
			                '</IfModule>' . "\n" .
			                '<IfModule mod_headers.c>' . "\n" .
			                'Header set Expires "max-age=A10368000, public"' . "\n" .
			                'Header unset ETag' . "\n" .
			                'Header set Connection keep-alive' . "\n" .
			                'FileETag None' . "\n" .
			                '</IfModule>' . "\n" .
			                '</FilesMatch>' . "\n" .
			                '<IfModule mod_deflate.c>' . "\n" .
			                "# Compress HTML, CSS, JavaScript, Text, XML and fonts" . "\n" .
			                'AddOutputFilterByType DEFLATE application/javascript' . "\n" .
			                'AddOutputFilterByType DEFLATE application/rss+xml' . "\n" .
			                'AddOutputFilterByType DEFLATE application/vnd.ms-fontobject' . "\n" .
			                'AddOutputFilterByType DEFLATE application/x-font' . "\n" .
			                'AddOutputFilterByType DEFLATE application/x-font-opentype' . "\n" .
			                'AddOutputFilterByType DEFLATE application/x-font-otf' . "\n" .
			                'AddOutputFilterByType DEFLATE application/x-font-truetype' . "\n" .
			                'AddOutputFilterByType DEFLATE application/x-font-ttf' . "\n" .
			                'AddOutputFilterByType DEFLATE application/x-javascript' . "\n" .
			                'AddOutputFilterByType DEFLATE application/xhtml+xml' . "\n" .
			                'AddOutputFilterByType DEFLATE application/xml' . "\n" .
			                'AddOutputFilterByType DEFLATE font/opentype' . "\n" .
			                'AddOutputFilterByType DEFLATE font/otf' . "\n" .
			                'AddOutputFilterByType DEFLATE font/ttf' . "\n" .
			                'AddOutputFilterByType DEFLATE image/svg+xml' . "\n" .
			                'AddOutputFilterByType DEFLATE image/x-icon' . "\n" .
			                'AddOutputFilterByType DEFLATE text/css' . "\n" .
			                'AddOutputFilterByType DEFLATE text/html' . "\n" .
			                'AddOutputFilterByType DEFLATE text/javascript' . "\n" .
			                'AddOutputFilterByType DEFLATE text/plain' . "\n" .
			                'AddOutputFilterByType DEFLATE text/xml' . "\n" .

			                "# Remove browser bugs (only needed for really old browsers)" . "\n" .
			                'BrowserMatch ^Mozilla/4 gzip-only-text/html' . "\n" .
			                'BrowserMatch ^Mozilla/4\.0[678] no-gzip' . "\n" .
			                'BrowserMatch \bMSIE !no-gzip !gzip-only-text/html' . "\n" .
			                'Header append Vary User-Agent' . "\n" .
			                '</IfModule>' . "\n" .
			                "# END Optimizo's rules" . "\n";

			$htaccessContents    = @file_get_contents( ABSPATH . ".htaccess" );
			$htaccessReplacement = str_replace( "# END WordPress", "# END WordPress\n\n" . $htaccessData, $htaccessContents );

			if ( ! @file_put_contents( ABSPATH . ".htaccess", $htaccessReplacement ) ) {

			}
		}

	}

	function removeFromWPConfig() {
		/*
		 * This is the function that will be used and called upon the deactivation of the plugin.
		 * Currently this function removes the "WP-CACHE" statement from the WP-Config file if it's available.
		 */

		$wp_config_file = @file_get_contents( ABSPATH . "wp-config.php" );
		$wp_config_file = str_replace( "/** Optimizo's configuration for cache **/ \ndefine('WP_CACHE', true);", null, $wp_config_file );
		if ( ! @file_put_contents( ABSPATH . "wp-config.php", $wp_config_file ) ) {
			add_action( 'admin_notices', 'failed_to_add_wp_config' );
		}
	}

	function removeFromHtaccess() {
		/*
		 * This is the function that will remove all the custom added rules from .htaccess file which was added upon the activation of the plugin.
		 */

		$htaccess_file = @file_get_contents( ABSPATH . ".htaccess" );

		$htaccessData = "#BEGIN Optimizo's rules" . "\n" .
		                '<FilesMatch "\.(webm|ogg|mp4|ico|pdf|flv|jpg|jpeg|png|gif|webp|js|css|swf|x-html|css|xml|js|woff|woff2|ttf|svg|eot)(\.gz)?$">' . "\n" .
		                '<IfModule mod_expires.c>' . "\n" .
		                'AddType application/font-woff2 .woff2' . "\n" .
		                'ExpiresActive On' . "\n" .
		                'ExpiresDefault A0' . "\n" .
		                'ExpiresByType video/webm A10368000' . "\n" .
		                'ExpiresByType video/ogg A10368000' . "\n" .
		                'ExpiresByType video/mp4 A10368000' . "\n" .
		                'ExpiresByType image/webp A10368000' . "\n" .
		                'ExpiresByType image/gif A10368000' . "\n" .
		                'ExpiresByType image/png A10368000' . "\n" .
		                'ExpiresByType image/jpg A10368000' . "\n" .
		                'ExpiresByType image/jpeg A10368000' . "\n" .
		                'ExpiresByType image/ico A10368000' . "\n" .
		                'ExpiresByType image/svg+xml A10368000' . "\n" .
		                'ExpiresByType text/css A10368000' . "\n" .
		                'ExpiresByType text/javascript A10368000' . "\n" .
		                'ExpiresByType application/javascript A10368000' . "\n" .
		                'ExpiresByType application/x-javascript A10368000' . "\n" .
		                'ExpiresByType application/font-woff2 A10368000' . "\n" .
		                '</IfModule>' . "\n" .
		                '<IfModule mod_headers.c>' . "\n" .
		                'Header set Expires "max-age=A10368000, public"' . "\n" .
		                'Header unset ETag' . "\n" .
		                'Header set Connection keep-alive' . "\n" .
		                'FileETag None' . "\n" .
		                '</IfModule>' . "\n" .
		                '</FilesMatch>' . "\n" .
		                '<IfModule mod_deflate.c>' . "\n" .
		                "# Compress HTML, CSS, JavaScript, Text, XML and fonts" . "\n" .
		                'AddOutputFilterByType DEFLATE application/javascript' . "\n" .
		                'AddOutputFilterByType DEFLATE application/rss+xml' . "\n" .
		                'AddOutputFilterByType DEFLATE application/vnd.ms-fontobject' . "\n" .
		                'AddOutputFilterByType DEFLATE application/x-font' . "\n" .
		                'AddOutputFilterByType DEFLATE application/x-font-opentype' . "\n" .
		                'AddOutputFilterByType DEFLATE application/x-font-otf' . "\n" .
		                'AddOutputFilterByType DEFLATE application/x-font-truetype' . "\n" .
		                'AddOutputFilterByType DEFLATE application/x-font-ttf' . "\n" .
		                'AddOutputFilterByType DEFLATE application/x-javascript' . "\n" .
		                'AddOutputFilterByType DEFLATE application/xhtml+xml' . "\n" .
		                'AddOutputFilterByType DEFLATE application/xml' . "\n" .
		                'AddOutputFilterByType DEFLATE font/opentype' . "\n" .
		                'AddOutputFilterByType DEFLATE font/otf' . "\n" .
		                'AddOutputFilterByType DEFLATE font/ttf' . "\n" .
		                'AddOutputFilterByType DEFLATE image/svg+xml' . "\n" .
		                'AddOutputFilterByType DEFLATE image/x-icon' . "\n" .
		                'AddOutputFilterByType DEFLATE text/css' . "\n" .
		                'AddOutputFilterByType DEFLATE text/html' . "\n" .
		                'AddOutputFilterByType DEFLATE text/javascript' . "\n" .
		                'AddOutputFilterByType DEFLATE text/plain' . "\n" .
		                'AddOutputFilterByType DEFLATE text/xml' . "\n" .

		                "# Remove browser bugs (only needed for really old browsers)" . "\n" .
		                'BrowserMatch ^Mozilla/4 gzip-only-text/html' . "\n" .
		                'BrowserMatch ^Mozilla/4\.0[678] no-gzip' . "\n" .
		                'BrowserMatch \bMSIE !no-gzip !gzip-only-text/html' . "\n" .
		                'Header append Vary User-Agent' . "\n" .
		                '</IfModule>' . "\n" .
		                "# END Optimizo's rules" . "\n";

		$htaccess_file = str_replace( $htaccessData, null, $htaccess_file );

		if ( ! @file_put_contents( ABSPATH . ".htaccess", $htaccess_file ) ) {

		}
	}

	function getCSS( $url, $css ) {
		global $wp_domain;

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

	function minifyCSSWithPHP( $css ) {
		$cssMinifier = new Minify\CSS( $css );
		$cssMinifier->setMaxImportSize( 15 ); # [css only] embed assets up to 15 Kb (default 5Kb) - processes gif, png, jpg, jpeg, svg & woff
		$min = $cssMinifier->minify();
		if ( $min !== false ) {
			return $this->compatURL( $min );
		}

		return $this->compatURL( $css );
	}

	function createCache() {
//		$cacheDir = WP_CONTENT_DIR . '/optimizoCache';
		$ctime             = time();
		$upload            = array();
		$upload['basedir'] = WP_CONTENT_DIR . '/optimizoCache';
//		$upload['baseurl'] = WP_CONTENT_DIR . '/optimizoCache';
		$upload['baseurl'] = site_url().'/wp-content/optimizoCache';
		# create
		$uploadsdir   = $upload['basedir'];
		$uploadsurl   = $upload['baseurl'];
		$cachebase    = $uploadsdir . '/' . $ctime;
		$cachebaseurl = $uploadsurl . '/' . $ctime;
		$cachedir     = $cachebase . '/out';
		$tmpdir       = $cachebase . '/tmp';
		$headerdir    = $cachebase . '/header';
		$cachedirurl  = $cachebaseurl . '/out';
		# get permissions from uploads directory
		$dir_perms = 0777;
		if ( is_dir( $uploadsdir . '/cache' ) && function_exists( 'stat' ) ) {
			if ( $stat = @stat( $uploadsdir . '/cache' ) ) {
				$dir_perms = $stat['mode'] & 0007777;
			}
		}
		# mkdir and check if umask requires chmod
		$dirs = array( $cachebase, $cachedir, $tmpdir, $headerdir );
		foreach ( $dirs as $target ) {
			if ( ! is_dir( $target ) ) {
				if ( @mkdir( $target, $dir_perms, true ) ) {
					if ( $dir_perms != ( $dir_perms & ~umask() ) ) {
						$folder_parts = explode( '/', substr( $target, strlen( dirname( $target ) ) + 1 ) );
						for ( $i = 1, $c = count( $folder_parts ); $i <= $c; $i ++ ) {
							@chmod( dirname( $target ) . '/' . implode( '/', array_slice( $folder_parts, 0, $i ) ), $dir_perms );
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
			'tmpdir'      => $tmpdir,
			'cachedir'    => $cachedir,
			'cachedirurl' => $cachedirurl,
			'headerdir'   => $headerdir
		);
	}

	function removeCache() {
		if ( is_dir( WP_CONTENT_DIR . '/optimizoCache' ) ) {
			rmdir( WP_CONTENT_DIR . '/optimizoCache' );
		}
	}

	function getWebsiteHTTPResponse( $url ) {
		if ( $url == null ) {
			return false;
		}

		$curl = curl_init( url );
		curl_setopt( $curl, CURLOPT_TIMEOUT, 5 );
		curl_setopt( $curl, CURLOPT_CONNECTTIMEOUT, 3 );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );

		$websiteData = curl_exec( $curl );

		$httpResponseCode = curl_getinfo( $curl, $websiteData );

		curl_close( $curl );

		if ( $httpResponseCode >= 200 && $httpResponseCode < 300 ) {
			return true;
		} else {
			return false;
		}

	}

	function returnFullURL( $src, $wp_domain, $wp_home ) {
		# preserve empty source handles
		$furl = trim( $src );
		if ( empty( $furl ) ) {
			return $furl;
		}

# some fixes
		$furl = str_ireplace( array( '&#038;', '&amp;' ), '&', $furl );

		if ( ( isset( $_SERVER['HTTPS'] ) && ( $_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1 ) ) || ( isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' ) ) {
			$default_protocol = 'https://';
		} else {
			$default_protocol = 'http://';
		}

#make sure wp_home doesn't have a forward slash
		$wp_home = rtrim( $wp_home, '/' );

# apply some filters
		if ( substr( $furl, 0, 2 ) === "//" ) {
			$furl = $default_protocol . ltrim( $furl, "/" );
		}  # protocol only
		if ( substr( $furl, 0, 4 ) === "http" && stripos( $furl, $wp_domain ) === false ) {
			return $furl;
		} # return if external domain
		if ( substr( $furl, 0, 4 ) !== "http" && stripos( $furl, $wp_domain ) !== false ) {
			$furl = $wp_home . '/' . ltrim( $furl, "/" );
		} # protocol + home

# prevent double forward slashes in the middle
		$furl = str_ireplace( '###', '://', str_ireplace( '//', '/', str_ireplace( '://', '###', $furl ) ) );

# consider different wp-content directory
		$proceed = 0;
		if ( ! empty( $wp_home ) ) {
			$alt_wp_content = basename( $wp_home );
			if ( substr( $furl, 0, strlen( $alt_wp_content ) ) === $alt_wp_content ) {
				$proceed = 1;
			}
		}

# protocol + home for relative paths
		if ( substr( $furl, 0, 12 ) === "/wp-includes" || substr( $furl, 0, 9 ) === "/wp-admin" || substr( $furl, 0, 11 ) === "/wp-content" || $proceed == 1 ) {
			$furl = $wp_home . '/' . ltrim( $furl, "/" );
		}

# make sure there is a protocol prefix as required
		$furl = $default_protocol . str_ireplace( array( 'http://', 'https://' ), '', $furl ); # enforce protocol

# no query strings
		if ( stripos( $furl, '.js?v' ) !== false ) {
			$furl = stristr( $furl, '.js?v', true ) . '.js';
		} # no query strings
		if ( stripos( $furl, '.css?v' ) !== false ) {
			$furl = stristr( $furl, '.css?v', true ) . '.css';
		} # no query strings

# make sure there is a protocol prefix as required
		$furl = $this->compatURL( $furl ); # enforce protocol

		return $furl;
	}

	function minifyInArray( $furl, $ignore ) {
		$furl = str_ireplace( array( 'http://', 'https://' ), '//', $furl ); # better compatibility
		$furl = strtok( urldecode( rawurldecode( $furl ) ), '?' ); # no query string, decode entities

		if ( ! empty( $furl ) && is_array( $ignore ) ) {
			foreach ( $ignore as $i ) {
				$i = str_ireplace( array( 'http://', 'https://' ), '//', $i ); # better compatibility
				$i = strtok( urldecode( rawurldecode( $i ) ), '?' ); # no query string, decode entities
				$i = trim( trim( trim( rtrim( $i, '/' ) ), '*' ) ); # wildcard char removal
				if ( stripos( $furl, $i ) !== false ) {
					return true;
				}
			}
		}
	}

	function checkIfInternalLink( $furl, $wp_home, $noxtra = null ) {
		if ( substr( $furl, 0, strlen( $wp_home ) ) === $wp_home ) {
			return true;
		}
		if ( stripos( $furl, $wp_home ) !== false ) {
			return true;
		}
		if ( isset( $_SERVER['HTTP_HOST'] ) && stripos( $furl, preg_replace( '/:\d+$/', '', $_SERVER['HTTP_HOST'] ) ) !== false ) {
			return true;
		}
		if ( isset( $_SERVER['SERVER_NAME'] ) && stripos( $furl, preg_replace( '/:\d+$/', '', $_SERVER['SERVER_NAME'] ) ) !== false ) {
			return true;
		}
		if ( isset( $_SERVER['SERVER_ADDR'] ) && stripos( $furl, preg_replace( '/:\d+$/', '', $_SERVER['SERVER_ADDR'] ) ) !== false ) {
			return true;
		}

		# allow specific external urls to be merged
		if ( $noxtra === null ) {
			$merge_allowed_urls = array_map( 'trim', explode( PHP_EOL ) );
			if ( is_array( $merge_allowed_urls ) && strlen( implode( $merge_allowed_urls ) ) > 0 ) {
				foreach ( $merge_allowed_urls as $e ) {
					if ( stripos( $furl, $e ) !== false && ! empty( $e ) ) {
						return true;
					}
				}
			}
		}

		return false;
	}

	function getWPProtocol( $url ) {
		$url = ltrim( str_ireplace( array( 'http://', 'https://' ), '', $url ), '/' ); # better compatibility

		# enforce protocol if needed
		if ( ( isset( $_SERVER['HTTPS'] ) && ( $_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1 ) ) ||
		     ( isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' ) ) {
			$default_protocol = 'https://';
		} else {
			$default_protocol = 'http://';
		}

		# return
		return $default_protocol . $url;
	}

	function fixPermissions( $file ) {
		if ( function_exists( 'stat' ) ) {
			if ( $stat = @stat( dirname( $file ) ) ) {
				$perms = $stat['mode'] & 0007777;
				@chmod( $file, $perms );

				clearstatcache();

				return true;
			}
		}


		# get permissions from parent directory
		$perms = 0777;
		if ( function_exists( 'stat' ) ) {
			if ( $stat = @stat( dirname( $file ) ) ) {
				$perms = $stat['mode'] & 0007777;
			}
		}

		if ( file_exists( $file ) ) {
			if ( $perms != ( $perms & ~umask() ) ) {
				$folder_parts = explode( '/', substr( $file, strlen( dirname( $file ) ) + 1 ) );
				for ( $i = 1, $c = count( $folder_parts ); $i <= $c; $i ++ ) {
					@chmod( dirname( $file ) . '/' . implode( '/', array_slice( $folder_parts, 0, $i ) ), $perms );
				}
			}
		}

		return true;
	}

	function compatURL( $code ) {

		if ( ( isset( $_SERVER['HTTPS'] ) && ( $_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1 ) )
		     || ( isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' ) ) {
			$default_protocol = 'https://';
		} else {
			$default_protocol = 'http://';
		}
		$code = str_ireplace( array( 'http://', 'https://' ), $default_protocol, $code );
		$code = str_ireplace( $default_protocol . 'www.w3.org', 'http://www.w3.org', $code );

		return $code;

	}

	function getTempStore( $key ) {
		$cachepath = $this->createCache();
		$tmpdir    = $cachepath['tmpdir'];
		$f         = $tmpdir . '/' . $key . '.transient';
		clearstatcache();
		if ( file_exists( $f ) ) {
			return file_get_contents( $f );
		} else {
			return false;
		}
	}

	function setTempStore( $key, $code ) {
		if ( is_null( $code ) || empty( $code ) ) {
			return false;
		}
		$cachepath = $this->createCache();
		$tmpdir    = $cachepath['tmpdir'];
		$f         = $tmpdir . '/' . $key . '.transient';
		file_put_contents( $f, $code );
		$this->fixPermissions( $f );

		return true;
	}


	function downloadAndMinify( $furl, $inline, $type, $handle ) {
		global $wp_domain, $wp_home, $wp_home_path, $fvm_debug;

		# must have
		if ( is_null( $furl ) || empty( $furl ) ) {
			return false;
		}
		if ( ! in_array( $type, array( 'js', 'css' ) ) ) {
			return false;
		}

		# defaults
		if ( is_null( $inline ) || empty( $inline ) ) {
			$inline = '';
		}
		$printhandle = '';
		if ( is_null( $handle ) || empty( $handle ) ) {
			$handle = '';
		} else {
			$printhandle = "[$handle]";
		}

		# debug request
		$dreq = array(
			'furl'   => $furl,
			'inline' => $inline,
			'type'   => $type,
			'handle' => $handle
		);

		# filters and defaults
		$printurl = str_ireplace( array( site_url(), home_url(), 'http:', 'https:' ), '', $furl );

		if ( stripos( $furl, $wp_domain ) !== false ) {
			# default
			$f = str_ireplace( rtrim( $wp_home, '/' ), rtrim( $wp_home_path, '/' ), $furl );
			clearstatcache();

			if ( file_exists( $f ) ) {
				if ( $type == 'js' ) {
					$code = $this->getJS( $furl, file_get_contents( $f ) );
				} else {
					$code = $this->getCSS( $furl, file_get_contents( $f ) . $inline );
				}

				# log, save and return
				$log = $printurl;
				if ( $fvm_debug == true ) {
					$log .= " --- Debug: $printhandle was opened from $f ---";
				}
				$log    .= PHP_EOL;
				$return = array( 'request' => $dreq, 'log' => $log, 'code' => $code, 'status' => true );

				return json_encode( $return );
			}

			# failover when home_url != site_url
			$nfurl = str_ireplace( site_url(), home_url(), $furl );
			$f     = str_ireplace( rtrim( $wp_home, '/' ), rtrim( $wp_home_path, '/' ), $nfurl );
			clearstatcache();
			if ( file_exists( $f ) ) {
				if ( $type == 'js' ) {
					$code = $this->getJS( $furl, file_get_contents( $f ) );
				} else {
					$code = $this->getCSS( $furl, file_get_contents( $f ) . $inline );
				}

				# log, save and return
				$log    = $printurl;
				$log    .= PHP_EOL;
				$return = array( 'request' => $dreq, 'log' => $log, 'code' => $code, 'status' => true );

				return json_encode( $return );
			}
		}


		# fallback when home_url != site_url
		if ( stripos( $furl, $wp_domain ) !== false && home_url() != site_url() ) {
			$nfurl = str_ireplace( site_url(), home_url(), $furl );
			$code  = $this->downloadFunction( $nfurl );
			if ( $code !== false && ! empty( $code ) && strtolower( substr( $code, 0, 9 ) ) != "<!doctype" ) {
				if ( $type == 'js' ) {
					$code = $this->getJS( $furl, $code );
				} else {
					$code = $this->getCSS( $furl, $code . $inline );
				}

				# log, save and return
				$log    = $printurl;
				$log    .= PHP_EOL;
				$return = array( 'request' => $dreq, 'log' => $log, 'code' => $code, 'status' => true );

				return json_encode( $return );
			}
		}


		# if remote urls failed... try to open locally again, regardless of OS in use
		if ( stripos( $furl, $wp_domain ) !== false ) {

			# default
			$f = str_ireplace( rtrim( $wp_home, '/' ), rtrim( $wp_home_path, '/' ), $furl );
			clearstatcache();
			if ( file_exists( $f ) ) {
				if ( $type == 'js' ) {
					$code = $this->getJS( $furl, file_get_contents( $f ) );
				} else {
					$code = $this->getCSS( $furl, file_get_contents( $f ) . $inline );
				}

				# log, save and return
				$log    = $printurl;
				$log    .= PHP_EOL;
				$return = array( 'request' => $dreq, 'log' => $log, 'code' => $code, 'status' => true );

				return json_encode( $return );
			}

			# failover when home_url != site_url
			$nfurl = str_ireplace( site_url(), home_url(), $furl );
			$f     = str_ireplace( rtrim( $wp_home, '/' ), rtrim( $wp_home_path, '/' ), $nfurl );
			clearstatcache();
			if ( file_exists( $f ) ) {
				if ( $type == 'js' ) {
					$code = $this->getJS( $furl, file_get_contents( $f ) );
				} else {
					$code = $this->getCSS( $furl, file_get_contents( $f ) . $inline );
				}

				# log, save and return
				$log    = $printurl;
				$log    .= PHP_EOL;
				$return = array( 'request' => $dreq, 'log' => $log, 'code' => $code, 'status' => true );

				return json_encode( $return );
			}
		}


		# else fail
		$log    = $printurl;
		$return = array( 'request' => $dreq, 'log' => $log, 'code' => '', 'status' => false );

		return json_encode( $return );
	}

# minify js on demand (one file at one time, for compatibility)
	function getJS( $url, $js ) {

		# exclude minification on already minified files + jquery (because minification might break those)
		$excl = array( 'jquery.js', '.min.js', '-min.js', '/uploads/fusion-scripts/', '/min/', '.packed.js' );
		foreach ( $excl as $e ) {
			if ( stripos( basename( $url ), $e ) !== false ) {
				$disable_js_minification = true;
				break;
			}
		}

		# minify JS
		$js = $this->compatURL( $js );

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

	function downloadFunction( $url ) {
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

	function checkIfGoogleFontsExist( $font ) {
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

	function concatenateGoogleFonts( $array ) {
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

}