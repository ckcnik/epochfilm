<?php while (have_posts()) : the_post(); ?>
  <article <?php post_class(); ?>>
      <div class="fp-screen">сюда лоадим плеер для вконтакта</div>
      <div class="details" >
          <div id="left-column-details" class="left-column-details">
              <div class="details-header">
                  <h3><?php the_title(); ?></h3>
              </div>
              <div class="content-wrapper-details">
                  <div class="details-poster">
                      <img width="183" height="259" src="./Watch Dream of a Warrior Online Free   Watching Full Movies Online_files/1061-10271-000_EN.jpg" alt="тут оображаем постер киношки" class="box-shadow" itemprop="image">
                  </div>
                  <div class="description-film-container">
                      <div class="header-comments">
                          <h1><?php the_title(); ?></h1>
                          <span>Comments(number)</span>
                          <?php get_template_part('templates/entry-meta'); ?>
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
                              <?php the_content(); ?>
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
      <div id="comments-to-film" class="box-shadow">
          <div class="wrapper-comments">
              сюда впариваем вконтактокомменты
          </div>
      </div>
    <footer>
      <?php wp_link_pages(array('before' => '<nav class="page-nav"><p>' . __('Pages:', 'roots'), 'after' => '</p></nav>')); ?>
    </footer>
  </article>
<?php endwhile; ?>
