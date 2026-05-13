<?php

session_start();

?>

<html>

<head>
  <title>Installation Shiva Auth 1.3 test - Step 3</title>
  <link href="style.css" rel="stylesheet" type="text/css" />
  <script type="text/javascript">
  function ChangeStatut(formulaire)
  {
    if(formu.install_notable.checked == false)
    {
      formu.install_tablemembre.disabled = false;
      formu.install_tableconf.disabled = false;
    }
    if(formu.install_notable.checked == true)
    {
      formu.install_tablemembre.disabled = true;
      formu.install_tableconf.disabled = true;
    }
  }
  </script> 
</head>

<body>

<div id="conteneur">

  <div id="entete">

    <h1>Etape 3</h1>

    Configuation générale

  </div>

  <div id="centre">

    <form action="step_register.php" method="post" name="formu">

      <input type="hidden" name="step" value="3">

      <?php

      if (!empty($_SESSION['report']))
      {
	echo '<span id="red">' . $_SESSION['report'] . '</span><br /><br />';
	unset($_SESSION['report']);
      }

      ?>

      <b>Cryptage du password?</b><br />
	<?php
	if (isset($_SESSION['install_crypt']) and $_SESSION['install_crypt'] == '0')
	{
	  echo '<input type="radio" name="install_crypt" value="1">Oui</b> ';
	  echo '<input type="radio" name="install_crypt" value="0" checked>Oui';
	}
	else
	{
	  echo '<input type="radio" name="install_crypt" value="1" checked>Oui ';
	  echo '<input type="radio" name="install_crypt" value="0">Non';
	}
	?>
        <br /><br />


      <b>Type d'activation?</b><br />
	<?php
	if (isset($_SESSION['install_activ']) and $_SESSION['install_activ'] == '1')
	{
	  echo '<input type="radio" name="install_activ" value="0">Aucune ';
	  echo '<input type="radio" name="install_activ" value="1" checked>Administrateur';
	}
	else
	{
	  echo '<input type="radio" name="install_activ" value="0" checked>Aucune ';
	  echo '<input type="radio" name="install_activ" value="1">Administrateur';
	}
	?>
        <br /><br />


      <b>Logs de connexion?</b><br />
	<?php
	if (isset($_SESSION['install_logs']) and $_SESSION['install_logs'] == '1')
	{
	  echo '<input type="radio" name="install_logs" value="1" checked>Oui ';
	  echo '<input type="radio" name="install_logs" value="0">Non';
	}
	else
	{
	  echo '<input type="radio" name="install_logs" value="1">Oui ';
	  echo '<input type="radio" name="install_logs" value="0" checked>Non';
	}
	?>
        <br /><br />


      <b>Email de confirmation d'inscription?</b><br />
	<?php
	if (isset($_SESSION['install_email']) and $_SESSION['install_email'] == '1')
	{
	  echo '<input type="radio" name="install_email" value="0">Non ';
	  echo '<input type="radio" name="install_email" value="1" checked>Oui ';
	  echo '<input type="radio" name="install_email" value="2">Oui avec Protomail (pour Free)';
	}
	elseif (isset($_SESSION['install_email']) and $_SESSION['install_email'] == '2')
	{
	  echo '<input type="radio" name="install_email" value="0">Non ';
	  echo '<input type="radio" name="install_email" value="1">Oui ';
	  echo '<input type="radio" name="install_email" value="2" checked>Oui avec Protomail (pour Free)';
	}
	else
	{
	  echo '<input type="radio" name="install_email" value="0" checked>Non ';
	  echo '<input type="radio" name="install_email" value="1">Oui ';
	  echo '<input type="radio" name="install_email" value="2">Protomail (pour Free)';
	}
	?>
        <br /><br />

      <input type="button" name="precedent" value="&#60; Etape précédente" onclick="window.location='step2.php';">
      <input type="submit" name="next" value="Etape suivante &#62;">

    </form>

  </div>

  <div id="bas">

    Shiva Authentification version 1.3 (test) - License GPL

  </div>


</div>

</body>

</html>