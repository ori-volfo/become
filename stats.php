<?php
include 'globals.php';
$title = 'stats';

include 'partials/header.php';
?>
<main>
    <div class="container">
        <h1><?=ucwords($title)?></h1>
        <div class="row charts">
            <div class="cities col-lg-6">
                <h2 class="text-center">Users by cities</h2>
                <canvas id="citiesChart" width="400" height="400"></canvas>
            </div>
            <div class="countries col-lg-6">
                <h2 class="text-center">Users by countries</h2>
                <canvas id="countriesChart" width="400" height="400"></canvas>
            </div>
            <div class="ages col-lg-12">
                <h2 class="text-center">Users by ages</h2>
                <div class="agesCanvas"">
                    <canvas id="agesChart" width="400" height="400"></canvas>
                </div>
            </div>
        </div>
    </div>

</main>


<?php
include 'partials/footer.php';