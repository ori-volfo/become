        </div>
        <footer class="section footer-classic context-dark bg-image">
                <div class="container">
                    <div class="row">
                        <!-- Rights-->
                        <div class="rights"><span>©  </span><span class="copyright-year">2019</span><span> </span><span>Ori Volfovitch</span><span>. </span><span>All Rights Reserved.</span></div>
                    </div>
                </div>
        </footer>
        <?php if($scripts[$title][0]): ?>
            <script defer>
                <?=$title?>.init({projectPath: "<?=$project_path?>"});
            </script>
        <?php endif; ?>

    </body>
</html>
