<header>
		<nav class="navbar navbar-expand-md navbar-light bg-light">
			<a class="navbar-brand" href="../www/intro.php">
				<img src="logo.png" style="width:54px;">
				ExchCurrency
			</a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>

			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<ul class="navbar-nav ml-auto">
					<li class="nav-item">
						<a class="nav-link" href="../www/intro.php">Главная</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="../www/kursval.php">Курсы валют</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="../www/otdel.php">Отделения</a>
					</li>
					<li class="nav-item mr-3">
						<!-- Если авторизован выведет логин -->
						<?php if (isset($_SESSION['auth'])) : ?>
							<?php include_once '../phpscripts/oneFromDB.php';
							$login = getDB($_SESSION['auth'], $link); ?>
							<a href="lichkab.php"><?php echo $login['login']; ?></a></br>

							<!-- Пользователь может нажать выйти для выхода из системы -->
							<a href="logout.php">
								<center>Выйти</center>
							</a>
						<?php else : ?>

							<!-- Если пользователь не авторизован выведем кнопку на авторизацию -->
							<a href="../login.php" class="btn btn-outline-secondary mr-3">Войти в аккаунт</a>
						<?php endif; ?>
					</li>
				</ul>
				<form class="form-inline">
					<input class="form-control" type="search" placeholder="Search" aria-label="Search">
					<button class="btn btn-outline-primary" type="submit">Search</button>
				</form>
			</div>
		</nav>
	</header>