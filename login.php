<?php
require_once('head_parts.php');
?>

<!DOCTYPE html>
<html lang="ja">
    
    <head>
        <?= head_parts('ログイン','login') ?>
    </head>

    <body>
        
        <div class="wrapper">

            <form class="form" name="form1" action="login_act.php" method="post">
                <table class="">
                    <tr>
                        <th>ID:</th>
                        <td>
                            <input type="text" name="lid" />
                        </td>
                    </tr>
                    <tr>
                        <th>PW:</th>
                        <td>
                            <input type="password" name="lpw" />
                        </td>
                    </tr>
                </table>
                <div class="btn_area">
                    <input class="btn" type="submit" value="LOGIN"/>
                </div>
            </form>
                
                <!-- <a href="logout.php">LOGOUT</a> -->
                

        </div>

    </body>

</html>