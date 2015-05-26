<?php require_once('common.php') ?>

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>VeloKvitochki — Регистрация</title>
</head>

<body>

<?php
function getRegistrationDetails($id) {
	$link = mysql_connect('localhost', 'osyno117_root', '79521756');
	
	if (!$link)
		return false;
		
	$db = mysql_select_db('osyno117_fixedgear');
	if (!db)
		return false;
	
	mysql_set_charset('utf8');
	
	$sql = 'SELECT * FROM `velokvitochki_regs_2015` WHERE `id` = '.$id;
	
	$result = mysql_query($sql);
	
	if (!$result)
		return false;
		
	while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
		mysql_free_result($result);		
		mysql_close($link);
		
		return $row;
	}
		
	mysql_free_result($result);
	
	mysql_close($link);
}

	if ($_GET['reg_id']) {
		$info = getRegistrationDetails($_GET['reg_id']);
		if (!$info)
			die('Ошибка: '.mysql_error());		
	} else {
		die('Ошибка: нет идентификатора пользователя');
	}
?>
	<table cellpadding="0" cellspacing="0" style="color: #333;font-family: Verdana;font-size:12px;text-align: justify;margin: 0;padding: 0;background: #eee;width: 500px;margin: 0 auto; border-radius: 5px; padding: 10px;	border: 0;">
        <tr>
            <td colspan="3" style='text-indent: 10px; padding: 10px'>
                <?=$info['name']?>, дякуємо за реєстрацію на <a href='http://velokvitochki.fixedgear.in.ua'>Жіночому Велодні "Дівчата в квіточку"</a>.
            </td>
        </tr>
		<tr>
			<td colspan="3">
				<a href='http://velokvitochki.fixedgear.in.ua'><img src='<?= getServerName(); ?>/img/mail-logo.png' alt='Вело Квіточки'/></a>
			</td>
		</tr>
		<tr>
			<td colspan="3" style="text-align: center;padding: 10px 0px; text-indent: 0px;">
				Ваш номер учасниці:
			</td>
		</tr>
		<tr>
			<td width="170 px"></td>
			<td width="150 px" height="100 px" style="border: solid 1px #000; color: #000; font-size: 60px; border-radius: 5px; vertical-align: middle; text-align: center; font-weight: bold;	text-indent: 0;">
				<?=$info['id']?>
			</td>
			<td width="170 px"></td>
		</tr>
		<tr>
			<td colspan="3" style="text-indent: 10px; padding: 10px">
				Якщо у тебе залишились питання, не соромся і напиши нам листа на пошту <a href="mailto:velogoroshina@gmail.com">velogoroshina@gmail.com</a>
			</td>
		</tr>
	</table>
</body>
	
</html>