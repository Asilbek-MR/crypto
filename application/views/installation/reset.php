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
        <form class="ui form" method="post" target="_self">
            <input type="hidden" name="force" value="true">
            <h2 class="ui icon red header">
                <i class="help icon"></i>
                <div class="content">
                    CoinTable <?php echo $version ?>
                    <div class="sub header">Is already installed!</div>
                </div>
            </h2>
            <div class="ui message">
                <p>The system seems to be already installed, do you want to reset the database?</p>
                <button type="submit" class="ui blue button">
                    Reset
                </button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
