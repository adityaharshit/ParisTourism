<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Events</title>
    <script
      src="https://kit.fontawesome.com/871106cf4e.js"
      crossorigin="anonymous"
    ></script>
    <link rel="stylesheet" href="css/responsive.css" />
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/styles.css" />
  </head>

  <body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg"></nav>


    <!-- Hero Section -->
    <section class="hero hero-explore">
        <div class="container d-flex justify-content-center align-items-center flex-column">
            <h1>Explore the Romantic Allure of Paris</h1>
            <p>Discover the magic and romance that Paris has to offer</p>
        </div>
        <div class="container">
        </div>
    </section>


    <section class="cards">
      
    
      <?php
      $conn = new mysqli("localhost", "root", "", "skilldev");

      // Fetch events from the database
      $query = "SELECT * FROM Events where EId in (SELECT EId FROM favorites WHERE UId = ".$_SESSION['UserID'].")";
      $result = $conn->query($query);

      if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
              $EId = $row['EId'];
              $title = $row['Title'];
              $date = $row['Date'];
              $time = $row['Time'];
              $venue = $row['Venue'];
              $image_url = $row['Image_url'];
              $image_url = '"'.$image_url.'"';
              // Check if the user has favorited this event
              $UserID = $_SESSION['UserID']; // Assuming the user is logged in and UserID is stored in session
              $checkFav = $conn->prepare("SELECT * FROM favorites WHERE UId = ? AND EId = ?");
              $checkFav->bind_param("ii", $UserID, $EId);
              $checkFav->execute();
              $favResult = $checkFav->get_result();
              $isFavorited = ($favResult->num_rows > 0) ? true : false;

              // Output card dynamically
              echo "
              <article class='card card--$EId'>
                  <div class='card__info-hover'>
                      <svg class='card__like ".($isFavorited ? "favorited" : "")."' id='like_$EId' viewBox='0 0 24 24' onclick='toggleFavorite($EId)'>
                          <path fill='".($isFavorited ? "#ff0000" : "#00ff00")."' d='M12.1,18.55L12,18.65L11.89,18.55C7.14,14.24 4,11.39 4,8.5C4,6.5 5.5,5 7.5,5C9.04,5 10.54,6 11.07,7.36H12.93C13.46,6 14.96,5 16.5,5C18.5,5 20,6.5 20,8.5C20,11.39 16.86,14.24 12.1,18.55M16.5,3C14.76,3 13.09,3.81 12,5.08C10.91,3.81 9.24,3 7.5,3C4.42,3 2,5.41 2,8.5C2,12.27 5.4,15.36 10.55,20.03L12,21.35L13.45,20.03C18.6,15.36 22,12.27 22,8.5C22,5.41 19.58,3 16.5,3Z' />
                      </svg>
                      <div class='card__clock-info'>
                          <svg class='card__clock' viewBox='0 0 24 24'>
                              <path d='M12,20A7,7 0 0,1 5,13A7,7 0 0,1 12,6A7,7 0 0,1 19,13A7,7 0 0,1 12,20M19.03,7.39L20.45,5.97C20,5.46 19.55,5 19.04,4.56L17.62,6C16.07,4.74 14.12,4 12,4A9,9 0 0,0 3,13A9,9 0 0,0 12,22C17,22 21,17.97 21,13C21,10.88 20.26,8.93 19.03,7.39M11,14H13V8H11M15,1H9V3H15V1Z' />
                          </svg>
                          <span class='card__time'>$time</span>
                      </div>
                  </div>
                  <div class='card__img' style='background-image: url($image_url)'></div>
                  <div class='card_link' onclick='toggleFavorite($EId)'>
                      <div class='card__img--hover' style='background-image: url($image_url)'></div>
                  </div>
                  <div class='card__info'>
                      <span class='card__category'>$date</span>
                      <h3 class='card__title'>$title</h3>
                      <span class='card__by'>at <a href='' class='card__author' title='author'>$venue</a></span>
                  </div>
              </article>";
          }
      }
      ?>

    
    
    </section>

    <!-- Footer section -->
    <div class="custom-footer"></div>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/jquery.magnific-popup.min.js"></script>
    <script src="js/jquery.waypoints.js"></script>
    <script src="js/jquery.counterup.min.js"></script>
    <script src="js/jquery.barfiller.js"></script>
    <script src="js/index.js"></script>

    <script>
    function toggleFavorite(EId) {
        const likeIcon = document.getElementById(`like_${EId}`);
        
        // Send AJAX request to toggle the favorite status
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'toggle_favorite.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            if (this.status == 200) {
                // On success, toggle the icon color
                const response = JSON.parse(this.responseText);
                if (response.status == "favorited") {
                    likeIcon.firstElementChild.setAttribute("fill", "#ff0000"); // Red color for favorited
                    likeIcon.classList.add("favorited");
                } else if (response.status == "unfavorited") {
                    likeIcon.firstElementChild.setAttribute("fill", "#000000"); // Default black color
                    likeIcon.classList.remove("favorited");
                }
            }
        };
        
        // Send EId and current status to PHP
        xhr.send(`EId=${EId}`);
    }
    </script>

  </body>
</html>



