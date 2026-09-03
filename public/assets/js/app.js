document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.needs-validation').forEach((form) => {
    form.addEventListener('submit', (event) => {
      if (!form.checkValidity()) {
        event.preventDefault();
        event.stopPropagation();
      }
      form.classList.add('was-validated');
    });
  });

  const header = document.querySelector('.site-header');
  const updateHeader = () => header?.classList.toggle('is-scrolled', window.scrollY > 12);
  window.addEventListener('scroll', updateHeader, { passive: true });
  updateHeader();

  const revealObserver = 'IntersectionObserver' in window
    ? new IntersectionObserver((entries) => entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          revealObserver.unobserve(entry.target);
        }
      }), { threshold: 0.12 })
    : null;
  document.querySelectorAll('.book-card, .news-card, .value-grid article, .admin-panel').forEach((element) => {
    element.classList.add('reveal-item');
    if (revealObserver) revealObserver.observe(element);
    else element.classList.add('is-visible');
  });

  const search = document.querySelector('#ajaxBookSearch');
  const results = document.querySelector('#ajaxSearchResults');
  let searchTimer;
  search?.addEventListener('input', () => {
    window.clearTimeout(searchTimer);
    const query = search.value.trim();
    if (query.length < 2) { results.hidden = true; results.innerHTML = ''; return; }
    searchTimer = window.setTimeout(async () => {
      results.hidden = false;
      results.innerHTML = '<div class="ajax-loading">Đang tìm sách...</div>';
      try {
        const response = await fetch(`/api/search-books.php?q=${encodeURIComponent(query)}`, { headers: { Accept: 'application/json' } });
        const payload = await response.json();
        results.innerHTML = payload.items.length
          ? payload.items.map((book) => `<a href="/book-detail.php?id=${book.id}"><img src="${escapeHtml(book.cover_image)}" alt=""><span><strong>${escapeHtml(book.title)}</strong><small>${formatMoney(book.sale_price || book.price)}</small></span></a>`).join('')
          : '<div class="ajax-loading">Không tìm thấy sách phù hợp.</div>';
      } catch (_error) {
        results.innerHTML = '<div class="ajax-loading">Không thể tìm kiếm lúc này.</div>';
      }
    }, 260);
  });

  document.addEventListener('click', (event) => {
    if (results && search && !results.contains(event.target) && event.target !== search) results.hidden = true;
    const action = event.target.closest('[data-demo-action="add-book"]');
    if (action) showNotice('Đã thêm sách vào danh sách yêu thích.');
  });

  function showNotice(message) {
    const notice = document.createElement('div');
    notice.className = 'app-notice';
    notice.setAttribute('role', 'status');
    notice.textContent = message;
    document.body.appendChild(notice);
    requestAnimationFrame(() => notice.classList.add('is-visible'));
    window.setTimeout(() => { notice.classList.remove('is-visible'); window.setTimeout(() => notice.remove(), 250); }, 2600);
  }

  function formatMoney(value) {
    return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(Number(value));
  }

  function escapeHtml(value) {
    const span = document.createElement('span');
    span.textContent = String(value ?? '');
    return span.innerHTML;
  }
});
