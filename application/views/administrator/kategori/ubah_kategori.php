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
	                    <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Home</a></li>
						<li class="breadcrumb-item"><a href="<?= base_url('admin/kategori') ?>">Kategori</a></li>
						<li class="breadcrumb-item active">Ubah Kategori</li>
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
							<h3 class="card-title font-weight-bold" style="font-size: 1.5rem;"><i class="fas fa-edit mr-2 text-primary"></i>Ubah Kategori</h3>

							<p class="card-text">
								<form method="post">
									<div class="mb-3">
										<label for="nama_kategori" class="form-label">Nama Kategori</label>
										<input type="text" class="form-control" name="nama_kategori" id="nama_kategori" value="<?= $kategori['nama'] ?>" aria-describedby="Nama Kategori">
									</div>
									<div class="mb-3">
										<label for="deskripsi_kategori" class="form-label">Deskripsi</label>
										<textarea name="deskripsi_kategori" id="deskripsi_kategori" cols="30" rows="10" class="form-control"><?= $kategori['deskripsi'] ?></textarea>
									</div>
									<button type="submit" class="btn btn-primary">Ubah</button>
									<a href="<?= base_url('admin/kategori') ?>" class="btn btn-danger">Kembali</a>
								</form>
							</p>
						</div>
					</div>

				</div>
				<!-- /.col-md-6 -->

			</div>
			<!-- /.row -->
		</div><!-- /.container-fluid -->
	</div>
	<!-- /.content -->
</div>
<!-- /.content-wrapper -->