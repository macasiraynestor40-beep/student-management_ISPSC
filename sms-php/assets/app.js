// Sidebar toggle on small screens
const menuBtn = document.getElementById("menuBtn");
if (menuBtn) menuBtn.addEventListener("click", () => document.getElementById("sidebar").classList.toggle("open"));

// Show / hide password buttons (login + signup)
document.querySelectorAll(".toggle").forEach(btn => btn.addEventListener("click", () => {
  const input = document.getElementById(btn.dataset.target);
  const show = input.type === "password";
  input.type = show ? "text" : "password";
  btn.textContent = show ? "Hide" : "Show";
}));

// Confirm before any form marked data-confirm (used by Delete)
document.querySelectorAll("form[data-confirm]").forEach(f =>
  f.addEventListener("submit", e => { if (!confirm(f.dataset.confirm)) e.preventDefault(); }));
