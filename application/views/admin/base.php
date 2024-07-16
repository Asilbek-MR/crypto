<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html lang="en" ng-app="AdminApp">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">

    <title><?php echo $title; ?></title>

    <link rel="icon" type="image/png" href="<?php echo $favicon; ?>">

    <?php foreach ($css as $file) : ?>
    <link rel="stylesheet" href="<?php echo $file; ?>">
    <?php endforeach; ?>
</head>
<body>

<div id="admin-mobile-menu" class="ui vertical inverted sidebar menu left">
    <a target="_blank" href="https://runcoders.net" class="item header">CoinTable v<?php echo COINTABLE; ?></a>
    <?php foreach ($menu as $item) : ?>
        <a class="item" ui-sref="<?php echo $item[0]; ?>" ui-sref-active="active">
            <i class="<?php echo $item[1]; ?> icon"></i><?php echo $item[2]; ?>
        </a>
    <?php endforeach; ?>
    <a href="<?php echo $constants->urls->logout; ?>" class="item">
        <i class="sign out alternative icon"></i>
        Logout
    </a>
</div>

<div id="admin-mobile-bar" class="ui inverted borderless fluid fixed menu" style="display: none;">
    <a class="item menu-opener">
        <i class="sidebar icon"></i>
    </a>
</div>

<div class="pusher">
    <div class="ui grid">
        <div class="row" style="padding-bottom: 0;">
            <div id="admin-menu">
                <div class="ui inverted vertical fluid large menu">
                    <a target="_blank" href="https://runcoders.net" class="item header">CoinTable v<?php echo COINTABLE; ?></a>
                    <?php foreach ($menu as $item) : ?>
                        <a class="item" ui-sref="<?php echo $item[0]; ?>" ui-sref-active="active">
                            <i class="<?php echo $item[1]; ?> icon"></i><?php echo $item[2]; ?>
                        </a>
                    <?php endforeach; ?>
                    <a href="<?php echo $constants->urls->logout; ?>" class="item">
                        <i class="sign out alternative icon"></i>
                        Logout
                    </a>
                </div>
            </div>

            <div id="admin-main">
                <form id="admin-main-form" class="ui big form">
                    <div id="admin-main-loading" class="ui basic segment">
                        <ui-view></ui-view>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="admin-bottom-bar" class="ui inverted blue borderless fluid menu">
        <a class="header item" target="_blank" href="<?php echo $copyrights[1]; ?>">
            <?php echo $copyrights[0]; ?>
        </a>
    </div>
</div>

<div id="admin-image-gallery" class="ui modal">
    <i class="close icon"></i>
    <div class="header">
        Image Gallery
        <div class="right floated ui tiny blue button" ng-click="image_gallery.upload()">
            <i class="upload icon"></i>
            Upload
        </div>
    </div>
    <div class="scrolling content">
        <div class="ui doubling link four cards" ng-if="image_gallery.images.length">
            <div class="card" ng-repeat="image in image_gallery.images">
                <a class="ui mini red right corner label" ng-click="image_gallery.remove($index)">
                    <i class="remove icon"></i>
                </a>
                <div class="image" ng-click="image_gallery.select($index)">
                    <img ng-src="{{image.url}}">
                </div>
            </div>
        </div>
        <div class="ui icon message" ng-if="!image_gallery.images.length">
            <i class="images outline icon"></i>
            <div class="content">
                No images were found...
            </div>
        </div>
    </div>
    <form id="admin-image-gallery-form" style="display: none;">
        <input name="image_file" type="file">
    </form>
</div>


<script>
    //<![CDATA[
    window.CoinTableAdminConstants = <?php echo json_encode($constants); ?>;
    //]]>
</script>
<?php foreach ($js as $file) : ?>
    <script src="<?php echo $file; ?>"></script>
<?php endforeach; ?>

</body>
</html>