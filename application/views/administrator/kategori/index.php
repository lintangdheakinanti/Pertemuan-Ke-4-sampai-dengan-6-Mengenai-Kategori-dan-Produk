<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
	    <div class="container-fluid">
	        <div class="row mb-2 align-items-center">
	            <div class="col-sm-6">
	                <h1 class="m-0 text-dark font-weight-bold">
	                    <i class="fas fa-tags mr-2 text-primary"></i> Kategori
	                </h1>
	            </div><!-- /.col -->
	            <div class="col-sm-6">
	                <ol class="breadcrumb float-sm-right bg-transparent p-0 m-0">
	                    <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>"> Home</a></li>
	                    <li class="breadcrumb-item active">Kategori</li>
	                </ol>
	            </div><!-- /.col -->
	        </div><!-- /.row -->
	    </div><!-- /.container-fluid -->
	</div><!-- /.content-header -->

	<!-- Main content -->
	<div class="content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-12">
					<div class="card">
						<div class="card-body">
							<h5 class="card-title" style="font-size: 1.5rem;"><b>Data Kategori</b></h5>
							<div class="clearfix"></div>
							<br>
							
							<!-- Tombol Tambah -->
							<a href="<?= base_url('admin/kategori/tambah') ?>" class="btn btn-labeled btn-primary mb-3">
								<span class="btn-label"><i class="fas fa-plus-circle"></i></span> Tambah Kategori
							</a>

							<!-- Flashdata Message -->
							<?php if ($this->session->flashdata('message')) : ?>
								<?= $this->session->flashdata('message') ?>
							<?php endif ?>

							<!-- Tabel Data -->
							<table class="table table-bordered table-hover">
								<thead>
									<tr>
										<th style="width: 45px;" class="text-center">No.</th>
										<th>Nama</th>
										<th>Deskripsi</th>
										<th class="text-center">Action</th>
									</tr>
								</thead>
								<tbody>
									<?php 
									$no = 1;
									foreach ($list_kategori as $kategori) : 
									?>
										<tr data-widget="expandable-table" aria-expanded="false">
											<td class="text-center"><?= $no ?></td>
											<td><?= $kategori['nama'] ?></td>
											<td><?= $kategori['deskripsi'] ?></td>
											<td class="text-center">
											   <a href="<?= base_url('admin/kategori/ubah/') ?><?= $kategori['id_kategori'] ?>">
											        <span class="badge bg-success"><i class="fas fa-edit"></i> Ubah</span>
											   </a>
											   &nbsp;&nbsp;
											   <a href="<?= base_url('admin/kategori/hapus/') ?><?= $kategori['id_kategori'] ?>" onclick="return confirm('Yakin ingin menghapus data ini?')">
											        <span class="badge bg-danger"><i class="fas fa-trash"></i> Hapus</span>
											   </a>
											</td>
										</tr>
									<?php 
										$no++; 
									endforeach 
									?>
								</tbody>
							</table>
						</div>
					</div>
				</div><!-- /.col-md-6 -->
			</div><!-- /.row -->
		</div><!-- /.container-fluid -->
	</div>
</div>