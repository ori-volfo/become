<?php
$title = 'users';

include 'env.php';
include 'partials/header.php';
?>
<main>
    <div class="container">
        <h1><?=ucwords($title)?></h1>
        <div class="users-table">
            <table id="users" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th>First name</th>
                        <th>Last name</th>
                        <th>E-mail</th>
                        <th>Phone</th>
                        <th>Age</th>
                        <th>City</th>
                        <th>Country</th>
                    </tr>
                </thead>
                <tbody class="tbody">

                </tbody>
            </table>
        </div>
    </div>

</main>











<?php
include 'partials/footer.php';