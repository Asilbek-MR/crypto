<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">

    <title>CoinTable - Installation</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/semantic-ui@2.4.2/dist/semantic.min.css">
</head>
<body>
<div id="auth-login" class="ui middle aligned center aligned grid" style="height: 100%">
    <div class="column" style="max-width: 800px;">

        <h2 class="ui icon green header">
            <i class="checkmark icon"></i>
            <div class="content">
                CoinTable <?php echo $version ?>
                <div class="sub header">Successfully Installed!</div>
            </div>
        </h2>

        <?php if(!$message_image && !$message_file) : ?>
        <div>
            <a class="ui blue button" href="<?php echo $homepage_url; ?>">Homepage</a>
            <a class="ui blue button" href="<?php echo $login_url; ?>">Login</a>
        </div>
        <?php endif; ?>

        <div class="ui left aligned basic segment form">
            <?php if($message_image) : ?>
                <div class="ui padded raised segment">
                    <div class="ui orange icon message">
                        <i class="picture icon"></i>
                        <div class="content">
                            <p>Check your <strong>images</strong> folder permissions to 644 (Check Documentation > Setup)</p>
                        </div>
                    </div>
                    <div class="field">
                        <label>Image Folder</label>
                        <textarea rows="2" readonly><?php echo $images_folder; ?></textarea>
                    </div>
                </div>
            <?php endif; ?>

            <?php if($message_file) : ?>
                <div class="ui padded raised segment">
                    <div class="ui red icon message">
                        <i class="warning icon"></i>
                        <div class="content">
                            <p>You need to manual remove this file (or it could reset your database).</p>

                        </div>
                    </div>
                    <div class="field">
                        <label>File Path</label>
                        <textarea rows="2" readonly><?php echo $file_path; ?></textarea>
                    </div>
                </div>
            <?php endif; ?>


        </div>

    </div>
</div>
</body>
</html>
