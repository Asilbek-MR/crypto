<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <?php if($favicon) : ?>
    <link rel="shorcut icon" type="image/png" href="<?php echo $favicon; ?>">
    <?php endif; ?>

    <title><?php echo $name ?> <?php if($title) echo "- $title"; ?></title>
    <meta name="description" content="<?php echo $description; ?>"/>
    <link rel="canonical" href="<?php echo $url; ?>" />


    <meta property="og:type" content="<?php echo $type; ?>"/>
    <meta property="og:url" content="<?php echo $url; ?>"/>
    <meta property="og:title" content="<?php echo $title; ?>"/>
    <meta property="og:description" content="<?php echo $description; ?>"/>
    <meta property="og:site_name" content="<?php echo $name; ?>"/>
    <?php if($og_image) : ?>
    <meta property="og:image" content="<?php echo $og_image; ?>"/>
    <?php endif; ?>


    <meta name="twitter:card" content="<?php echo $twitter_card; ?>"/>
    <meta name="twitter:description" content="<?php echo $description; ?>"/>
    <meta name="twitter:title" content="<?php echo $title; ?>"/>
    <meta name="twitter:url" content="<?php echo $url; ?>"/>
    <?php if($twitter_image) : ?>
    <meta name="twitter:image" content="<?php echo $twitter_image; ?>"/>
    <?php endif; ?>
    <?php if($twitter_username) : ?>
        <meta name="twitter:site" content="<?php echo $twitter_username; ?>"/>
    <?php endif; ?>
    <?php if($twitter_creator) : ?>
        <meta name="twitter:creator" content="<?php echo $twitter_creator; ?>" />
    <?php endif; ?>

    <meta name="theme-color" content="<?php echo $theme_color; ?>">

    <?php foreach ($css as $file) : ?>
        <link rel="stylesheet" href="<?php echo $file; ?>">
    <?php endforeach; ?>

    <style>
        #top-menu {background-color: <?php echo $header['header_bg_color']; ?>;}
        #top-menu .item {color: <?php echo $header['header_font_color']; ?>;}

        <?php if($header['style'] === 'left' || $header['style'] === 'right') : ?>
        @media only screen and (max-width: <?php echo $header['screen_breakpoint']; ?>px) {
            #top-menu .select-item, #top-menu .menu-item  {display: none;}
        }

        @media only screen and (min-width: <?php echo $header['screen_breakpoint']; ?>px) {
            #sidebar-menu-toggle  {display: none;}
        }
        <?php endif; ?>

        #sidebar-menu {background-color: <?php echo $header['sidebar_bg_color']; ?>;}
        #sidebar-menu .item {color: <?php echo $header['sidebar_font_color']; ?>;}
        #header-logo {height: <?php echo $header['logo_height']; ?>px; }
        #stats {padding-top: <?php echo $padding_top; ?>;}
        #footer {background-color: <?php echo $footer['bg_color']; ?>;}
        #footer-main h1, #footer-main h1 .sub.header {color: <?php echo $footer['text_color']; ?>;}
        #footer-main h3 {color: <?php echo $footer['heading_color']; ?>;}
        #footer-main .item, #footer-main .item i {color: <?php echo $footer['link_color']; ?>;}
        #footer-bottom-bar, #footer-bottom-bar a {
            background-color: <?php echo $footer['bottom_bar_bg_color']; ?>;
            color: <?php echo $footer['bottom_bar_text_color']; ?>;
        }
    </style>

    <style><?php echo $custom_css; ?></style>
</head>
<body>

<div id="sidebar-menu" class="ui big vertical sidebar menu <?php echo $sidebar_side; ?>">
    <?php printItems('header', $header['menu'], 'both'); ?>
    <div class="item">
        <?php priceCurrencySelect('fluid'); ?>
    </div>
    <div class="item">
        <?php languageSelect($lang, 'fluid'); ?>
    </div>
</div>

<nav id="top-menu" class="ui borderless fluid fixed menu">
    <?php if($header['style'] === 'left') : ?>

        <a id="sidebar-menu-toggle" class="item"><i class="sidebar icon"></i></a>
        <?php printHeaderBrand($header['brand_type'], $logo, $name); ?>
        <div class="right menu">
            <?php printItems('header', $header['menu'], 'icon'); ?>
            <div class="select-item item">
                <?php priceCurrencySelect(); ?>
            </div>
            <div class="select-item item">
                <?php languageSelect($lang); ?>
            </div>
        </div>

    <?php elseif($header['style'] === 'right') : ?>

        <div class="select-item item">
            <?php priceCurrencySelect(); ?>
        </div>
        <div class="select-item item">
            <?php languageSelect($lang); ?>
        </div>
        <?php printItems('header', $header['menu'], 'icon'); ?>
        <div class="right menu">
            <?php printHeaderBrand($header['brand_type'], $logo, $name); ?>
            <a id="sidebar-menu-toggle" class="item"><i class="sidebar icon"></i></a>
        </div>

    <?php elseif($header['style'] === 'left_sidebar') : ?>

        <a id="sidebar-menu-toggle" class="item"><i class="sidebar icon"></i></a>
        <?php printHeaderBrand($header['brand_type'], $logo, $name); ?>

    <?php elseif($header['style'] === 'right_sidebar') : ?>

        <div class="right menu">
            <?php printHeaderBrand($header['brand_type'], $logo, $name); ?>
            <a id="sidebar-menu-toggle" class="item"><i class="sidebar icon"></i></a>
        </div>

    <?php endif; ?>
</nav>

<div class="pusher">
    <div id="stats" class="ui center aligned basic segment">
        <div class="ui basic labels">
            <a class="ui <?php echo $theme; ?> label" href="<?php echo $urls->market_page; ?>">
                <?php et('cryptocurrencies'); ?>
                <div class="detail"><?php echo $stats->cryptocurrencies; ?></div>
            </a>
            <?php if($stats->exchanges) : ?>
            <a class="ui <?php echo $theme; ?> label" href="<?php echo $urls->exchanges_page; ?>">
                <?php et('exchanges'); ?>
                <div class="detail"><?php echo $stats->exchanges; ?></div>
            </a>
            <?php endif; ?>
            <a class="ui <?php echo $theme; ?> label" href="<?php echo $urls->market_page; ?>">
                <?php et('total_market_cap'); ?>
                <div class="detail"><?php echo $stats->market_cap; ?></div>
            </a>
            <a class="ui <?php echo $theme; ?> label" href="<?php echo $urls->market_page; ?>">
                <?php et('volume_24h'); ?>
                <div class="detail"><?php echo $stats->volume; ?></div>
            </a>
            <a class="ui <?php echo $theme; ?> label" href="<?php echo $urls->market_page; ?>">
                <?php et('dominance'); ?>
                <div class="detail"><?php echo $market_cap_percentages; ?></div>
            </a>
        </div>
    </div>
    <main id="content"><?php echo $content; ?></main>
    <footer id="footer">
        <div class="ui stackable grid">

            <div id="footer-main" class="centered row">
                <div class="middle aligned <?php echo $footer_col_wide; ?> wide column">
                    <div class="ui huge header">
                        <?php if($footer['logo']) : ?>
                            <img class="ui image" src="<?php echo $logo; ?>">
                        <?php endif; ?>
                        <?php echo $name; ?>
                    </div>
                </div>

                <?php foreach(array(1,2,3) as $i) : ?>
                    <?php if(count($footer["menu$i"])) : ?>
                        <div class="center aligned <?php echo $footer_col_wide; ?> wide column">
                            <h3 class="ui header"><?php echo $footer["menu{$i}_heading"]; ?></h3>
                            <div class="ui large list">
                                <?php printItems("footer_$i", $footer["menu$i"], 'both'); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>

            <?php

            $show_credits = $footer['show_credits'];
            $bottom_bar_text = $footer['bottom_bar_text'];

            if($show_credits || strlen($bottom_bar_text)) : ?>
            <div id="footer-bottom-bar" class="row">
                <div class="center aligned sixteen wide column">
                    <div class="ui transparent label">
                        <?php if($footer['bottom_bar_text']) echo $footer['bottom_bar_text']; ?>
                        <?php if($show_credits && strlen($bottom_bar_text)) echo ' - '; ?>
                        <?php if($show_credits) :?>
                            Powered By <a target="_blank" href="https://www.cryptocompare.com/">CryptoCompare</a> & <a href="https://www.coingecko.com/">CoinGecko</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </footer>
</div>

<div id="slide-up" class="ui <?php echo $theme; ?> icon button" style="z-index: 9999; display: none;"><i class="arrow up icon"></i></div>

<div id="donation-box" class="ui modal">
    <div class="scrolling content">
        <div class="ui icon huge message">
            <i class="hand peace icon"></i>
            <div class="content">
                <div class="header"><?php _et($donation['window_title']); ?></div>
                <div><?php _et($donation['window_content']); ?></div>
            </div>
        </div>
        <?php if($donation['paypal_enabled']) : ?>
            <div>
                <a target="_blank" href="https://www.paypal.me/<?php echo $donation['paypal_user']; ?>" class="ui blue large labeled icon button">
                    <i class="paypal icon"></i>
                    Paypal
                </a>
            </div>
        <?php endif; ?>
        <?php if(count($donation['addresses'])) : ?>
            <div class="ui divider"></div>
            <div class="ui form">
                <?php foreach ($donation['addresses'] as $i => $item) : ?>
                    <div class="field">
                        <label><?php echo $item['name']; ?></label>
                        <div class="ui fluid action input">
                            <input type="text" readonly="" value="<?php echo $item['address']; ?>">
                            <button class="ui right icon <?php echo $theme; ?> button">
                                <i class="copy icon"></i>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    <div class="actions">
        <div class="ui ok green button"><?php et('thank_you'); ?></div>
    </div>
</div>


<?php if($gdpr) :?>
<div id="gdpr-message" class="ui large warning message">
    <i class="large close icon"></i>
    <div class="header"><?php echo $gdpr_title; ?></div>
    <div class="content"><?php echo $gdpr_message; ?></div>
</div>
<?php endif; ?>

<script>
    //<![CDATA[
    window.CoinTableConstants = <?php echo json_encode($constants); ?>;
    //]]>
</script>
<?php foreach ($js as $file) : ?>
    <script src="<?php echo $file; ?>"></script>
<?php endforeach; ?>
<?php echo $custom_html; ?>


</body>
</html>
