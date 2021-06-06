<?php
session_start();

session_unset(); // Détruit les variables de session
session_destroy();

setcookie('utilisateur', '', time() - 3600); // cookie

?>

<script>
    document.location.href = '/portfolio/index.php';
    </script>

<body>

</body>

</html>