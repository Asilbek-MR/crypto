<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php if(!empty($header_top_html)) : ?>
    <div class="ui basic segment">
        <?php echo $header_top_html; ?>
    </div>
<?php endif; ?>

<div class="ui container">

    <div class="ui stackable two columns grid basic segment">
        <div class="row">
            <div id="currency-header-col" class="column">
                <h1 class="ui header">
                    <img src="<?php echo $coin['image_large'] ?>">
                    <div class="content">
                        <?php echo $coin['name']; ?>
                        <div class="sub header"><?php echo $coin['symbol']; ?></div>
                    </div>
                </h1>
            </div>

            <div class="center aligned column">
                <div class="ui two small statistics">
                    <div id="currency-price" class="statistic">
                        <div class="value"><?php echo $coin['price']; ?></div>
                        <div class="label"><?php echo $price_rate->unit; ?></div>
                    </div>

                    <div class="<?php echo $change_24h->color; ?> mini statistic">
                        <div class="value">
                            <i class="<?php echo $change_24h->icon; ?> icon"></i>
                            <?php echo "$change_24h->text %"; ?>
                        </div>
                        <div class="label"><?php et('change_24h'); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<?php if(!empty($header_bottom_html)) : ?>
    <div class="ui basic segment">
        <?php echo $header_bottom_html; ?>
    </div>
<?php endif; ?>


<div class="ui container">

    <?php if(!empty($show_info)): ?>
    <div id="currency-info" class="ui center aligned segment container">
        <div class="ui large horizontal list">
            <?php if(isset($coin['info']) && isset($coin['info']->genesis_date)): ?>
                <div class="item">
                    <div class="header"><i>Genesis</i></div>
                    <div class="description"><?php echo $coin['info']->genesis_date; ?></div>
                </div>
            <?php endif; ?>
            <div class="item">
                <div class="header"><?php et('market_cap'); ?></div>
                <div class="description"><?php echo $coin['market_cap']; ?></div>
            </div>
            <?php if ( ! empty( $coin['volume_24h_usd'] ) ) : ?>
            <div class="item">
                <div class="header"><?php et('volume_24h'); ?></div>
                <div class="description"><?php echo $coin['volume']; ?></div>
            </div>
            <?php endif; ?>
            <?php if( ! empty( $coin['circulating_supply'] ) ): ?>
            <div class="item">
                <div class="header"><?php et('circulating_supply'); ?></div>
                <div class="description"><?php echo ct_number_format($coin['circulating_supply']); ?></div>
            </div>
            <?php endif; ?>
            <?php if( ! empty( $coin['total_supply'] ) ): ?>
            <div class="item">
                <div class="header"><?php et('total_supply'); ?></div>
                <div class="description"><?php echo ct_number_format($coin['total_supply']); ?></div>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>



    <?php if(isset($coin['info'])) :

        $links = $coin['info']->links;
    ?>
        <?php if(!empty($show_links)): ?>
            <div id="currency-links" class="ui basic segment">

                <div class="ui divided list">
                    <?php if(!empty($links->websites)): ?>
                        <div class="item">
                            <div class="ui horizontal list">
                                <div class="item">
                                    <div class="ui <?php echo $theme; ?> heading-label label"><?php et('website'); ?></div>
                                </div>
                                <?php foreach ($links->websites as $domain => $url) :?>
                                    <div class="item">
                                        <a class="ui basic label" target='_blank' href="<?php echo $url; ?>">
                                            <i class="linkify icon"></i>
                                            <?php echo $domain; ?>
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="item">
                        <div class="ui horizontal list">
                            <div class="item">
                                <div class="ui <?php echo $theme; ?> heading-label label"><?php et('explorers'); ?></div>
                            </div>
                            <?php foreach ($links->blockchain_explorers as $domain => $url) :?>
                                <div class="item">
                                    <a class="ui basic label" target='_blank' href="<?php echo $url; ?>">
                                        <i class="linkify icon"></i>
                                        <?php echo $domain; ?>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="item">
                        <div class="ui horizontal list">
                            <div class="item">
                                <div class="ui <?php echo $theme; ?> heading-label label"><?php et('social'); ?></div>
                            </div>
                            <?php if(isset($links->facebook)): ?>
                                <div class="item">
                                    <a href="<?php echo $links->facebook; ?>" target="_blank" class="ui blue label">
                                        <i class="facebook icon"></i>
                                        Facebook
                                    </a>
                                </div>
                            <?php endif; ?>
                            <?php if(isset($links->twitter)): ?>
                                <div class="item">
                                    <a href="<?php echo $links->twitter; ?>" target="_blank" class="ui teal label">
                                        <i class="twitter icon"></i>
                                        Twitter
                                    </a>
                                </div>
                            <?php endif; ?>
                            <?php if(isset($links->bitcointalk)): ?>
                                <div class="item">
                                    <a href="<?php echo $links->bitcointalk; ?>" target="_blank" class="ui label">
                                        <i class="linkify icon"></i>
                                        BitcoinTalk
                                    </a>
                                </div>
                            <?php endif; ?>
                            <?php if(!empty($links->forums)): ?>
                                <?php foreach ($links->forums as $domain => $url) :?>
                                    <div class="item">
                                        <a class="ui basic label" target='_blank' href="<?php echo $url; ?>">
                                            <i class="linkify icon"></i>
                                            <?php echo $domain; ?>
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    <?php endif; ?>

    <?php if(!empty($show_converter)): ?>
        <div id="currency-converter" class="ui centered padded segment">
            <div class="ui two columns grid">
                <div class="ui vertical divider"><i class="exchange icon"></i></div>
                <div class="centered row">
                    <div class="right aligned column">
                        <div class="ui fluid labeled input">
                            <div class="ui <?php echo $theme; ?> label"><?php echo $coin['symbol'] ?></div>
                            <input class="input-left" type="number" value="1">
                        </div>
                    </div>
                    <div class="column">
                        <div class="ui fluid right labeled input">
                            <input class="input-right" type="number">
                            <div class="ui <?php echo $theme; ?> label"><?php echo $price_rate->unit; ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>


    <?php if(!empty($show_chart)) :?>
    <div id="currency-chart-wrapper">
        <div id="currency-chart-menu" class="ui tiny secondary menu">
            <div class="right menu">
                <div class="link item" data-dataset="1"><?php et('1d'); ?></div>
                <div class="active link item" data-dataset="7"><?php et('7d'); ?></div>
                <div class="link item" data-dataset="30"><?php et('1m'); ?></div>
                <div class="link item" data-dataset="90"><?php et('3m'); ?></div>
                <div class="link item" data-dataset="180"><?php et('6m'); ?></div>
                <div class="link item" data-dataset="365"><?php et('1y'); ?></div>
                <div class="link item" data-dataset="max"><?php et('all'); ?></div>
            </div>
        </div>
        <div id="currency-chart"></div>
    </div>
    <?php endif; ?>

	<?php if(!empty($show_tickers) && empty($coin['tracking_slug'])) :?>
    <div id="currency-tickers" class="ui attached segment">
        <table class="ui very basic table">
            <thead>
            <tr>
                <th>#</th>
                <th><?php et('exchange'); ?></th>
                <th><?php et('pair'); ?></th>
                <th class="right aligned"><?php et('price'); ?></th>
                <th class="right aligned"><?php et('volume_24h'); ?></th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
    <div id="currency-tickers-load-btn" class="ui <?php echo $theme; ?> bottom attached small button">
        <i class="arrow down icon"></i>
    </div>
	<?php endif; ?>

    <?php if(!empty($display_description)): ?>
    <div id="currency-description" class="">
        <h2 class="ui centered header"><?php et('description'); ?></h2>
        <div>
	        <?php echo $display_description; ?>
        </div>
    </div>
    <?php endif; ?>

    <?php if(!empty($display_content)): ?>
    <div id="currency-content" class="ui segment">
        <?php echo $display_content; ?>
    </div>
    <?php endif; ?>

</div>

<?php if(!empty($after_html)) : ?>
    <div class="ui basic segment">
        <?php echo $after_html; ?>
    </div>
<?php endif; ?>
