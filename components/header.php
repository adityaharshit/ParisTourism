<?php
session_start();
?>
    <div class="container-fluid navbar fixed-top">
        <div class="container">

            <a class="navbar-brand" href="#">Explore Paris</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="index.html">Home</a></li>
                <?php if(isset($_SESSION['username'])): ?>
                <li class="nav-item"><a class="nav-link" href="explore.html">Explore</a></li>
                <li class="nav-item"><a class="nav-link" href="cuisine.html">Cuisine</a></li>
                <li class="nav-item"><a class="nav-link" href="events.php">Events</a></li>
                <li class="nav-item"><a class="nav-link" href="favorites.php">Favorites</a></li>
                <?php endif; ?>
                <!-- <li class="nav-item"><a class="nav-link" href="faq.php">FAQ</a></li> -->
                 <!-- display if the php session named username is set, else hide it -->
                <?php if(isset($_SESSION['username'])): ?>
                <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                <?php else: ?>
                <li class="nav-item"><a class="nav-link" href="login.html">Login/Sign Up</a></li>
                <?php endif; ?>

            </ul>
        </div>
        </div>
    </div>