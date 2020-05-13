<?php
//phpinfo();
require 'functions.php';
$items = loadAll();

//$xml = loadRss('https://dumskaya.net/rssnews/');
//$items = $xml->channel->item;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
          integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body>

<div class="container">

    <form method="get" >
        <div class="form-group">
            <label for="formGroupExampleInput">Поиск по странице</label>
            <input type="text" class="form-control" id="formGroupExampleInput" name="word" placeholder="введите слово для поиска"
                   value="<?= $_GET['word'] ?? '' ?>">
        </div>
        <button type="submit" class="btn btn-primary">Поиск</button>
    </form>

    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link active" href="?limit=5">5</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="?limit=10">10</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="index2.php">all</a>
        </li>
    </ul>
    <ol>
        <?php foreach ($items as $key => $article): ?>
            <?php if (!empty($_GET['limit'])) {
                if ($key == $_GET['limit']) {
                    break;
                }
            }
            ?>
            <?php if (!empty($_GET['word'])) {
                if (stristr($article->description, $_GET['word']) == FALSE) {
                    unset($article->description);

                }
            }
            ?>

            <li>
                <a href="<?php echo $article->Link ?>" target="_blank"><?= $article->title ?></a>
                <p><?php echo $article->description ?></p>
            </li>
        <?php endforeach; ?>
    </ol>
</div>
</body>
</html>
