// main.js – ClarkMovie

document.addEventListener('DOMContentLoaded', () => {

    // ── Animate rating bars on detail page ──
    const bars = document.querySelectorAll('.rating-bar');
    if (bars.length) {
        const obs = new IntersectionObserver((entries) => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    e.target.style.width = e.target.style.getPropertyValue('--pct') ||
                        getComputedStyle(e.target).getPropertyValue('--pct');
                    obs.unobserve(e.target);
                }
            });
        }, { threshold: 0.2 });
        bars.forEach(b => obs.observe(b));
    }

    // ── Lazy image fade-in ──
    const imgs = document.querySelectorAll('img[loading="lazy"]');
    imgs.forEach(img => {
        img.style.opacity = '0';
        img.style.transition = 'opacity .4s ease';
        if (img.complete) {
            img.style.opacity = '1';
        } else {
            img.addEventListener('load', () => img.style.opacity = '1');
            img.addEventListener('error', () => img.style.opacity = '1');
        }
    });

    // ── Disabled button click feedback ──
    document.querySelectorAll('.btn.disabled').forEach(btn => {
        btn.addEventListener('click', e => {
            e.preventDefault();
            btn.textContent = '🚧 Coming Soon!';
            setTimeout(() => {
                btn.textContent = btn.dataset.orig || '▶ Watch (Coming Soon)';
            }, 1800);
        });
        btn.dataset.orig = btn.textContent;
    });

});

