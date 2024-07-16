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

<style>
	#gainers-table .ui.image.header > .ui.image,
	#losers-table .ui.image.header > .ui.image {
		height: 2.5em;
	}
</style>

<div class="ui basic segment">
    <div class="ui stackable grid">
        <div class="centered row">
            <div class="sixteen wide tablet eight wide computer column" style="max-width: 800px">
                <h2 class="ui centered header">
                    <?php et('gainers'); ?>
                </h2>
                <table id="gainers-table" class="ui unstackable single line table" style="width: 100%">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th><?php et('name'); ?></th>
                        <th><?php et('change_24h'); ?></th>
                        <th><?php et('price'); ?></th>
                        <th><?php et('volume_24h'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($gainers as $i => $coin): ?>
                        <tr>
                            <td><?php echo $i+1; ?></td>
                            <td>
                                <a href="<?php echo $coin['url']; ?>">
                                    <h5 class="ui image header">
                                        <img class="ui image" src="<?php echo $coin['image_small']; ?>">
                                        <div class="content">
                                            <?php echo $coin['symbol'] ?>
                                            <div class="sub header"><?php echo $coin['name'] ?></div>
                                        </div>
                                    </h5>
                                </a>
                            </td>
                            <td>
                                <a class="ui green fluid large label" href="<?php echo $coin['url']; ?>">
                                    <i class="arrow up icon"></i>
                                    <?php echo $coin['change']; ?>
                                    <div class="detail">%</div>
                                </a>
                            </td>
                            <td><a href="<?php echo $coin['url']; ?>"><?php echo $coin['price']; ?></a></td>
                            <td><a href="<?php echo $coin['url']; ?>"><?php echo $coin['volume']; ?></a></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="sixteen wide tablet eight wide computer column" style="max-width: 800px">
                <h2 class="ui centered header">
                    <?php et('losers'); ?>
                </h2>
                <table id="losers-table" class="ui unstackable single line table" style="width: 100%">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th><?php et('name'); ?></th>
                        <th><?php et('change_24h'); ?></th>
                        <th><?php et('price'); ?></th>
                        <th><?php et('volume_24h'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($losers as $i => $coin): ?>
                        <tr>
                            <td><?php echo $i+1; ?></td>
                            <td>
                                <a href="<?php echo $coin['url']; ?>">
                                    <h5 class="ui image header">
                                        <img class="ui image" src="<?php echo $coin['image_small']; ?>">
                                        <div class="content">
                                            <?php echo $coin['symbol'] ?>
                                            <div class="sub header"><?php echo $coin['name'] ?></div>
                                        </div>
                                    </h5>
                                </a>
                            </td>
                            <td>
                                <a class="ui red fluid large label" href="<?php echo $coin['url']; ?>">
                                    <i class="arrow down icon"></i>
                                    <?php echo $coin['change']; ?>
                                    <div class="detail">%</div>
                                </a>
                            </td>
                            <td><a href="<?php echo $coin['url']; ?>"><?php echo $coin['price']; ?></a></td>
                            <td><a href="<?php echo $coin['url']; ?>"><?php echo $coin['volume']; ?></a></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<?php if($after_html) : ?>
    <div class="ui basic segment">
        <?php echo $after_html; ?>
    </div>
<?php endif; ?>
