$(document).ready(function () {
	var heightDescriptionFilm = 40;
	$('.film-description').height(heightDescriptionFilm);
	if ( $('#film-description').height() === $('#film-content').height() ) {
		$('#expand-film-description').hide();
	}

	correctHeightDetailsBlock();	// correcting heights left and right blocks

	/**
	 * logic for the button folding and unfolding of information on the film
	 */
	$('#expand-film-description').on('click', function () {

		contentHeight 		= $('#film-content').height();			// реальная высота блока с описанием фильма
		wrapHeiht 			= $('#film-description').height();			// высота урезанного блока с описанием
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