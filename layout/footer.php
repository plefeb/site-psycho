<div id="footer-wrapper">
	<div id="footer" class="container">
		<header class="major">
			<h2>Formulaire de contact</h2>
			<p>
				N'hésitez pas à nous envoyer votre demande, nous vous répondrons dans les meilleurs délais.<br /><br />
				Nous sommes également joignables aux numéros suivants : <br />
				Marie SLOSSE : <strong>06.01.02.03.04</strong> <br />
				Camille MARCQ : <strong>07.67.65.34.65</strong>
			</p>
		</header>
		<div class="row">
			<section class="6u 12u(narrower)">
				<?php
                    /*
                     * *******************************************************************************************
                     * CONFIGURATION
                     * *******************************************************************************************
                     */
				
                    // destinataire est votre adresse mail. Pour envoyer a plusieurs a la fois, separez-les par une virgule
                    $destinataire = 'pierre.lefebvre.5@free.fr,pilou.lefebure@gmail.com';
                    
                    // Action du formulaire (si votre page a des parametres dans l'URL)
                    // si cette page est index.php?page=contact alors mettez index.php?page=contact
                    // sinon, laissez vide
                    $form_action = '';
                    
                    // Messages de confirmation du mail
                    $message_envoye = "Votre message a bien été envoyé.";
                    $message_non_envoye = "L'envoi du mail a échoué, veuillez réessayer SVP.";
                    
                    // Message d'erreur du formulaire
                    $message_formulaire_invalide = "Vérifiez que tous les champs soient bien remplis et que l'email soit sans erreur !";
                    
                    /*
                     * *******************************************************************************************
                     * FIN DE LA CONFIGURATION
                     * *******************************************************************************************
                     */
                    
                    /*
                     * cette fonction sert a nettoyer et enregistrer un texte
                     */
                    function Rec($text)
                    {
                        $text = htmlspecialchars(trim($text), ENT_QUOTES);
                        if (1 === get_magic_quotes_gpc()) {
                            $text = stripslashes($text);
                        }
                        
                        $text = nl2br($text);
                        return $text;
                    }
                    ;
                    
                    /*
                     * Cette fonction sert a verifier la syntaxe d'un email
                     */
                    function IsEmail($email)
                    {
                        $value = preg_match('/^(?:[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+\.)*[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+@(?:(?:(?:[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!\.)){0,61}[a-zA-Z0-9_-]?\.)+[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!$)){0,61}[a-zA-Z0-9_]?)|(?:\[(?:(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\.){3}(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\]))$/', $email);
                        return (($value === 0) || ($value === false)) ? false : true;
                    }
                    
                    // formulaire envoye, on recupere tous les champs.
                    $nom = (isset($_POST['nom'])) ? Rec($_POST['nom']) : '';
                    $email = (isset($_POST['email'])) ? Rec($_POST['email']) : '';
                    $objet = (isset($_POST['objet'])) ? Rec($_POST['objet']) : '';
                    $message = (isset($_POST['message'])) ? Rec($_POST['message']) : '';
                    
                    // On va verifier les variables et l'email ...
                    $email = (IsEmail($email)) ? $email : ''; // soit l'email est vide si errone, soit il vaut l'email entre
                    $err_formulaire = false; // sert pour remplir le formulaire en cas d'erreur si besoin
                    
                    if (isset($_POST['envoi'])) {
                        if (($nom != '') && ($email != '') && ($objet != '') && ($message != '')) {
                            // les 4 variables sont remplies, on genere puis envoie le mail
                            $headers  = 'MIME-Version: 1.0' . "\r\n";
                            $headers .= 'From:'.$nom.' <'.$email.'>' . "\r\n" .
                                'Reply-To:'.$email. "\r\n" .
                                'Content-Type: text/plain; charset="utf-8"; DelSp="Yes"; format=flowed '."\r\n" .
                                'Content-Disposition: inline'. "\r\n" .
                                'Content-Transfer-Encoding: 7bit'." \r\n" .
                                'X-Mailer:PHP/'.phpversion();
                            
                            // Remplacement de certains caracteres speciaux
                            $message = str_replace("&#039;", "'", $message);
                            $message = str_replace("&#8217;", "'", $message);
                            $message = str_replace("&quot;", '"', $message);
                            $message = str_replace('&lt;br&gt;', '', $message);
                            $message = str_replace('&lt;br /&gt;', '', $message);
                            $message = str_replace("&lt;", "&lt;", $message);
                            $message = str_replace("&gt;", "&gt;", $message);
                            $message = str_replace("&amp;", "&", $message);
                            
                            // Envoi du mail
                            if (mail($destinataire, $objet, $message, $headers)) {
                                echo '<p>'.$message_envoye.'</p>';
                            } else {
                                echo '<p>'.$message_non_envoye.'</p>';
                            }
                            ;
                        } else {
                            // une des 3 variables (ou plus) est vide ...
                            echo '<p><strong>'.$message_formulaire_invalide.'</strong></p>';
                            $err_formulaire = true;
                        }
                        ;
                    }
                    ; // fin du if (!isset($_POST['envoi']))
                    
                    if (($err_formulaire) || (!isset($_POST['envoi']))) {
                        // afficher le formulaire
                        echo '
                            <form method="post" action="'.$form_action.'">
                    			<div class="row 50%">
                    				<div class="6u 12u(mobile)">
                    					<input name="nom" placeholder="Nom" type="text" required="required" value="'.stripslashes($nom).'"/>
                    				</div>
                    				<div class="6u 12u(mobile)">
                    					<input name="email" placeholder="Email" type="text" required="required" value="'.stripslashes($email).'"/>
                    				</div>
                    			</div>
                    			<div class="row 50%">
                    				<div class="12u">
                    					<input name="objet" placeholder="Objet de la demande" type="text" required="required" value="'.stripslashes($objet).'"/>
                    				</div>
                    			</div>
                    			<div class="row 50%">
                    				<div class="12u">
                    					<textarea name="message" placeholder="Message" required="required">'.stripslashes($message).'</textarea>
                    				</div>
                    			</div>
                    			<div class="row 50%">
                    				<div class="12u">
                    					<ul class="actions">
                    						<li><input type="submit" name="envoi" value="Envoyer" /></li>
                    					</ul>
                    				</div>
                    			</div>
                    		</form>
                    	';
                    }
                    ;
                ?>
				
			</section>
			<section class="6u 12u(narrower)">
				<h2>Adresse</h2>
				<p>
					Rue Raoul Briquet <br /> 62 138 Auchy-les-Mines
				</p>
				<iframe
					src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d897.7658593538744!2d2.7858806618538354!3d50.50910220440428!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNTDCsDMwJzMyLjgiTiAywrA0NycxMi4wIkU!5e0!3m2!1sfr!2sfr!4v1515849070416"
					width="100%" height="300"></iframe>
			</section>
		</div>
	</div>
	<div id="copyright" class="container">
		<ul class="menu">
			<li>Mme SLOSSE _ N° SIRET : 000 000 000 00000 _ N° ADELI : 00 0000000</li>
			<li>Mme MARCQ _ N° SIRET : 833 628 175 00014 _ N° ADELI : 62 9309766</li>
		</ul>
		<ul class="menu">
			<li>Developped by <a href="https://github.com/plefeb/">plefeb</a> _ Based on a <a href="http://html5up.net">HTML5 UP</a> Theme</li>
			<li>v0.1</li>
		</ul>
	</div>
</div>