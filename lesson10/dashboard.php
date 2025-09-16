<!DOCTYPE html>
<html>
    <head>
        <title>Dashboard</title>
        <style>
            table,td,th{
                border:1px solid black;
                border-collapse:collapse;
            }
            td,th{
                padding: 10px 20px;
            }
        </style>
    </head>

    <body>
        <?php
        include_once('config.php');
        $sql = "SELECT * FROM user";
        $getUser = $conn->prepare($sql);
        $getUser->execute();
        $user=$getUser->fetchAll(); 
        ?>

        <br><br>

        <table>
            <thead>
                <th>ID</th>
                <th>Name</th>
                <th>Surname</th>
                <th>Email</th>
            </thead>
            <tbody>
                <?php
                    foreach($user as $user){
                ?>
                <tr>
                    <td><?= $user['id']?></td>
                    <td><?= $user['name']?></td>
                    <td><?= $user['surname']?></td>
                    <td><?= $user['email']?></td>
                </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
        <a href="index.php">Add Users</a>
    </body>
</html>