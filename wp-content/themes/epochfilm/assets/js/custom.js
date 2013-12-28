$(document).ready(function () {
	setTimeout(removePlusoLink, 1000);

	var heightDescriptionFilm = 35;
	$('.film-description').height(heightDescriptionFilm);
	if ( $('#film-description').height() >= $('#film-content').height() ) {
		$('#expand-film-description').hide();
	}

	correctHeightDetailsBlock();	// correcting heights left and right blocks

	/**
	 * logic for the button folding and unfolding of information on the film
	 */
	$('#expand-film-description').on('click', function () {

		contentHeight 		= $('#film-content').height();			// реальная высота блока с описанием фильма
		wrapHeiht 			= $('#film-description').height();		// высота урезанного блока с описанием
		rightColumnHeiht 	= $('#right-column-details').height();	// высота блока с релевантными ыильмами
		leftColumnHeiht 	= $('#left-column-details').height();	// высота блока с описанием фильма

		// разница между требуемой высотой и высотой скрытого блока с описанием
		diffHeight = contentHeight - heightDescriptionFilm;

		if (contentHeight > wrapHeiht) {
			resultheight = contentHeight;
		}
		else {
			resultheight = heightDescriptionFilm;
		}

		// анимируем изменение высоты для блока с описанием фильма
		$('#film-description').animate({
			height: resultheight
		}, 500);

		// анимируем изменение высоты для блоков с описанием фильма и блока с релевантными фильмами
		if (resultheight != heightDescriptionFilm) {
			$('#left-column-details').animate({
				height: leftColumnHeiht + diffHeight
			}, 500);
			$('#right-column-details').animate({
				height: rightColumnHeiht + diffHeight
			}, 500);
		}
		else {
			$('#left-column-details').animate({
				height: leftColumnHeiht - diffHeight
			}, 500);
			$('#right-column-details').animate({
				height: rightColumnHeiht - diffHeight
			}, 500);
		}
		return false;
	});

	/**
	 * Hover event on the film title on the search page
	 */
	$('#search-page li div.film-info header .headers a').hover(
		function(){
			$(this).find('span').stop().css({left:'0%'}).animate({width:'100%'},450);
		},
		function(){
			$(this).find('span').stop().animate({width:'0%', left:'100%'}, 450);
		}
	);

	afterLoad(); // включить эффект последовательной загрузки картинок для шаблона films

	/**
	 * Сабмит формы поиска
	 */
	$('.icon-search').on('click', function(){
		$('.search-form').submit();
	});
});

/**
 * корректирует по высоте блоки: left-column-details, left-column-details
 */
function correctHeightDetailsBlock() {
	heightRightColumn 	= $('#right-column-details').height();
	heightLeftColumn 	= $('#left-column-details').height();
	if (heightRightColumn != heightLeftColumn) {
		if (heightLeftColumn < heightRightColumn) {
			$('#left-column-details').height(heightRightColumn);
		}
		else {
			$('#right-column-details').height(heightLeftColumn);
		}
	}
}

/**
 * Удаляем ссылку на сервис плюсо
 */
function removePlusoLink() {
	$('.pluso-more').remove();
}

/**
 * Эффект поочередной подгрузки картинок
 */
function afterLoad() {
	jQuery(function() {
		jQuery('.presentation li.new-film div.main-poster a img').hide();
	});

	jQuery(window).bind('load', function() {
		var i = 1;
		var imgs = jQuery('.presentation li.new-film div.main-poster a img').length;
		var int = setInterval(function() {
			//console.log(i); check to make sure interval properly stops
			if(i >= imgs) clearInterval(int);
			jQuery('.presentation li.new-film div.main-poster a img:hidden').eq(0).fadeIn(400);
			i++;
		}, 10);
	});
}