<div id="menu">
    <div>
        <a class="hrefMenu" href="index.php">Главная</a>
        <a class="hrefMenu" href="turlist.php">Турниры</a>
        <a class="hrefMenu" href="about.php">О нас</a>
    </div>

    <div class="menuReg">
        <?php
            if(!isset($_SESSION['user']))
            {
                include 'loginWin.html';
                echo "<a class='hrefMenu' onclick=\"show('block')\">Вход</a>";
            }
            else
            {
                $id = $_SESSION['user']['id'];

                echo "<a class='hrefMenu' href='playerdet.php?id=$id'>Привет! ".$_SESSION['user']['first_name']."</a>";
                echo "<a class='hrefMenu' href='exit.php'>Выйти</a>";
            }
        ?>
    </div>
    <div >
        <?php
        if(isset($_SESSION['user']['admin']))
        {
            echo '<a class="hrefMenu" href="createT.php">Создать турнир</a>
        <a class="hrefMenu" href="turlistedit.php">Редактировать турнир</a>
        <a class="hrefMenu" href="newsAdd.php">Добавить новость</a>';
        }
        else if(isset($_SESSION['user']))
        {
            echo '<a class="hrefMenu" href="newsAdd.php">Добавить запись</a>';
        }
        ?>
    </div>
</div>