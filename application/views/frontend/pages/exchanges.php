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

<div class="ui container">

    <table id="exchange-table" class="ui attached single line selectable very basic table">
        <thead>
        <tr>
            <th>#</th>
            <th><?php et('name'); ?></th>
            <th class="right aligned"><?php echo t('trust_score') ?: 'Trust Score'; ?></th>
            <th class="right aligned"><?php et('volume_24h'); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($pagination->items as $exchange) : ?>

            <tr class="item">
                <td class="collapsing">
                    <?php echo $exchange->trust_score_rank;  ?>
                </td>
                <td>
                    <h4 class="ui header">
                        <img src="<?php echo $exchange->image; ?>" class="ui rounded image" style="margin-right: 12px;">
                        <a target="_blank" href="<?php echo $exchange->url; ?>" class="ui header"><?php echo $exchange->name; ?></a>
                    </h4>
                </td>

                <td class="collapsing right aligned">
                    <?php
                        if ($exchange->trust_score > 6) {
                            $color = 'green';
                        } elseif ($exchange->trust_score > 4) {
                            $color = 'orange';
                        } else {
                            $color = 'red';
                        }
                    ?>
                    <div class="ui <?php echo $color; ?> circular label">
                        <?php echo $exchange->trust_score; ?>
                    </div>
                </td>

                <td class="collapsing right aligned volume"><?php echo $exchange->trade_volume; ?></td>
            </tr>

        <?php endforeach; ?>
        </tbody>
        <?php if($pagination->pages > 1) :?>
        <tfoot>
        <tr>
            <td colspan="4"><?php paginationMenu($pagination, $redirect); ?></td>
        </tr>
        </tfoot>
        <?php endif; ?>
    </table>

</div>

<?php if($after_html) : ?>
    <div class="ui basic segment">
        <?php echo $after_html; ?>
    </div>
<?php endif; ?>



