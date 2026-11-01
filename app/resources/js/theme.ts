type Theme = "system" | "light" | "dark";

const STORAGE_KEY = "theme";
const DEFAULT_THEME: Theme = "system";

const systemTheme = window.matchMedia("(prefers-color-scheme: dark)");

function getTheme(): Theme {
  const stored = localStorage.getItem(STORAGE_KEY);

  return stored === "system" || stored === "light" || stored === "dark" ? stored : DEFAULT_THEME;
}

function applyTheme(theme: Theme): void {
  const isDark = theme === "dark" || (theme === "system" && systemTheme.matches);

  document.documentElement.classList.toggle("dark", isDark);
  document.documentElement.classList.toggle("light", !isDark);
  document.documentElement.style.colorScheme = isDark ? "dark" : "light";
}

function syncSwitchers(): void {
  const theme = getTheme();

  document.querySelectorAll<HTMLInputElement>('input[name="theme"]').forEach((input) => {
    input.checked = input.value === theme;
  });
}

export function switchTheme(theme: Theme): void {
  localStorage.setItem(STORAGE_KEY, theme);

  applyTheme(theme);
  syncSwitchers();
}

export function themeSwitcherSetup(): void {
  applyTheme(getTheme());
  syncSwitchers();

  document.querySelectorAll<HTMLInputElement>('input[name="theme"]').forEach((input) => {
    input.addEventListener("change", () => {
      if (input.checked) {
        switchTheme(input.value as Theme);
      }
    });
  });

  // Keep compatibility with existing header buttons.
  document.querySelectorAll<HTMLElement>(".js-color-scheme-switcher").forEach((button) => {
    button.addEventListener("click", () => {
      const theme = button.dataset.colorScheme;

      if (theme === "light" || theme === "dark") {
        switchTheme(theme);
      }
    });
  });

  // React to changes in OS theme when System is selected.
  systemTheme.addEventListener("change", () => {
    if (getTheme() === "system") {
      applyTheme("system");
    }
  });

  // Synchronize preferences between browser tabs.
  window.addEventListener("storage", (event) => {
    if (event.key === STORAGE_KEY) {
      applyTheme(getTheme());
      syncSwitchers();
    }
  });
}
