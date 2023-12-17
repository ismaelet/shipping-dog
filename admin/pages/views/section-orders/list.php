<?php /*<div class="list-wrap">
	<div class="list">
		<div class="list-head">
			<h1 class="list-title txt-wht bgr-c1">Lista ordini</h1>
					
			<form class="list-srch-box" method="GET">
				<input type="text" class="list-srch inp" name="search" placeholder="Cerca un nome,  email, ID, design, data, stato, commento" value="<?= $search;?>">
				<button type="submit" class="hide"></button>

				<p class="txt padt1">Etichette internazionali: <?= $intN;?>, cambi: <?= $chanN;?></p>

				<a href="add" class="sbtn wid100">Aggiungi un ordine</a>
			</form>
		</div>


		<div class="list-body">
			<div class="list-cont">
				<div class="list-tbl">
					<div class="list-tbl-head">

						<p class="list-name list-col-xsh">ID</p>
						<p class="list-name list-col-shr">Dati</p>
						<p class="list-name list-col-med">Indirizzo</p>
						<p class="list-name list-col-med">Contatti</p>
						<p class="list-name list-col-lon">Articoli</p>
						<p class="list-name list-col-shr">Totale</p>
						<p class="list-name list-col-shr">Stato</p>
						<p class="list-name list-col-btn"></p>
					</div>
			
					<?php
						$i = 1; while ($row = $res_sel->fetch_assoc()) {$i++;
						
						$cart = new Cart($row['ID'], ['buyer', 'payID', 'tot', 'time', 'code']);

						$row_data = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM carts_data WHERE cartID=$cart->id"));
						$res_ord = DB::select('ID FROM orders WHERE cartID=? ORDER BY time ASC', [$cart->id]);
						$ship = strtoupper(mysqli_fetch_assoc(mysqli_query($con, "SELECT name FROM sys_shipping WHERE ID=$cart->shipID"))['name']);

						if ($row_addr = DB::row('* FROM addresses WHERE cartID=?', $cart->id)) {
							$country = in_array($row_addr['country'], array('Italy', 'Italia')) ? '' : "<br>".$row_addr['country'];
							$region = empty($row_addr['region']) ? '' : ' ('.$row_addr['region'].')';
							$phone = $row_addr['phone'];
						}

						$pay_str = $cart->payID == 1 ? 'PayPal' : 'Stringipe';
						if (!$cart->tot) $pay_str = 'Promozione';?>
					
						<form id="row<?= $cart->id;?>" class="list-row <?php if ($i % 2) echo 'dark';?>" method="POST">
							<input type="hidden" name="id" value="<?= $cart->id;?>">
							<div class="list-cell cartID-cell">
								<div class="cart-line cover status<?= $cart->status;?>"></div>
								<p class="cartID list-inp"><?= $cart->id;?></p>
							</div>

							<div class="list-cell">
								<p class="list-inp">
									<?php
										echo date('d/m/Y', strtotime($cart->time)).'<br>';
										if ($cart->customer[0] != 'T') echo 'ID: '.$cart->customer.'<br>';
										echo $pay_str.'<br>';
										echo $ship.'<br>';
										if (!empty($cart->code)) echo $cart->code.'<br>';
										if (!empty($row_data['comm_vip'])) echo 'NOTA: <strong>'.$row_data['comm_vip'].'</strong><br>';
									?>
								</p>
							</div>

							<div class="list-cell">
								<p class="list-inp">
									<?= $row_addr['aname'] ?? '';?><br>
									<?= $row_addr['address'] ?? ''; echo isset($row_addr['number']) ? ', '.$row_addr['number'] : '';?><br>
									<?= $row_addr['zip'] ?? '';;?> <?= $row_addr['city'] ?? ''; echo $region ?? '';?><br>
									<?= $country ?? '';?>
								</p>
							</div>

							<div class="list-cell">
								<p class="list-inp">
									<?= $row_addr['email'] ?? '';?><br>
									<?= $phone ?? '';?>
								</p>
							</div>

							<div class="list-cell">
								<p class="list-inp">
									<?php

									if ($res_ord->num_rows) while ($row_ord = $res_ord->fetch_assoc()) {
									
										$order = new Order($row_ord['ID'], 'desc');

										echo $order->desc[0].'<br>';

									} else echo '- Nessun articolo registrato -';

									if ($cart->status > 0  && $cart->status < 3) {?>

										<a href="edit?id=<?= $cart->id;?>">
											<button type="button" class="edit-btn blk clickable">Modifica</button>
										</a>

									<?php } ?>
								</p>
							</div>

							<!-- Total -->
							<div class="list-cell"><p class="list-inp"><?= format($cart->tot['all']);?> €</p></div>

							<!-- Status -->
							<div class="list-cell">
								<p class="list-inp txt-line">
									<?php
										switch($cart->status) {
											case 5: $cart->status_str = 'CAMBIO'; break;
											case 4: $cart->status_str = 'CONSEGNATO'; break;
											case 3: $cart->status_str = 'SPEDITO'; break;
											case 2: $cart->status_str = 'ELABORATO'; break;
											case 1: $cart->status_str = 'ELABORAZIONE'; break;
											case 0: $cart->status_str = 'NON COMPLETATO'; break;
											case -1: $cart->status_str = 'CANCELLATO'; break;
										} echo $cart->status_str;
									?>
								</p>
							</div>

							<!-- Buttons -->
							<div class="list-cell">												
								<?php if (in_array($cart->status, [-1, 0])) {?>
									<button type="submit" class="list-btn blu" name="proc">Elabora</button>
									<button type="submit" class="list-btn red" name="delete" onclick="return confirm('Sei sicuro di voler eliminare l\'ordine?');">Elimina</button>

								<?php } if (in_array($cart->status, [1, 5])) {?>
									<button type="submit" class="list-btn ora" name="ready">Pronto</button>
									<button type="submit" class="list-btn red" name="cancel">Cancella</button>

								<?php } if (in_array($cart->status, [1, 2])) {?>
									<a href="address?id=<?= $cart->id;?>" class="list-btn blu">Indirizzo</a>
									<button type="submit" class="list-btn gre" name="email" onclick="return confirm('Sei sicuro di voler rimandare l\' email di conferma?');">email</button>

								<?php } if (in_array($cart->status, [2])) {?>
									<button type="submit" class="list-btn ora" name="ship">Spedisci</button>

								<?php } if (in_array($cart->status, [2, 3])) {?>
									<button type="submit" class="list-btn gre" name="reship">Riaggiungi</button>
									<button type="submit" class="list-btn red" name="return">Non Pronto</button>

								<?php } if (in_array($cart->status, [3])) {?>
									<button type="submit" class="list-btn ora" name="end">Consegnato</button>

								<?php }?>
							</div>
						</form>
					<?php }?>
				</div>
			</div>
		</div>
	</div>
</div>*/
