<?php
session_start();
//initialize variable session
//executer la méthode pour récuperer le contenu de l'appel à l'api
include './service_api/get_api.php';
include './utiles/functions.php';
include './dto/userDto.php';
include './dto/congeDto.php';


    if(isset($_POST['liste_consultation'])){
unset($_POST['liste_consultation']);
        //intanciation d'une dto user:
        $userDto = new UserDto();
        $userDto->num_id = strval($_POST['num_id']);
        $userDto->user_id = strval($_POST['salarie']);
        //transforme objet tableau json
        $userDto = json_encode(array("user" => $userDto));

        $rslt = json_decode(CallAPI("POST","loginController.php",$userDto),true);
        
        if($rslt['code'] == 1) { 
            $_POST['liste_consultation'] = true;
            $_SESSION['statut'] = $_POST['statut'];
        }
    }else{
        if(isset($_POST['soumise'])){
            if(isset($_POST['titre']) && !empty(trim($_POST['titre'])) &&
             isset($_POST['contenu']) && !empty(trim($_POST['contenu'])) &&
             isset($_POST['date_depart']) && validateDate(trim($_POST['date_depart'].' 00:00:00')) && 
                isset($_POST['date_fin']) && validateDate(trim($_POST['date_fin'].' 00:00:00')) &&
                strtotime(trim($_POST['date_depart'])) < strtotime(trim($_POST['date_fin']))){ 

                    unset($_POST['liste_consultation']);

                $congeDto = new CongeDto();
                $congeDto->user_id = $_POST['salarie'];
                $congeDto->titre = trim($_POST['titre']);
                $congeDto->contenu = trim($_POST['contenu']);
                $congeDto->date_depart = trim($_POST['date_depart']);
                $congeDto->date_fin = trim($_POST['date_fin']);
                //transforme objet tableau json
                $congeDto = json_encode(array("conge" => $congeDto));

                $rslt = json_decode(CallAPI("POST","createCongeController.php",$congeDto),true); 
                
               if($rslt['code_conge_cree'] == 1) { $_POST['liste_consultation'] = 1; }
            }else{
                $_POST['choix_consultation'] = 1;
            }
        }else{
            if(isset($_POST['choix_consultation']) && $_POST['choix_consultation'] == 0){
                $_POST['liste_consultation'] = 1;
                unset($_POST['login']);
            }else{
                if(isset($_POST['choix_consultation']) && $_POST['choix_consultation'] == 1){                    
                    unset($_POST['login']);
                }else{
                    if(isset($_POST['choix_consultation']) && $_POST['choix_consultation'] == 2){
                        if(isset($_POST['salarie'])){               
                            $rslt = json_decode(CallAPI("GET","listeCongeBySalarieController.php?id=".$_POST['salarie'],false),true);                            
                        }
                    }else{
                        if(isset($_POST['choix_consultation']) && $_POST['choix_consultation'] == 4){
                            if(isset($_POST['salarie'])){
                                if(isset($_SESSION['statut']) && $_SESSION['statut']== 1){
                                    $rslt = json_decode(CallAPI("GET","listeCongeDesSalariesByManagerController.php?idStatut=1&id=".$_POST['salarie'],false),true);
                                }else{              
                                    $rslt = json_decode(CallAPI("GET","listeCongeDesSalariesByManagerController.php?idStatut=0&id=".$_POST['salarie'],false),true);                            
                                }
                            }
                        }else{
                            if(isset($_POST['retour_liste_consultation'])){
                                $_POST['liste_consultation'] = 1;
                                $_SESSION['statut'] = $_POST['statut'];                        
                            }else{
                                if(isset($_POST['statut']) && empty($_POST['statut'])){
                                    unset($_POST['salarie']);
                                    unset($_POST['num_id']);    
                                    $_POST['login'] = true;                
                                    $rslt = json_decode(CallAPI("GET","listeSalarieController.php",false),true);
                                }else{
                                    if(isset($_POST['choix_consultation']) && $_POST['choix_consultation'] == 3){
                                        $_POST['login'] = true;
                                        $rslt = json_decode(CallAPI("GET","listeSalarieController.php",false),true);
                                        unset($_POST['salarie']);
                                        unset($_POST['statut']);
                                        unset($_SESSION);
                                        session_destroy();
                                    }else{
                                        if(isset($_POST['consulter_conge'])){
                                            $rslt = json_decode(CallAPI("GET","consulterCongeController.php?id=".$_POST['idConge'],false),true);
                                        }else{
                                            if(isset($_POST['verdicte'])){
                                                $congeDto = new CongeDto();
                                                $congeDto->id = $_POST['idConge'];
                                                $congeDto->valide = $_POST['valide'];
                                                $congeDto->motif = trim($_POST['motif']);
                                                //transforme objet tableau json
                                                $congeDto = json_encode(array("conge" => $congeDto));
                                
                                                $rslt = json_decode(CallAPI("POST","updateCongeController.php",$congeDto),true); 
                                                
                                                $_POST['retour_liste_consultation'] = true;
                                            }else{
                                                $_POST['login'] = true;
                                                $rslt = json_decode(CallAPI("GET","listeSalarieController.php",false),true);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }


?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="author" content="Kodinger">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title>Gestion des demandes de congé.</title>
	<link rel="stylesheet" href="./assets/css/bootstrap.min.css" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="./assets/css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="./assets/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <script>
        function myFunction(id) {            
            document.getElementById("myForm" + id).submit();
        }

        function valider(){            
            var v2 = document.getElementById("valide2");
            var motif = document.getElementById("motif");
            motif.style.display = v2.checked ? "block" : "none";
            
        }
    </script>

</head>

<body class="my-login-page">
	<section class="h-100">
		<div class="container h-100">
			<div class="row justify-content-md-center h-100">
				<div style="<?php if(isset($_POST['choix_consultation']) && $_POST['choix_consultation'] == 4){ echo 'width: 800px;'; } ?>" class="card-wrapper">
					<div class="brand">
						<img src="assets/img/logo.jpg" alt="logo">
					</div>
                    <?php if(isset($_POST['login'])){ ?>
					<div class="card fat">
						<div class="card-body">
							<h4 class="card-title" style="text-align:center;">Gestion des congés</h4>
							<form id="myForm1" method="POST">
								<div class="form-group">
									<label><b>Lister les salariés par : </b></label>
									<select onChange="myFunction(1);" class="form-control" name="statut" required>
                                        <option value="">Coisissez un statut...</option>
                                        <?php
                                            foreach ( $rslt as $obj ){
                                                foreach ( $obj as $item ){
                                                    if(!isset($item['user']) && !empty($item)){ ?>
                                                        <option <?php if(isset($_POST['statut']) && $_POST['statut'] == $item['id']) echo 'selected'; ?> value="<?php echo $item['id']; ?>"><?php echo ucfirst($item['statut']); ?></option>
                                       <?php        }
                                                }
                                            }
                                    ?>
                                        
                                    </select>
								</div>
                                <?php if(isset($_POST['statut']) && !empty($_POST['statut'])){ ?>
								<div class="form-group">
									<label>
                                        <b>Liste des salariés</b> :										
									</label>
                                    <div class="form-group">
									<select onChange="myFunction(1);" class="form-control" name="salarie" class="form-control" required>
                                        <option value="">Coisissez un salarié...</option>
                                        <?php
                                            foreach ( $rslt as $obj ){
                                                foreach ( $obj as $item ){
                                                    if(isset($item['user']) && !empty($item)){
                                                        //on trie la liste des salariés si le statut est séléctionné.
                                                        if($item['user']['role_id'] == 1 && $_POST['statut'] == 1){                                                        
                                        ?>                                            
                                                                <option <?php if(isset($_POST['salarie']) && $_POST['salarie'] == $item['user']['id']) echo 'selected'; ?> value="<?php echo $item['user']['id']; ?>"><?php echo $item['user']['prenom'] . ' ' . $item['user']['nom'] .' - ' . ucfirst($item['role']['statut']) . ' (Le patron!)'; ?></option>; ?></option>
                                        <?php            
                                                        }else{ 
                                                            if(isset($_POST['statut']) && $item['user']['role_id'] == $_POST['statut'] && $item['user']['role_id'] != 1){                                                        
                                            ?>                                            
                                                                    <option <?php if(isset($_POST['salarie']) && $_POST['salarie'] == $item['user']['id']) echo 'selected'; ?> value="<?php echo $item['user']['id']; ?>"><?php echo $item['user']['prenom'] . ' ' . $item['user']['nom'] .' - ' . ucfirst($item['role']['statut']) . ' (sous la direction de: ' . ucfirst($item['responsablePrenom']) . ' ' .ucfirst($item['responsableNom']). ')'; ?></option>; ?></option>
                                            <?php               
                                                               
                                                            }else{                                     
                                                                //on affiche tous les salariés si on a pas choisis de statut               
                                                                if(isset($_POST['statut']) && empty($_POST['statut'])){ 
                                        ?>                    
                                                                    <option value="<?php echo $item['user']['id']; ?>"><?php echo $item['user']['prenom'] . ' ' . $item['user']['nom'] .' - ' . ucfirst($item['role']['statut']) . ' (sous la direction de: ' . ucfirst($item['reponsableNom']) . ' ' .ucfirst($item['reponsableNom']). ')'; ?></option>
                                        <?php                   }
                                                            }
                                                        }
                                                    }
                                                }
                                            } 
                                        ?>                                        
                                    </select>  
                                    </div>                                  
								</div>	
                                <?php } if(isset($_POST['salarie']) && !empty($_POST['salarie'])){ ?>
                                <div class="form-group">
                                    <label for="email"><b>Numéro d'identification :</b></label>
                                    <!-- normalement on devrai ajouter un attribut autocomplete à no pour éviter que le navigateur affiche 
                                    la liste des identifiants déjà inséré auparavant par d'autre salariés mais j'ai laissé par défaut pour 
                                    lors du test pas la peine de ré-introduire à chaque fois -->
                                    <input type="text" class="form-control" placeholder="Numéro d'identification..." name="num_id" required/>
                                </div>
                                <?php } ?>
								<div class="form-group m-0">
									<input name="liste_consultation" value="Consulter" type="submit" class="btn btn-primary btn-block"/>										
								</div>								
							</form>
						</div>
					</div>
                    <?php }else{ 
                        if(isset($_POST['liste_consultation']) || isset($_POST['retour_liste_consultation'])){
                         if(isset($rslt['code_conge_cree']) && $rslt['code_conge_cree'] == 1){ ?>
                            <div class="alert alert-success">
                                <strong>Félicitation!</strong> la demande de congé a été créée avec succès.
                            </div>
                        <?php }else{ 
                        if(isset($rslt['code_conge_update']) && $rslt['code_conge_update'] == 1){ ?>
                            <div class="alert alert-success">
                                <strong>Félicitation!</strong> la demande de congé a été mis à jour avec succès.
                            </div>
                        <?php }} ?>
                        <form method="POST" id="myForm2">
                            <div class="form-group">
                                <input name="salarie" value="<?php if(isset($_POST['salarie'])) echo $_POST['salarie']; ?>" type="hidden"/>
                                <label for="titre"><b>Que voulez-vous faire? : </b></label>
                                <select onChange="myFunction(2);" class="form-control" name="choix_consultation" required>
                                    <option value="0">Voulez-vous...</option>
                                    <?php if($_SESSION['statut'] != 1){ ?>
                                        <option value="1">Entrer une nouvelle demande de congé?</option>                                    
                                        <option value="2">Consulter la liste de vos demandes de congé?</option>
                                    <?php } ?>
                                    <option value="3">Retourner à la page d'identification?</option>                                    
                                    <?php if($_SESSION['statut'] == 2 || $_SESSION['statut'] == 1){ ?>
                                        <option value="4">Consulter la liste des demandes de congé de vos salariés?</option>
                                    <?php } ?>
                                </select>
                            </div>
                        </form>
                    <?php }else{ if(isset($_POST['choix_consultation']) && $_POST['choix_consultation'] == 1){ ?>
                        <?php if(isset($_POST['date_depart']) && !validateDate(trim($_POST['date_depart'].' 00:00:00'))){ ?>
                            <div class="alert alert-danger">
                                <strong>Attention!</strong> Format date de début inconforme!
                            </div>
                        <?php }else{ 
                         if(isset($_POST['date_fin']) && !validateDate(trim($_POST['date_fin'].' 00:00:00'))){ ?>
                            <div class="alert alert-danger">
                                <strong>Attention!</strong> Format date de fin inconforme!
                            </div>
                        <?php }else{
                        if(isset($_POST['titre']) && empty(trim($_POST['titre']))){ ?>
                            <div class="alert alert-danger">
                                <strong>Attention!</strong> le titre de la demande de congé est vide!
                            </div>
                        <?php }else{
                         if(isset($_POST['contenu']) && empty(trim($_POST['contenu']))){ ?>
                                <div class="alert alert-danger">
                                    <strong>Attention!</strong> le contenu de la demande de congé est vide!
                                </div>
                        <?php }else{ if(isset($_POST['date_depart']) && isset($_POST['date_fin']) 
                            && strtotime(trim($_POST['date_depart'])) >= strtotime(trim($_POST['date_fin']))){ ?>
                            <div class="alert alert-danger">
                                <strong>Attention!</strong> La date de départ de votre demande de 
                                    congé est supérieure ou égale à celle de la date de fin!
                            </div>
                        <?php }
                    
                    }}}} ?>
                        <form method="POST" id="myForm3">
                            <input name="statut" value="<?php if(isset($_SESSION['statut'])) echo $_SESSION['statut']; ?>" type="hidden"/>
                            <button name="retour_liste_consultation" onClick="myFunction(3);" class="btn btn-primary">Revenir à la liste des consultations</button><br/><br/>
                        </form>
                        <form method="POST">
                            <input name="salarie" value="<?php if(isset($_POST['salarie'])) echo $_POST['salarie']; ?>" type="hidden"/>
                            <div class="form-group">
                                <label for="titre">Titre de la demande de congé : </label>
                                <input type="text" class="form-control" placeholder="Entrer le titre de votre demande de conger..." 
                                    name="titre" required value="<?php if(isset($_POST['titre'])) echo $_POST['titre']; ?>"/>
                            </div>

                            <div class="row form-group">
                                <div class="col-lg-2">Du : </div>
                                <div class="col-lg-4">
                                    <input type="text" class="form-control" placeholder="2001-01-01" name="date_depart" 
                                        value="<?php if(isset($_POST['date_depart'])) echo $_POST['date_depart']; ?>" required/>
                                </div>
                                <div class="col-lg-2">au : </div>
                                <div class="col-lg-4">
                                    <input type="text" class="form-control" placeholder="2001-01-01" name="date_fin" 
                                        value="<?php if(isset($_POST['date_fin'])) echo $_POST['date_fin']; ?>" required/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="pwd">Commentaire :</label>
                                <textarea name="contenu" type="text" class="form-control" placeholder="Enter votre commentaire..." required><?php if(isset($_POST['contenu'])) echo $_POST['contenu']; ?></textarea>
                            </div>
                            <button name="soumise" type="submit" class="btn btn-primary">Soumettre</button>
                        </form>
                        <?php }else{
                                if(isset($_POST['choix_consultation']) && $_POST['choix_consultation'] == 2){ ?>
                                <h3>Récapitulatif de vos demandes de congé</h3>
                                <?php if(isset($rslt['code_erreur']) && $rslt['code_erreur'] == 1){ ?>
                                    <br/><br/><h5>Vous n'avez introduit aucune demande de congé!</h5><br/><br/>
                                <?php }else{ ?>
                                <?php $i = 1;?>
                                    <table style="background-color: white;" class="table">
                                        <tr style="border: 1px solid gray;">
                                            <td style="border: 1px solid gray;"><b>#</b></td>
                                            <td style="border: 1px solid gray;"><b>Titre</b></td>
                                            <td style="border: 1px solid gray;"><b>Date de départ</b></td>
                                            <td style="border: 1px solid gray;"><b>Date de fin</b></td>
                                            <td style="border: 1px solid gray;"><b>Etat de<br/>validation</b></td>
                                        </tr>
                                        <?php foreach($rslt as $item){ ?>
                                        <tr style="border: 1px solid gray;">
                                            <td style="border: 1px solid gray;"><?php echo $i; ?></td>
                                            <td style="border: 1px solid gray;"><?php echo $item['titre'] ?></td>
                                            <td style="border: 1px solid gray;"><?php $dateDepart = new DateTime($item['date_depart']); echo $dateDepart->format('Y-m-d'); ?></td>
                                            <td style="border: 1px solid gray;"><?php $dateFin = new DateTime($item['date_fin']); echo $dateFin->format('Y-m-d'); ?></td>
                                            <td style="border: 1px solid gray; <?php
                                                        switch($item['valide']){
                                                            case 0:
                                                                echo 'color: black;';
                                                            break;
                                                            case 1:
                                                                echo 'color: green;';
                                                            break;
                                                            case 2:
                                                                echo 'color: red;';
                                                            break;
                                                        }
                                                        ?>"><b><?php
                                                        switch($item['valide']){
                                                            case 0:
                                                                echo 'En cours <br/>de validation.';
                                                            break;
                                                            case 1:
                                                                echo 'Accepté';
                                                            break;
                                                            case 2:
                                                                echo 'Refusé';
                                                            break;
                                                        }
                                                        ?></b></td>
                                        </tr>
                                        <?php $i++; } ?>
                                    </table><br/><br/>
                          <?php } ?>
                          <form method="POST" id="myForm3">
                                        <input name="salarie" value="<?php if(isset($_POST['salarie'])) echo $_POST['salarie']; ?>" type="hidden"/>
                                        <input name="statut" value="<?php if(isset($_SESSION['statut'])) echo $_SESSION['statut']; ?>" type="hidden"/>
                                        <button name="retour_liste_consultation" onClick="myFunction(3);" class="btn btn-primary">Revenir à la liste des consultations</button><br/><br/>
                                    </form>
                        <?php }else{ ?>
                                <?php if(isset($rslt['code']) && $rslt['code'] == 0){ ?>
                                    <div class="alert alert-danger">
                                        <strong>Erreur d'identification!</strong> : Vous n'avez pas introduit le bon numéro d'identification!
                                    </div><br/>
                                    <form method="POST" id="myForm4">
                                        <button name="login" onClick="myFunction(4);" class="btn btn-primary">Retourner à la page d'identification</button>
                                    </form>
                                <?php                                 
                                    }else{
                                        if(isset($_POST['choix_consultation']) && $_POST['choix_consultation'] == 4){ ?>
                                        <h3>Récapitulatif des demandes de congé de vos salariés</h3><br/>
                                        <form method="POST" id="myForm3">
                                                <input name="salarie" value="<?php if(isset($_POST['salarie'])) echo $_POST['salarie']; ?>" type="hidden"/>
                                                <input name="statut" value="<?php if(isset($_SESSION['statut'])) echo $_SESSION['statut']; ?>" type="hidden"/>
                                                <button name="retour_liste_consultation" onClick="myFunction(3);" class="btn btn-primary">Revenir à la liste des consultations</button><br/><br/>
                                            </form><br/>
                                        <?php if(isset($rslt['code_erreur']) && $rslt['code_erreur'] == 1){ ?>
                                            <h5>Il n'y a aucune demande de congé de vos salariés!</h5><br/>
                                        <?php }else{ ?>                                
                                        <?php $i = 1;?>
                                            <table style="background-color: white;" class="table">
                                                <tr style="border: 1px solid gray;">
                                                    <td style="border: 1px solid gray;"><b>#</b></td>
                                                    <td style="border: 1px solid gray;"><b>Nom</b></td>
                                                    <td style="border: 1px solid gray;"><b>Prénom</b></td>
                                                    <td style="border: 1px solid gray;"><b>Titre</b></td>
                                                    <td style="border: 1px solid gray;"><b>Date de départ</b></td>
                                                    <td style="border: 1px solid gray;"><b>Date de fin</b></td>
                                                    <td style="border: 1px solid gray;"><b>Etat<br/>validation</b></td>
                                                    <td style="border: 1px solid gray;"><b>Action</b></td>
                                                </tr>
                                                <?php foreach($rslt as $item){ ?>
                                                <tr style="border: 1px solid gray;">
                                                    <td style="border: 1px solid gray;"><?php echo $i; ?></td>
                                                    <td style="border: 1px solid gray;"><?php echo $item['user']['nom'] ?></td>
                                                    <td style="border: 1px solid gray;"><?php echo $item['user']['prenom'] ?></td>
                                                    <td style="border: 1px solid gray;"><?php echo $item['conge']['titre'] ?></td>
                                                    <td style="border: 1px solid gray;"><?php $dateDepart = new DateTime($item['conge']['date_depart']); echo $dateDepart->format('Y-m-d'); ?></td>
                                                    <td style="border: 1px solid gray;"><?php $dateFin = new DateTime($item['conge']['date_fin']); echo $dateFin->format('Y-m-d'); ?></td>
                                                    <td style="border: 1px solid gray; <?php
                                                        switch($item['conge']['valide']){
                                                            case 0:
                                                                echo 'color: black;';
                                                            break;
                                                            case 1:
                                                                echo 'color: green;';
                                                            break;
                                                            case 2:
                                                                echo 'color: red;';
                                                            break;
                                                        }
                                                        ?>"><b><?php
                                                        switch($item['conge']['valide']){
                                                            case 0:
                                                                echo 'En cours <br/>de validation.';
                                                            break;
                                                            case 1:
                                                                echo 'Accepté';
                                                            break;
                                                            case 2:
                                                                echo 'Refusé';
                                                            break;
                                                        }
                                                        ?></b></td>
                                                        <td style="border: 1px solid gray;">
                                                        <form method="POST" id="myForm3">
                                                            <input name="idConge" value="<?php echo $item['conge']['id'] ?>" type="hidden"/>
                                                            <input name="salarie" value="<?php if(isset($_POST['salarie'])) echo $_POST['salarie']; ?>" type="hidden"/>
                                                            <button name="consulter_conge" type="submit" onClick="myFunction(3);" 
                                                                class="btn btn-primary">Consulter</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                                <?php $i++; } ?>
                                            </table><br/><br/>
                                    <?php } 
                                        }else{ 
                                            if(isset($_POST['consulter_conge'])){ ?>
                                            <form method="POST" id="myForm3">
                                                <input name="salarie" value="<?php if(isset($_POST['salarie'])) echo $_POST['salarie']; ?>" type="hidden"/>
                                                <input name="statut" value="<?php if(isset($_SESSION['statut'])) echo $_SESSION['statut']; ?>" type="hidden"/>
                                                <button name="retour_liste_consultation" onClick="myFunction(3);" class="btn btn-primary">Revenir à la liste des consultations</button><br/><br/>
                                            </form><br/>
                                                <form method="POST">                                                    
                                                    <div class="form-group">
                                                        <label for="titre"><b>Titre de la demande de congé : </b></label>
                                                        <input type="text" class="form-control" readonly="true"
                                                            placeholder="Entrer le titre de votre demande de conger..." 
                                                            name="titre" required value="<?php if(isset($rslt['conge']['titre'])) echo $rslt['conge']['titre']; ?>"/>
                                                    </div>
                                                    <input name="idConge" value="<?php if(isset($_POST['idConge'])) echo $_POST['idConge']; ?>" type="hidden"/>                                                
                                                    <div class="row form-group">
                                                        <div class="col-lg-2"><b>Du : </b></div>
                                                        <div class="col-lg-4">
                                                            <input type="text" class="form-control" placeholder="2001-01-01" 
                                                                name="date_depart" readonly="true"
                                                                value="<?php if(isset($rslt['conge']['date_depart'])) echo $rslt['conge']['date_depart']; ?>" required/>
                                                        </div>
                                                        <div class="col-lg-2"><b>au : </b></div>
                                                        <div class="col-lg-4">
                                                            <input type="text" class="form-control" placeholder="2001-01-01" 
                                                                name="date_fin" readonly="true"
                                                                value="<?php if(isset($rslt['conge']['date_fin'])) echo $rslt['conge']['date_fin']; ?>" required/>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="pwd"><b>Commentaire :</b></label>
                                                        <textarea name="contenu" type="text" class="form-control" readonly="true"
                                                            placeholder="Enter votre commentaire..." required><?php if(isset($rslt['conge']['contenu'])) echo $rslt['conge']['contenu']; ?></textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="form-check form-check-inline">
                                                            <input type="radio" id="valide1" onclick="valider()" 
                                                            <?php if(isset($_SESSION['statut']) && $_SESSION['statut'] != 1) echo 'disabled'; ?>
                                                            <?php if(isset($rslt['conge']['valide']) && $rslt['conge']['valide'] == 1) echo 'checked'; ?>
                                                                name="valide" value="1" required>
                                                            <label style="margin:5px;"><b>Accépter</b></label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input type="radio" id="valide2" onclick="valider()" 
                                                            <?php if(isset($_SESSION['statut']) && $_SESSION['statut'] != 1) echo 'disabled'; ?>
                                                            <?php if(isset($rslt['conge']['valide']) && $rslt['conge']['valide'] == 2) echo 'checked'; ?> 
                                                                name="valide" value="2" required>
                                                            <label style="margin:5px;"><b>Refuser</b></label>
                                                        </div>                                                       
                                                    </div>
                                                    <div class="form-group" id="motif" 
                                                    <?php if($rslt['conge']['valide'] == 2){ ?> style="display: block" <?php }else{ ?> style="display: none" <?php } ?>>
                                                        <label for="pwd"><b>Motif du refus :</b></label>
                                                        <textarea <?php if(isset($_SESSION['statut']) && $_SESSION['statut'] != 1) echo 'readonly'; ?> name="motif" type="text" class="form-control"
                                                            placeholder="Enter la raison de votre refus..."><?php if(isset($rslt['conge']['motif']) && !empty($rslt['conge']['motif'])) echo $rslt['conge']['motif']; ?></textarea>
                                                    </div>
                                                    <?php if(isset($_SESSION['statut']) && $_SESSION['statut'] == 1){ ?>
                                                    <button name="verdicte" type="submit" class="btn btn-primary">Soumettre</button>
                                                    <?php } ?>
                                                </form>
                                          <?php 
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    } ?>				
				</div>
			</div>
		</div>
	</section>
</body>
</html>