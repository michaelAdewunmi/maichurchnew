<?php
/**
 * Description File to send an sms but to be queried via javascript ajax
 *
 * @category Cashier_Token_View
 * @package  Surulere_Finance_Project
 * @author   Suruler_DevTeam <surulere_devteam@gmail.com>
 * @license  MIT https://github/tunjiup/mainchurch
 * @link     https://github/tunjiup/mainchurch
 */
session_start();
require_once './config/config.php';
require_once './includes/create_and_send_token.php';

?>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Document</title>
    </head>
<body>
    <form method="POST">
        <h1>Welcome "<?php echo $admin_name; ?>" - Generate Token!</h1>
        <?php
        if (isset($_SESSION['token_val_info'])) {
            echo '<p class="err">' . $_SESSION['token_val_info'] . '</p>';
            unset($_SESSION['token_val_info']);
        }
        ?>
        <div class="display-on-token-error">
            <input
                class="submit generate-token retry <?php
                if (isset($_SESSION['token_generated']) AND isset($_SESSION['token_btn'])
                    AND $_SESSION['token_btn'] === 'hide'
                ) {
                    echo $_SESSION['token_btn'];
                } else {
                    echo 'show';
                }
                ?>"
                type="submit" value="Retry Previous Token"
                name="retry"
            />
            <input
                class="submit generate-token <?php
                if (isset($_SESSION['token_generated']) AND isset($_SESSION['token_btn'])
                    AND $_SESSION['token_btn'] === 'hide'
                ) {
                    echo $_SESSION['token_btn'];
                } else {
                    echo 'show';
                }
                ?>"
                type="submit" value="Click to Generate Token" name="generate_token"
            />
        </div>
        <?php
        if (isset($_SESSION['token_generated'])
            AND $_SESSION['token_generated'] === true
            AND $_SESSION['token_btn'] === 'hide'
        ) {
            ?>
        <p class="info">
            A confirmation token has been sent to your number.
            Please Type in the token below for verification
        </p>
        <div class="input-holder">
            <input
                class="token-input" placeholder="Four Digit Token"
                type="text" name="user-token" autocomplete="off"
            />
            <input class="submit" type="submit" value="Submit" name="submit" />
        </div>
    <?php } ?>
        <a class="logout" href="logout.php">Log Out</a>
        </form>

    <style>
    body {
        background: #eee;
        display:flex;
        flex-direction: column;
        justify-content: center;
    }

    body, input {
        font-family: "Raleway" !important;
    }

    h1 {
        width: 100%;
        font-size: 18px;
        background: #18aa8d;
        color: white;
        line-height: 150%;
        border-radius: 3px 3px 0 0;
        box-shadow: 0 2px 5px 1px rgba(0, 0, 0, 0.2);
        margin: 0;
    }

    form {
        box-sizing: border-box;
        width: 260px;
        margin: 0 auto;
        box-shadow: 2px 2px 5px 1px rgba(0, 0, 0, 0.2);
        padding-bottom: 40px;
        border-radius: 3px;
        background: #fff;
        margin-bottom: 40px;

    }
    form h1 {
        box-sizing: border-box;
        padding: 20px;
    }

    .info {
        margin-left: 25px;
        width: 200px;
        display: block;
        border: none;
        padding: 10px 0;
        line-height: 1.5;
        font-style: italic;
        font-weight: 500;
        color: #5a5a5a;
        margin-bottom: 0 !important;
    }

    .token-input {
        margin: 0  25px;
        width: 200px;
        display: block;
        border: none;
        padding: 10px 0;
        border-bottom: solid 1px #1abc9c;
        -webkit-transition: all 0.3s cubic-bezier(0.64, 0.09, 0.08, 1);
        transition: all 0.3s cubic-bezier(0.64, 0.09, 0.08, 1);
        background: -webkit-linear-gradient(top, rgba(255, 255, 255, 0) 96%, #1abc9c 4%);
        background: linear-gradient(to bottom, rgba(255, 255, 255, 0) 96%, #1abc9c 4%);
        background-position: -200px 0;
        background-size: 200px 100%;
        background-repeat: no-repeat;
        color: #0e6252;
        padding-left: 10px;
    }
    .token-input:focus, .token-input:valid {
        box-shadow: none;
        outline: none;
        background-position: 0 0;
        -webkit-transition: all 0.3s cubic-bezier(0.64, 0.09, 0.08, 1);
        transition: all 0.3s cubic-bezier(0.64, 0.09, 0.08, 1);
    }
    .token-input:focus::-webkit-input-placeholder,
    .token-input:valid::-webkit-input-placeholder {
        color: #1abc9c;
        font-size: 11px;
        -webkit-transform: translateY(-25px);
        transform: translateY(-25px);
        visibility: visible !important;
        -webkit-transition: all 0.3s cubic-bezier(0.64, 0.09, 0.08, 1);
        transition: all 0.3s cubic-bezier(0.64, 0.09, 0.08, 1);
    }

    .submit, .logout {
        border: none;
        background: #1abc9c;
        cursor: pointer;
        border-radius: 3px;
        padding: 10px;
        width: 80%;
        color: white;
        margin-left: 25px;
        margin-top: 40px;
        box-shadow: 0 3px 6px 0 rgba(0, 0, 0, 0.2);
        -webkit-transition: all 0.3s cubic-bezier(0.64, 0.09, 0.08, 1);
        transition: all 0.3s cubic-bezier(0.64, 0.09, 0.08, 1);
    }

    .logout {
        text-decoration: none;
        margin: 20px auto;
        padding: 10px 0;
        display: block;
        width: 80%;
        text-align: center;
        background: #ea4343;
    }

    .submit:hover, .logout:hover {
        -webkit-transform: translateY(-3px);
        -ms-transform: translateY(-3px);
        transform: translateY(-3px);
        box-shadow: 0 6px 6px 0 rgba(0, 0, 0, 0.2);
    }

    .display-on-token-error {
        display: flex;
        flex-wrap: wrap;
        margin: 25px;
        margin-bottom: 0;
    }

    .generate-token {
        width: 80% !important;
        margin: 10px auto 0 auto !important;
        display: block !important;
        font-weight: 500;
    }

    .generate-token:first-of-type {
        margin-top: 40px !important;
    }

    .generate-token.retry {
        background: #1a7fbc;
    }

    .submit.generate-token.hide {
        display: none !important;
    }

    .err {
        font-size: 12px;
        color: red;
        font-weight: 700;
        text-align: center;
        margin-bottom: -30px;
        margin-top: 30px;
    }
    @media screen and (min-width: 640px) {
        form {
            width: 60%;
        }


        .info, .input-holder {
            width: 90%;
        }

        .logout {
            margin: 10px 0 0 25px;
            width: 90%;

        }

        .input-holder {
            display: flex;
            margin: 10px auto;
        }
        .token-input{
            width: 70%;
            margin: 0;
            background-position: -90% 0;
            background-size: 0%;
        }

        .token-input:focus, .token-input:valid {
            background-position: 0 0;
            background-size: 100%;
        }

        .submit {
            width: 20%;
            min-width: 100px;
            margin-top: 0;
        }

        .generate-token {
            width: 48% !important;
        }

        .generate-token:first-of-type, .generate-token {
            margin-top: 30px !important;
        }
    }

    @media screen and (min-width: 960px) {
        form {
            width: 42%;
        }
    }
    </style>
</body>
</html>

