<aside class="sidebar">
    <div class="user-profile">
        <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['username'])?>&background=random" alt="Perfil">
        <h3><?php echo htmlspecialchars($_SESSION['username'])?></h3>
    </div>
    <nav class="menu">
        <ul>
            <li><a href="index.php"><i class="fas fa-home"></i> Recepcion Principal</a></li>
            <li><a href="reportes.php"><i class="fas fa-file-alt"></i> Reportes</a></li>
        </ul>
    </nav>
</aside>