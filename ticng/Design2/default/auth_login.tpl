<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
    "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" >
    <head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Tactical Information Center - Next Generation</title>
    </head>
    <body>
        <div style="text-align:center">
            <h1 style="margin-bottom:25px">Tactical Information Center - Next Generation</h1>
            <h2>Login</h2>
            {if $failed}
            <h3 style="color:red;">Login oder Passwort falsch</h3>
            {/if}
            <form action="wrapper.php" method="post">
                <table border="0" style="margin-left:auto; margin-right:auto; text-align:left;">
                    <tr>
                        <td>Login:</td>
                        <td><input id="login" name="username" size="20" maxlength="50" /></td>
                    </tr>
                    <tr>
                        <td>Passwort:</td>
                        <td><input name="password" type="password" size="20" /></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td align="left"><input type="submit" value=" Login " /></td>
                    </tr>
                </table>
            </form>
        </div>
    </body>
</html>
