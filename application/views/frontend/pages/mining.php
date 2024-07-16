<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>


<?php if($header_top_html) : ?>
    <div class="ui basic segment">
        <?php echo $header_top_html; ?>
    </div>
<?php endif; ?>

<div class="ui basic segment container">
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




<div class="ui grid">
    <div class="centered row">
        <div id="mining-wrapper" class="fifteen wide column">
            <div id="mining-form" class="ui form">
                <div class="field">
                    <div class="ui styled fluid accordion">
                        <?php foreach (array('cryptocurrencies','types','sources') as $param_name) :?>
                            <div class="title"><i class="dropdown icon"></i> <?php et($param_name); ?></div>
                            <div class="<?php echo $param_name; ?> content">
                                <div class="inline fields">
                                    <?php foreach ($results->$param_name as $code => $name) :?>
                                        <?php if(is_array($name)) $name = $name[0]; ?>
                                        <div class="three wide field">
                                            <div class="ui checkbox">
                                                <?php $checked = !property_exists($results->params, $param_name) || (is_array($results->params->$param_name) && in_array($code, $results->params->$param_name)); ?>
                                                <input <?php if($checked) echo 'checked'; ?> type="checkbox" name="<?php echo $param_name; ?>[]" value="<?php echo $code; ?>">
                                                <label><?php echo $name; ?></label>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <div class="field">
                                    <div class="cursor-pointer select-all-button ui label"><?php et('select_all'); ?></div>
                                    <div class="cursor-pointer clear-button ui label"><?php et('clear'); ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="field" style="text-align: right">
                    <div class="submit-button ui <?php echo $theme; ?> labeled icon button">
                        <i class="search icon"></i>
                        <?php et('search'); ?>
                    </div>
                </div>

                <div class="two fields">
                    <div class="field">
                        <h2 class="ui header"><?php et('results'); ?> (<?php echo $results->total; ?>)</h2>
                    </div>
                    <div class="field">
                        <h4 class="ui right floated header">
                            <?php et('order_by'); ?>
                            <div id="mining-order" class="ui inline dropdown">
                                <input type="hidden">
                                <div class="text">
                                    <i class="<?php echo $results->params->desc ? 'arrow alternate circle down' : 'arrow alternate circle up outline'; ?> icon"></i>
                                    <?php et($results->params->order); ?>
                                </div>
                                <div class="menu">
                                    <div class="item" data-value="-relevance">
                                        <?php et('relevance'); ?>
                                    </div>
                                    <div class="item" data-value="-name">
                                        <i class="arrow alternate circle down icon"></i>
                                        <?php et('name'); ?>
                                    </div>
                                    <div class="item" data-value="+name">
                                        <i class="arrow alternate circle up outline icon"></i>
                                        <?php et('name'); ?>
                                    </div>
                                    <div class="item" data-value="-cryptocurrency">
                                        <i class="arrow alternate circle down icon"></i>
                                        <?php et('cryptocurrency'); ?>
                                    </div>
                                    <div class="item" data-value="+cryptocurrency">
                                        <i class="arrow alternate circle up outline icon"></i>
                                        <?php et('cryptocurrency'); ?>
                                    </div>
                                    <div class="item" data-value="-price">
                                        <i class="arrow alternate circle down icon"></i>
                                        <?php et('price'); ?>
                                    </div>
                                    <div class="item" data-value="+price">
                                        <i class="arrow alternate circle up outline icon"></i>
                                        <?php et('price'); ?>
                                    </div>
                                    <div class="item" data-value="-hashrate">
                                        <i class="arrow alternate circle down icon"></i>
                                        <?php et('hashrate'); ?>
                                    </div>
                                    <div class="item" data-value="+hashrate">
                                        <i class="arrow alternate circle up outline icon"></i>
                                        <?php et('hashrate'); ?>
                                    </div>
                                    <div class="item" data-value="-power">
                                        <i class="arrow alternate circle down icon"></i>
                                        <?php et('power'); ?>
                                    </div>
                                    <div class="item" data-value="+power">
                                        <i class="arrow alternate circle up outline icon"></i>
                                        <?php et('power'); ?>
                                    </div>
                                    <div class="item" data-value="-type">
                                        <i class="arrow alternate circle down icon"></i>
                                        <?php et('type'); ?>
                                    </div>
                                    <div class="item" data-value="+type">
                                        <i class="arrow alternate circle up outline icon"></i>
                                        <?php et('type'); ?>
                                    </div>
                                </div>
                            </div>
                        </h4>
                    </div>
                </div>
            </div>


            <table id="mining-table" class="ui selectable very basic compact large table<?php if($cc_clickable) echo ' clickable'; ?>">
                <thead>
                <tr>
                    <th></th>
                    <th class="center aligned"><?php et('cryptocurrency'); ?></th>
                    <th class="name"><?php et('name'); ?></th>
                    <th class="center aligned"><?php et('type'); ?></th>
                    <th class="center aligned"><?php et('power'); ?></th>
                    <th class="center aligned"><?php et('hashrate'); ?></th>
                    <th class="center aligned"><?php et('price'); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($results->pagination->items as $e) :?>
                <?php $cryptocurrency = $results->cryptocurrencies[$e->mined_code]; ?>
                    <tr data-url="<?php echo $cc_clickable? $e->url : null; ?>">
                        <td class="image">
                            <img class="ui centered small image" src="<?php echo $e->image; ?>">
                        </td>
                        <td class="center aligned">
                            <img class="ui centered mini image" alt="<?php echo $cryptocurrency[0]; ?>" src="<?php echo $cryptocurrency[1]; ?>">
                            <div class="target-name"><?php echo $cryptocurrency[0]; ?></div>
                            <div class="ui basic large fluid label"><?php echo $e->algorithm; ?></div>
                        </td>
                        <td class="name">
                            <h4 class="ui header">
                                <?php echo $results->sources[$e->source_code]; ?>
                                <div class="sub header"><?php echo $e->name; ?></div>
                            </h4>
                        </td>
                        <td class="center aligned"><span class="ui basic large fluid label"><?php echo $results->types[$e->type_code]; ?></span></td>
                        <td class="single line center aligned"><?php echo $e->power; ?> W</td>
                        <td class="single line center aligned"><?php echo $e->hash_format; ?></td>
                        <td class="center aligned"><strong><?php echo $e->price; ?></strong></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
                <?php if($results->pagination->pages > 1) :?>
                    <tfoot>
                    <tr>
                        <td colspan="7"><?php paginationMenu($results->pagination, $redirect, $results->params); ?></td>
                    </tr>
                    </tfoot>
                <?php endif; ?>
            </table>
        </div>
    </div>
</div>


<?php if($after_html) : ?>
    <div class="ui basic segment">
        <?php echo $after_html; ?>
    </div>
<?php endif; ?>

