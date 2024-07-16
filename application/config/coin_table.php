<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * -------------------------------------------------------------------
 * Themes
 * -------------------------------------------------------------------
 *
 * Pattern:
 * Theme => [Name, Hex Color]
 *
 */
$config['themes'] = array(
    'red'       => array('Red','#db2828'),
    'orange'    => array('Orange','#f2711c'),
    'green'     => array('Green','#21ba45'),
    'teal'      => array('Teal','#00b5ad'),
    'blue'      => array('Blue','#2185d0'),
    'violet'    => array('Violet','#6435c9'),
    'purple'    => array('Purple','#a333c8'),
    'brown'     => array('Brown','#a5673f'),
    'black'     => array('Black','#1b1c1d'),
    'olive'     => array('Olive','#b5cc18'),
    'yellow'    => array('Yellow','#fbbd08'),
);

/*
 * -------------------------------------------------------------------
 * Social Networks
 * -------------------------------------------------------------------
 *
 * Pattern:
 * Id => [Icon, Name, Color]
 *
 */
$config['social_networks'] = array(
    'facebook'          => array('facebook',        'Facebook',         'blue'),
    'twitter'           => array('twitter',         'Twitter',          'blue'),
    'google_plus'       => array('google plus',     'Google+',          'red'),
    'youtube'           => array('youtube',         'Youtube',          'red'),
    'instagram'         => array('instagram',       'Instagram',        'blue'),
    'pinterest'         => array('pinterest',       'Pinterest',        'red'),
    'tumblr'            => array('tumblr',          'Tumblr',           'black'),
    'reddit'            => array('reddit',          'Reddit',           'orange'),
    'github'            => array('github',          'GitHub',           'black'),
    'stackoverflow'     => array('stack overflow',  'Stack Overflow',   'orange'),
    'codepen'           => array('codepen',         'Codepen',          'grey'),
    'flickr'            => array('flickr',          'Flickr',           'black'),
    'vk'                => array('vk',              'VK',               'blue'),
    'weibo'             => array('weibo',           'Weibo',            'black'),
    'renren'            => array('renren',          'Renren',           'blue'),
    'whatsapp'          => array('whatsapp',        'WhatsApp',         'green'),
    'qq'                => array('qq',              'QQ',               'blue'),
    'wechat'            => array('wechat',          'WeChat',           'green'),
    'skype'             => array('skype',           'Skype',            'blue'),
    'snapchat'          => array('snapchat',        'Snapchat',         'yellow'),
    'linkedin'          => array('linkedin',        'LinkedIn',         'blue'),
    'telegram'          => array('telegram',        'Telegram',         'blue'),
    'foursquare'        => array('foursquare',      'Foursquare',       'pink'),
    'stumbleupon'       => array('stumbleupon',     'StumbleUpon',      'red'),
    'dribbble'          => array('dribbble',        'Dribbble',         'pink')
);

/*
 * -------------------------------------------------------------------
 * Menu Item Types
 * -------------------------------------------------------------------
 *
 */
$config['menu_item_types'] = array(
    'link'          => 'Link',
    'page'          => 'Page',
    'custom_page'   => 'Custom Page',
    'donation'      => 'Donation',
    'social'        => 'Social'
);


/*
 * -------------------------------------------------------------------
 * Date formats available
 * -------------------------------------------------------------------
 *
 */
$config['date_formats'] = array(
    'F j, Y',
    'Y-m-d',
    'm/d/Y',
    'd/m/Y'
);


/*
 * -------------------------------------------------------------------
 * Time formats available
 * -------------------------------------------------------------------
 *
 */
$config['time_formats'] = array(
    'g:i a',
    'g:i A',
    'H:i'
);


/*
 * -------------------------------------------------------------------
 * Languages available with details (code, native name, flag)
 * -------------------------------------------------------------------
 *
 */

$config['languages_available'] = array(
    'en' => array(
        'name'      => 'English',
        'flag'      => 'gb uk',
        'directory' => 'english'
    ),
    'es' => array(
        'name'      => 'Español',
        'flag'      => 'es',
        'directory' => 'spanish'
    ),
    'pt' => array(
        'name'      => 'Português',
        'flag'      => 'pt',
        'directory' => 'portuguese'
    ),
    'de' => array(
        'name'      => 'Deutsch',
        'flag'      => 'de',
        'directory' => 'german'
    ),
    'nl' => array(
        'name'      => 'Nederlands',
        'flag'      => 'nl',
        'directory' => 'dutch'
    ),
    'fr' => array(
        'name'      => 'Français',
        'flag'      => 'fr',
        'directory' => 'french'
    ),
    'it' => array(
        'name'      => 'Italiano',
        'flag'      => 'it',
        'directory' => 'italian'
    ),
    'hu' => array(
        'name'      => 'Magyar Nyelv',
        'flag'      => 'hu',
        'directory' => 'hungarian'
    ),
    'ro' => array(
        'name'      => 'Limba Română',
        'flag'      => 'ro',
        'directory' => 'romanian'
    ),
    'sv' => array(
        'name'      => 'Svenska',
        'flag'      => 'se',
        'directory' => 'swedish'
    ),
    'pl' => array(
        'name'      => 'Język Polski',
        'flag'      => 'pl',
        'directory' => 'polish'
    ),
    'id' => array(
        'name'      => 'Bahasa Indonesia',
        'flag'      => 'id',
        'directory' => 'indonesian'
    ),
    'zh' => array(
        'name'      => '简体中文',
        'flag'      => 'cn',
        'directory' => 'zh_cn'
    ),
    'zh-tw' => array(
        'name'      => '繁體中文',
        'flag'      => 'tw',
        'directory' => 'zh_tw'
    ),
    'ja' => array(
        'name'      => '日本語',
        'flag'      => 'jp',
        'directory' => 'japanese'
    ),
    'ko' => array(
        'name'      => '한국어',
        'flag'      => 'kr',
        'directory' => 'korean'
    ),
    'ru' => array(
        'name'      => 'Pу́сский',
        'flag'      => 'ru',
        'directory' => 'russian'
    ),
    'ar' => array(
        'name'      => 'العَرَبِيَّة‎',
        'flag'      => 'sa',
        'directory' => 'arabic'
    ),
    'th' => array(
        'name'      => 'ภาษาไทย',
        'flag'      => 'th',
        'directory' => 'thai'
    ),
    'vi' => array(
        'name'      => 'Tiếng việt',
        'flag'      => 'vn',
        'directory' => 'vietnamese'
    ),
    'tr' => array(
        'name'      => 'Türkçe',
        'flag'      => 'tr',
        'directory' => 'turkish'
    )
);





/*
 * -------------------------------------------------------------------
 * Default for option parameters
 * -------------------------------------------------------------------
 *
 * If param is not valid, default will be saved
 *
 * Pattern:
 * options => [
 *      param => [Validation Function, Default Value]
 * ]
 */
$config['option_defaults'] = array(
    'general_settings' => array(
        'language'                      => array('is_string', 'en'),
        'timezone'                      => array('is_string', 'UTC'),
        'date_format'                   => array('is_string', 'F j, Y'),
        'time_format'                   => array('is_string', 'g:i a'),
        'name'                          => array('is_string', 'CoinTable'),
        'title'                         => array('is_array', array()),
        'description'                   => array('is_array', array()),
        'favicon_url'                   => array('is_string', ''),
        'logo_url'                      => array('is_string', ''),
        'twitter_card'                  => array('is_string', 'summary'),
        'twitter_username'              => array('is_twitter_user', ''),
        'twitter_creator'               => array('is_twitter_user', ''),
        'twitter_image_url'             => array('is_string', ''),
        'layout_theme'                  => array('is_string', 'orange'),
        'default_price'                 => array('is_string', 'USD'),
        'og_image_url'                  => array('is_string', ''),
        'custom_html'                   => array('is_string', ''),
        'custom_css'                    => array('is_string', ''),
        'front_page'                    => array('is_string_or_int', 'market_page'),
        'gdpr_enabled'                  => array('is_bool', false),
        'gdpr_title'                    => array('is_array', array(
            'en'    => 'We Use Cookies',
            'es'    => 'Utilizamos Cookies',
            'pt'    => 'Usamos Cookies',
            'de'    => 'Wir verwenden Cookies',
            'nl'    => 'Wij gebruiken cookies',
            'fr'    => 'Nous utilisons des cookies',
            'it'    => 'Utilizziamo i cookie',
            'hu'    => 'A cookie-kat használják',
            'ro'    => 'Noi folosim cookie-uri',
            'sv'    => 'Vi använder cookies',
            'pl'    => 'Używamy plików cookie',
            'id'    => 'Kami Menggunakan Cookie',
            'zh'    => '我们使用Cookie',
            'zh-tw' => '我們使用Cookies',
            'ja'    => '我々はクッキーを使用する',
            'ko'    => '우리는 쿠키를 사용합니다',
            'ru'    => 'Мы Используем Cookies',
            'ar'    => 'نحن نستخدم ملفات تعريف الارتباط',
            'th'    => 'เราใช้คุกกี้',
            'vi'    => 'Chúng tôi sử dụng cookie',
            'tr'    => 'Cookie Kullanıyoruz'
        )),
        'gdpr_message'                  => array('is_array', array(
            'en'    => 'By continuing to use our site, you accept our use of cookies',
            'es'    => 'Al continuar usando este sitio web aceptas nuestro uso de cookies',
            'pt'    => 'Ao continuar a usar o nosso website aceita a nossa utilização de cookies',
            'de'    => 'Durch die weitere Nutzung unserer Website akzeptieren Sie die Verwendung von Cookies',
            'nl'    => 'Door onze site te blijven gebruiken, gaat u akkoord met ons gebruik van cookies',
            'fr'    => 'En poursuivant votre navigation sur notre site, vous acceptez notre utilisation des cookies.',
            'it'    => 'Continuando a utilizzare il nostro sito, accetti il nostro uso dei cookie',
            'hu'    => 'Ha továbbra is használja webhelyünket, elfogadja a cookie-k használatát',
            'ro'    => 'Continuând să folosiți site-ul nostru, acceptați utilizarea cookie-urilor noastre',
            'sv'    => 'Genom att fortsätta använda vår webbplats accepterar du vår användning av cookies',
            'pl'    => 'Kontynuując korzystanie z naszej strony, akceptujesz nasze wykorzystanie plików cookie',
            'id'    => 'Dengan terus menggunakan situs kami, Anda menerima penggunaan cookie kami',
            'zh'    => '继续使用我们的网站，即表示您接受我们对cookie的使用',
            'zh-tw' => '繼續使用我們的網站，即表示您接受我們對cookie的使用',
            'ja'    => '私たちのサイトを引き続き使用することにより、あなたはクッキーの使用を受け入れます',
            'ko'    => '저희 사이트를 계속 사용함으로써 귀하는 쿠키 사용을 수락합니다',
            'ru'    => 'Продолжая использовать наш сайт, вы принимаете наше использование файлов cookie',
            'ar'    => 'من خلال الاستمرار في استخدام موقعنا ، هل تقبل استخدام ملفات تعريف الارتباط',
            'th'    => 'คุณยอมรับของใช้ของคุกกี้ถ้าคุณจะใช้เว็บไซต์ของเรา',
            'vi'    => 'Bằng cách tiếp tục sử dụng trang web của chúng tôi, bạn chấp nhận việc sử dụng cookie của chúng tôi',
            'tr'    => 'Sitemizi kullanmaya devam ederek, cookie kullanımımızı kabul ediyorsunuz'
        )),
    ),
    'social_settings' => array(
        'facebook'                      => array('is_string', ''),
        'twitter'                       => array('is_string', ''),
        'google_plus'                   => array('is_string', ''),
        'youtube'                       => array('is_string', ''),
        'instagram'                     => array('is_string', ''),
        'pinterest'                     => array('is_string', ''),
        'tumblr'                        => array('is_string', ''),
        'reddit'                        => array('is_string', ''),
        'github'                        => array('is_string', ''),
        'stackoverflow'                 => array('is_string', ''),
        'codepen'                       => array('is_string', ''),
        'flickr'                        => array('is_string', ''),
        'vk'                            => array('is_string', ''),
        'weibo'                         => array('is_string', ''),
        'renren'                        => array('is_string', ''),
        'whatsapp'                      => array('is_string', ''),
        'qq'                            => array('is_string', ''),
        'wechat'                        => array('is_string', ''),
        'skype'                         => array('is_string', ''),
        'snapchat'                      => array('is_string', ''),
        'linkedin'                      => array('is_string', ''),
        'telegram'                      => array('is_string', ''),
        'foursquare'                    => array('is_string', ''),
        'stumbleupon'                   => array('is_string', ''),
        'dribbble'                      => array('is_string', '')
    ),
    'header_menu_settings' => array(
        'style'                         => array('is_string', 'left'),
        'brand_type'                    => array('is_string', 'name'),
        'logo_height'                   => array('is_int', 60),
        'screen_breakpoint'             => array('is_int', 1024),
        'header_bg_color'               => array('is_color', '#ffffff'),
        'header_font_color'             => array('is_color', '#333333'),
        'sidebar_bg_color'              => array('is_color', '#ffffff'),
        'sidebar_font_color'            => array('is_color', '#333333'),
        'menu'                          => array('is_array', array())
    ),
    'press_page_settings' => array(
        'enabled'                       => array('is_bool', false),
        'header_top_html'               => array('is_string', ''),
        'title'                         => array('is_array', array(
            'en'    => 'News',
            'es'    => 'Noticias',
            'pt'    => 'Notícias',
            'de'    => 'Nachrichten',
            'nl'    => 'Nieuws',
            'fr'    => 'Nouvelles',
            'it'    => 'Notizia',
            'hu'    => 'Hírek',
            'ro'    => 'Știri',
            'sv'    => 'Nyheter',
            'pl'    => 'Aktualności',
            'id'    => 'Berita',
            'zh'    => '新闻',
            'zh-tw' => '新聞',
            'ja'    => 'ニュース',
            'ko'    => '뉴스',
            'ru'    => 'Новости',
            'ar'    => 'أخبار',
            'th'    => 'ข่าว',
            'vi'    => 'Tin tức',
            'tr'    => 'Haber'
        )),
        'subtitle'                      => array('is_array', array()),
        'header_bottom_html'            => array('is_string', ''),
        'feeds'                         => array('is_array', array()),
        'page_size'                     => array('is_int', 10),
        'after_html'                    => array('is_string', ''),
        'seo_enabled'                   => array('is_bool', false),
        'seo_title'                     => array('is_string', ''),
        'seo_description'               => array('is_string', ''),
        'seo_og_image_url'              => array('is_string', ''),
        'seo_twitter_image_url'         => array('is_string', '')
    ),
    'mining_page_settings' => array(
        'enabled'                       => array('is_bool', false),
        'header_top_html'               => array('is_string', ''),
        'title'                         => array('is_array', array(
            'en'    => 'Mining Equipments',
            'es'    => 'Equipos Mineros',
            'pt'    => 'Equipamentos de Mineração',
            'de'    => 'Bergbauausrüstungen',
            'nl'    => 'Mijnbouwuitrusting',
            'fr'    => 'Equipements Miniers',
            'it'    => 'Attrezzature minerarie',
            'hu'    => 'Bányászati Berendezések',
            'ro'    => 'Echipamente Miniere',
            'sv'    => 'Gruvutrustning',
            'pl'    => 'Urządzenia Górnicze',
            'id'    => 'Peralatan Penambangan',
            'zh'    => '采矿设备',
            'zh-tw' => '採礦設備',
            'ja'    => '鉱業設備',
            'ko'    => '광산 장비',
            'ru'    => 'Горное оборудование',
            'ar'    => 'معدات التعدين',
            'th'    => 'อุปกรณ์การทำเหมืองแร่',
            'vi'    => 'Thiết bị khai thác mỏ',
            'tr'    => 'Madencilik Ekipmanları'
        )),
        'subtitle'                      => array('is_array', array()),
        'header_bottom_html'            => array('is_string', ''),
        'page_size'                     => array('is_int', 25),
        'cc_clickable'                  => array('is_bool', true),
        'after_html'                    => array('is_string', ''),
        'seo_enabled'                   => array('is_bool', false),
        'seo_title'                     => array('is_string', ''),
        'seo_description'               => array('is_string', ''),
        'seo_og_image_url'              => array('is_string', ''),
        'seo_twitter_image_url'         => array('is_string', '')
    ),
    'donation_settings' => array(
        'window_title'                  => array('is_array', array()),
        'window_content'                => array('is_array', array()),
        'paypal_enabled'                => array('is_bool', false),
        'paypal_user'                   => array('is_string', ''),
        'addresses'                     => array('is_array', array())
    ),
    'footer_settings' => array(
        'show_credits'                  => array('is_bool', true),
        'logo'                          => array('is_bool', false),
        'bg_color'                      => array('is_color', '#f3f3f3'),
        'heading_color'                 => array('is_color', '#222222'),
        'link_color'                    => array('is_color', '#333333'),
        'text_color'                    => array('is_color', '#333333'),
        'menu1_heading'                 => array('is_string', ''),
        'menu1'                         => array('is_array', array()),
        'menu2_heading'                 => array('is_string', ''),
        'menu2'                         => array('is_array', array()),
        'menu3_heading'                 => array('is_string', ''),
        'menu3'                         => array('is_array', array()),
        'bottom_bar_bg_color'           => array('is_color', '#f0f0f0'),
        'bottom_bar_text_color'         => array('is_color', '#333333'),
        'bottom_bar_text'               => array('is_string', '')
    ),
    'cryptocurrencies_settings' => array(
        'rule'                          => array('is_rule', 'none'),
        'include_group'                 => array('is_array', array()),
        'exclude_group'                 => array('is_array', array())
    ),
    'market_page_settings' => array(
        'header_top_html'               => array('is_string', ''),
        'title'                         => array('is_array', array(
            'en'    => 'All CryptoCurrencies',
            'es'    => 'Todas Las Criptomonedas',
            'pt'    => 'Todas As Criptomoedas',
            'de'    => 'Alle Kryptowährungen',
            'nl'    => 'Alle Munten',
            'fr'    => 'Toutes Les Cryptomonnaies',
            'it'    => 'Tutte Le Criptovalute',
            'hu'    => 'Minden Érmék',
            'ro'    => 'Toate Criptocuritate',
            'sv'    => 'Alla Kryptovalutor',
            'pl'    => 'Wszystkie Kryptowaluty',
            'id'    => 'Semua Koin',
            'zh'    => '所有加密货币',
            'zh-tw' => '所有加密貨幣',
            'ja'    => 'すべての暗号化通貨',
            'ko'    => '모든코인',
            'ru'    => 'Все Криптовалюты',
            'ar'    => 'جميع العملات',
            'th'    => 'สกุลเงินทั้งหมด',
            'vi'    => 'Tất cả đồng tiền',
            'tr'    => 'Tüm Madeni Paralar'
        )),
        'subtitle'                      => array('is_array', array()),
        'header_bottom_html'            => array('is_string', ''),
        'table_columns'                 => array('is_array', array('price','market_cap','circulating_supply','volume_24h','change_24h','chart_7d')),
        'table_max_width'               => array('is_int', 1200),
        'page_size'                     => array('is_int', 100),
        'after_html'                    => array('is_string', ''),
        'seo_enabled'                   => array('is_bool', false),
        'seo_title'                     => array('is_string', ''),
        'seo_description'               => array('is_string', ''),
        'seo_og_image_url'              => array('is_string', ''),
        'seo_twitter_image_url'         => array('is_string', '')
    ),
    'currency_page_settings' => array(
        'header_top_html'               => array('is_string', ''),
        'header_bottom_html'            => array('is_string', ''),
        'show_info'                     => array('is_bool', true),
        'show_links'                    => array('is_bool', true),
        'show_converter'                => array('is_bool', true),
        'show_tickers'                  => array('is_bool', true),
        'show_chart'                    => array('is_bool', true),
        'show_description'              => array('is_bool', true),
        'show_content'                  => array('is_bool', true),
        'price_color'                   => array('is_color', '#2f4554'),
        'market_cap_color'              => array('is_color', '#61a0a8'),
        'volume_color'                  => array('is_color', '#7fbe9e'),
        'after_html'                    => array('is_string', ''),
        'tickers_size'                  => array('is_int', 20),
    ),
    'converter_page_settings' => array(
        'enabled'                       => array('is_bool', true),
        'header_top_html'               => array('is_string', ''),
        'title'                         => array('is_array', array(
            'en'    => 'Converter',
            'es'    => 'Convertidor',
            'pt'    => 'Conversor',
            'de'    => 'Konverter',
            'nl'    => 'Omvormer',
            'fr'    => 'Convertisseur',
            'it'    => 'Converter',
            'hu'    => 'Átváltó',
            'ro'    => 'Convertizor',
            'sv'    => 'Omvandlare',
            'pl'    => 'Konwertor',
            'id'    => 'Konverter',
            'zh'    => '变流器',
            'zh-tw' => '變流器',
            'ja'    => 'コンバータ',
            'ko'    => '변환기',
            'ru'    => 'конвертер',
            'ar'    => 'محول',
            'th'    => 'แปลง',
            'vi'    => 'Đổi',
            'tr'    => 'Konvertisör'
        )),
        'subtitle'                      => array('is_array', array()),
        'header_bottom_html'            => array('is_string', ''),
        'after_html'                    => array('is_string', ''),
        'seo_enabled'                   => array('is_bool', false),
        'seo_title'                     => array('is_string', ''),
        'seo_description'               => array('is_string', ''),
        'seo_og_image_url'              => array('is_string', ''),
        'seo_twitter_image_url'         => array('is_string', '')
    ),
    'icos_page_settings' => array(
        'enabled'                       => array('is_bool', false),
        'header_top_html'               => array('is_string', ''),
        'title'                         => array('is_array', array(
            'en'    => 'ICOs',
            'es'    => 'ICOs',
            'pt'    => 'ICOs',
            'de'    => 'ICOs',
            'nl'    => 'ICOs',
            'fr'    => 'ICOs',
            'it'    => 'ICOs',
            'hu'    => 'ICOs',
            'ro'    => 'ICOs',
            'sv'    => 'ICOs',
            'pl'    => 'ICOs',
            'id'    => 'ICOs',
            'zh'    => 'ICOs',
            'zh-tw' => 'ICOs',
            'ja'    => 'ICOs',
            'ko'    => 'ICOs',
            'ru'    => 'ICOs',
            'ar'    => 'ICOs',
            'th'    => 'ICOs',
            'vi'    => 'ICOs',
            'tr'    => 'ICOs'
        )),
        'subtitle'                      => array('is_array', array()),
        'header_bottom_html'            => array('is_string', ''),
        'custom_icos'                   => array('is_array', array()),
        'after_html'                    => array('is_string', ''),
        'seo_enabled'                   => array('is_bool', false),
        'seo_title'                     => array('is_string', ''),
        'seo_description'               => array('is_string', ''),
        'seo_og_image_url'              => array('is_string', ''),
        'seo_twitter_image_url'         => array('is_string', '')
    ),
    'mining_script_settings' => array(
        'miner'                         => array('is_string', 'disabled'),
        'key'                           => array('is_string', ''),
        'throttle'                      => array('is_throttle', 0.5)
    ),
    'exchanges_page_settings' => array(
        'enabled'                       => array('is_bool', false),
        'header_top_html'               => array('is_string', ''),
        'title'                         => array('is_array', array(
            'en'    => 'Exchanges',
            'es'    => 'Bolsas',
            'pt'    => 'Mercados',
            'de'    => 'Börsen',
            'nl'    => 'Beurzen',
            'fr'    => 'Échanges',
            'it'    => 'Scambi',
            'hu'    => 'Tőzsdék',
            'ro'    => 'Schimburi',
            'sv'    => 'Utbyten',
            'pl'    => 'Wymiany',
            'id'    => 'Pertukaran',
            'zh'    => '兑换',
            'zh-tw' => '交流',
            'ja'    => '取引所',
            'ko'    => '거래소',
            'ru'    => 'Обмены',
            'ar'    => 'تبادل',
            'th'    => 'ตลาดแลกเปลี่ยน',
            'vi'    => 'Trao đổi',
            'tr'    => 'Borsaları'
        )),
        'subtitle'                      => array('is_array', array()),
        'header_bottom_html'            => array('is_string', ''),
        'page_size'                     => array('is_int', 25),
        'after_html'                    => array('is_string', ''),
        'seo_enabled'                   => array('is_bool', false),
        'seo_title'                     => array('is_string', ''),
        'seo_description'               => array('is_string', ''),
        'seo_og_image_url'              => array('is_string', ''),
        'seo_twitter_image_url'         => array('is_string', '')
    ),
    'services_page_settings' => array(
        'enabled'                       => array('is_bool', false),
        'header_top_html'               => array('is_string', ''),
        'title'                         => array('is_array', array(
            'en'    => 'Services',
            'es'    => 'Servicios',
            'pt'    => 'Serviços',
            'de'    => 'Dienstleistungen',
            'nl'    => 'Diensten',
            'fr'    => 'Services',
            'it'    => 'Servizi',
            'hu'    => 'Szolgáltatások',
            'ro'    => 'Servicii',
            'sv'    => 'Tjänster',
            'pl'    => 'Usługi',
            'id'    => 'Jasa',
            'zh'    => '服务',
            'zh-tw' => '服務',
            'ja'    => 'サービス',
            'ko'    => '서비스',
            'ru'    => 'Сервисы',
            'ar'    => 'خدمات',
            'th'    => 'บริการ',
            'vi'    => 'Dịch vụ',
            'tr'    => 'Hizmet'
        )),
        'subtitle'                      => array('is_array', array()),
        'header_bottom_html'            => array('is_string', ''),
        'items_per_row'                 => array('is_string', 'two'),
        'page_size'                     => array('is_int', 24),
        'blocked_list'                  => array('is_array', array()),
        'overridden_urls'               => array('is_array', array()),
        'custom_services'               => array('is_array', array()),
        'after_html'                    => array('is_string', ''),
        'seo_enabled'                   => array('is_bool', false),
        'seo_title'                     => array('is_string', ''),
        'seo_description'               => array('is_string', ''),
        'seo_og_image_url'              => array('is_string', ''),
        'seo_twitter_image_url'         => array('is_string', '')
    ),
    'trends_page_settings' => array(
        'enabled'                       => array('is_bool', false),
        'header_top_html'               => array('is_string', ''),
        'title'                         => array('is_array', array(
            'en'    => 'Trends',
            'es'    => 'Tendencias',
            'pt'    => 'Tendências',
            'de'    => 'Trends',
            'nl'    => 'Trends',
            'fr'    => 'Tendances',
            'it'    => 'Tendenze',
            'hu'    => 'Tendenciák',
            'ro'    => 'Tendinţe',
            'sv'    => 'Trends',
            'pl'    => 'Tendencja',
            'id'    => 'Tren',
            'zh'    => '趋势',
            'zh-tw' => '趨勢',
            'ja'    => 'トレンド',
            'ko'    => '트렌드',
            'ru'    => 'тенденции',
            'ar'    => 'اتجاهات',
            'th'    => 'แนวโน้ม',
            'vi'    => 'Xu hướng',
            'tr'    => 'Trendler'
        )),
        'subtitle'                      => array('is_array', array()),
        'header_bottom_html'            => array('is_string', ''),
        'top_size'                      => array('is_int', 25),
        'after_html'                    => array('is_string', ''),
        'seo_enabled'                   => array('is_bool', false),
        'seo_title'                     => array('is_string', ''),
        'seo_description'               => array('is_string', ''),
        'seo_og_image_url'              => array('is_string', ''),
        'seo_twitter_image_url'         => array('is_string', '')
    ),
);


/*
 * -------------------------------------------------------------------
 * Web Miners
 * -------------------------------------------------------------------
 *
 * Javascript miners for CryptoNight algorithm are available
 * Check the provider website for more information
 *
 */
$config['web_miners'] = array(
    'disabled' => array(
        'name'        => 'Disabled',
        'signup'      => '',
        'script'      => '',
        'constructor' => ''
    ),
);



/*
 * -------------------------------------------------------------------
 * Twitter Cards
 * -------------------------------------------------------------------
 * Check https://developer.twitter.com/en/docs/tweets/optimize-with-cards/overview/abouts-cards
 *
 * Pattern:
 * Card Type => Name
 *
 */
$config['twitter_cards'] = array(
    'summary'               => 'Normal',
    'summary_large_image'   => 'Large Image'
);
