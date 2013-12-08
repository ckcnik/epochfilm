$( document ).ready(function() {
    var heightDescriptionFilm = 80;
    correctHeightDetailsBlock();

    $('#expand-film-description').on('click', function() {

        contentHeight       = $('#film-content').height();
        wrapHeiht           = $('#film-description').height();
        rightColumnHeiht    = $('#right-column-details').height();

        if ( contentHeight > wrapHeiht ) {
            resultheight = contentHeight;
        }
        else {
            resultheight = heightDescriptionFilm;
        }
        $('#film-description').animate({
            height: resultheight
        },500);

        if (resultheight != heightDescriptionFilm) {
            $('#right-column-details').animate({
                height: rightColumnHeiht + contentHeight - heightDescriptionFilm
            },500);
        } else {
            $('#right-column-details').animate({
                height: rightColumnHeiht - (contentHeight - heightDescriptionFilm)
            },500);
        }
    });
});

/**
 * корректирует по высоте блоки: left-column-details, left-column-details
 */
function correctHeightDetailsBlock() {
    heightRightColumn   = $('#right-column-details').height();
    heightLeftColumn    = $('#left-column-details').height();
    if (heightRightColumn != heightLeftColumn) {
        if (heightLeftColumn < heightRightColumn ) {
            $('#left-column-details').height(heightRightColumn);
        }
        else {
            $('#right-column-details').height(heightLeftColumn);
        }
    }
}