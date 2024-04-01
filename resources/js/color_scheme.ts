/**
 * Switch the color scheme of the website
 */
export function colorSchemeSwitcherSetup() {
    const switcherSelector = ".js-color-scheme-switcher";
    const transitionClass = "color-scheme-transition";
    const transitionTime = 500;

    // Check if the user prefers dark scheme and set the theme accordingly
    if (
        window.matchMedia &&
        window.matchMedia("(prefers-color-scheme: dark)").matches
    ) {
        if (document.cookie.indexOf("color-scheme") == -1) {
            triggerSwitch("dark");
        }
    }

    // Switch color scheme when the user clicks on the switcher
    document.querySelectorAll(switcherSelector).forEach((element) => {
        element.addEventListener("click", () => {
            const htmlElement = element as HTMLElement;
            const colorScheme: string | undefined =
                htmlElement.dataset.colorScheme;

            if (colorScheme) {
                triggerSwitch(colorScheme);
            } else {
                console.error(
                    "Color scheme switcher element is missing the data-color-scheme attribute."
                );
            }
        });
    });

    // Trigger the color scheme switch
    function triggerSwitch(colorScheme: string) {
        const expiration = new Date().getMonth() + 1;

        // Add the transition class to the document element
        document.documentElement.classList.add(transitionClass);

        // Set the color scheme attribute
        document.documentElement.dataset.colorScheme = colorScheme;

        // Remove the transition class after the transition time
        setTimeout(function () {
            document.documentElement.classList.remove(transitionClass);
        }, transitionTime);

        // Set the cookie
        document.cookie = `color-scheme=${colorScheme}; expires=${expiration}; path=/`;
    }
}
