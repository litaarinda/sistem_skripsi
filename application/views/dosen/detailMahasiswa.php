<head> 
	<script type="text/javascript">

		$(document).ready(function(){

			$(".status").on('submit',
				function(e) {
					e.preventDefault();
					var form = $(this);
					var formdata = false;
					var id = $(this).attr("id");
					var nama = $(this).attr('nama');


					if (window.FormData) {
						formdata = new FormData(form[0]);
					}	
					swal({
						title: nama+" Akan Di ACC",
						text: "Sekali Di ACC Tidak Akan Bisa Diubah!",
						icon: "warning",
						buttons: true,
						dangerMode: true,
					})
					.then((willDelete) => {
						if (willDelete) {
							$.ajax({
								type: 'POST',
								url: form.attr('action'),
								data: formdata ? formdata: form.serialize(),
								contentType: false,
								processData: false,
								cache: false,
								beforeSend: function() {
									$('.loading').show();
								},
								success: function() {
									$('.loading').fadeOut('slow');
									$(".btn" + id).prop("disabled",true);
									$("#Skripsi").fadeIn("slow");
								}
							});
						} else {
							swal("Skripsi Batal di ACC");
						}
					});
				});
		});

		$(document).ready(function(){
			$(".catatan").on('submit',
				function(e) {
					e.preventDefault();
					var form = $(this);
					var formdata = false;
					var ID = form.attr('id');

					if (window.FormData) {
						formdata = new FormData(form[0]);
					}

					var formAction = form.attr('action')+ID;

					$.ajax({
						type: 'POST',
						url: formAction,
						data: formdata ? formdata: form.serialize(),
						contentType: false,
						processData: false,
						cache: false,
						beforeSend: function() {
							$('.loading').show();
						},
						success: function() {
							location.reload();
						}
					});
				});
		});

	</script>
</head>
<?php foreach ($skripsi->result() as $u) {
	?>
	<div class="container-fluid">
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-md-2 mr-3">
						<img class="card-img-top" src="<?= base_url('assets/images/User/'.$u->Foto) ;?>">
					</div>
					<div class="col-md">
						<div class="form-row">
							<div class="form-group col-md-5">
								<p class="h5"> <?= $u->Nama;?> / <?= $u->ID;?>  </p>
								<p class="text-subtitle h6"><i class="fas fa-envelope fa-sm"></i> <?= $u->Email;?> <br> 
									<i class="fas fa-phone fa-sm"></i> No. HP : <?= $u->NoHP;?> 
								</p>
							</div>						
							<div class="form-group col-md">
								<div>
									<h4><?= $u->JudulSkripsi;?></h4>
								</div>
							</div>
							<div class="form-group">
								<small><?= word_limiter($u->Deskripsi, 200) ;?></small>
							</div>
						</div>
						<?php foreach ($pembimbing->result() as $p) {
							if ($u->IDSkripsi === $p->IDSkripsiPmb) {
								$StaProposal = $p->StatusProposal;
								$StaSkripsi = $p->StatusSkripsi;
								?>

								<div class="form-row">
									<div class="form-group">
										<a class="btn-sm"
										<?php if (empty($u->FileProposal)) {
											echo "";
										} else {
											echo "href=".base_url("ControllerGlobal/downloadFile/".$u->FileProposal);
										} ?>> <i class="fa fa-download"></i> </a>
									</div>
									<div class="form-group ml-2">
										<form class="status" id="<?= $p->IDPembimbing;?>" nama="Proposal" method="POST" action="<?= base_url('Dosen/accUsers/'.$u->IDSkripsi.'/Proposal');?>">
											<input type="submit" class="btn<?= $p->IDPembimbing;?> btn btn-outline-primary btn-sm" value="Menyetujui Proposal" 
											<?php if ($StaProposal) {
												echo 'disabled';
											} else {
												echo "";
											} ?> >
										</form>
									</div> 
									<div class="form-group ml-2">
										<a class="btn-sm"
										<?php if (empty($u->FileSkripsi)) {
											echo "";
										} else {
											echo "href=".base_url("ControllerGlobal/downloadFile/".$u->FileSkripsi);
										} ?>> <i class="fa fa-download"></i> </a>
									</div>
									<div class="form-group ml-2">
										<div id="Skripsi" class="form-row" <?php if (!$StaProposal) {
											echo 'style="display: none"';	} ?>>
											<div class="form-group col-md"> 
												<form id="<?= $p->IDPembimbing;?>" class="status" nama="Skripsi" method="POST" action="<?= base_url('Dosen/accUsers/'.$u->IDSkripsi.'/Skripsi');?>">
													<input type="submit" class="btn<?= $p->IDPembimbing;?> btn btn-outline-primary btn-sm" value="Menyetujui Skripsi" 
													<?php if ($proposal->num_rows() < 2) {
														echo 'disabled';
														$status = 'Proposal Telah diSetujui mohon tunggu acc dari pemmbimbing yang lain untuk dapat mengacc Skripsi';
													} else {
														echo "";
														$status = '';
													} ?>>
												</form>
											</div>
										</div>
									</div>
									<div class="form-group ml-5">
										<?= $status ?>
									</div>
								</div>
							<?php } } ?>
						</div>
					</div>
					<?php if ($pembimbing->num_rows() > 0) { ?>
						<form id="<?= $u->ID ?>" method="POST" action="<?= base_url('dosen/catatan/');?>" class="catatan">
							<div class="form-group">
								<h6 class="text-right"> Catatan Bimbingan </h6>
								<textarea class="form-control" name="note"></textarea>
							</div>
							<div class="form-group">
								<input class="btn btn-primary" type="submit" name="submit">
							</div>
						</form>
					<?php } } ?>
					<hr>
					<div class="form-row">
						<div class="form-group col-8">
							<h5> <i class="fas fa-pencil-alt fa-xs"></i> Kartu Bimbingan </h5>	
						</div>

						<?php foreach ($skripsi->result() as $p);
						{ ?>
							<div class="form-group col-4 text-right">
								<a href="<?= base_url('Cetak/kartu/'.$p->ID);?>"> <button class="btn btn-outline-primary btn-sm"> <i class="fas fa-print"></i> Cetak</button>		</a>
							</div>
						<?php } ?>
					</div>
					<div class="table-responsive">
						<table class="table small">
							<thead>
								<tr>
									<th>No</th>
									<th>Tanggal</th>
									<th>Pembimbing</th>
								</tr>
							</thead>
							<tbody>
								<?php $no = 1 ?>
								<?php foreach ($konsultasi->result() as $k) { ?>
									<tr>
										<td><?= $no++;?></td>
										<td><?= longdate_indo($k->TanggalBimbingan);?></td>
										<td><?= $k->Nama;?></td>
									</tr>
									<tr>
										<th> Catatan </th>
										<td colspan="2"><?= $k->Catatan;?></td>

									</tr>
								<?php } ?>	

							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>