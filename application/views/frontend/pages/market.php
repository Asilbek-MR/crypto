<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

    <style>
        .wrapper {max-width: <?php echo $table_max_width; ?>px;}
    </style>

<?php if($header_top_html) : ?>
    <div class="ui basic segment">
        <?php echo $header_top_html; ?>
    </div>
<?php endif; ?>

    <div class="ui basic  segment">
        <h1 class="ui centered header">
            <?php _et($title); ?>
            <div class="sub header"><?php _et($subtitle); ?></div>
        </h1>
    </div>

<?php if($header_bottom_html) : ?>
    <div class="ui basic segment">
        <?php echo $header_bottom_html; ?>
    </div>
<?php endif; ?>



<div id="market" class="ui grid">
    <div class="centered row">
        <div class="wrapper fifteen wide column">
            <div id="market-form" class="ui form">

                <div class="field">
                    <div class="ui styled fluid accordion">
                        <div class="title"><i class="dropdown icon"></i> <?php et('cryptocurrencies'); ?></div>
                        <div class="content">

                            <div class="field">
                                <div id="coins-selection" class="ui fluid multiple search selection dropdown">
                                    <div class="text"></div>
                                    <i class="dropdown icon"></i>
                                </div>
                            </div>

                            <div class="field">
                                <div id="coins-clear" class="cursor-pointer clear-button ui label"><?php et('clear'); ?></div>
                            </div>

                        </div>
                        <div class="title"><i class="dropdown icon"></i> <?php et('filter'); ?></div>
                        <div class="content">

                            <div class="three fields">

                                <div class="field">
                                    <label><?php et('market_cap'); ?> (USD)</label>
                                    <input id="market-cap-slider" data-type="double" data-force-edges="true">
                                </div>

                                <div class="field">
                                    <label><?php et('price'); ?> (USD)</label>
                                    <input id="price-slider" data-type="double" data-force-edges="true">
                                </div>

                                <div class="field">
                                    <label><?php et('volume_24h'); ?> (USD)</label>
                                    <input id="volume-slider" data-type="double" data-force-edges="true">
                                </div>
                            </div>

                            <div class="field">
                                <div id="reset-sliders" class="cursor-pointer clear-button ui label"><?php et('reset'); ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="two fields">
                    <div class="field">
                        <div id="save-checkbox" class="ui slider checkbox">
                            <input type="checkbox">
                            <label><?php et('save'); ?></label>
                        </div>
                    </div>

                    <div class="field" style="text-align: right">
                        <div class="submit-button ui <?php echo $theme; ?> labeled icon button">
                            <i class="search icon"></i>
                            <?php et('search'); ?>
                        </div>
                    </div>
                </div>



                <div class="field">
                    <h2 class="ui header"><?php et('results'); ?> (<?php echo $results->total; ?>)</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="centered row">
        <div class="wrapper fifteen wide column">

            <table id="market-table" class="ui single line unstackable very compact table" style="width:100%">
                <thead>
                <tr>
                    <?php
                    columnHeaderOrder('name', t('name'), $redirect, $results->params, 'col-name');

                    foreach ($table_columns as $col) {
                        if($col === 'chart_7d') echo '<th class="col-chart_7d center aligned">' . t('chart_7d') . '</th>';
                        else columnHeaderOrder($col, t($col), $redirect, $results->params, "col-$col right aligned");
                    }

                    ?>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($results->pagination->items as $c) :
                    $change = tableChangeDetails($c['price_usd_change_24h']);
                    $slug = $c['slug'];
                    $url = "$currency_redirect/$slug";
                ?>
                    <tr data-slug="<?php echo $slug; ?>" <?php if(isset($c['tracking_slug'])) echo 'class="warning"'; ?>>
                        <td class="col-name">
                            <a href="<?php echo $url; ?>">
                                <h5 class="ui image header">
                                    <img class="ui image" src="<?php echo $c['image_small'] ?: $placeholder; ?>">
                                    <div class="content">
                                        <?php echo $c['symbol'] ?>
                                        <div class="sub header"><?php echo $c['name'] ?></div>
                                    </div>
                                </h5>
                            </a>
                        </td>
                        <?php foreach ($table_columns as $col) : ?>
                            <?php
                            $classes = "col-$col";

                            if($col !== 'chart_7d') $classes .= ' right aligned';

                            if($col === 'change_24h') $classes .= " $change->class";
                            ?>
                            <td class="<?php  echo $classes; ?>">
                                <?php
                                switch ($col) {

                                    case 'price':
                                        echo "<a href=\"$url\">{$c['price']}</a>";
                                        break;

                                    case 'change_24h':
                                        if($change->icon) echo "<i class=\"$change->icon icon\"></i>";
                                        echo $change->text;
                                        break;

                                    case 'market_cap':
                                        echo $c['market_cap'];
                                        break;

                                    case 'volume_24h':
                                        echo "<a href=\"$url\">{$c['volume']}</a>";
                                        break;

                                    case 'total_supply':
                                        echo "<a href=\"$url\" class=\"ui large transparent label\">" . ($c['total_supply'] ? ct_number_format($c['total_supply']) : '?') ."<div class=\"detail\">{$c['symbol']}</div></a>";
                                        break;

                                    case 'circulating_supply':
                                        echo "<a href=\"$url\" class=\"ui large transparent label\">" . ($c['circulating_supply'] ? ct_number_format($c['circulating_supply']) : '?') ."<div class=\"detail\">{$c['symbol']}</div></a>";
                                        break;

                                    case 'chart_7d':
                                        echo "<div class=\"market-chart\" id=\"chart-$slug\"></div>";
                                        break;
                                }
                                ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
                </tbody>

            </table>
            <div class="ui basic segment">
                <?php paginationMenu($results->pagination, $redirect, $results->params); ?>
            </div>


        </div>
    </div>
</div>


<?php if($after_html) : ?>
    <div class="ui basic segment">
        <?php echo $after_html; ?>
    </div>
<?php endif; ?>