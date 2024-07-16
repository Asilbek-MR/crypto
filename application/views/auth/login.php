<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <title>CoinTable - Login</title>
    <link rel="icon" type="image/png" href="">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/semantic-ui@2.4.2/dist/semantic.min.css">
    <style>
        #auth-login{
            height: 100%;
        }
        #auth-login .column{
            max-width: 450px;
        }
    </style>
</head>
<body>
<div id="auth-login" class="ui middle aligned center aligned grid">
    <div class="column">
        <h2 class="ui header">
            <div class="content">
                <?php echo lang('login_heading');?>
                <div class="sub header"><?php echo lang('login_subheading');?></div>
            </div>
        </h2>
        <div id="infoMessage"><?php echo $message;?></div>


        <?php echo form_open("auth/login",array('class' => 'ui large form'));?>

            <div class="ui stacked segment">
                <div class="field">
                    <div class="ui left icon input">
                        <i class="user icon"></i>
                        <?php echo form_input($identity);?>
                    </div>
                </div>

                <div class="field">
                    <div class="ui left icon input">
                        <i class="lock icon"></i>
                        <?php echo form_input($password);?>
                    </div>
                </div>

                <div class="field">
                    <div class="ui checkbox">
                        <?php echo form_checkbox('remember', '1', FALSE, array('id'=>'remember')) ?>
                        <?php echo lang('login_remember_label', 'remember'); ?>
                    </div>
                </div>

                <div class="field">
                    <?php echo form_submit('submit', lang('login_submit_btn'), array('class' => 'ui green button'));?>
                </div>
            </div>

            <div class="ui error message"></div>

        <?php echo form_close();?>

    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/semantic-ui@2.4.2/dist/semantic.min.js"></script>
</body>
</html>
