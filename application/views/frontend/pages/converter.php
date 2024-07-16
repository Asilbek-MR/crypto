<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

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

<div id="converter" class="ui container segment">
    <div id="converter-grid" class="ui two columns grid">
        <div class="column">
            <div class="ui fluid labeled input">
                <div class="ui <?php echo $theme; ?> label"><?php et('input'); ?></div>
                <select id="converter-input" class="ui fluid search selection dropdown">
                    <?php foreach ($rates as $code => $rate) {echo "<option value=\"$code\">{$rate->name}</option>";} ?>
                </select>
            </div>
        </div>
        <div class="column">
            <div class="ui fluid labeled input">
                <div class="ui <?php echo $theme; ?> label"><?php et('quantity'); ?></div>
                <input type="text" id="converter-quantity" value="1.0">
            </div>
        </div>
    </div>
    <br>
    <div class="ui fluid labeled input">
        <div class="ui <?php echo $theme; ?> label"><?php et('output'); ?></div>
        <select id="converter-output" class="ui fluid search selection dropdown" multiple>
            <?php foreach ($rates as $code => $rate) {echo "<option value=\"$code\">{$rate->name}</option>";} ?>
        </select>
    </div>

    <div class="ui basic segment">
        <div id="converter-panel" class="ui stackable grid"></div>
    </div>
</div>

<?php if($after_html) : ?>
    <div class="ui basic segment">
        <?php echo $after_html; ?>
    </div>
<?php endif; ?>