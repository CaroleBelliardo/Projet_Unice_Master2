<!DOCTYPE html PUBLIC >
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>ServiceCreer</title>
</head>

<body>
<p class="" style="margin-top:5px;">

<div class="signin-form">
    	
        <form method="post" class="form-signin">

            <h2 class="form-signin-heading">Ajouter un nouveau service</h2><hr />

            <?php
			if(isset($error))
			{
			 	foreach($error as $error)
			 	{
					 ?>
                     <div class="alert alert-danger">
                        <i class=""></i> &nbsp; <?php echo $error; ?>
                     </div>
                     <?php
				}
			}
			else if(isset($_GET['Valide']))
			{
			?>
                 <div class="alert alert-info">
                      <i class=""></i>Service ajouté avec succes<br><a href='Pageprincipale.php'>Page principale</a>
                 </div>
            <?php
			}
			?>
			
			<div class="form-group" >
			
			<fieldset>
			<legend> Service </legend> <!-- Titre du fieldset --> 
			<p>
				<input type="text" class="form-control" name="text_nomService" pattern="[A-Za-z]{1-20}" title="Majuscule en première lettre"        placeholder="Nom du service :" value="<?php if(isset($error)){echo $text_nomService;}?>" /><br><br>
				<input type="tel" class="form-control" name="text_telephone" pattern="[0-9]{1-15}" title="Veuillez rentrer un n° de téléphone correct"    placeholder="N° téléphone :" value="<?php if(isset($error)){echo $text_telephone;}?>" /><br><br>
				<label class="form-control"> Horaire d'ouverture : &nbsp;&nbsp;
				<input type="time" class="form-control" name="text_ouverture" value="<?php if(isset($error)){echo $text_ouverture;}?>" /><br><br>
				<label class="form-control"> Horaire de fermeture : &nbsp;&nbsp;
				<input type="time" class="form-control" name="text_fermeture" value="<?php if(isset($error)){echo $text_fermeture;}?>" />
			</p>
			</fieldset>
			
			<fieldset>
			<legend> Localisation </legend> <!-- Titre du fieldset --> 
			<p>
				Batiment : 
				<select name="text_batiment">
					<option value="A">A</option>
					<option value="B">B</option>
					<option value="C">C</option>
					<option value="D">D</option>
					<option value="E">E</option>
					<option value="F">F</option>
				</select><br><br>  
				Etage :  
				<select name="text_etage"> 
					<option value="0">RDC</option>
					<option value="1">1</option>
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="4">4</option>
					<option value="-1">-1</option>
				</select><br><br> 
				Aile :  
				<select name="text_aile">
					<option value="a">a</option>
					<option value="b">b</option>
					<option value="c">c</option>
					<option value="d">d</option>
					<option value="e">e</option>
					<option value="f">f</option>
				</select>
			
			</p>
			</fieldset>
			
			</div>
            <div class="clearfix"></div><hr />
            <div class="form-group">
            	<button type="submit" class="btn btn-primary" name="btn-valider">
                	<i class=""></i>Valider
                </button>
            </div>
        </form>
       </div>
</div>
	<?php 	quitter1()	;?>	
</body>

 
</html>
