<!DOCTYPE html> 
<html lang="fr">
<head>
    <title><?php echo $title; ?></title>
</head>
<body>
    <header>
        <nav class="menu">
            <ul>
                <?php
                    foreach ($this->menu as $cle => $valeur) {
                        echo "<li><a href=\"$valeur\">$cle</a></li>";
                    }
                ?>
            </ul>
        </nav>
    </header>

    <h1><?php echo $title; ?></h1>

    <!-- Affichage du feedback -->
    <?php echo $feedback ? '<p style="color: green;">' . htmlspecialchars($feedback, ENT_QUOTES, 'UTF-8') . '</p>' : ''; ?>

    <?php echo $content; ?>
       
    <script type="text/javascript">
        <?php echo $scriptjs; ?>
        
    </script>
</body>
</html>
