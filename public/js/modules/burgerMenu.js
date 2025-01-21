/**
 * Module handles burger menu in mobile view
 */
export function BurgerMenu() {
    const $burgerIcon = $('#burger-icon');
    const $sideMenu = $('#side-menu');
    const $inputElements = $('.input-element');

    /** 
     * Event-Listener to close or open menu by clicking burger sign.
     */
    $burgerIcon.on('click', function (event) {
        event.stopPropagation();  // Stops the click event so that it doesn't also trigger the close
        $burgerIcon.toggleClass('active');
        $sideMenu.toggleClass('open');

        ($sideMenu.hasClass('open')) ?  $inputElements.css('z-index', '-1') : $inputElements.css('z-index', '1');
    });

    /**
     * Event-Listener to close the menu when clicked outside the menu.
     */
    $(document).on('click', function (event) {
        if ($sideMenu.hasClass('open')) {
            if (!$sideMenu.is(event.target) && !$sideMenu.has(event.target).length) {
                $sideMenu.removeClass('open');
                $burgerIcon.removeClass('active');
                $inputElements.css('z-index', '1');
            }
        }
    });

    /** 
     * Close the menu by clicking the escape button.
     */
    $(document).on('keydown', function (event) {
        if (event.key === "Escape" && $sideMenu.hasClass('open')) {
            $sideMenu.removeClass('open');
            $burgerIcon.removeClass('active');
            $inputElements.css('z-index', '1');
        }
    });
}
