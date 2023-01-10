<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
    <link rel="stylesheet" href="../css/charte_profil_page.css">
    <link rel="stylesheet" href="../css/charte_nav_bar.css">
</head>


<?php
session_start();
//print_r($_SESSION);
if (isset($_SESSION['login'], $_SESSION['admin'])){
    ?>

    <?php
    include 'nav_bar.php';
    ?>
    <header></header>
    
    <body>
    <div class="form-table">
    
        <div class="form-mdp">
            <form action="" method="post">
                <h3>Changer de mot de passe</h3>
                <div class="form-mdp-mdp1">
                    <label for="mdp_actu">mot de passe actuel</label>
                    <input type="text" name="mdp_actu" id="mdp_actu">
                </div>
                <div class="form-mdp-mdp2">
                    <label for="new_mdp">nouveau mot de passe</label>
                    <input type="text" name="new_mdp" id="new_mdp">
                </div>
                <div class="form-mdp-mdp3">
                    <label for="new_mdp_verif">confirmer le mot de passe</label>
                    <input type="text" name="new_mdp_verif" id="new_mdp_verif">
                </div>
                <div class="form-submit">
                    <input type="submit" name="send" id="send" value="confirmer">
                </div>
                
    
            </form>
        </div>
    
        <div class="table-mdp">
            <h2>Mot de passe déja utilisés auparavant</h2>

            <?php
            $token=(bool)($connexion=mysqli_connect("localhost", "root", "01r1173"));
            
                if ($token){
                    $token2=(bool)($bd=mysqli_select_db($connexion, "Utilisateurs"));
                    #display($connexion);
                    if ($token2){
                        #display($bd);
                        $sql="SELECT password FROM users_mdp_historique WHERE login='".$_SESSION['login']."'";
                        $token3=(bool)($res=mysqli_query($connexion,$sql));
                        if ($token3){
                            #display($res);
                            echo "<div class='table-aff'>";
                            echo "<table>";
                            echo "<tr id='first-line'><th>ordre de changement</th><th>mot de passe</th></tr>";
                            
                            $cpt_mdp=0;
                            while ($ligne=mysqli_fetch_row($res)){
                                
                                echo "<tr>";
                                foreach ($ligne as $v){
                                    $cpt_mdp++;
                                    echo "<td>".$cpt_mdp."</td>";
                                    echo "<td>".$v."</td>";
                                }
                                echo "</tr>";
                            }
                            echo "</table>";
                            echo "</div>";
                        }
                    }
                }
            
            ?>
        </div>
    </div>
    
    </body>
    
    <?php
    include '../html/script_nav_bar.html';
    ?>
<?php
}
else{
    header("Location: ../index.php");
}

if (isset($_POST["send"],$_POST["mdp_actu"],$_POST["new_mdp"],$_POST["new_mdp_verif"])){

    if ($_POST["new_mdp"] != $_POST["mdp_actu"]){
        if ($_POST["new_mdp"] == $_POST["new_mdp_verif"]){
            $select="SELECT login, password FROM Utilisateur_inscrit WHERE login='".$_SESSION['login']."'";
            $res=mysqli_query($connexion, $select);
        
            $ligne = mysqli_fetch_assoc($res);

            if (md5($_POST["mdp_actu"]) == $ligne["password"]){
                $ins = "UPDATE utilisateur_inscrit SET password = ? WHERE login='".$_SESSION['login']."'";
                $insp = mysqli_prepare($connexion, $ins);
                $new_password = $_POST["new_mdp"];
                $new_password_md5 = md5($new_password);
                mysqli_stmt_bind_param($insp, 's', $new_password_md5);
                mysqli_stmt_execute($insp);

                $ins2 = "INSERT into users_mdp_historique(login,password) values(?,?)";
                $insp2 = mysqli_prepare($connexion, $ins2);
                $login = $_SESSION['login'];
                mysqli_stmt_bind_param($insp2, 'ss', $login, $new_password);
                mysqli_stmt_execute($insp2);
                header("Location: ../index.php");
            }
            else{
                header("Location: profil_page.php");
            }
        }
        else{
            header("Location: profil_page.php");
        }
    }
    else{
        header("Location: profil_page.php");
    }


}

?>

</html>
