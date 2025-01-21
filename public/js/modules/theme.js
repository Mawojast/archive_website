/**
 * Module for handling dark and light theme.
 */
export function Theme() {

    const darkThemeProperties = {
        "--background-color": "#222222",
        "--clickable-background-color": "#FF6E1F",
        "--clickable-font-color": "#222222",
        "--font-color": "#f2f2f2",
        "--header-border-bottom": "#2b2b2b"
    };

    const lightThemeProperties = {
        "--background-color": "#f2f2f2",
        "--clickable-background-color": "#222222",
        "--clickable-font-color": "#f2f2f2",
        "--header-border-bottom": "#e3e0d8",
        "--font-color": "#222222"
    };
    // function to set css theme properties.
    const setThemeProperties = (themeProperties) => {
        $(":root").css(themeProperties);
    };

    // Loads light theme from local storage if exists and is true or if local storage has no theme data.
    const theme = localStorage.getItem('theme');
    if (!theme || theme === "true") {
        $("#theme-switch-mobile").prop('checked', true);
        $('#theme-switch-desktop').html('&#9790;');
        setThemeProperties(lightThemeProperties);
    }

    /** 
    * Event-Listener for Theme-Switch mobile view checkbox
    * It sets dark or light icons, new theme properties
    * and saves true for lighttheme or false for darktheme in localstorage.
    */
    $("#theme-switch-mobile").click(function(){
        const theme = $(this).prop('checked');
        let themeProperties;
        if(theme) {
            themeProperties = lightThemeProperties;
            $('#theme-switch-desktop').html('&#9790;');
        }else {
            themeProperties = darkThemeProperties;
            $('#theme-switch-desktop').html('&#9728;');
        }
        setThemeProperties(themeProperties);
        localStorage.setItem('theme', theme);
    });

    /** 
    * Event-Listener for Theme-Switch destop view icons
    * It sets dark or light icons, new theme properties
    * and saves true for lighttheme or false for darktheme in localstorage.
    */
    $("#theme-switch-desktop").click(function(){
        const theme = $("#theme-switch-mobile").prop('checked');
        $("#theme-switch-mobile").prop('checked', !theme);
        let themeProperties;
        if(!theme) {
            themeProperties = lightThemeProperties;
            $('#theme-switch-desktop').html('&#9790;');
        }else {
            themeProperties = darkThemeProperties;
            $('#theme-switch-desktop').html('&#9728;');
        }
        setThemeProperties(themeProperties);
        localStorage.setItem('theme', !theme);
    });
}