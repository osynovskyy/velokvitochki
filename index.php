<?php
require_once('./php/common.php');

function register() {

    if (!empty($_POST['name'])) {
        $pattern = "/^([є-їa-zа-я'-]+)\s+([є-їa-zа-я'-]+)$/iu";
        $text = $_POST['name'];

        if (!preg_match($pattern, $text, $matches))
            return -1;
    } else
        return -1;

    if (empty($_POST['city']))
        return -1;

    if (!empty($_POST['phone'])) {
        $pattern = "/^\+?[0-9]{0,2}\s?\(?([0-9]{3})\)?\s?([0-9-\s]{7,9})$/";
        $text = $_POST['phone'];

        if (!preg_match($pattern, $text, $matches))
            return -1;
    } else
        return -1;

    if (!empty($_POST['email'])) {
        $pattern = "/^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/";
        $text = $_POST['email'];

        if (!preg_match($pattern, $text, $matches))
            return -1;
    } else
        return -1;

    $link = mysql_connect('localhost', 'osyno117_root', '79521756');

    if (!$link)
        return -1;

    $db = mysql_select_db('osyno117_fixedgear');
    if (!db)
        return -1;

    mysql_set_charset('utf8');

    $sql = 'INSERT INTO `velokvitochki_regs_2015` (`name`, `city`, `phone`, `email`) VALUES("'.$_POST['name'].'","'.$_POST['city'].'","'.$_POST['phone'].'","'.$_POST['email'].'")';

    $result = mysql_query($sql);

    if (!$result)
        return -1;

    $insert_id = mysql_insert_id();

    mysql_free_result($result);

    mysql_close($link);

    return $insert_id;
}

$id = 0;

if (!empty($_POST)) {

    $res = array('RESULT' => 'OK');

    $id = register();

    if (-1 == $id) {
        $res['RESULT'] = 'ERROR';

        sendMail("panOsynovskyy@gmail.com", "VeloKvitochki - ERROR",
            '<p>NAME: '.$_POST['name'].'</p>'.
            '<p>CITY: '.$_POST['city'].'</p>'.
            '<p>PHONE: '.$_POST['phone'].'</p>'.
            '<p>EMAIL: '.$_POST['email'].'</p>'
        );
    } else { //velogoroshina@gmail.com
        sendMail("panOsynovskyy@gmail.com, velogoroshina@gmail.com", "VeloKvitochki - REGISTRATION #".$id,
            '<p>ID:'.$id.'</p>'.
            '<p>NAME: '.$_POST['name'].'</p>'.
            '<p>CITY: '.$_POST['city'].'</p>'.
            '<p>PHONE: '.$_POST['phone'].'</p>'.
            '<p>EMAIL: '.$_POST['email'].'</p>'
        );

        sendMail($_POST['email'], "VeloKvitochki - Реєстрація", file_get_contents(getServerName().'/php/reg_confirm.php?reg_id='.$id));
    }

    $res['id'] = $id;

    if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
    {
        echo json_encode($res);

        exit();
    }
}

?>

<!doctype html>
<html class="no-js" lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="apple-touch-icon" href="apple-touch-icon.png">
        <!-- Place favicon.ico in the root directory -->
        <link href='http://fonts.googleapis.com/css?family=Roboto&subset=cyrillic,latin' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Roboto+Slab:400,100&subset=latin,cyrillic' rel='stylesheet' type='text/css'>

        <link rel="stylesheet" href="css/normalize.css">
        <script src="js/vendor/modernizr-2.8.3.min.js"></script>
    </head>
    <body>
        <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

        <!-- Add your site or application content here -->
        <div class="container">
            <section class="section clearfix">
                <div class="row clearfix">
                    <section class="info column small-12 medium-6 large-9">
                        <div class="wrapper clearfix">
                            <div class="header clearfix">
                                <div class="right">
                                    <a href="http://velokvitochki.fixedgear.in.ua">
                                        <div class="logo"></div>
                                    </a>
                                </div>
                            </div>

                            <div class="text clearfix">
                                <h1>Наш щоричний круїз жіночності, краси і квітів!</h1>
                                <h2>20 червня 2015</h2>
                                <p>Пані, вам вже і так все відомо: сукні, спідниці, туфлі на підборах — все у квіточку.</p>
                                <p>Наше завдання: приголомшити Харків квітами і своєю красою на роверах!</p>
                            </div>
                            <div class="route">

                            </div>
                            <div class="text">
                                <p>Як завжди, за найкащій образ очікують подарунки від наших друзів та партнерів.</p>
                                <p>Цього року ми робимо для вас обов'язкову реєстрацію — для того, щоб кожна вєло-квіточка отримала власний номер та залишилася задоволеною нашим святом.</p>
                            </div>
                        </div>
                    </section>

                    <section class="form column small-12 medium-6 large-3 clearfix">
                        <div class="wrapper clearfix">
                            <div id="reg-error" <?php if (-1 != $id) echo 'class="visuallyhidden"'?> >
                                <div class="error">
                                    <h1>Ой, якась помилка</h1>
                                    <p>
                                        Може ви ввели неправильні дані, або за вашими емейлом чи телефоном вже є реєстрація у базі.
                                    </p>
                                </div>
                            </div>

                            <?php
                            if (0 >= $id) {
                            ?>

                                <div id="reg">

                                    <h1>Реєстрація</h1>
                                    <form method="post" id="registration">
                                        <div class="form-row clearfix">
                                            <span>Ім'я та Прізвище:</span>
                                            <input type="text" name="name" id="name" autocomplete="off" <?php echo 'value="'.$_POST['name'].'"'?>>
                                            <span <?php if (-1 != $id) echo 'class="note visuallyhidden"'; else echo 'class="note"' ?>>Будь ласка, вкажи своє ім'я та прізвище.<br/>Наприклад, "Леся Українка"</span>
                                        </div>
                                        <div class="form-row clearfix">
                                            <span>Місто:</span>
                                            <input type="text" name="city" id="city" autocomplete="off" value="<?php echo empty($_POST['city'])?'Харків':$_POST['city']?>">
                                            <span <?php if (-1 != $id) echo 'class="note visuallyhidden"'; else echo 'class="note"' ?>>Дуже приємно дізнатися звідки наші учасніци.<br/>Наприклад, "Харків"</span>
                                        </div>
                                        <div class="form-row clearfix">
                                            <span>Телефон:</span>
                                            <input type="tel" name="phone" id="phone" autocomplete="off" <?php echo 'value="'.$_POST['phone'].'"'?>>
                                            <span <?php if (-1 != $id) echo 'class="note visuallyhidden"'; else echo 'class="note"' ?>>Обіцяємо нікому не казати твій номер.<br/>Наприклад, "(097) 123-45-67"</span>
                                        </div>
                                        <div class="form-row clearfix">
                                            <span>E-mail:</span>
                                            <input type="email" name="email" id="email" autocomplete="off" <?php echo 'value="'.$_POST['email'].'"'?>>
                                            <span <?php if (-1 != $id) echo 'class="note visuallyhidden"'; else echo 'class="note"' ?>>Ніякого спаму, лише важливі організаційні листи.<br/>Наприклад, "lady@ukr.net"</span>
                                        </div>
                                        <div class="form-row clearfix">
                                            <input type="submit" name="submit" id="submit" value="Зареєструватися">
                                        </div>
                                    </form>

                                </div>

                            <?php
                            }
                            ?>

                            <div id="reg-ok"  <?php if (0 >= $id) echo 'class="visuallyhidden"'?>>
                                <h1>Дякуэмо</h1>

                                <p>
                                    Реєстрація на Жіночому Велодні "Дівчата в квіточку" пройшла
                                    вдало. Дякуємо та чекаємо на зустріч!
                                </p>

                                <p>
                                    Твій номер учасниці:
                                </p>

                                <div id="reg-id">
                                    <?php echo $id ?>
                                </div>
                            </div>

                        </div>

                    </section>
                </div>
            </section>
            <section class="section clearfix">
                <div class="row clearfix">
                    <div class="sponsors clearfix">
                        <h1>Наші друзі</h1>
                        <div class="sponsor small-12 medium-3">
                            <a href="http://vk.com/ehali_medvedi_1923" target="_blank" class="sponsor-1"></a>
                            <span>Ехалимедведи пригощає велоквіточок кавою та смаколиками</span>
                        </div>
                        <div class="sponsor small-12 medium-3">
                            <a href="https://vk.com/bikeplanet" target="_blank" class="sponsor-2"></a>
                            <span>Велопланета надає чарівні ровери</span>
                        </div>
                        <div class="sponsor small-12 medium-3">
                            <a href="https://vk.com/fastfeetclub" target="_blank" class="sponsor-3"></a>
                            <span>Школа свінгових танців Fast Feet Club</span>
                        </div>
                        <div class="sponsor small-12 medium-3">
                            <a href="https://vk.com/eco.snack" target="_blank" class="sponsor-4"></a>
                            <span>Солодощі Эко-снэк</span>
                        </div>
                    </div>
                </div>
            </section>
        </div>


        <link rel="stylesheet" href="css/main.css">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.11.3.min.js"><\/script>')</script>
        <script src="js/plugins.js"></script>
        <script src="js/main.js"></script>

        <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
        <script>
            (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
            function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
            e=o.createElement(i);r=o.getElementsByTagName(i)[0];
            e.src='https://www.google-analytics.com/analytics.js';
            r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
            ga('create','UA-1643152-7','auto');ga('send','pageview');
        </script>
    </body>
</html>
