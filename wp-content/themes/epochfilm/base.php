<?php get_template_part('templates/head'); ?>
<body <?php body_class(); ?>>

  <!--[if lt IE 8]>
    <div class="alert alert-warning">
      <?php _e('You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.', 'roots'); ?>
    </div>
  <![endif]-->

  <?php
    do_action('get_header');
    // Use Bootstrap's navbar if enabled in config.php
    if (current_theme_supports('bootstrap-top-navbar')) {
      get_template_part('templates/header-top-navbar');
    } else {
      get_template_part('templates/header');
    }
  ?>

  <div class="wrap container" role="document">
    <div class="content row">
      <main class="main <?php echo roots_main_class(); ?>" role="main">
          <div class="fp-screen">сюда лоадим плеер для вконтакта</div>
          <div class="details" >
              <div id="left-column-details" class="left-column-details">
                  <div class="details-header"><h3>Home</h3></div>
                  <div class="content-wrapper-details">
                      <div class="details-poster">
                          <img width="183" height="259" src="./Watch Dream of a Warrior Online Free   Watching Full Movies Online_files/1061-10271-000_EN.jpg" alt="тут оображаем постер киношки" class="box-shadow" itemprop="image">
                      </div>
                      <div class="description-film-container">
                          <div class="header-comments">
                              <h1>Dream of a Warrior</h1>
                              <span>Comments(number)</span>
                          </div>
                          <div class="film-history">
                              <ul>
                                  <li>Korea, Republic of | 2001 | 108 min. | Rated: 1</li>
                                  <li>Languages available: EN, KO (EN subtitles), KO</li>
                                  <li>Original Title: Dream of a Warrior</li>
                                  <li>Genre: Action & Adventure , Korean Drama</li>
                                  <li>Director: Hee-joon Park</li>
                                  <li>Cast: Eun-hye Park , Leon Lai Ming , Na-yeong Lee</li>
                              </ul>
                          </div>
                          <div id="film-description" class="film-description">
                              <div id="film-content">
                                  Dream of a Warrior’ takes the concept of former life and puts in it a new twist. It talks of a place called Dilmoon, which hosts our past lives. It is a futuristic city where inhabitants live with peace and a lot of optimism. Dean is a warrior in this city who is in love with Rose, but
                                  Dream of a Warrior’ takes the concept of former life and puts in it a new twist. It talks of a place called Dilmoon, which hosts our past lives. It is a futuristic city where inhabitants live with peace and a lot of optimism. Dean is a warrior in this city who is in love with Rose, but
                                  Dream of a Warrior’ takes the concept of former life and puts in it a new twist. It talks of a place called Dilmoon, which hosts our past lives. It is a futuristic city where inhabitants live with peace and a lot of optimism. Dean is a warrior in this city who is in love with Rose, but
                              </div>
                          </div>
<!--                          <a href="#" id="expand-film-description" class="show-more">Show More</a>-->
                          <label id="expand-film-description" class="show-more">Show More</label>
                      </div>
                      <div class="clear"></div>
                  </div>
              </div>
              <div id="right-column-details" class="right-column-details">
                  <div class="details-header"><h3>Related Movies</h3></div>
                  <div class="wrapper-related-movies">
                      <div class="related-movie">
                          <a href="http://www.viewster.com/movie/1055-10791-000/4-weeks-of-sunshine">
                            <img alt="4 Weeks of Sunshine" src="./Watch Dream of a Warrior Online Free   Watching Full Movies Online_files/1055-10791-000_EN_S.jpg" width="90" class="cover element-box-shadow">
                          </a>
                      </div>
                      <div class="related-movie">
                          <a href="http://www.viewster.com/movie/1055-10791-000/4-weeks-of-sunshine">
                            <img alt="4 Weeks of Sunshine" src="./Watch Dream of a Warrior Online Free   Watching Full Movies Online_files/1055-10791-000_EN_S.jpg" width="90" class="cover element-box-shadow">
                          </a>
                      </div>
                      <div class="related-movie">
                          <a href="http://www.viewster.com/movie/1055-10791-000/4-weeks-of-sunshine">
                            <img alt="4 Weeks of Sunshine" src="./Watch Dream of a Warrior Online Free   Watching Full Movies Online_files/1055-10791-000_EN_S.jpg" width="90" class="cover element-box-shadow">
                          </a>
                      </div>
                      <div class="related-movie">
                          <a href="http://www.viewster.com/movie/1055-10791-000/4-weeks-of-sunshine">
                            <img alt="4 Weeks of Sunshine" src="./Watch Dream of a Warrior Online Free   Watching Full Movies Online_files/1055-10791-000_EN_S.jpg" width="90" class="cover element-box-shadow">
                          </a>
                      </div>
                      <div class="related-movie">
                          <a href="http://www.viewster.com/movie/1055-10791-000/4-weeks-of-sunshine">
                            <img alt="4 Weeks of Sunshine" src="./Watch Dream of a Warrior Online Free   Watching Full Movies Online_files/1055-10791-000_EN_S.jpg" width="90" class="cover element-box-shadow">
                          </a>
                      </div>
                      <div class="related-movie">
                          <a href="http://www.viewster.com/movie/1055-10791-000/4-weeks-of-sunshine">
                            <img alt="4 Weeks of Sunshine" src="./Watch Dream of a Warrior Online Free   Watching Full Movies Online_files/1055-10791-000_EN_S.jpg" width="90" class="cover element-box-shadow">
                          </a>
                      </div>
                      <div class="clear"></div>
                  </div>
              </div>
              <div class="clear"></div>
          </div>
        <?php include roots_template_path(); ?>
      </main><!-- /.main -->
      <?php if (roots_display_sidebar()) : ?>
        <aside class="sidebar <?php echo roots_sidebar_class(); ?>" role="complementary">
          <?php include roots_sidebar_path(); ?>
        </aside><!-- /.sidebar -->
      <?php endif; ?>
    </div><!-- /.content -->
  </div><!-- /.wrap -->

  <?php get_template_part('templates/footer'); ?>

</body>
</html>
