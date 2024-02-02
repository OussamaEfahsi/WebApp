<?php
// Définit les paramètres du cookie de session
session_set_cookie_params([
   'lifetime' => 60 * 60 * 24, // 1 jour
   'path' => '/',
   'domain' => $_SERVER['HTTP_HOST'],
   'secure' => true,
   'httponly' => true,
   'samesite' => 'Lax',
]);

// Démarre la session
session_start();

// Vérifie si l'utilisateur est connecté, sinon redirige vers la page de connexion
if (!isset($_SESSION['user_name'])) {
    header('location: login_formB.php');
    exit;
}

// Remplacez ces valeurs par vos propres identifiants de base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_db";
$port = "3308";

// Crée la connexion à la base de données
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Vérifie la connexion à la base de données
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Vérifie si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $influencer_id = $_POST['influencer_id'];

    // Redirige vers la page conversations.php pour commencer la conversation avec l'influenceur sélectionné
    header("Location: conversations.php?brand_id={$_SESSION['user_id']}&influencer_id=$influencer_id");
    exit;
}

// Requête SQL pour récupérer la liste des influenceurs
$influencersQuery = "SELECT id, name, file FROM influencer_form1";
$influencersResult = $conn->query($influencersQuery);

?>


<!DOCTYPE html>
<html>
<head>
    <title>Brand Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css"><!-- c'est juste les icons -->
    <link rel="stylesheet" href="brandpage1.css">
    <style>
        .link{
            text-decoration: none;
            color:lightskyblue;

        }
        .link:hover{
            color:yellow;
        } 
        .titre{
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
            color:black;
            text-align: center;
            
        }
        .titre1{
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
            color:purple;
            text-align: center;
            margin-top: 20px;
        }</style>
       
</head>
<body>

<div class="wrapper">

    <input type="checkbox" id="btn" hidden>
    <label for="btn" class="menu-btn">
      <i class="fas fa-bars"></i>
      <i class="fas fa-times"></i>
    </label>
    <nav id="sidebar">
      <div class="title"> Menu</div>
      <ul class="list-items">
       
      
        <li><?php echo "<a href='modification2.php?brand_id={$_SESSION['user_id']}'> <i class='fas fa-user'></i>I.P"; ?></a></li>

          
        <li><a href="logout1.php"><div class="image"><img src="logout.png"><span>Log Out</span></div></a></li>


        
        <div class="icons">
          <a href="#"><i class="fab fa-facebook-f"></i></a>
          <a href="#"><i class="fab fa-twitter"></i></a>
          <a href="#"><i class="fab fa-github"></i></a>
          <a href="#"><i class="fab fa-youtube"></i></a>
        </div>
      </ul>
    </nav>
  </div>

  <h1 class="titre">Welcome, <?php echo $_SESSION['user_name']; ?>!</h1>
   <h1 class="titre1">liste Des Influenceurs :</h1>
   <form method="post">

<div class="container">
         

<?php while ($influencerRow = $influencersResult->fetch_assoc()) { ?>
  <div class="card">
    <?php
    $imageExtensions = array('jpeg', 'jpg', 'png');
    $influencerId = $influencerRow['id'];
    $imagePath = '';

    // Recherche de l'image correspondante pour l'influenceur
    foreach ($imageExtensions as $extension) {
        $imageFilename = "/uploads/influencerid$influencerId.$extension";

        if (file_exists($_SERVER['DOCUMENT_ROOT'] . $imageFilename)) {
            $imagePath = $imageFilename;
            break;
        }
    }
    ?>

    <img src="<?php echo $imagePath; ?>" alt="<?php echo $influencerRow['name']; ?>" class="card__image">
    <p class="card__name"><?php echo $influencerRow['name']; ?></p>
    <input type="hidden" name="influencer_id" value="<?php echo $influencerRow['id']; ?>">
    
    </form>
    
    <!-- Bouton pour envoyer un message -->
    <button class="btn draw-border">
        <a class="link" href="conversations.php?brand_id=<?php echo $_SESSION['user_id']; ?>&influencer_id=<?php echo $influencerRow['id']; ?>">Message</a>
    </button>

    <!-- Bouton pour afficher les informations -->
    <button class="btn draw-border">
        <?php echo '<a class="link" href="description.php?influencerid=' . $influencerId . '">Info</a>'; ?>
    </button>
    
    <!-- Bouton pour afficher le contrat -->
    <button class="btn draw-border">
        <?php echo '<a class="link" href="contract.php?influencer_id=' . $influencerId . '&brand_id=' . $_SESSION['user_id'] . '">contract</a>'; ?>
    </button>
  </div>
<?php } ?>
</div>
</body>
</html>
