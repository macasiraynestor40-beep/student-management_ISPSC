// Follow the device's light/dark setting
const mq = matchMedia("(prefers-color-scheme: dark)");
const applyTheme = () => document.documentElement.setAttribute("data-bs-theme", mq.matches ? "dark" : "light");
applyTheme(); mq.addEventListener("change", applyTheme);
