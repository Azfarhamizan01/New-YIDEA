document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.project-card').forEach(card => {
        card.addEventListener('click', e => {
            if (e.target.closest('a,button')) return;
            card.style.transform = 'translateY(-4px)';
        });
    });

    const search = document.querySelector('.filters input');
    if (search) {
        search.addEventListener('keydown', e => {
            if (e.key === 'Enter') e.target.form.submit();
        });
    }
});
