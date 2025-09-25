(function () {
  function markNewTab(a) {
    a.setAttribute('target','_blank');
    if (!a.getAttribute('rel')) a.setAttribute('rel','noopener');
  }
  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('a[data-newwin]').forEach(markNewTab);
  }, { once:true });
  document.addEventListener('click', function (e) {
    var a = e.target.closest && e.target.closest('a');
    if (!a) return;
    var href = a.getAttribute('href') || '';
    if (a.hasAttribute('data-newwin') || /^https?:\/\//i.test(href)) {
      markNewTab(a);
    }
  }, true);
})();
